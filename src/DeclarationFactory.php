<?php

declare(strict_types=1);

namespace Mijnkantoor\Belastingdienst;

use Carbon\Carbon;
use Mijnkantoor\Belastingdienst\Enums\BlockTypes;
use Mijnkantoor\Belastingdienst\Enums\DeclarationTypes;
use Mijnkantoor\Belastingdienst\Exceptions\DeclarationException;
use Mijnkantoor\Belastingdienst\Exceptions\PeriodException;
use Mijnkantoor\Belastingdienst\Exceptions\TimeBlockException;

class DeclarationFactory
{
    public function createFromDeclarationIdAndDateRange(
        DeclarationTypes $decType,
        string $declarationId,
        Carbon $from,
        Carbon $till,
        ?BlockTypes $blockType = null,
        ?int $period = null
    ): Declaration
    {
        $from = $from->copy();
        $till = $till->copy();

        if ($blockType === null && $decType === DeclarationTypes::LOAN) {
            throw DeclarationException::incompatibleLoan();
        }

        $blockType = $blockType ?? $this->calculateBlock($from, $till);
        $period = $period ?? $this->calculatePeriod($blockType, $from);

        $year = $from->year;
        $month = $from->month; // we need this one for shifted quarters

        $block = new TimeBlock(
            $decType,
            $year,
            $month,
            $blockType,
            $period,
            $from,
            $till
        );

        return $this->create($declarationId, $block);
    }

    public function calculateBlock(Carbon $from, Carbon $till): BlockTypes
    {
        $differenceInDays = $from->diffInDays($till);

        if ($from->format('j') == 1) {
            if ($differenceInDays >= 27 && $differenceInDays <= 31) {
                return BlockTypes::MONTHLY;
            }

            if ($differenceInDays > 31 && $differenceInDays <= 168) {
                return BlockTypes::QUARTER;
            }

            if ($differenceInDays > 168 && $differenceInDays <= 186) {
                return BlockTypes::HALFYEAR;
            }

            return BlockTypes::YEARLY;

        }

        return BlockTypes::FOURWEEK;
    }

    public function calculatePeriod(BlockTypes $type, Carbon $from): int
    {
        $from = $from->copy();

        switch ($type) {
            case BlockTypes::FOURWEEK:
                return $this->calculateFourWeekPeriod($from);
            case BlockTypes::MONTHLY:
                return $from->month;
            case BlockTypes::QUARTER:
                return $from->quarter;
            case BlockTypes::HALFYEAR:
                return $from->month < 6 ? 1 : 2;
            case BlockTypes::YEARLY:
                return 0;
        }

        throw PeriodException::invalidPeriod();
    }

    public function calculateFiscalYear(Carbon $from, int $fiscalYearStartMonth): int
    {
        // get the year of the start date
        $year = (int)$from->format('Y');

        // check if the start date is before the fiscal year start month
        if ((int)$from->format('m') < $fiscalYearStartMonth) {
            // if so, the year is the previous year
            $year--;
        }

        return $year;

    }

    public function calculateFourWeekPeriod(Carbon $from): int
    {
        $entry = $from->copy();
        $table = $this->generateFourWeekPeriodTable($entry);

        foreach ($table as $key => $periodRow) {
            if ($from->between($periodRow['from'], $periodRow['till'])) {
                return $key;
            }
        }

        throw PeriodException::invalidPeriod();
    }

    public function generateFourWeekPeriodTable(Carbon $from): array
    {
        $from = $from->copy()->startOfYear();
        $startCount = $from->copy()->previous('Sunday');

        if ($from->weekOfYear > 1) {
            $startCount = $from->copy()->addWeek()->previous('Sunday');
        }

        $till = $startCount->copy()->addWeeks(4);
        $endOfYear = $from->copy()->endOfYear();


        $table = [];
        $table[1] = ['from' => $from->copy(), 'till' => $till->copy()];

        for ($i = 2; $i < 14; $i ++) {
            $from = $till->copy()->addDay();
            $till = $from->copy()->addWeeks(4)->subDay();

            if ($i == 13) {
                $till = $endOfYear;
            }

            $table[$i] = ['from' => $from, 'till' => $till];
        }

        return $table;
    }

    public function create($declarationId, TimeBlock $timeBlock): Declaration
    {
        $declarationIdStripped = preg_replace('/[\s.]+/', '', $declarationId);

        //Composite the payment reference
        $paymentReference = sprintf('X%s%s%s%s%s%s',
            substr($declarationIdStripped, 0, 8),
            $timeBlock->getTypeCode(),
            $timeBlock->getYearCode(),
            substr($declarationIdStripped, 10, 2),
            $timeBlock->getPeriodCode(),
            0 // fixed value
        );

        //Calculate control number
        $controlNumber = $this->getControlNumber($paymentReference);

        //Substitute control number
        $paymentReference[0] = $controlNumber;

        //Calculate date
        $paymentDueDate = $this->calculatePaymentDueDate($timeBlock);

        return new Declaration($declarationId, $paymentReference, $paymentDueDate);
    }

    private function getControlNumber(string $value): int
    {
        $number = substr($value, (strlen($value) === 16 ? 1 : 2));

        if (strlen($number) < 15) {
            $number = str_pad($number, 15, '0', STR_PAD_LEFT);
        }

        $sum = 0;
        $sum += $number[strlen($number) - 1] * 2;
        $sum += $number[strlen($number) - 2] * 4;
        $sum += $number[strlen($number) - 3] * 8;
        $sum += $number[strlen($number) - 4] * 5;
        $sum += $number[strlen($number) - 5] * 10;
        $sum += $number[strlen($number) - 6] * 9;
        $sum += $number[strlen($number) - 7] * 7;
        $sum += $number[strlen($number) - 8] * 3;
        $sum += $number[strlen($number) - 9] * 6;
        $sum += $number[strlen($number) - 10] * 1;
        $sum += $number[strlen($number) - 11] * 2;
        $sum += $number[strlen($number) - 12] * 4;
        $sum += $number[strlen($number) - 13] * 8;
        $sum += $number[strlen($number) - 14] * 5;
        $sum += $number[strlen($number) - 15] * 10;

        $check = 11 - ($sum % 11);

        return $check == 10 ? 1 : ($check == 11 ? 0 : $check);
    }

    public function calculatePaymentDueDate(TimeBlock $timeBlock): Carbon
    {
        $till = $timeBlock->getTill()->copy();

        switch ($timeBlock->getBlock()) {
            case BlockTypes::FOURWEEK:
                $till->addMonth();
                if ($till->month - $timeBlock->getTill()->month > 1) {
                    $till->subMonth()->endOfMonth();
                }
                return $till;
            case BlockTypes::MONTHLY:
            case BlockTypes::QUARTER:
            case BlockTypes::HALFYEAR:
            case BlockTypes::YEARLY:
                return $till->addMonthNoOverflow()->endOfMonth();
        }

        throw PeriodException::invalidPeriod();
    }
}

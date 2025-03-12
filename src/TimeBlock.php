<?php

declare(strict_types=1);

namespace Mijnkantoor\Belastingdienst;

use Carbon\Carbon;
use Mijnkantoor\Belastingdienst\Enums\BlockTypes;
use Mijnkantoor\Belastingdienst\Enums\DeclarationTypes;
use Mijnkantoor\Belastingdienst\Exceptions\DeclarationException;
use Mijnkantoor\Belastingdienst\Exceptions\PeriodException;
use Mijnkantoor\Belastingdienst\Exceptions\TimeBlockException;

class TimeBlock
{
    protected int $month;

    protected int $year;

    protected DeclarationTypes $type;

    protected BlockTypes $block;

    protected int $period;

    protected Carbon $from;

    protected Carbon $till;

    public function __construct(
        DeclarationTypes $type,
        int $year,
        int $month,
        BlockTypes $block,
        int $period,
        Carbon $from,
        Carbon $till
    )
    {
        if ($year < 1990 || $year > 2100) {
            throw TimeBlockException::invalidYear($year);
        }

        $this->year = $year;
        $this->month = $month;
        $this->type = $type;
        $this->block = $block;
        $this->period = $period;
        $this->from = $from;
        $this->till = $till;
    }

    public function getTypeLetter(): string
    {
        return match($this->type) {
            DeclarationTypes::LOAN => 'L',
            DeclarationTypes::REVENUE => 'B',
        };
    }

    public function getTypeCode(): int
    {
        return match($this->type) {
            DeclarationTypes::LOAN => 6,
            DeclarationTypes::REVENUE => 1,
        };
    }

    public function createTimeCode(): string
    {
        return sprintf('%1d%2s0',
            $this->getYearCode(),
            $this->getPeriodCode()
        );
    }

    public function getYearCode(): int
    {
        return (int)((string)$this->year)[- 1];
    }

    public function getPeriodCode(): string
    {
        return match ($this->type) {
             DeclarationTypes::LOAN => $this->getPeriodCodeForLoan(),
             DeclarationTypes::REVENUE =>$this->getPeriodCodeForRevenue(),
        };
    }

    public function getPeriodCodeForLoan(): string
    {
        switch ($this->block) {
            case BlockTypes::MONTHLY:
                return str_pad((string)$this->period, 2, '0', STR_PAD_LEFT);
            case BlockTypes::FOURWEEK:
                return $this->period >= 10 ? "8" . ((string)$this->period)[- 1] : "7" . $this->period;
            case BlockTypes::HALFYEAR:
                return "3" . $this->period;
            case BlockTypes::YEARLY:
                return "40";
        }

        throw PeriodException::invalidPeriod();
    }

    public function getPeriodCodeForRevenue(): string
    {
        switch ($this->block) {
            case BlockTypes::MONTHLY:
                return str_pad((string)$this->period, 2, '0', STR_PAD_LEFT);
            case BlockTypes::QUARTER:
                return (string)((int)$this->month + 20);
            case BlockTypes::YEARLY:
                return "40";
        }

        throw PeriodException::invalidPeriod();
    }

    public function getBlock(): BlockTypes
    {
        return $this->block;
    }

    public function setBlock(BlockTypes $block): void
    {
        $this->block = $block;
    }

    public function getPeriod(): int
    {
        return $this->period;
    }

    public function getFrom(): Carbon
    {
        return $this->from;
    }

    public function getTill(): Carbon
    {
        return $this->till;
    }

    public function getType(): DeclarationTypes
    {
        return $this->type;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getMonth(): int
    {
        return $this->month;
    }
}

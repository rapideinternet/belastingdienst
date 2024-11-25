<?php

declare(strict_types=1);

namespace Mijnkantoor\Belastingdienst;


use Carbon\Carbon;
use Mijnkantoor\Belastingdienst\Enums\BlockTypes;
use Mijnkantoor\Belastingdienst\Enums\DeclarationTypes;
use Mijnkantoor\Belastingdienst\Exceptions\DeclarationException;
use Mijnkantoor\Belastingdienst\Exceptions\TimeBlockException;

class TimeBlock
{
    public function __construct(
        protected DeclarationTypes $type, 
        protected int $year, 
        protected int $month, 
        protected BlockTypes $block, 
        protected int $period, 
        protected Carbon $from, 
        protected Carbon $till
        )
    {
        if ($year < 1990 || $year > 2100) {
            throw TimeBlockException::invalidYear($year);
        }
    }

    public function getTypeLetter(): string
    {
        return match ($this->type->getValue()) {
            DeclarationTypes::LOAN => 'L',
            DeclarationTypes::REVENUE => 'B',
            default => throw DeclarationException::notSupported($this->type->getValue()),
        };
    }

    public function getTypeCode(): int
    {
        return match ($this->type->getValue()) {
            DeclarationTypes::LOAN => 6,
            DeclarationTypes::REVENUE => 1,
            default => throw DeclarationException::notSupported($this->type->getValue()),
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
        return match ($this->type->getValue()) {
            DeclarationTypes::LOAN => $this->getPeriodCodeForLoan(),
            DeclarationTypes::REVENUE => $this->getPeriodCodeForRevenue(),
            default => throw DeclarationException::notSupported($this->type->getValue()),
        };
    }

    public function getPeriodCodeForLoan(): string
    {
        return match ($this->block->getValue()) {
            BlockTypes::MONTHLY => str_pad((string)$this->period, 2, '0', STR_PAD_LEFT),
            BlockTypes::FOURWEEK => $this->period >= 10 ? "8" . ((string)$this->period)[- 1] : "7" . $this->period,
            BlockTypes::HALFYEAR => "3" . $this->period,
            BlockTypes::YEARLY => "40",
            default => throw TimeBlockException::noPeriodCodeForLoan(),
        };
    }

    public function getPeriodCodeForRevenue(): string
    {
        return match ($this->block->getValue()) {
            BlockTypes::MONTHLY => str_pad((string)$this->period, 2, '0', STR_PAD_LEFT),
            BlockTypes::QUARTER => (string)((int)$this->month + 20),
            BlockTypes::YEARLY => "40",
            default => throw TimeBlockException::noPeriodCodeForRevenue(),
        };
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

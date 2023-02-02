<?php

namespace Unknown\Cleaning\Classes\Actions;

use Carbon\Carbon;
use Unknown\Cleaning\Classes\Interfaces\CleanerInterface;

class WindowCleaning extends BaseCleaning implements CleanerInterface
{

    public int $time = 35;
    public \Actions $action = \Actions::WindowCleaning;

    function getData(Carbon $date): array
    {
        if ($this->isLastWorkingDate($date)) {
            return $this->getoutput();
        }
        return [];
    }

    public function isLastWorkingDate(Carbon $date): bool
    {
        $lastDate = $date->clone();
        $lastDate->lastOfMonth();

        if ($lastDate->isWeekend()) {
            $lastDate->addDays(-1);
        }
        if ($lastDate->isWeekend()) {
            $lastDate->addDays(-1);
        }

        return $lastDate->toDateString() === $date->toDateString();
    }


}

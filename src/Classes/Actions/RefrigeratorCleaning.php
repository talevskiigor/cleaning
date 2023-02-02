<?php

namespace Unknown\Cleaning\Classes\Actions;

use Carbon\Carbon;
use Unknown\Cleaning\Classes\Interfaces\CleanerInterface;

class RefrigeratorCleaning extends BaseCleaning implements CleanerInterface
{

    public int $time = 50;
    public \Actions $action = \Actions::RefrigeratorCleaning;

    function getData(Carbon $date): array
    {
        if ($this->isFirstCleaningDay($date)) {
            return $this->getoutput();
        }
        return [];
    }

    private function isFirstCleaningDay(Carbon $date): bool
    {
        $firsDate = $date->clone();
        $firsDate->firstOfMonth();

        if ($firsDate < Carbon::now()) {
            $firsDate = Carbon::now();
        }

        while ($firsDate->dayOfWeek != Carbon::TUESDAY && $firsDate->dayOfWeek != Carbon::THURSDAY) {
            $firsDate->addDays(1);
        }

        return $firsDate->toDateString() == $date->toDateString();


    }


}

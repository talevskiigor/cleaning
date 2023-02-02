<?php

namespace Unknown\Cleaning\Classes\Actions;

use Carbon\Carbon;
use Unknown\Cleaning\Classes\Interfaces\CleanerInterface;

class VacuumCleaning extends BaseCleaning implements CleanerInterface
{

    public int $time = 21;
    public \Actions $action = \Actions::VacuumCleaning;

    function getData(Carbon $date): array
    {
        if ($date->dayOfWeek === Carbon::TUESDAY || $date->dayOfWeek === Carbon::THURSDAY) {
            return $this->getoutput();
        }
        return [];
    }


}

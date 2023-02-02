<?php

namespace Unknown\Cleaning\Classes\Interfaces;

use Carbon\Carbon;

interface CleanerInterface
{
    function getData(Carbon $date): array;
}

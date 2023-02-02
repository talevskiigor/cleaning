#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Unknown\Cleaning\Commands\CreateCleaningSchedule;

$application = new Application();

$application->add(new CreateCleaningSchedule());

$application->run();

<?php

namespace Unknown\Cleaning\Commands;

use APP_STRINGS;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCleaningSchedule extends Command
{

    protected static $defaultDescription = 'Create CSV schedule information.';

    protected function configure(): void
    {
        $this->setName('schedule:create');
        $this->addArgument(APP_STRINGS::MONTH, InputArgument::OPTIONAL, 'Number of months', 3);
        $this->setHelp('This command allows you to create CVS Schedule file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $calculationPeriod = $this->getCalculationPeriod($input->getArgument(APP_STRINGS::MONTH));

        $data = $this->getGeneratedData($calculationPeriod);

        $headers = ['Date', 'Action', 'Time'];

        $table = new Table($output);
        $table->setHeaders($headers);
        $table->setRows($data);
        $table->render();

        try {

            $fp = fopen('report.csv', 'w');
            fputcsv($fp,$headers);
            foreach ($data as $line) {
                fputcsv($fp, $line);
            }

            fclose($fp);
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
            return Command::FAILURE;
        }

    }

    protected function getCalculationPeriod(mixed $months): CarbonPeriod
    {
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addMonths($months);
        return new CarbonPeriod($startDate, $endDate);
    }

    protected function getActions(): array
    {
        $actions = [];
        foreach (\Actions::cases() as $action) {
            $actions[] = new $action->value;
        }
        return $actions;
    }

    protected function getGeneratedData(CarbonPeriod $calculationPeriod): array
    {
        $data = [];
        $actions = $this->getActions();

        foreach ($calculationPeriod as $date) {
            $results = [];
            foreach ($actions as $action) {
                $results[] = $action->getData($date);
            }

            if ($mergedData = array_merge_recursive(...$results)) {
                $minutes = is_array($mergedData['time']) ? array_sum($mergedData['time']) : $mergedData['time'];

                $data[] = $this->formatArray($date, $mergedData['action'], $minutes);

            };
        }
        return $data;
    }

    protected function formatArray(?\Carbon\CarbonInterface $date, $action1, mixed $minutes): array
    {
        return [
            'date' => $date->toDateString(),
            'action' => is_array($action1) ? implode(PHP_EOL, $action1) : $action1,
            'time' => Carbon::now()->diff(
                Carbon::now()->addMinute($minutes)
            )->format('%h:%i')
        ];
    }
}

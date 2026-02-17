<?php

namespace Database\Seeders;

use App\Services\TeamService;
use Illuminate\Database\Seeder;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class TeamSeeder extends Seeder
{
    private TeamService $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $output = new ConsoleOutput();
        $output->writeln('<info>Starting Teams import from BallDontLie API...</info>');
        $result = $this->teamService->importTeams();
        $output->writeln('');
        $output->writeln("<info>Teams imported successfully: {$result['imported']}</info>");
        if (!empty($result['errors'])) {
            $output->writeln("<error>Errors occurred: " . count($result['errors']) . "</error>");
        }
    }
}


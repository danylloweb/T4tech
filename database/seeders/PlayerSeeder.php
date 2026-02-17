<?php

namespace Database\Seeders;

use App\Services\PlayerService;
use Illuminate\Database\Seeder;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class PlayerSeeder extends Seeder
{
    private PlayerService $playerService;

    public function __construct(PlayerService $playerService)
    {
        $this->playerService = $playerService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $output = new ConsoleOutput();
        $output->writeln('<info>Starting Players import from BallDontLie API...</info>');
        $result = $this->playerService->importPlayers();
        $output->writeln('');
        $output->writeln("<info>Players imported successfully: {$result['imported']}</info>");
        if (!empty($result['errors'])) {
            $output->writeln("<error>Errors occurred: " . count($result['errors']) . "</error>");
        }
    }
}


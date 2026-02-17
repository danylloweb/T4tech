<?php

namespace Database\Seeders;

use App\Services\GameService;
use Illuminate\Database\Seeder;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class GameSeeder extends Seeder
{
    private GameService $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    /**
     * Run the database seeds.
     * Uses season 2024 (previous season) as current season is not free
     *
     * @return void
     */
    public function run(): void
    {
        $output = new ConsoleOutput();
        $season = 2024;
        $output->writeln("<info>Starting Games import from BallDontLie API for season {$season}...</info>");
        $result = $this->gameService->importGames($season);
        $output->writeln('');
        $output->writeln("<info>Games imported successfully: {$result['imported']}</info>");
        if (!empty($result['errors'])) {
            $output->writeln("<error>Errors occurred: " . count($result['errors']) . "</error>");
        }
    }
}


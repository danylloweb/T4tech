<?php

namespace App\Console\Commands;

use App\Integrations\BallDontLieIntegration;
use Illuminate\Console\Command;

class TestBallDontLieApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balldontlie:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test BallDontLie API connection and response format';

    private BallDontLieIntegration $integration;

    /**
     * Create a new command instance.
     */
    public function __construct(BallDontLieIntegration $integration)
    {
        parent::__construct();
        $this->integration = $integration;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Testing BallDontLie API connection...');
        $this->newLine();

        // Test Teams endpoint
        $this->line('Testing Teams endpoint...');
        $teamsResponse = $this->integration->send('GET', 'teams?per_page=2');

        if (isset($teamsResponse['error']) && $teamsResponse['error']) {
            $this->error('Teams endpoint failed!');
            $this->error('Error: ' . $teamsResponse['message']);
            return Command::FAILURE;
        }

        $this->info('✓ Teams endpoint working');
        $this->line('  - Teams found: ' . count($teamsResponse['data'] ?? []));
        $this->line('  - Has cursor: ' . (isset($teamsResponse['meta']['next_cursor']) ? 'Yes' : 'No'));
        $this->newLine();

        // Test Players endpoint
        $this->line('Testing Players endpoint...');
        $playersResponse = $this->integration->send('GET', 'players?per_page=2');

        if (isset($playersResponse['error']) && $playersResponse['error']) {
            $this->error('Players endpoint failed!');
            $this->error('Error: ' . $playersResponse['message']);
            return Command::FAILURE;
        }

        $this->info('✓ Players endpoint working');
        $this->line('  - Players found: ' . count($playersResponse['data'] ?? []));
        $this->line('  - Has cursor: ' . (isset($playersResponse['meta']['next_cursor']) ? 'Yes' : 'No'));
        $this->newLine();

        // Test Games endpoint
        $this->line('Testing Games endpoint...');
        $gamesResponse = $this->integration->send('GET', 'games?seasons[]=2024&per_page=2');

        if (isset($gamesResponse['error']) && $gamesResponse['error']) {
            $this->error('Games endpoint failed!');
            $this->error('Error: ' . $gamesResponse['message']);
            return Command::FAILURE;
        }

        $this->info('✓ Games endpoint working');
        $this->line('  - Games found: ' . count($gamesResponse['data'] ?? []));
        $this->line('  - Has cursor: ' . (isset($gamesResponse['meta']['next_cursor']) ? 'Yes' : 'No'));
        $this->newLine();

        // Test cursor pagination
        if (isset($playersResponse['meta']['next_cursor'])) {
            $this->line('Testing cursor pagination...');
            $cursor = $playersResponse['meta']['next_cursor'];
            $cursorResponse = $this->integration->send('GET', "players?per_page=2&cursor={$cursor}");

            if (isset($cursorResponse['error']) && $cursorResponse['error']) {
                $this->error('Cursor pagination failed!');
                return Command::FAILURE;
            }

            $this->info('✓ Cursor pagination working');
            $this->line('  - Next page players: ' . count($cursorResponse['data'] ?? []));
            $this->newLine();
        }

        $this->info('All API tests passed! ✓');
        $this->newLine();

        $this->info('API Configuration:');
        $this->line('  - Base URL: ' . config('balldontlie.url'));
        $this->line('  - API Key: ' . substr(config('balldontlie.apikey'), 0, 10) . '...');

        return Command::SUCCESS;
    }
}


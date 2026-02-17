<?php

namespace App\Services;

use App\Criterias\AppRequestCriteria;
use App\Integrations\BallDontLieIntegration;
use App\Repositories\GameRepository;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * GameService
 */
class GameService extends AppService
{
    /**
     * @var GameRepository
     */
    protected $repository;

    /**
     * @var BallDontLieIntegration
     */
    private BallDontLieIntegration $integration;

    /**
     * @param GameRepository $repository
     * @param BallDontLieIntegration $integration
     */
    public function __construct(GameRepository $repository, BallDontLieIntegration $integration)
    {
        $this->repository = $repository;
        $this->integration = $integration;
    }

    /**
     * @param int $limit
     * @return mixed
     * @throws RepositoryException
     */
    public function all(int $limit = 20): mixed
    {
        return $this->repository
            ->resetCriteria()
            ->pushCriteria(app(AppRequestCriteria::class))
            ->paginate($limit);
    }

    /**
     * Import games from BallDontLie API for a specific season
     *
     * @param int $season
     * @return array
     */
    public function importGames(int $season = 2024): array
    {
        $cursor   = null;
        $imported = 0;
        $errors   = [];

        do {
            // Build endpoint with cursor if available
            $endpoint = "games?seasons[]={$season}&per_page=100";
            if ($cursor) {
                $endpoint .= "&cursor={$cursor}";
            }

            $response = $this->integration->send('GET', $endpoint);

            if (isset($response['error']) && $response['error']) {
                Log::error('Error importing games', $response);
                $errors[] = $response;
                break;
            }

            if (empty($response['data'])) {
                break;
            }

            foreach ($response['data'] as $gameData) {
                try {
                    $this->repository->skipPresenter()->create([
                        'date'                       => $gameData['date'],
                        'season'                     => $gameData['season'],
                        'status'                     => $gameData['status'] ?? null,
                        'period'                     => $gameData['period'] ?? null,
                        'time'                       => $gameData['time'] ?? null,
                        'postseason'                 => $gameData['postseason'] ?? false,
                        'postponed'                  => $gameData['postponed'] ?? false,
                        'home_team_score'            => $gameData['home_team_score'] ?? null,
                        'visitor_team_score'         => $gameData['visitor_team_score'] ?? null,
                        'datetime'                   => $gameData['datetime'] ?? null,
                        'home_q1'                    => $gameData['home_q1'] ?? null,
                        'home_q2'                    => $gameData['home_q2'] ?? null,
                        'home_q3'                    => $gameData['home_q3'] ?? null,
                        'home_q4'                    => $gameData['home_q4'] ?? null,
                        'home_ot1'                   => $gameData['home_ot1'] ?? null,
                        'home_ot2'                   => $gameData['home_ot2'] ?? null,
                        'home_ot3'                   => $gameData['home_ot3'] ?? null,
                        'home_timeouts_remaining'    => $gameData['home_timeouts_remaining'] ?? null,
                        'home_in_bonus'              => $gameData['home_in_bonus'] ?? null,
                        'visitor_q1'                 => $gameData['visitor_q1'] ?? null,
                        'visitor_q2'                 => $gameData['visitor_q2'] ?? null,
                        'visitor_q3'                 => $gameData['visitor_q3'] ?? null,
                        'visitor_q4'                 => $gameData['visitor_q4'] ?? null,
                        'visitor_ot1'                => $gameData['visitor_ot1'] ?? null,
                        'visitor_ot2'                => $gameData['visitor_ot2'] ?? null,
                        'visitor_ot3'                => $gameData['visitor_ot3'] ?? null,
                        'visitor_timeouts_remaining' => $gameData['visitor_timeouts_remaining'] ?? null,
                        'visitor_in_bonus'           => $gameData['visitor_in_bonus'] ?? null,
                        'ist_stage'                  => $gameData['ist_stage'] ?? null,
                        'home_team_id'               => $gameData['home_team']['id'] ?? null,
                        'visitor_team_id'            => $gameData['visitor_team']['id'] ?? null
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    Log::error('Error saving game', [
                        'game_id' => $gameData['id'] ?? 'unknown',
                        'error'   => $e->getMessage()
                    ]);
                    $errors[] = [
                        'game_id' => $gameData['id'] ?? 'unknown',
                        'error'   => $e->getMessage()
                    ];
                }
            }

            // Respect API rate limit (30 requests per minute)
            sleep(2);

            // Get next cursor from meta
            $cursor = $response['meta']['next_cursor'] ?? null;

        } while ($cursor !== null);

        return [
            'imported' => $imported,
            'errors'   => $errors
        ];
    }

}


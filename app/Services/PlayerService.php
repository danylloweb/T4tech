<?php

namespace App\Services;

use App\Criterias\AppRequestCriteria;
use App\Integrations\BallDontLieIntegration;
use App\Repositories\PlayerRepository;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * PlayerService
 */
class PlayerService extends AppService
{
    /**
     * @var PlayerRepository
     */
    protected $repository;

    /**
     * @var BallDontLieIntegration
     */
    private BallDontLieIntegration $integration;

    /**
     * @param PlayerRepository $repository
     * @param BallDontLieIntegration $integration
     */
    public function __construct(PlayerRepository $repository, BallDontLieIntegration $integration)
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
     * Import players from BallDontLie API
     *
     * @return array
     */
    public function importPlayers(): array
    {
        $cursor   = null;
        $imported = 0;
        $errors   = [];

        do {
            // Build endpoint with cursor if available
            $endpoint = "players?per_page=100";
            if ($cursor) {
                $endpoint .= "&cursor={$cursor}";
            }

            $response = $this->integration->send('GET', $endpoint);

            if (isset($response['error']) && $response['error']) {
                Log::error('Error importing players', $response);
                $errors[] = $response;
                break;
            }

            if (empty($response['data'])) {
                break;
            }

            foreach ($response['data'] as $playerData) {
                try {
                    $this->repository->skipPresenter()->create([
                        'first_name'    => $playerData['first_name'],
                        'last_name'     => $playerData['last_name'],
                        'position'      => $playerData['position'] ?? null,
                        'height'        => $playerData['height'] ?? null,
                        'weight'        => $playerData['weight'] ?? null,
                        'jersey_number' => $playerData['jersey_number'] ?? null,
                        'college'       => $playerData['college'] ?? null,
                        'country'       => $playerData['country'] ?? null,
                        'draft_year'    => $playerData['draft_year'] ?? null,
                        'draft_round'   => $playerData['draft_round'] ?? null,
                        'draft_number'  => $playerData['draft_number'] ?? null,
                        'team_id'       => $playerData['team']['id'] ?? null
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    Log::error('Error saving player', [
                        'player_id' => $playerData['id'] ?? 'unknown',
                        'error'     => $e->getMessage()
                    ]);
                    $errors[] = [
                        'player_id' => $playerData['id'] ?? 'unknown',
                        'error'     => $e->getMessage()
                    ];
                }
            }
            sleep(2);
            $cursor = $response['meta']['next_cursor'] ?? null;

        } while ($cursor !== null);

        return [
            'imported' => $imported,
            'errors'   => $errors
        ];
    }

}


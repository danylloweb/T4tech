<?php

namespace App\Services;

use App\Criterias\AppRequestCriteria;
use App\Criterias\FilterByTeamIdCriteria;
use App\Integrations\BallDontLieIntegration;
use App\Repositories\PlayerRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Exceptions\RepositoryException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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
            ->pushCriteria(app(FilterByTeamIdCriteria::class))
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
        $cursor   = 25;
        $imported = 0;
        $errors   = [];

        do {
            $endpoint = "players";
            if ($cursor) {
                $endpoint .= "?cursor={$cursor}";
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
                        'last_name'     => $playerData['last_name']?? null,
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
                        'error'     => $e->getMessage(),
                        'body'      => $playerData
                    ]);
                    $errors[] = [
                        'player_id' => $playerData['id'] ?? 'unknown',
                        'error'     => $e->getMessage(),

                    ];
                }
            }

            $cursor = $response['meta']['next_cursor'] ?? null;
            if ($cursor > 2000){
                $cursor = null;
            }
        } while ($cursor !== null);

        return [
            'imported' => $imported,
            'errors'   => $errors
        ];
    }

}


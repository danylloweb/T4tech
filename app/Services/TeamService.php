<?php

namespace App\Services;

use App\Criterias\AppRequestCriteria;
use App\Integrations\BallDontLieIntegration;
use App\Repositories\TeamRepository;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * TeamService
 */
class TeamService extends AppService
{
    /**
     * @var TeamRepository
     */
    protected $repository;

    /**
     * @var BallDontLieIntegration
     */
    private BallDontLieIntegration $integration;

    /**
     * @param TeamRepository $repository
     * @param BallDontLieIntegration $integration
     */
    public function __construct(TeamRepository $repository, BallDontLieIntegration $integration)
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
     * Import teams from BallDontLie API
     *
     * @return array
     */
    public function importTeams(): array
    {
        $cursor   = null;
        $imported = 0;
        $errors   = [];

        do {
            // Build endpoint with cursor if available
            $endpoint = "teams?per_page=100";
            if ($cursor) {
                $endpoint .= "&cursor={$cursor}";
            }

            $response = $this->integration->send('GET', $endpoint);

            if (isset($response['error']) && $response['error']) {
                Log::error('Error importing teams', $response);
                $errors[] = $response;
                break;
            }

            if (empty($response['data'])) {
                break;
            }

            foreach ($response['data'] as $teamData) {
                try {
                    $this->repository->skipPresenter()->create([
                        'conference'   => $teamData['conference'] ?? '',
                        'division'     => $teamData['division'] ?? '',
                        'city'         => $teamData['city'] ?? '',
                        'name'         => $teamData['name'],
                        'full_name'    => $teamData['full_name'],
                        'abbreviation' => $teamData['abbreviation']
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    Log::error('Error saving team', [
                        'team_id' => $teamData['id'] ?? 'unknown',
                        'error'   => $e->getMessage()
                    ]);
                    $errors[] = [
                        'team_id' => $teamData['id'] ?? 'unknown',
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

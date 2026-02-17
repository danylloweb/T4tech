<?php

namespace App\Services;

use App\Criterias\AppRequestCriteria;
use App\Integrations\BallDontLieIntegration;
use App\Repositories\TeamRepository;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Exceptions\RepositoryException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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
        $imported = 0;
        $errors   = [];

        $endpoint = "teams";
        $response = $this->integration->send('GET', $endpoint);

        if (isset($response['error']) && $response['error']) {
            Log::error('Error importing teams', $response);
            $errors[] = $response;
            return [
                'imported' => 0,
                'errors' => $errors
            ];
        }


        foreach ($response['data'] as $teamData) {
            try {
                $conference = trim($teamData['conference'] ?? '');
                $division   = trim($teamData['division'] ?? '');
                $city       = trim($teamData['city'] ?? '');
                $conference = empty($conference) ? 'N/A' : $conference;
                $division   = empty($division) ? 'N/A' : $division;
                $city       = empty($city) ? 'N/A' : $city;

                $this->repository->skipPresenter()->create([
                    'conference'   => $conference,
                    'division'     => $division,
                    'city'         => $city,
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

        return [
            'imported' => $imported,
            'errors'   => $errors
        ];
    }

}

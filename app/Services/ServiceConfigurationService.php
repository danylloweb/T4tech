<?php

namespace App\Services;

use App\Criterias\AppRequestCriteria;
use App\Criterias\FilterByRefIdProviderCriteria;
use App\Criterias\FilterByServiceConfigTypeCriteria;
use App\Repositories\ServiceConfigurationRepository;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * ServiceConfigurationService
 */
class ServiceConfigurationService extends AppService
{
    /**
     * @var ServiceConfigurationRepository
     */
    protected $repository;

    /**
     * @param ServiceConfigurationRepository $repository
     */
    public function __construct(ServiceConfigurationRepository $repository)
    {
        $this->repository = $repository;
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
            ->pushCriteria(app(FilterByServiceConfigTypeCriteria::class))
            ->pushCriteria(app(FilterByRefIdProviderCriteria::class))
            ->pushCriteria(app(AppRequestCriteria::class))
            ->paginate($limit);
    }

}

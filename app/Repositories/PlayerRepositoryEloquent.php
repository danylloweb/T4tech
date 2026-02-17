<?php

namespace App\Repositories;

use App\Presenters\PlayerPresenter;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PlayerRepository;
use App\Entities\Player;
use App\Validators\PlayerValidator;

/**
 * Class PlayerRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PlayerRepositoryEloquent extends BaseRepository implements PlayerRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Player::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return PlayerValidator::class;
    }

    public function presenter()
    {
        return PlayerPresenter::class;
    }

}

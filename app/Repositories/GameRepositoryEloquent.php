<?php

namespace App\Repositories;

use App\Presenters\GamePresenter;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\GameRepository;
use App\Entities\Game;
use App\Validators\GameValidator;

/**
 * Class GameRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class GameRepositoryEloquent extends BaseRepository implements GameRepository
{

    protected $fieldSearchable = [
        'season' => 'like',
    ];

    /**
     * Regras para busca
     *
     * @var array
     */
    protected $fieldsRules = [
        'season' => ['numeric'],
    ];
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Game::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return GameValidator::class;
    }



    public function presenter()
    {
        return GamePresenter::class;
    }

}

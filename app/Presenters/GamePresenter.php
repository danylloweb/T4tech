<?php

namespace App\Presenters;

use App\Transformers\GameTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class GamePresenter.
 *
 * @package namespace App\Presenters;
 */
class GamePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new GameTransformer();
    }
}

<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class PlayerValidator.
 *
 * @package namespace App\Validators;
 */
class PlayerValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'first_name'    => 'sometimes|required|string|max:100',
            'last_name'     => 'sometimes|required|string|max:100',
            'position'      => 'nullable|string|max:10',
            'height'        => 'nullable|string|max:20',
            'weight'        => 'nullable|string|max:20',
            'jersey_number' => 'nullable|string|max:10',
            'college'       => 'nullable|string|max:150',
            'country'       => 'nullable|string|max:100',
            'draft_year'    => 'nullable|integer|min:1900',
            'draft_round'   => 'nullable|integer|min:1',
            'draft_number'  => 'nullable|integer|min:1',
            'team_id'       => 'nullable|integer|exists:teams,id'
        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];

    /**
     * Validation Messages
     *
     * @var array
     */
    protected $messages = [
        'first_name.required' => 'O campo primeiro nome é obrigatório.',
        'first_name.string'   => 'O primeiro nome deve ser um texto.',
        'first_name.max'      => 'O primeiro nome não pode exceder 100 caracteres.',
        'last_name.required'  => 'O campo sobrenome é obrigatório.',
        'last_name.string'    => 'O sobrenome deve ser um texto.',
        'last_name.max'       => 'O sobrenome não pode exceder 100 caracteres.',
        'position.string'     => 'A posição deve ser um texto.',
        'position.max'        => 'A posição não pode exceder 10 caracteres.',
        'height.string'       => 'A altura deve ser um texto.',
        'height.max'          => 'A altura não pode exceder 20 caracteres.',
        'weight.string'       => 'O peso deve ser um texto.',
        'weight.max'          => 'O peso não pode exceder 20 caracteres.',
        'jersey_number.string'=> 'O número da camisa deve ser um texto.',
        'jersey_number.max'   => 'O número da camisa não pode exceder 10 caracteres.',
        'college.string'      => 'A faculdade deve ser um texto.',
        'college.max'         => 'A faculdade não pode exceder 150 caracteres.',
        'country.string'      => 'O país deve ser um texto.',
        'country.max'         => 'O país não pode exceder 100 caracteres.',
        'draft_year.integer'  => 'O ano do draft deve ser um número inteiro.',
        'draft_year.min'      => 'O ano do draft deve ser no mínimo 1900.',
        'draft_round.integer' => 'A rodada do draft deve ser um número inteiro.',
        'draft_round.min'     => 'A rodada do draft deve ser no mínimo 1.',
        'draft_number.integer'=> 'O número do draft deve ser um número inteiro.',
        'draft_number.min'    => 'O número do draft deve ser no mínimo 1.',
        'team_id.integer'     => 'O ID do time deve ser um número inteiro.',
        'team_id.exists'      => 'O time selecionado não existe.',
    ];
}

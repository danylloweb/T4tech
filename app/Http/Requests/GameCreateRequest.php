<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GameCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date'                       => 'sometimes|required|date',
            'season'                     => 'sometimes|required|integer|min:1900|max:' . (date('Y') + 1),
            'status'                     => 'sometimes|required|string|max:50',
            'period'                     => 'nullable|integer|min:1',
            'time'                       => 'nullable|string|max:20',
            'postseason'                 => 'nullable|boolean',
            'postponed'                  => 'nullable|boolean',
            'home_team_score'            => 'nullable|integer|min:0',
            'visitor_team_score'         => 'nullable|integer|min:0',
            'datetime'                   => 'nullable|date',
            'home_q1'                    => 'nullable|integer|min:0',
            'home_q2'                    => 'nullable|integer|min:0',
            'home_q3'                    => 'nullable|integer|min:0',
            'home_q4'                    => 'nullable|integer|min:0',
            'home_ot1'                   => 'nullable|integer|min:0',
            'home_ot2'                   => 'nullable|integer|min:0',
            'home_ot3'                   => 'nullable|integer|min:0',
            'home_timeouts_remaining'    => 'nullable|integer|min:0',
            'home_in_bonus'              => 'nullable|boolean',
            'visitor_q1'                 => 'nullable|integer|min:0',
            'visitor_q2'                 => 'nullable|integer|min:0',
            'visitor_q3'                 => 'nullable|integer|min:0',
            'visitor_q4'                 => 'nullable|integer|min:0',
            'visitor_ot1'                => 'nullable|integer|min:0',
            'visitor_ot2'                => 'nullable|integer|min:0',
            'visitor_ot3'                => 'nullable|integer|min:0',
            'visitor_timeouts_remaining' => 'nullable|integer|min:0',
            'visitor_in_bonus'           => 'nullable|boolean',
            'ist_stage'                  => 'nullable|string|max:50',
            'home_team_id'               => 'nullable|integer|exists:teams,id',
            'visitor_team_id'            => 'nullable|integer|exists:teams,id'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'date.required'                      => 'O campo data é obrigatório.',
            'date.date'                          => 'O campo data deve ser uma data válida.',
            'season.required'                    => 'O campo temporada é obrigatório.',
            'season.integer'                     => 'A temporada deve ser um número inteiro.',
            'season.min'                         => 'A temporada deve ser no mínimo 1900.',
            'season.max'                         => 'A temporada não pode ser maior que ' . (date('Y') + 1) . '.',
            'status.required'                    => 'O campo status é obrigatório.',
            'status.string'                      => 'O status deve ser um texto.',
            'status.max'                         => 'O status não pode exceder 50 caracteres.',
            'period.integer'                     => 'O período deve ser um número inteiro.',
            'period.min'                         => 'O período deve ser no mínimo 1.',
            'time.string'                        => 'O tempo deve ser um texto.',
            'time.max'                           => 'O tempo não pode exceder 20 caracteres.',
            'postseason.boolean'                 => 'O campo pós-temporada deve ser verdadeiro ou falso.',
            'postponed.boolean'                  => 'O campo adiado deve ser verdadeiro ou falso.',
            'home_team_score.integer'            => 'A pontuação do time da casa deve ser um número inteiro.',
            'home_team_score.min'                => 'A pontuação do time da casa deve ser no mínimo 0.',
            'visitor_team_score.integer'         => 'A pontuação do time visitante deve ser um número inteiro.',
            'visitor_team_score.min'             => 'A pontuação do time visitante deve ser no mínimo 0.',
            'datetime.date'                      => 'O campo data/hora deve ser uma data válida.',
            'home_q1.integer'                    => 'Q1 do time da casa deve ser um número inteiro.',
            'home_q1.min'                        => 'Q1 do time da casa deve ser no mínimo 0.',
            'home_q2.integer'                    => 'Q2 do time da casa deve ser um número inteiro.',
            'home_q2.min'                        => 'Q2 do time da casa deve ser no mínimo 0.',
            'home_q3.integer'                    => 'Q3 do time da casa deve ser um número inteiro.',
            'home_q3.min'                        => 'Q3 do time da casa deve ser no mínimo 0.',
            'home_q4.integer'                    => 'Q4 do time da casa deve ser um número inteiro.',
            'home_q4.min'                        => 'Q4 do time da casa deve ser no mínimo 0.',
            'home_ot1.integer'                   => 'OT1 do time da casa deve ser um número inteiro.',
            'home_ot1.min'                       => 'OT1 do time da casa deve ser no mínimo 0.',
            'home_ot2.integer'                   => 'OT2 do time da casa deve ser um número inteiro.',
            'home_ot2.min'                       => 'OT2 do time da casa deve ser no mínimo 0.',
            'home_ot3.integer'                   => 'OT3 do time da casa deve ser um número inteiro.',
            'home_ot3.min'                       => 'OT3 do time da casa deve ser no mínimo 0.',
            'home_timeouts_remaining.integer'    => 'Tempos restantes do time da casa deve ser um número inteiro.',
            'home_timeouts_remaining.min'        => 'Tempos restantes do time da casa deve ser no mínimo 0.',
            'home_in_bonus.boolean'              => 'O campo bônus do time da casa deve ser verdadeiro ou falso.',
            'visitor_q1.integer'                 => 'Q1 do time visitante deve ser um número inteiro.',
            'visitor_q1.min'                     => 'Q1 do time visitante deve ser no mínimo 0.',
            'visitor_q2.integer'                 => 'Q2 do time visitante deve ser um número inteiro.',
            'visitor_q2.min'                     => 'Q2 do time visitante deve ser no mínimo 0.',
            'visitor_q3.integer'                 => 'Q3 do time visitante deve ser um número inteiro.',
            'visitor_q3.min'                     => 'Q3 do time visitante deve ser no mínimo 0.',
            'visitor_q4.integer'                 => 'Q4 do time visitante deve ser um número inteiro.',
            'visitor_q4.min'                     => 'Q4 do time visitante deve ser no mínimo 0.',
            'visitor_ot1.integer'                => 'OT1 do time visitante deve ser um número inteiro.',
            'visitor_ot1.min'                    => 'OT1 do time visitante deve ser no mínimo 0.',
            'visitor_ot2.integer'                => 'OT2 do time visitante deve ser um número inteiro.',
            'visitor_ot2.min'                    => 'OT2 do time visitante deve ser no mínimo 0.',
            'visitor_ot3.integer'                => 'OT3 do time visitante deve ser um número inteiro.',
            'visitor_ot3.min'                    => 'OT3 do time visitante deve ser no mínimo 0.',
            'visitor_timeouts_remaining.integer' => 'Tempos restantes do time visitante deve ser um número inteiro.',
            'visitor_timeouts_remaining.min'     => 'Tempos restantes do time visitante deve ser no mínimo 0.',
            'visitor_in_bonus.boolean'           => 'O campo bônus do time visitante deve ser verdadeiro ou falso.',
            'ist_stage.string'                   => 'O estágio IST deve ser um texto.',
            'ist_stage.max'                      => 'O estágio IST não pode exceder 50 caracteres.',
            'home_team_id.integer'               => 'O ID do time da casa deve ser um número inteiro.',
            'home_team_id.exists'                => 'O time da casa selecionado não existe.',
            'visitor_team_id.integer'            => 'O ID do time visitante deve ser um número inteiro.',
            'visitor_team_id.exists'             => 'O time visitante selecionado não existe.',
        ];
    }
}

<?php

namespace App;

/**
 * Class Helper
 * @package App
 */
class AppHelper
{
    /**
     * @param $string
     * @return string
     */
    public static function removeSpecialCharacters($string):string
    {
        return preg_replace('/[^A-Za-z0-9]/', '', $string);
    }

    /**
     * @param $string
     * @return array|string|string[]|null
     */
    public static function removeAccentuation($string): array|string|null
    {
        return preg_replace([
            "/(á|à|ã|â|ä)/",
            "/(Á|À|Ã|Â|Ä)/",
            "/(é|è|ê|ë)/",
            "/(É|È|Ê|Ë)/",
            "/(í|ì|î|ï)/",
            "/(Í|Ì|Î|Ï)/",
            "/(ó|ò|õ|ô|ö)/",
            "/(Ó|Ò|Õ|Ô|Ö)/",
            "/(ú|ù|û|ü)/",
            "/(Ú|Ù|Û|Ü)/",
            "/(ñ)/",
            "/(Ñ)/"
        ], explode(" ", "a A e E i I o O u U n N"), $string);
    }

    /**
     * Price formatter
     *
     * @param $value
     * @return string
     */
    public static function formatPrice($value): string
    {
        if (str_contains($value, '.')) {
            $exp = explode('.', $value);

            if (mb_strlen($exp[1]) == 1) {
                $decimal = $exp[1] . '0';
            } else {
                $decimal = $exp[1];
            }

            $price = $exp[0] . $decimal;
        } else {
            $price = $value . '00';
        }

        return $price;
    }

    /**
     * Insert blank spaces into string
     *
     * @param $quantity
     * @return string
     */
    public static function insertSpace($quantity): string
    {
        return str_repeat(' ', $quantity);
    }

    /**
     * Remove blank spaces into string
     *
     * @param $value
     * @return string
     */
    public static function removeSpaces($value): string
    {
        return trim(str_replace(" ", "", $value));
    }

    /**
     * Insert characters to the left side of string
     *
     * @param $value
     * @param $qtd
     * @param $char
     * @param bool $custom
     * @return string
     */
    public static function insertChar($value, $qtd, $char, bool $custom = false): string
    {
        if (mb_strlen($value) > $qtd) {
            return substr($value, 0, $qtd);
        }

        $quantity = $qtd - mb_strlen($value);

        $return = str_repeat($char, $quantity);

        if ($custom) {
            return $value . $return;
        }

        return $return . $value;
    }

    /**
     * Read array and return this values
     *
     * @param $values
     * @return string $result
     */
    public static function getValues($values): string
    {
        return implode('', $values);
    }

    /**
     * Check if is a valid date
     *
     * @param $date
     * @return bool
     */
    public static function isValidDate($date): bool
    {
        return preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $date);
    }

    /**
     * @param $date
     * @return false|string
     */
    public static function formatDateDB($date): bool|string
    {
        return (self::isValidDate($date)) ? $date : date("Y-m-d", strtotime($date));
    }

    /**
     * Return age by date of birth
     *
     * @param $dateBirth
     * @return int|null
     */
    public static function getAgeByDateBirth($dateBirth): ?int
    {
        return (empty($dateBirth)) ? null : date_diff(date_create($dateBirth), date_create('now'))->y;
    }


    /**
     * @param array $majorities
     * @return false|string
     */
    public static function buildProfessionalDetail(array $majorities)
    {
        /** Flags de referência */
        $goodFlags = [
            'arrived_on_time',      // Pontual
            'polite_professional',  // Gentil
            'excellent_service',    // Excelente Profissional
            'clean_service',        // Serviço limpo
        ];

        $badFlags = [
            'arrived_outside_scheduled_window',
            'rude_professional',
            'poor_service',
            'left_environment_dirty',
            'other_bad',
        ];

        /** Mapeia cada boa flag para seu “characteristic” */
        $characteristicMap = [
            'arrived_on_time' => [
                'icon' => 'clock',
                'name' => 'Pontual',
                'value' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120l0 136c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2 280 120c0-13.3-10.7-24-24-24s-24 10.7-24 24z" /></svg>',
            ],
            'polite_professional' => [
                'icon' => 'smile',
                'name' => 'Gentil',
                'value' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm0 448c-110.3 0-200-89.7-200-200S137.7 56 248 56s200 89.7 200 200-89.7 200-200 200zm-80-216c17.7 0 32-14.3 32-32s-14.3-32-32-32-32 14.3-32 32 14.3 32 32 32zm160 0c17.7 0 32-14.3 32-32s-14.3-32-32-32-32 14.3-32 32 14.3 32 32 32zm4 72.6c-20.8 25-51.5 39.4-84 39.4s-63.2-14.3-84-39.4c-8.5-10.2-23.7-11.5-33.8-3.1-10.2 8.5-11.5 23.6-3.1 33.8 30 36 74.1 56.6 120.9 56.6s90.9-20.6 120.9-56.6c8.5-10.2 7.1-25.3-3.1-33.8-10.1-8.4-25.3-7.1-33.8 3.1z"/></svg>',
            ],
            'excellent_service' => [
                'icon' => 'screwdriver-wrench',
                'name' => 'Excelente Profissional',
                'value' => '<svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg"><path d="M4.65625 1.23438L9.53125 4.98438C9.8125 5.21875 10 5.54688 10 5.875V8.40625L15.1094 13.5156C16.4688 12.8594 18.1562 13.0938 19.2812 14.2188L24.5312 19.4688C25.1406 20.0312 25.1406 21.0156 24.5312 21.5781L21.5312 24.5781C20.9688 25.1875 19.9844 25.1875 19.4219 24.5781L14.1719 19.3281C13.0469 18.2031 12.8125 16.4688 13.5156 15.1094L8.40625 10H5.82812C5.5 10 5.17188 9.85938 4.9375 9.57812L1.1875 4.70312C0.859375 4.23438 0.90625 3.625 1.32812 3.20312L3.20312 1.32812C3.57812 0.953125 4.23438 0.90625 4.65625 1.23438Z"/></svg>',
            ],
            'clean_service' => [
                'icon' => 'broom',
                'name' => 'Serviço limpo',
                'value' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M64 480L448 96l-32-32L32 448l32 32zM112 512l-96-96 32-32 96 96-32 32z"/></svg>',
            ],
        ];

        // Conta quantas flags boas/ruins têm maioria
        $goodMajorities = array_filter($goodFlags, fn($f) => $majorities[$f] ?? false);
        $badMajorities = array_filter($badFlags, fn($f) => $majorities[$f] ?? false);

        /* ---------- Seleciona título / descrição conforme resultado ---------- */
        if (count($goodMajorities) >= 3 && count($badMajorities) === 0) {
            $title = 'Super profissional';
            $iconTitle = 'circle-star';
            $description = 'Com base nas avaliações dos clientes nos últimos 90 dias, este profissional está classificado entre os melhores da Madeira Serviços';
        } elseif (count($goodMajorities) >= 1 && count($badMajorities) <= count($goodMajorities)) {
            $title = 'Profissional bem avaliado';
            $iconTitle = 'medal';
            $description = 'Os clientes avaliaram positivamente este profissional nos últimos 90 dias.';
        } else {
            $title = 'Em aperfeiçoamento';
            $iconTitle = 'circle-exclamation';
            $description = 'Há oportunidades de melhoria apontadas pelos clientes nas avaliações recentes.';
        }

        /* ---------- Monta characteristics apenas com flags boas em maioria ---------- */
        $characteristics = [];
        foreach ($goodMajorities as $flag) {
            $characteristics[] = $characteristicMap[$flag];
        }

        return json_encode([
            'title'           => $title,
            'iconTitle'       => $iconTitle,
            'description'     => $description,
            'characteristics' => $characteristics,
        ]);
    }


}

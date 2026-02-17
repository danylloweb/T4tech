<?php

namespace Database\Seeders;
ini_set('memory_limit', '-1');

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class DatabaseSeeder extends Seeder
{

    /**
     * @return void
     */
    public function run(): void
    {
        $this->call(UserTypeSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(TeamSeeder::class);
        $this->call(PlayerSeeder::class);
        $this->call(GameSeeder::class);
    }
}

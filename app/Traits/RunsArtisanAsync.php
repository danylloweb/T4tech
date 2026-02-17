<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

trait RunsArtisanAsync
{
    /**
     * Executa um comando Artisan de forma assíncrona.
     *
     * Ex:
     *  $this->runArtisanAsync('command:name', ['--force' => null]);
     */
    protected function runArtisanAsync(string $command, array $options = []): void
    {
        try {
            $artisanPath = base_path('artisan');
            $phpBinary = defined('PHP_BINARY') ? PHP_BINARY : 'php';
            $processArgs = [$phpBinary, $artisanPath, $command];

            foreach ($options as $key => $value) {
                $optionKey = (str_starts_with((string)$key, '-')) ? (string)$key : '--' . (string)$key;
                if ($value === null) {
                    $processArgs[] = $optionKey;
                } else {
                    $processArgs[] = $optionKey . '=' . (string)$value;
                }
            }

            $process = new Process($processArgs);
            $process->setWorkingDirectory(base_path());
            $process->setTimeout(null);
            $process->start();
        } catch (\Throwable $e) {
            Log::error("Falha ao iniciar comando [{$command}]: " . $e->getMessage(), ['exception' => $e]);
        }
    }
}

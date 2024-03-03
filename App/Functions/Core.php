<?php

namespace App\Functions;
use JetBrains\PhpStorm\NoReturn;

/** This class contains basic functions that can be used in anywhere */
class Core
{
    /**
     * @param string $key
     * @return string|null
     */
    public static function getEnvVariable(string $key): ?string
    {
        $envFile = dirname(__DIR__) . '/../.env';
        $envContent = file_get_contents($envFile);
        $envLines = explode("\n", $envContent);

        foreach ($envLines as $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#')) {
                continue;
            }

            list($envKey, $envValue) = explode('=', $line, 2);

            if ($envKey === $key) {
                return $envValue;
            }
        }

        return null;
    }

    /**
     * @param $text
     * @return array|string
     */
    public static function html($text): array|string
    {
        return str_replace(['<','>'],['&#60;','&#62;'],$text);
    }

    /**
     * @param $data
     * @return void
     */
    #[NoReturn] public static function dd($data) : void
    {
        echo sprintf("<pre>%s</pre>", die(var_dump($data)));
    }
}
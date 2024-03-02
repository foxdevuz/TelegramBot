<?php

namespace App\Functions;
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
}
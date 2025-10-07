<?php

namespace App\Services;

class CliStyleMessage
{
    public static function displayErrorMessage(string $message): \RuntimeException {
        $msg = strtoupper("Erreur critique : " . $message);
        $border = str_repeat('=', strlen($msg) + 4);
        echo "\033[1;31m$border\n";
        echo "| $msg |\n";
        echo "$border\033[0m\n";
        return new \RuntimeException($msg);
    }

    public static function displaySuccessMessage(string $message): string {
        $msg = strtoupper("Succès : " . $message);
        $border = str_repeat('=', strlen($msg) + 1);
        echo "\033[1;32m$border\n";
        echo "| $msg |\n";
        echo "$border\033[0m\n";
        return $msg;
    }

    public static function displayLoadingBar(int $durationInSeconds = 2): void {
        echo "Arrêt du serveur en cours... ";
        $steps = 20;
        $sleepTime = ($durationInSeconds * 1000000) / $steps; // en microsecondes
        for ($i = 0; $i <= $steps; $i++) {
            echo "\033[42m \033[0m"; // Bloc vert
            usleep($sleepTime);
            flush();
        }
        echo "  OK\n";
    }

    public static function displayInfoMessage(string $message): void {
        echo $message . "\n";
    }
}

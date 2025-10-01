<?php

require_once 'vendor/autoload.php';

use App\Services\CliStyleMessage;
use App\Services\CommandManager;
use App\Services\CommandProvider;

function mainLoop() {
    $commandManager = new CommandManager();
    $checker = CommandProvider::getInstance($commandManager);
    list($commandMap, $commandInfo) = $checker->getCommandNamesAndDescriptions();

    while (true) {
        $line = readline("Entrer votre commande (\u{23CE} Entrée pour voir la liste des commandes ou 'help'): ");
        if ($line === 'exit') {
            echo "Au revoir !\n";
            break;
        }
        if ($line === '' || $line === 'help') {
            echo "Commandes disponibles :\n";
            foreach ($commandInfo as $desc) {
                echo " $desc\n";
            }
            continue;
        }
        if (isset($commandMap[$line])) {
            $commandMap[$line]->execute([]);
        } else {
            echo "Commande inconnue. Essayez peut être :\n";
            foreach ($commandInfo as $desc) {
                echo " $desc\n";
            }
        }
    }
}

try {
    mainLoop();
} catch (\RuntimeException $e) {
    CliStyleMessage::displayLoadingBar();
    exit(1);
}


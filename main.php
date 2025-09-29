<?php

require_once 'vendor/autoload.php';

use App\Services\CommandManager;
use App\Services\CommandProvider;

function mainLoop() {
    $commandManager = new CommandManager();
    $checker = CommandProvider::getInstance($commandManager);
    list($commandMap, $commandInfo) = $checker->getCommandNamesAndDescriptions();

    while (true) {
        $line = readline("Entrer votre commande : ");
        if ($line === 'exit') {
            echo "Au revoir !\n";
            break;
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

mainLoop();

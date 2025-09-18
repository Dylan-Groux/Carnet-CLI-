<?php

require_once 'services/bddManager.php';
require_once 'services/commandManager.php';

while (true) {
    $line = readline("Entrer votre commande : ");
    echo "Vous avez entré : " . $line . "\n";
    if ($line === 'exit') {
        echo "Au revoir !\n";
        break;
    }
    if ($line === 'liste') {
        Commande::list();
    } else {
        ContactManager::afficherErreur("Commande invalide, veuillez entrer 'liste' ou 'exit'.");
    }
}

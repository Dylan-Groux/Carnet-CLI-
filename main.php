<?php

require_once 'vendor/autoload.php';

use App\Services\CommandManager;
use App\Services\ContactManager;

require_once 'src/services/CommandManager.php';

function mainLoop() {
    $contactManager = new ContactManager();
    $command = new CommandManager();

    while (true) {
        $line = readline("Entrer votre commande : ");
        echo "Vous avez entré : " . $line . "\n";
        if ($line === 'exit') {
            echo "Au revoir !\n";
            break;
        }
        if ($line === 'liste') {
            $command->listContacts();
        } elseif ($line === 'detail') {
            $id = readline("Entrez l'ID du contact : ");
            if (is_numeric($id)) {
                $command->detailContact($id);
            } else {
                $contactManager->afficherErreur("L'ID doit être un nombre.");
            }
        } elseif ($line === 'create') {
            $command->createContact();
        } elseif ($line === 'delete') {
            $command->deleteContact();
        } elseif ($line === 'help') {
            echo "Liste des commandes disponibles :\n";
            echo "- liste : Afficher la liste des contacts\n";
            echo "- detail : Afficher les détails d'un contact\n";
            echo "- create : Créer un nouveau contact\n";
            echo "- delete : Supprimer un contact\n";
            echo "- help : Afficher cette aide\n";
            echo "- modify : Modifier un contact\n";
            echo "- find : Rechercher des contacts via des critères (nom, email, téléphone)\n";
            echo "- exit : Quitter l'application\n";
        } elseif ($line === 'modify') {
            $command->modifyContact();
        } elseif ($line === 'find') {
            $command->findContacts();
        }
    }
}

mainLoop();

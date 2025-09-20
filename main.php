<?php

require_once 'services/commandManager.php';

use Services\CommandeManager;
use Services\ContactManager;

function mainLoop() {
    $contactManager = new ContactManager();
    $commande = new CommandeManager();

    while (true) {
        $line = readline("Entrer votre commande : ");
        echo "Vous avez entré : " . $line . "\n";
        if ($line === 'exit') {
            echo "Au revoir !\n";
            break;
        }
        if ($line === 'liste') {
            $commande->list();
        } elseif ($line === 'detail') {
            $id = readline("Entrez l'ID du contact : ");
            if (is_numeric($id)) {
                $commande->detail($id);
            } else {
                $contactManager->afficherErreur("L'ID doit être un nombre.");
            }
        } elseif ($line === 'create') {
            $commande->create();
        } elseif ($line === 'delete') {
            $commande->delete();
        } elseif ($line === 'help') {
            echo "Liste des commandes disponibles :\n";
            echo "- liste : Afficher la liste des contacts\n";
            echo "- detail : Afficher les détails d'un contact\n";
            echo "- create : Créer un nouveau contact\n";
            echo "- delete : Supprimer un contact\n";
            echo "- help : Afficher cette aide\n";
            echo "- modify : Modifier un contact\n";
            echo "- find : Rechercher des contacts\n";
            echo "- exit : Quitter l'application\n";
        } elseif ($line === 'modify') {
            $commande->modify();
        } elseif ($line === 'find') {
            $commande->find();
        }
    }
}

mainLoop();
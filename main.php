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
    } elseif ($line === 'detail') {
        $id = readline("Entrez l'ID du contact : ");
        if (is_numeric($id)) {
            Commande::detail($id);
        } else {
            ContactManager::afficherErreur("L'ID doit être un nombre.");
        }
    } elseif ($line === 'create') {
        Commande::create();
    } elseif ($line === 'delete') {
        Commande::delete();
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
        Commande::modify();
    } elseif ($line === 'find') {
        Commande::find();
    }

}

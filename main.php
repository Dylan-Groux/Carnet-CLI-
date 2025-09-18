<?php 

while (true) {
    $line = readline("Entrer votre commande : ");
    echo "Vous avez entré : " . $line . "\n";
    if ($line === 'exit') {
        break;
    }
    if ($line === 'liste') {
        echo " Liste - Afficher la liste des contacts\n";
    }
}
<?php

namespace App\Services\Command;

use App\Services\CommandManager;
use App\Services\CommandInterface;

class DetailContactCommand implements CommandInterface
{
    private CommandManager $commandManager;
    
    public function __construct(CommandManager $commandManager) {
        $this->commandManager = $commandManager;
    }

    public function getName(): string {
        return 'detail';
    }

    public function execute(array $args): void {
    if (!isset($args[0]) || !is_numeric($args[0])) {
        $id = readline("Entrez l'ID du contact à afficher : ");
        if (!is_numeric($id)) {
            echo "ID invalide.\n";
            return;
        }
        $args[0] = $id;
    }
        $this->commandManager->detailContact((int)$args[0]);
    }

    public function getDescription(): string {
        return 'Detail - Affiche les détails d\'un contact par son ID';
    }
}

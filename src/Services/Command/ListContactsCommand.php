<?php

namespace App\Services\Command;

use App\Services\CommandManager;
use App\Services\CommandInterface;

class ListContactsCommand implements CommandInterface
{
    private CommandManager $commandManager;
    
    public function __construct(CommandManager $commandManager) {
        $this->commandManager = $commandManager;
    }

    public function getName(): string {
        return 'liste';
    }

    public function execute(array $args): void {
        $this->commandManager->listContacts();
    }

    public function getDescription(): string {
        return 'Liste - Affiche la liste des contacts';
    }
}

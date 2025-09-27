<?php

namespace App\Services\Command;

use App\Services\CommandManager;
use App\Services\CommandInterface;

class FindContactCommand implements CommandInterface
{
    private CommandManager $commandManager;
    
    public function __construct(CommandManager $commandManager) {
        $this->commandManager = $commandManager;
    }

    public function getName(): string {
        return 'find';
    }

    public function execute(array $args): void {
        $this->commandManager->findContacts($args);
    }

    public function getDescription(): string {
        return 'Find - Recherche un contact par nom, email ou numéro de téléphone';
    }
}

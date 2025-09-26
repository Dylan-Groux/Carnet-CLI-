<?php

namespace App\Services\Command;

use App\Services\CommandManager;

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
}

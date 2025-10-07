<?php

namespace App\Services\Command;

use App\Services\CommandManager;
use App\Services\CommandInterface;

class CreateContactCommand implements CommandInterface
{
    private CommandManager $commandManager;
    
    public function __construct(CommandManager $commandManager) {
        $this->commandManager = $commandManager;
    }

    public function getName(): string {
        return 'create';
    }

    public function execute(array $args): void {
        $this->commandManager->createContact();
    }

    public function getDescription(): string {
        return 'Create - Crée un nouveau contact';
    }
}

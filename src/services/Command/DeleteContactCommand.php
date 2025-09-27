<?php

namespace App\Services\Command;

use App\Services\CommandManager;
use App\Services\CommandInterface;

class DeleteContactCommand implements CommandInterface
{
    private CommandManager $commandManager;
    
    public function __construct(CommandManager $commandManager) {
        $this->commandManager = $commandManager;
    }

    public function getName(): string {
        return 'delete';
    }

    public function execute(array $args): void {
        $this->commandManager->deleteContact((int)$args[0]);
    }

    public function getDescription(): string {
        return 'Delete - Supprime un contact par son ID';
    }
}

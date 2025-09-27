<?php

namespace App\Services\Command;

use App\Services\CommandManager;
use App\Services\CommandInterface;

class ModifyContactCommand implements CommandInterface
{
    private CommandManager $commandManager;
    
    public function __construct(CommandManager $commandManager) {
        $this->commandManager = $commandManager;
    }

    public function getName(): string {
        return 'modify';
    }

    public function execute(array $args): void {
        $this->commandManager->modifyContact();
    }

    public function getDescription(): string {
        return 'Modify - Modifie un contact par son ID';
    }
}

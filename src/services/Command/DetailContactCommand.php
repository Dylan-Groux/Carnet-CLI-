<?php

namespace App\Services\Command;

use App\Services\CommandManager;
use App\Services\CommandInterface;

class InfoContactCommand implements CommandInterface
{
    private CommandManager $commandManager;
    
    public function __construct(CommandManager $commandManager) {
        $this->commandManager = $commandManager;
    }

    public function getName(): string {
        return 'detail';
    }

    public function execute(array $args): void {
        $this->commandManager->detailContact((int)$args[0]);
    }

    public function getDescription(): string {
        return 'Detail - Affiche les détails d\'un contact par son ID';
    }
}

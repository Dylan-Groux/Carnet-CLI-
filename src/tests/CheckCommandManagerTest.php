<?php

use PHPUnit\Framework\TestCase;
use App\Services\CheckCommandManager;
use App\Services\CommandManager;

class CheckCommandManagerTest extends TestCase
{
    public function testLoadCommandsReturnsArrayOfCommands()
    {
        $commandManager = new CommandManager();
        $checker = CheckCommandManager::getInstance($commandManager);

        $commands = $checker->loadCommands();

        if (!empty($commands)) {
        var_dump($commands); // Affiche le contenu en CLI
        }

        $this->assertIsArray($commands);
        $this->assertNotEmpty($commands);
         foreach ($commands as $command) {
            $this->assertTrue(method_exists($command, 'getName'));
            $this->assertTrue(method_exists($command, 'execute'));
        }
    }
}

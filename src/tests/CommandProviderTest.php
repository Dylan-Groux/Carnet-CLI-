<?php

use PHPUnit\Framework\TestCase;
use App\Services\CommandProvider;
use App\Services\CommandManager;

class CommandProviderTest extends TestCase
{
    public function testLoadCommandsReturnsArrayOfCommands()
    {
        $commandManager = new CommandManager();
        $checker = CommandProvider::getInstance($commandManager);

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

    public function testGetCommandNamesAndDescriptionsReturnsCorrectFormat()
    {
        $commandManager = new CommandManager();
        $checker = CommandProvider::getInstance($commandManager);

        list($commandMap, $commandInfo) = $checker->getCommandNamesAndDescriptions();

        $this->assertIsArray($commandMap);
        $this->assertIsArray($commandInfo);
        $this->assertNotEmpty($commandMap);
        $this->assertNotEmpty($commandInfo);

        foreach ($commandMap as $name => $command) {
            $this->assertIsString($name);
            $this->assertTrue(method_exists($command, 'getName'));
            $this->assertTrue(method_exists($command, 'execute'));
            $this->assertArrayHasKey($name, $commandInfo);
            $this->assertIsString($commandInfo[$name]);
        }
    }
}

<?php

namespace App\Services;

use ReflectionClass;

/**
 * Singleton class pour vérifier et charger les commandes
 * Permet de s'assurer que chaque commande implémente CommandInterface
 * et de les instancier correctement
 */
class CommandProvider
{
    private CommandManager $commandManager;
    private ?array $commands = null;
    private static ?CommandProvider $instance = null;
    
    public function __construct(CommandManager $commandManager) {
        $this->commandManager = $commandManager;
    }

    public static function getInstance(CommandManager $commandManager): CommandProvider {
        if (self::$instance === null) {
            self::$instance = new CommandProvider($commandManager);
        }
        return self::$instance;
    }

    /**
     * Charge dynamiquement les commandes depuis le répertoire Command
     * Vérifie que chaque commande implémente CommandInterface
     * @return CommandInterface[] Liste des instances de commandes valides
     */
    public function loadCommands(): array {
        if ($this->commands !== null) {
            return $this->commands;
        }
        $commandDir = __DIR__ . '/Command';
        $files = glob($commandDir . '/*Command.php');

        $commands = [];
        foreach ($files as $file) {
            $className =  'App\\Services\\Command\\' . basename($file, '.php');
            if (class_exists($className)) {
                $ref = new ReflectionClass($className);
                if ($ref->implementsInterface(CommandInterface::class)) {
                    $commands[] = new $className($this->commandManager);
                }
            }
        }
        $this->commands = $commands;
        return $commands;
    }

    /**
     * Retourne un tableau associatif des noms de commandes et leurs descriptions
     * @return array [ 'commandName' => CommandInterface, ... ], [ 'commandName' => 'Description', ... ]
     */
    public function getCommandNamesAndDescriptions(): array {
        $commands = $this->loadCommands();
        $commandMap = [];
        $commandInfo = [];
        foreach ($commands as $command) {
            $commandMap[$command->getName()] = $command;
            $commandInfo[$command->getName()] = $command->getDescription();
        }
        return [$commandMap, $commandInfo];
    }
}

<?php

namespace App\Services;

use \PDO;
use \PDOException;

/**
 * Classe de gestion de la connexion à la base de données
 * Utilise le pattern Singleton pour s'assurer qu'une seule instance de la connexion existe
 * @return Database L'instance de la connexion à la base de données
 */
class Database
{
    private static ?Database $instance = null;
    private \PDO $pdo;

    private function __construct() {
        $host = getenv('DB_HOST') ?: 'localhost';
        $dbname = getenv('DB_NAME') ?: 'contact_cli';
        $username = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';

        // Connexion à la base de données avec gestion des erreurs
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if (php_sapi_name() === 'cli') {
                echo "Connexion réussie à la base de données '$dbname' sur le serveur '$host'.\n";
            }
        } catch (PDOException $e) {
            echo "Erreur de connexion à la base de données '$dbname' sur le serveur '$host' : " . $e->getMessage();
            die('Erreur de connexion : ' . $e->getMessage());
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getPDO(): PDO {
        return $this->pdo;
    }
}

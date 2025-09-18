<?php
// Singleton class pour gérer la connexion à la base de données
class Database {
    private static ?Database $instance = null;
    private \PDO $pdo;

    private function __construct() {
        $host = 'localhost';
        $dbname = 'contact_cli';
        $username = 'root';
        $password = '';

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

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getPDO() {
        return $this->pdo;
    }

    // méthode pour obtenir le dernier ID inséré
    public static function getLastInsertId() {
        $db = Database::getInstance()->getPDO();
        return $db->lastInsertId();
    }
}

if (php_sapi_name() === 'cli') {
    $db = Database::getInstance()->getPDO();
    print_r(" Information liée à la connexion de la BDD : " . $db->getAttribute(PDO::ATTR_CONNECTION_STATUS) . "\n");
    print_r(" Information sur le serveur de la BDD : " . $db->getAttribute(PDO::ATTR_SERVER_INFO) . "\n");
}
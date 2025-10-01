<?php

namespace App\Repository;

use App\Entity\Contact;
use App\Services\ContactHydrator;
use App\Services\Database;
use \PDO;
use RuntimeException;
use App\Services\DisplayObjectService;

class ContactRepository
{
    private Database $database;

    public function __construct() {
        $this->database = Database::getInstance();
    }

    /**
     * Récupère tous les contacts depuis la base de données
     * @return Contact[] Un tableau d'objets Contact
     */
    public function findAll(): array {
        $db = $this->database->getPDO();
        $stmt = $db->query(("SELECT * FROM contact"));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ContactHydrator::hydrateAll($result);
    }

    /**
     * Récupère un contact par son ID
     * @param int $id L'ID du contact à récupérer
     * @return Contact Un tableau contenant l'objet Contact correspondant, ou vide si non trouvé
     */
    public function getContactById(int $id): ?Contact {
        $db = $this->database->getPDO();
        $stmt = $db->prepare("SELECT * FROM contact WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
            throw new \RuntimeException("Aucun contact trouvé avec l'ID $id.");
        }
        return ContactHydrator::hydrate($result[0]);
    }

    /**
     * Crée un nouveau contact dans la base de données
     * @param string $name Le nom du contact
     * @param string $email L'email du contact
     * @param string $phone_number Le numéro de téléphone du contact
     * @return Contact|null L'objet Contact créé
     */
    public function createContact(string $name, string $email, string $phone_number): ?Contact {
        $db = $this->database->getPDO();

        $stmt = $db->prepare("INSERT INTO contact (name, email, phone_number) VALUES (:name, :email, :phone_number)");
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'phone_number' => $phone_number
        ]);

        return new Contact($this->database->getPDO()->lastInsertId(), $name, $email, $phone_number);
    }

    /**
     * Supprime un contact de la base de données par son ID
     * @param int $id L'ID du contact à supprimer
     * @return void
     */
    public function deleteContact(int $id): Contact {
        $contact = $this->getContactById($id);
        if (empty($contact)) {
            throw new \RuntimeException("Aucun contact trouvé avec l'ID $id.");
        }
        $db = $this->database->getPDO();
        $stmt = $db->prepare("DELETE FROM contact WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $contact;
    }

    /**
     * Met à jour un contact existant dans la base de données
     * @param int $id L'ID du contact à mettre à jour
     * @param string $name Le nouveau nom du contact
     * @param string $email Le nouvel email du contact
     * @param string $phone_number Le nouveau numéro de téléphone du contact
     * @return void
     */
    public function updateContact(int $id, string $name, string $email, string $phone_number) : ?Contact {
        $db = $this->database->getPDO();

        $stmt = $db->prepare("UPDATE contact SET name = :name, email = :email, phone_number = :phone_number WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'phone_number' => $phone_number
        ]);

        return new Contact($id, $name, $email, $phone_number);
    }
}

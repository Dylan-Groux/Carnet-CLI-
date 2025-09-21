<?php

namespace Repository;

use Entity\Contact;
use Services\Database;
use Services\ContactManager;
use \PDO;

require_once 'src/services/BddManager.php';
require_once 'src/entity/Contact.php';
require_once 'src/services/ContactManager.php';

class ContactRepository
{
    private Database $database;
    private ContactManager $contactManager;

    public function __construct() {
        $this->database = Database::getInstance();
        $this->contactManager = new ContactManager();
    }

    /**
     * Récupère tous les contacts depuis la base de données
     * @return array Un tableau d'objets Contact
     */
    public function findAll(): array {
        $db = $this->database->getPDO();
        $stmt = $db->query(("SELECT * FROM contact"));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $key => $value) {
            $result[$key] = new Contact($value['id'], $value['name'], $value['email'], $value['phone_number']);
        }
        return $result;
    }

    /**
     * Récupère un contact par son ID
     * @param int $id L'ID du contact à récupérer
     * @return array Un tableau contenant l'objet Contact correspondant, ou vide si non trouvé
     */
    public function getContactById(int $id): array {
        $db = $this->database->getPDO();
        $stmt = $db->prepare("SELECT * FROM contact WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $contacts = [];
        foreach ($result as $row) {
            $contacts[] = new Contact($row['id'], $row['name'], $row['email'], $row['phone_number']);
        }
        return $contacts;
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

        // Nettoyage et validation des entrées
        $sanitized = $this->contactManager->sanitizeInput($email, $phone_number, $name);
        if (!$sanitized['isEmailValid']) {
            $this->contactManager->afficherErreur("L'email fourni n'est pas valide.");
            return null;
        }
        if (!$sanitized['isPhoneValid']) {
            $this->contactManager->afficherErreur("Le numéro de téléphone fourni n'est pas valide.");
            return null;
        }

        $stmt = $db->prepare("INSERT INTO contact (name, email, phone_number) VALUES (:name, :email, :phone_number)");
        $stmt->execute([
            'name' => $sanitized['name'],
            'email' => $sanitized['email'],
            'phone_number' => $sanitized['phone_number']
        ]);

        $this->contactManager->afficherContacts($this->getContactById(Database::getLastInsertId()));

        return new Contact((int)Database::getLastInsertId(), $name, $sanitized['email'], $sanitized['phone_number']);
    }

    /**
     * Supprime un contact de la base de données par son ID
     * @param int $id L'ID du contact à supprimer
     * @return void
     */
    public function deleteContact(int $id)  {
        $contact = $this->getContactById($id);
        if (empty($contact)) {
            $this->contactManager->afficherErreur("Aucun contact trouvé avec l'ID $id.");
            return;
        }
        $db = $this->database->getPDO();
        $stmt = $db->prepare("DELETE FROM contact WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $this->contactManager->afficherContacts($contact);
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

        // Nettoyage et validation des entrées
        $sanitized = $this->contactManager->sanitizeInput($email, $phone_number, $name);
        if (!$sanitized['isEmailValid']) {
            $this->contactManager->afficherErreur("L'email fourni n'est pas valide.");
            return null;
        }
        if (!$sanitized['isPhoneValid']) {
            $this->contactManager->afficherErreur("Le numéro de téléphone fourni n'est pas valide.");
            return null;
        }

        $stmt = $db->prepare("UPDATE contact SET name = :name, email = :email, phone_number = :phone_number WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'name' => $sanitized['name'],
            'email' => $sanitized['email'],
            'phone_number' => $sanitized['phone_number']
        ]);

        $this->contactManager->afficherContacts($this->getContactById($id));
        return new Contact($id, $name, $sanitized['email'], $sanitized['phone_number']);
    }
}

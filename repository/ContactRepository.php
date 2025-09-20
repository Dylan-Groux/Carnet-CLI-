<?php

require_once 'services/bddManager.php';
require_once 'entity/Contact.php';
require_once 'services/contactManager.php';

class ContactRepository {
        private Contact $contact;
        private Database $database;
        private ContactManager $contactManager;

    public function __construct(Contact $contact) {
        $this->contact = $contact;
        $this->database = Database::getInstance();
        $this->contactManager = new ContactManager();
    }

    /**
     * Récupère tous les contacts depuis la base de données
     * @return array Un tableau d'objets Contact
     */
    public static function findAll(): array {
        $db = Database::getInstance()->getPDO();
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
    public static function getContactById($id): array {
        $db = Database::getInstance()->getPDO();
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
     * @return Contact L'objet Contact créé
     */
    public static function createContact($name, $email, $phone_number): ?Contact {
        $db = Database::getInstance()->getPDO();

        // Nettoyage et validation des entrées
        $sanitized = ContactManager::sanitizeInput($email, $phone_number);
        if (!$sanitized['isEmailValid']) {
            ContactManager::afficherErreur("L'email fourni n'est pas valide.");
            return null;
        }
        if (!$sanitized['isPhoneValid']) {
            ContactManager::afficherErreur("Le numéro de téléphone fourni n'est pas valide.");
            return null;
        }

        $stmt = $db->prepare("INSERT INTO contact (name, email, phone_number) VALUES (:name, :email, :phone_number)");
        $stmt->execute([
            'name' => $name,
            'email' => $sanitized['email'],
            'phone_number' => $sanitized['phone_number']
        ]);

        echo "Contact créé avec l'ID : " . Database::getLastInsertId() . "\n";
        ContactManager::afficherContacts(self::getContactById(Database::getLastInsertId()));

        return new Contact((int)Database::getLastInsertId(), $name, $sanitized['email'], $sanitized['phone_number']);
    }

    /**
     * Supprime un contact de la base de données par son ID
     * @param int $id L'ID du contact à supprimer
     * @return void
     */
    public static function deleteContact($id)  {
        $contact = self::getContactById($id);
        if (empty($contact)) {
            ContactManager::afficherErreur("Aucun contact trouvé avec l'ID $id.");
            return;
        }
        $db = Database::getInstance()->getPDO();
        $stmt = $db->prepare("DELETE FROM contact WHERE id = :id");
        $stmt->execute(['id' => $id]);
        echo "Contact avec l'ID $id supprimé.\n";
        ContactManager::afficherContacts($contact);
    }

    /**
     * Met à jour un contact existant dans la base de données
     * @param int $id L'ID du contact à mettre à jour
     * @param string $name Le nouveau nom du contact
     * @param string $email Le nouvel email du contact
     * @param string $phone_number Le nouveau numéro de téléphone du contact
     * @return void
     */
    public static function updateContact($id, $name, $email, $phone_number) : ?Contact {
        $db = Database::getInstance()->getPDO();

        // Nettoyage et validation des entrées
        $sanitized = ContactManager::sanitizeInput($email, $phone_number);
        if (!$sanitized['isEmailValid']) {
            ContactManager::afficherErreur("L'email fourni n'est pas valide.");
            return null;
        }
        if (!$sanitized['isPhoneValid']) {
            ContactManager::afficherErreur("Le numéro de téléphone fourni n'est pas valide.");
            return null;
        }

        $stmt = $db->prepare("UPDATE contact SET name = :name, email = :email, phone_number = :phone_number WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'name' => $name,
            'email' => $sanitized['email'],
            'phone_number' => $sanitized['phone_number']
        ]);

        echo "Contact avec l'ID $id mis à jour.\n";
        ContactManager::afficherContacts(self::getContactById($id));
        return new Contact($id, $name, $sanitized['email'], $sanitized['phone_number']);
    }
}

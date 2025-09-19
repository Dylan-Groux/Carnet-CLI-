<?php

require_once 'bddManager.php';
require_once 'entity/Contact.php';

class ContactManager {
    private Contact $contact;

    public function __construct(Contact $contact) {
        $this->contact = $contact;
    }

    public static function findAll(): array {
        $db = Database::getInstance()->getPDO();
        $stmt = $db->query(("SELECT * FROM contact"));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $key => $value) {
            $result[$key] = new Contact($value['id'], $value['name'], $value['email'], $value['phone_number']);
        }
        return $result;
    }

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

    public static function createContact($name, $email, $phone_number) {
        $db = Database::getInstance()->getPDO();
        // Nettoyage et validation des entrées
        $sanitized = self::sanitizeInput($email, $phone_number);
        if (!$sanitized['isEmailValid']) {
            self::afficherErreur("L'email fourni n'est pas valide.");
            return;
        }
        if (!$sanitized['isPhoneValid']) {
            self::afficherErreur("Le numéro de téléphone fourni n'est pas valide.");
            return;
        }
        $stmt = $db->prepare("INSERT INTO contact (name, email, phone_number) VALUES (:name, :email, :phone_number)");
        $stmt->execute([
            'name' => $name,
            'email' => $sanitized['email'],
            'phone_number' => $sanitized['phone_number']
        ]);
        echo "Contact créé avec l'ID : " . Database::getLastInsertId() . "\n";
        self::afficherContacts(self::getContactById(Database::getLastInsertId()));
    }

    public static function deleteContact($id)  {
        $contact = self::getContactById($id);
        if (empty($contact)) {
            self::afficherErreur("Aucun contact trouvé avec l'ID $id.");
            return;
        }
        $db = Database::getInstance()->getPDO();
        $stmt = $db->prepare("DELETE FROM contact WHERE id = :id");
        $stmt->execute(['id' => $id]);
        echo "Contact avec l'ID $id supprimé.\n";
        self::afficherContacts($contact);
    }

    public static function afficherErreur($message = "Erreur : veuillez relire la question.") {
    echo $message . "\n";
    }

    public static function afficherContacts(array $contacts) {
        foreach ($contacts as $contact) {
            echo $contact->toString() . "\n";
        }
    }

    public static function sanitizeInput(string $email, string $phone_number): array {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $phone_number = preg_replace('/[^0-9+]/', '', $phone_number);
        $isEmailValid = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        $isPhoneValid = preg_match('/^(\+?\d{10,15})$/', $phone_number);
        return [
            'email' => $email,
            'phone_number' => $phone_number,
            'isEmailValid' => $isEmailValid,
            'isPhoneValid' => $isPhoneValid
        ];
    }
}

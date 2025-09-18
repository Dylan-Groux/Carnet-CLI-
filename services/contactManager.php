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

    public static function afficherErreur($message = "Erreur : veuillez relire la question.") {
    echo $message . "\n";
    }

    public static function afficherContacts(array $contacts) {
        foreach ($contacts as $contact) {
            echo $contact->toString() . "\n";
        }
    }
}

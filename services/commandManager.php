<?php

require_once 'services/contactManager.php';
require_once 'services/ContactSorter.php';

class Commande {
    public static function list() {
        $contacts = ContactManager::findAll();
        ContactManager::afficherContacts($contacts);
        // Appel de la demande du tri
        $contacts = ContactSorter::interactiveSort($contacts);
    }

    public static function detail($id) {
        $contacts = ContactManager::getContactById($id);
        if (empty($contacts)) {
            ContactManager::afficherErreur("Aucun contact trouvé avec l'ID $id.");
        } else {
            ContactManager::afficherContacts($contacts);
        }
    }

    public static function create() {
        $name = readline("Entrez le nom du contact : ");
        $email = readline("Entrez l'email du contact : ");
        $phone_number = readline("Entrez le numéro de téléphone du contact : ");
        ContactManager::createContact($name, $email, $phone_number);
    }

    public static function delete() {
        $id = readline("Entrez l'ID du contact à supprimer : ");
        if (is_numeric($id)) {
            ContactManager::deleteContact($id);
        } else {
            ContactManager::afficherErreur("L'ID doit être un nombre.");
        }
    }
}

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
}

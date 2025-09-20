<?php

require_once 'services/contactManager.php';
require_once 'services/ContactSorter.php';
require_once 'repository/ContactRepository.php';

class Commande {
    public static function list() {
        $contacts = ContactRepository::findAll();
        ContactManager::afficherContacts($contacts);
        // Appel de la demande du tri
        $contacts = ContactSorter::interactiveSort($contacts);
    }

    public static function detail($id) {
        $contacts = ContactRepository::getContactById($id);
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
        ContactRepository::createContact($name, $email, $phone_number);
    }

    public static function delete() {
        $id = readline("Entrez l'ID du contact à supprimer : ");
        if (is_numeric($id)) {
            ContactRepository::deleteContact($id);
        } else {
            ContactManager::afficherErreur("L'ID doit être un nombre.");
        }
    }

    /**
     * Modifie un contact existant
     * @return Contact|null Le contact modifié ou null en cas d'erreur
     */
    public static function modify() : ?Contact {
        $id = readline("Entrez l'ID du contact à modifier : ");
        if (!is_numeric($id)) {
            ContactManager::afficherErreur("L'ID doit être un nombre.");
            return null;
        }
        $contacts = ContactRepository::getContactById($id);
        if (empty($contacts)) {
            ContactManager::afficherErreur("Aucun contact trouvé avec l'ID $id.");
            return null;
        }
        $contact = $contacts[0];
        echo "Contact actuel : " . $contact . "\n";

        $name = readline("Entrez le nouveau nom du contact (laisser vide pour ne pas changer) : ");
        $email = readline("Entrez le nouvel email du contact (laisser vide pour ne pas changer) : ");
        $phone_number = readline("Entrez le nouveau numéro de téléphone du contact (laisser vide pour ne pas changer) : ");

        // Utiliser les valeurs actuelles si l'entrée est vide
        if (empty($name)) {
            $name = $contact->getName();
        }
        if (empty($email)) {
            $email = $contact->getEmail();
        }
        if (empty($phone_number)) {
            $phone_number = $contact->getPhoneNumber();
        }

        return ContactRepository::updateContact($id, $name, $email, $phone_number);
    }

    /**
     * Recherche un contact par un champ spécifique
     * @return Contact|null Le contact trouvé ou null s'il n'existe pas
     */
    public static function find() : ?Contact {
        $contacts = ContactRepository::findAll();
        $field = readline("Par quel champ voulez-vous rechercher ? (name, email, phone_number) : ");
        if (!in_array($field, ['name', 'email', 'phone_number'])) {
            ContactManager::afficherErreur("Champ de recherche invalide. Choisissez 'name', 'email' ou 'phone_number'.");
            return null;
        }
        $search = readline("Entrez la valeur à rechercher : ");
        foreach ($contacts as $contact) {
            if ($contact->$field === $search) {
                echo $contact . "\n"; // Utilise __toString()
                return $contact;
            }
        }
        ContactManager::afficherErreur("Aucun contact trouvé avec le $field $search.");
        return null;
    }
}

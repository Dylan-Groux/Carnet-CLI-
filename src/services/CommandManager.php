<?php

namespace App\Services;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use App\Services\ContactManager;
use App\Services\ContactSorter;

class CommandManager
{
    private ContactRepository $contactRepository;
    private ContactManager $contactManager;
    private ContactSorter $contactSorter;

    public function __construct() {
        $this->contactRepository = new ContactRepository();
        $this->contactManager = new ContactManager();
        $this->contactSorter = new ContactSorter();
    }

    public function listContacts() {
        $contacts = $this->contactRepository->findAll();
        $this->contactManager->afficherContacts($contacts);
        // Appel de la demande du tri
        $contacts = $this->contactSorter->interactiveSort($contacts);
    }

    public function detailContact(int $id) {
        $contacts = $this->contactRepository->getContactById($id);
        if (empty($contacts)) {
            $this->contactManager->afficherErreur("Aucun contact trouvé avec l'ID $id.");
            return null;
        } else {
            $this->contactManager->afficherContacts($contacts);
        }
    }

    public function createContact() {
        $name = readline("Entrez le nom du contact : ");
        $email = readline("Entrez l'email du contact : ");
        $phone_number = readline("Entrez le numéro de téléphone du contact : ");
        $this->contactRepository->createContact($name, $email, $phone_number);
    }

    public function deleteContact() {
        $id = readline("Entrez l'ID du contact à supprimer : ");
        if (is_numeric($id)) {
            $this->contactRepository->deleteContact($id);
        } else {
            $this->contactManager->afficherErreur("L'ID doit être un nombre.");
        }
    }

    /**
     * Modifie un contact existant
     * @return Contact|null Le contact modifié ou null en cas d'erreur
     */
    public function modifyContact() : ?Contact {
        $id = readline("Entrez l'ID du contact à modifier : ");
        if (!is_numeric($id)) {
            $this->contactManager->afficherErreur("L'ID doit être un nombre.");
            return null;
        }
        $contacts = $this->contactRepository->getContactById($id);
        if (empty($contacts)) {
            $this->contactManager->afficherErreur("Aucun contact trouvé avec l'ID $id.");
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

        return $this->contactRepository->updateContact($id, $name, $email, $phone_number);
    }

    /**
     * Recherche un ou des contacts par un champ spécifique
     * @return Contact[]|null Les contacts trouvés ou null s'il n'existe pas
     */
    public function findContacts() : array {
        $contacts = $this->contactRepository->findAll();
        $field = readline("Par quel champ voulez-vous rechercher ? (name, email, phone_number) : ");
        if (!in_array($field, ['name', 'email', 'phone_number'])) {
            $this->contactManager->afficherErreur("Champ de recherche invalide. Choisissez 'name', 'email' ou 'phone_number'.");
            return [];
        }
        $search = readline("Entrez la valeur à rechercher : ");
        $found = [];
        foreach ($contacts as $contact) {
            if ($contact->$field === $search) {
                echo $contact . "\n"; // Utilise __toString()
                $found[] = $contact;
            }
        }
        if (empty($found)) {
            $this->contactManager->afficherErreur("Aucun contact trouvé avec le $field $search.");
        }
        return $found;
    }
}

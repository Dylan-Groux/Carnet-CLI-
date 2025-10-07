<?php

namespace App\Services;

use App\Entity\Contact;
use App\Repository\ContactManager;
use App\Services\ContactHelper;
use App\Services\ContactSorter;

class CommandManager
{
    private ContactManager $contactManager;
    private ContactHelper $contactHelper;
    private ContactSorter $contactSorter;
    private Database $database;

    public function __construct() {
        $this->contactManager = new ContactManager();
        $this->contactHelper = new ContactHelper();
        $this->contactSorter = new ContactSorter();
        $this->database = Database::getInstance();
    }

    /**
     * Affiche la liste des contacts
     * @return Contact[] La liste des contacts triés
     */
    public function listContacts(): array {
        $contacts = $this->contactManager->findAll();
        DisplayObjectService::displayObjects($contacts);
        // Appel de la demande du tri
        $contacts = $this->contactSorter->interactiveSort($contacts);
        return $contacts;
    }

    /**
     * Affiche les détails d'un contact par son ID
     * @param int $id L'ID du contact
     * @return Contact|null Le contact trouvé ou null s'il n'existe pas
     */
    public function detailContact(int $id): ?Contact {
        try {
            $contacts = $this->contactHelper->showContact((int)$id);
            DisplayObjectService::displaySuccess("Contact trouvé avec succès.");
            DisplayObjectService::displayObject($contacts);
            return $contacts;
        } catch (\RuntimeException $e) {
            DisplayObjectService::displayError($e->getMessage());
            return null;
        }
    }
    
    /**
     * Crée un nouveau contact
     * @return Contact Le contact créé
     */
    public function createContact(): Contact {
        $name = readline("Entrez le nom du contact : ");
        $email = readline("Entrez l'email du contact : ");
        $phone_number = readline("Entrez le numéro de téléphone du contact : ");
        $sanitized = DisplayObjectService::sanitizeContactObjectInput($name, $email, $phone_number);
        $this->contactManager->createContact($sanitized['name'], $sanitized['email'], $sanitized['phone_number']);
        $id = $this->database->getPDO()->lastInsertId();
        $contact = new Contact($id, $sanitized['name'], $sanitized['email'], $sanitized['phone_number']);
        DisplayObjectService::displaySuccess("Contact créé avec succès.");
        return $contact;
    }

    /**
     * Supprime un contact par son ID
     * @return Contact|null Le contact supprimé ou null en cas d'erreur
     */
    public function deleteContact(): ?Contact {
        $id = readline("Entrez l'ID du contact à supprimer : ");
        if (!is_numeric($id)) {
            DisplayObjectService::displayError("L'ID doit être un nombre.");
            return null;
        }
        try {
            $contact = $this->contactManager->deleteContact($id);
            if ($contact !== null) {
                DisplayObjectService::displaySuccess("Contact supprimé avec succès.");
                DisplayObjectService::displayObject($contact);
            }
            return $contact;
        } catch (\RuntimeException $e) {
            DisplayObjectService::displayError($e->getMessage());
            return null;
        }
    }

    /**
     * Modifie un contact existant
     * @return Contact|null Le contact modifié ou null en cas d'erreur
     */
    public function modifyContact() : ?Contact {
        $id = readline("Entrez l'ID du contact à modifier : ");

        $this->contactHelper->showContact((int)$id);

        $name = readline("Entrez le nouveau nom du contact (laisser vide pour ne pas changer) : ");
        $email = readline("Entrez le nouvel email du contact (laisser vide pour ne pas changer) : ");
        $phone_number = readline("Entrez le nouveau numéro de téléphone du contact (laisser vide pour ne pas changer) : ");

        $lastInfo = $this->contactHelper->getLastInformationContact((int)$id, $name, $email, $phone_number);

        $this->contactManager->updateContact((int)$lastInfo->getId(), $lastInfo->getName(), $lastInfo->getEmail(), $lastInfo->getPhoneNumber());

        DisplayObjectService::displaySuccess("Contact modifié avec succès.");
        DisplayObjectService::displayObject($lastInfo);
        return $lastInfo;
    }

    /**
     * Recherche un ou des contacts par un champ spécifique
     * @return Contact[]|null Les contacts trouvés ou null s'il n'existe pas
     */
    public function findContacts() : array {
        $contacts = $this->contactManager->findAll();
        $field = readline("Par quel champ voulez-vous rechercher ? (name, email, phone_number) : ");
        if (!in_array($field, ['name', 'email', 'phone_number'])) {
            DisplayObjectService::displayError("Champ de recherche invalide. Choisissez 'name', 'email' ou 'phone_number'.");
            return [];
        }
        $search = readline("Entrez la valeur à rechercher : ");
        $found = [];
        foreach ($contacts as $contact) {
            // Génère dynamiquement le nom du getter
            $getter = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $field)));
            if (method_exists($contact, $getter)) {
                $value = $contact->$getter();
                if ($value === $search) {
                    DisplayObjectService::displayObject($contact);
                    $found[] = $contact;
                }
            } else {
                DisplayObjectService::displayError("Méthode $getter n'existe pas dans la classe Contact.");
                return [];
            }
        }
        if (empty($found)) {
            DisplayObjectService::displayError("Aucun contact trouvé avec le $field $search.");
        }
        return $found;
    }
}

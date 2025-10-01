<?php

declare(strict_types=1);

namespace App\Services;

use App\Repository\ContactRepository;
use App\Entity\Contact;

class ContactManager
{
    private ContactRepository $contactRepository;

    public function __construct() {
        $this->contactRepository = new ContactRepository();
    }

    /**
     * Affiche un contact par son ID
     * @param int $id L'ID du contact à afficher
     * @return Contact Le contact trouvé
     */
    public function showContact(int $id): Contact {
        $contact = $this->contactRepository->getContactById((int)$id);
        if ($contact === null) {
            DisplayObjectService::displayError("Aucun contact trouvé avec l'ID $id.");
            exit;
        } elseif (is_array($contact)) {
            $contact = $contact[0] ?? null;
        }
        echo $contact . "\n";
        return $contact;
    }

    /**
     * Récupère les dernières informations d'un contact avant modification
     * @param string $name Le nom du contact (peut être vide)
     * @param string $email L'email du contact (peut être vide)
     * @param string $phone_number Le numéro de téléphone du contact (peut être vide
     * @param int $id L'ID du contact à modifier
     * @return Contact Le contact avec les informations complètes
     */
    public function getLastInformationContact(int $id, string $name, string $email, string $phone_number): Contact {
        $contact = $this->contactRepository->getContactById((int)$id);
        if (is_array($contact)) {
            $contact = $contact[0] ?? null;
        }

        if (empty($name)){
            $name = $contact->getName();
        }
        if (empty($email)){
            $email = $contact->getEmail();
        }
        if (empty($phone_number)){
            $phone_number = $contact->getPhoneNumber();
        }

        $sanitized = DisplayObjectService::sanitizeContactObjectInput(
            $name,
            $email,
            $phone_number,
            $contact->getId()
        );

        return new Contact($sanitized['id'], $sanitized['name'], $sanitized['email'], $sanitized['phone_number']);
    }
}

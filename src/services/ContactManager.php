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

    public static function afficherErreur(string $message = "Erreur : veuillez relire la question."): string {
    echo $message . "\n";
    return throw new \RuntimeException($message);
    }

    public static function afficherContacts(array $contacts): array {
        foreach ($contacts as $contact) {
            echo $contact . "\n";
        }
        return $contacts;
    }

    public static function sanitizeInput(string $name, string $email, string $phone_number, ?int $id = null): array {
        if (empty($email) || empty($phone_number) || empty($name)) {
            self::afficherErreur("Tous les champs (nom, email, numéro de téléphone) sont requis.");
            exit;
        }

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $name = trim(strip_tags($name));
        // Optionnel : n'autoriser que lettres, espaces, tirets et apostrophes
        $name = preg_replace('/[^a-zA-ZÀ-ÿ\' -]/u', '', $name);

        // Formatage du numéro de téléphone : suppression des espaces, tirets, etc.
        $phone_number = preg_replace('/[^0-9+]/', '', $phone_number);
        // Ajout d'espaces tous les 2 chiffres
        $formatted_phone = trim(chunk_split($phone_number, 2, ' '));

        $isEmailValid = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        $isPhoneValid = preg_match('/^(\+?\d{10,15})$/', $phone_number);

        if (!$isEmailValid || !$isPhoneValid) {
            if (!$isEmailValid) {
                self::afficherErreur("L'email fourni n'est pas valide.");
            }
            if (!$isPhoneValid) {
                self::afficherErreur("Le numéro de téléphone fourni n'est pas valide. Il doit contenir entre 10 et 15 chiffres, avec un '+' optionnel au début.");
            }
        }

        if ($id !== null && (!is_int($id) || $id <= 0)) {
            self::afficherErreur("L'ID doit être un nombre entier positif.");
        }

        return [
            'id' => $id ?? 0,
            'name' => $name,
            'email' => $email,
            'phone_number' => $formatted_phone
        ];
    }

    /**
     * Affiche un contact par son ID
     * @param int $id L'ID du contact à afficher
     * @return Contact Le contact trouvé
     */
    public function showContact(int $id): Contact {
        $contact = $this->contactRepository->getContactById((int)$id);
        if ($contact === null) {
            self::afficherErreur("Aucun contact trouvé avec l'ID $id.");
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

        $sanitized = self::sanitizeInput(
            $contact->getName(),
            $contact->getEmail(),
            $contact->getPhoneNumber(),
            $contact->getId()
        );

        return new Contact($sanitized['id'], $sanitized['name'], $sanitized['email'], $sanitized['phone_number']);
    }
}

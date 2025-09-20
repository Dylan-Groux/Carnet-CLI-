<?php

namespace Services;

class ContactManager
{
    public static function afficherErreur($message = "Erreur : veuillez relire la question.") {
    echo $message . "\n";
    }

    public static function afficherContacts(array $contacts) {
        foreach ($contacts as $contact) {
            echo $contact . "\n";
        }
    }

    public static function sanitizeInput(string $email, string $phone_number): array {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Formatage du numéro de téléphone : suppression des espaces, tirets, etc.
        $phone_number = preg_replace('/[^0-9+]/', '', $phone_number);
        // Ajout d'espaces tous les 2 chiffres
        $formatted_phone = trim(chunk_split($phone_number, 2, ' '));

        $isEmailValid = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        $isPhoneValid = preg_match('/^(\+?\d{10,15})$/', $phone_number);
        return [
            'email' => $email,
            'phone_number' => $formatted_phone,
            'isEmailValid' => $isEmailValid,
            'isPhoneValid' => $isPhoneValid
        ];
    }
}

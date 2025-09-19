<?php
class ContactManager {
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

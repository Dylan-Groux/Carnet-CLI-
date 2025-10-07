<?php

namespace App\Services;

use PHPUnit\Event\Runtime\Runtime;
use RuntimeException;

class DisplayObjectService
{
    /**
     * Affiche un message d'erreur et lance une exception.
     * @param string $message Le message d'erreur à afficher
     * @return string
     * @throws \RuntimeException
     */
    public static function displayErrorWithRunTimeException(string $message = "Erreur : veuillez relire la question."): RuntimeException {
        throw CliStyleMessage::displayErrorMessage($message);
    }

    /**
     * Affiche un message d'erreur.
     * @param string $message Le message d'erreur à afficher
     * @return void
     */
    public static function displayError(string $message = "Erreur : veuillez relire la question."): void {
        CliStyleMessage::displayErrorMessage($message);
    }

    /**
     * Affiche un message de succès.
     * @param string $message Le message de succès à afficher
     */
    public static function displaySuccess(string $message = "Opération réussie."): void {
        CliStyleMessage::displaySuccessMessage($message);
    }

    /**
     * Affiche une liste d'objets avec un message de succès.
     * @return object[] Les objets affichés
     */
    public static function displayObjects(array $objects): array {
        foreach ($objects as $object) {
            echo $object . "\n";
        }
        return $objects;
    }

    /**
     * Affiche un objet avec un message de succès.
     * @return object L'objet affiché
     */
    public static function displayObject(object $object): object {
        echo $object . "\n";
        return $object;
    }

   
    /**
     * Nettoie et valide les entrées pour un objet Contact.
     * @param string $name Le nom du contact
     * @param string $email L'email du contact
     * @param string $phone_number Le numéro de téléphone du contact
     * @param int|null $id L'ID du contact (optionnel)
     * @return array Un tableau associatif avec les champs nettoyés et validés
     */
    public static function sanitizeContactObjectInput(string $name, string $email, string $phone_number, ?int $id = null): array {
        if (empty($email) || empty($phone_number) || empty($name)) {
            self::displayErrorWithRunTimeException("Tous les champs (nom, email, numéro de téléphone) sont requis.");
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

        if (!$isEmailValid) {
            self::displayErrorWithRunTimeException("L'email fourni n'est pas valide.");
        }
        if (!$isPhoneValid) {
            self::displayErrorWithRunTimeException("Le numéro de téléphone fourni n'est pas valide. Il doit contenir entre 10 et 15 chiffres, avec un '+' optionnel au début.");
        }

        if ($id !== null && (!is_int($id) || $id <= 0)) {
            self::displayErrorWithRunTimeException("L'ID doit être un nombre entier positif.");
        }

        return [
            'id' => $id ?? 0,
            'name' => $name,
            'email' => $email,
            'phone_number' => $formatted_phone
        ];
    }
}

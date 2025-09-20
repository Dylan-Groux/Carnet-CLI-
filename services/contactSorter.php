<?php
require_once 'services/contactManager.php';

class ContactSorter {
    public static function interactiveSort(array $contacts): array {
        do {
            $critere = readline("Voulez vous trier votre liste ? si oui par quel critère ? (id, name, mail, phone_number) si non tapez 'non' : ");
            if ($critere === 'id' || $critere === 'name' || $critere === 'mail' || $critere === 'phone_number') {
                $contacts = self::sort($contacts, $critere);
            } elseif ($critere === 'non') {
                // Ne pas trier
                break;
            } else {
                ContactManager::afficherErreur("Critère de tri invalide, veuillez choisir 'id', 'name' ou 'mail'. si vous ne souhaitez pas trier, tapez 'non'.");
            }
        } while ($critere !== 'id' && $critere !== 'name' && $critere !== 'mail');
        return $contacts;
    }

    public static function sort(array $contacts, string $critere) {
        if ($critere === 'id') {
            ksort($contacts); // Trie par clé (ici, id)
            ContactManager::afficherContacts($contacts);
        } elseif ($critere === 'name' || $critere === 'mail' || $critere === 'phone_number') {
            usort($contacts, function($a, $b) use ($critere) {
                return strcmp($a->$critere, $b->$critere);
            });
            ContactManager::afficherContacts($contacts);
        }
    return $contacts;
    }
}
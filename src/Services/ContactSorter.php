<?php

namespace App\Services;

use App\Services\ContactHelper;

class ContactSorter
{
    private ContactHelper $contactHelper;
    
    public function __construct() {
        $this->contactHelper = new ContactHelper();
    }
    
    public function interactiveSort(array $contacts): array {
        do {
            $critere = readline("Voulez vous trier votre liste ? si oui par quel critère ? (id, name, mail, phone_number) si non tapez 'non' : ");
            if ($critere === 'id' || $critere === 'name' || $critere === 'mail' || $critere === 'phone_number') {
                $contacts = $this->sort($contacts, $critere);
            } elseif ($critere === 'non') {
                // Ne pas trier
                break;
            } else {
                DisplayObjectService::displayError("Critère de tri invalide, veuillez choisir 'id', 'name', 'mail' ou 'phone_number'. si vous ne souhaitez pas trier, tapez 'non'.");
            }
        } while ($critere !== 'id' && $critere !== 'name' && $critere !== 'mail' && $critere !== 'phone_number' && $critere !== 'non');
        return $contacts;
    }

    public function sort(array $contacts, string $critere): array {
        if ($critere === 'id') {
            ksort($contacts); // Trie par clé (ici, id)
            DisplayObjectService::displayObjects($contacts);
        } elseif ($critere === 'name') {
            usort($contacts, function($a, $b) {
                // Accède dynamiquement à la propriété de l'objet via __get
                return strcmp($a->getName(), $b->getName());
            });
        } elseif ($critere === 'mail') {
            usort($contacts, function($a, $b) {
                return strcmp($a->getEmail(), $b->getEmail());
            });
        } elseif ($critere === 'phone_number') {
            usort($contacts, function($a, $b) {
                return strcmp($a->getPhoneNumber(), $b->getPhoneNumber());
            });
        }
        DisplayObjectService::displayObjects($contacts);
        return $contacts;
    }
}

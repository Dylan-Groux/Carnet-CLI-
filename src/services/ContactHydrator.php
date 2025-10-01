<?php

namespace App\Services;

use App\Entity\Contact;

class ContactHydrator
{
    public static function hydrate(array $row): ?Contact
    {
        if (empty($row)) {
            return null;
        }
        return new Contact($row['id'], $row['name'], $row['email'], $row['phone_number']);
    }

    /**
     * Hydrate un tableau de lignes en objets Contact
     * @param array $rows
     * @return Contact[]
     */
    public static function hydrateAll(array $rows): array
    {
        return array_map([self::class, 'hydrate'], $rows);
    }
}

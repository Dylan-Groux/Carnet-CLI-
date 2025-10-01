# Carnet d'Adresses CLI

![PROJECT WITH](https://img.shields.io/badge/PROJECT%20WITH-violet?style=for-the-badge&logo=github)
![OPENCLASSROOMS](https://img.shields.io/badge/OPENCLASSROOMS-blue?style=for-the-badge&logo=openclassrooms)

## Description

Ce projet est une application PHP orientée objet permettant de gérer un carnet d'adresses en ligne de commande.  
Il a été réalisé dans le cadre d'un parcours OpenClassrooms pour illustrer les bonnes pratiques de conception logicielle :  
- Architecture en couches (Entity, Repository, Service)
- Respect des principes SOLID et Clean Code
- Utilisation de design patterns (Singleton, Repository, Command)
- Validation et nettoyage des entrées utilisateur

L’application permet de :
- Créer, afficher, modifier, supprimer des contacts
- Rechercher et trier les contacts selon différents critères
- Interagir via une interface CLI intuitive

## Fonctionnalités

- **Liste des contacts**  
- **Détail d’un contact**  
- **Création, modification, suppression**  
- **Recherche par nom, email, téléphone**  
- **Tri interactif**  
- **Validation et nettoyage des données**  
- **Connexion sécurisée à la base de données (Singleton)**

## Structure du projet

```
src/
  entity/         # Entités métier (Contact)
  repository/     # Accès aux données (ContactRepository)
  services/       # Logique métier et utilitaires (ContactManager, CommandManager, ContactSorter, Database, DisplayObjectService)
    Command/      # Implémentations concrètes des commandes CLI (CreateContactCommand, DeleteContactCommand, etc.)
main.php          # Point d’entrée CLI
.env              # Configuration base de données
tests/            # Tests unitaires
```

## Installation

1. **Cloner le projet**
   ```bash
   git clone https://github.com/votre-utilisateur/carnet-adresses-cli.git
   cd carnet-adresses-cli
   ```

2. **Configurer la base de données**
   - Renseigner les accès dans le fichier `.env` (voir exemple fourni)
   - Importer la structure SQL si nécessaire

3. **Lancer l’application**
   ```bash
   php main.php
   ```

## Prérequis

- PHP >= 8.0
- MySQL/MariaDB
- Accès CLI

---

## Explication technique : CommandProvider

### Rôle de CommandProvider

`CommandProvider` est le **registre central** des commandes CLI de l’application.  
Il implémente le **pattern Command** : chaque action utilisateur (créer, lister, modifier, etc.) est encapsulée dans une classe dédiée (ex : `CreateContactCommand`, `DeleteContactCommand`)

#### Fonctionnement

- **Découverte automatique** :  
  `CommandProvider` instancie et référence toutes les commandes disponibles dans le dossier `src/services/Command/`.
- **Injection de dépendances** :  
  Chaque commande reçoit le `CommandManager` pour accéder à la logique métier.
- **Mapping** :  
  Il expose une méthode pour obtenir la liste des commandes disponibles et leur description, ce qui permet d’afficher dynamiquement l’aide dans le CLI.
- **Exécution** :  
  Lorsqu’une commande est saisie par l’utilisateur, `CommandProvider` retrouve la classe correspondante et exécute sa méthode `execute()`.

#### Avantages techniques

- **Extensibilité** :  
  Ajouter une nouvelle commande se fait simplement en créant une nouvelle classe dans `src/services/Command/` qui implémente `CommandInterface`.
- **Séparation des responsabilités** :  
  La logique de chaque commande est isolée, ce qui facilite la maintenance et les tests.
- **Centralisation** :  
  Toute la gestion des commandes est centralisée dans `CommandProvider`, évitant la duplication de code et rendant le CLI évolutif.

#### Exemple d’utilisation

```php
$commandManager = new CommandManager();
$provider = CommandProvider::getInstance($commandManager);
list($commandMap, $commandInfo) = $provider->getCommandNamesAndDescriptions();

if (isset($commandMap[$userInput])) {
    $commandMap[$userInput]->execute([]);
}
```

---

## Auteur

Projet réalisé dans le cadre du parcours [OpenClassrooms](https://openclassrooms.com/)  
Contact : dylangroux2105@gmail.com

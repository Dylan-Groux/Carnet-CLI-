# Carnet d'Adresses CLI

![PROJECT WITH](https://img.shields.io/badge/PROJECT%20WITH-violet?style=for-the-badge&logo=github)
![OPENCLASSROOMS](https://img.shields.io/badge/OPENCLASSROOMS-blue?style=for-the-badge&logo=openclassrooms)

## Description

Ce projet est une application PHP orientée objet permettant de gérer un carnet d'adresses en ligne de commande.  
Il a été réalisé dans le cadre d'un parcours OpenClassrooms pour illustrer les bonnes pratiques de conception logicielle :  
- Architecture en couches (Entity, Repository, Service)
- Respect des principes SOLID et Clean Code
- Utilisation de design patterns (Singleton, Repository)
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
  services/       # Logique métier et utilitaires (ContactManager, CommandManager, ContactSorter, Database)
main.php          # Point d’entrée CLI
.env              # Configuration base de données
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

## Auteur

Projet réalisé dans le cadre du parcours [OpenClassrooms](https://openclassrooms.com/)  
Contact : votre.email@exemple.com

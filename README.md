# Candoria

Bienvenue sur **Candoria**, le site web dédié aux amateurs de bonbons ! Ce projet est conçu avec le framework Symfony et utilise EasyAdmin pour la gestion du back-office.

---

## Description

Candoria est une plateforme e-commerce qui permet aux utilisateurs de découvrir, acheter et explorer une large gamme de bonbons. Le projet inclut :

- Une interface utilisateur conviviale pour la navigation et les achats.
- Un système d'administration (back-office) pour gérer les produits, les commandes et les utilisateurs, basé sur EasyAdmin.

## Prérequis

Avant de commencer, assurez-vous d'avoir installé les éléments suivants sur votre machine :

- PHP >= 8.1
- Composer
- Symfony CLI
- Un serveur web (Apache/Nginx) ou Symfony local server
- Une base de données (MySQL, PostgreSQL, etc.)

## Installation


### 1. Installer les dépendances

```bash
composer install
```

### 2. Configurer l'environnement

Créez un fichier `.env.local` à la racine du projet et configurez vos paramètres de base de données :

```
DATABASE_URL="mysql://user:password@127.0.0.1:3306/candoria"
```

### 3. Initialiser la base de données

Générer le schéma de la base de données et les données de test :

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

### 4. Lancer le serveur

```bash
symfony server:start
```

Le site sera accessible sur [http://localhost:8000](http://localhost:8000).

## Usage

### Frontend
Les utilisateurs peuvent :
- Naviguer parmi les catégories de bonbons.
- Ajouter des articles au panier.
- Passer des commandes.

### Backoffice
L'administration est accessible via `/admin` :

- Gestion des produits : ajouter, modifier ou supprimer des bonbons.
- Gestion des commandes : visualiser et mettre à jour le statut des commandes.
- Gestion des utilisateurs : administrer les comptes.

Connectez-vous avec les identifiants administrateurs créés dans les fixtures ou ajoutez-en directement dans la base de données.

## Fonctionnalités

### Frontend
- **Catalogue** : Explorez les produits classés par catégorie.
- **Panier d'achat** : Ajoutez des bonbons au panier et passez commande.
- **Compte utilisateur** : Connectez-vous pour suivre vos commandes.

### Backend
- **EasyAdmin** : Gestion simplifiée des entités.
- **Gestion des stocks** : Mettre à jour la disponibilité des produits.
- **Statistiques** : Visualisez les tendances des ventes.


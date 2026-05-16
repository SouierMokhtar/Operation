# Algérie Poste - Dashboard MVC

Application de gestion pour la Direction Régionale d'Oran (Wilaya 31), restructurée selon le modèle **MVC** (Model-View-Controller).

## Architecture

```
algerie-poste-mvc/
├── public/                     # Document root (point d'entrée web)
│   ├── index.php               # Front controller
│   ├── .htaccess               # Réécriture d'URL Apache
│   └── assets/                 # CSS, JS, Images
├── app/
│   ├── Core/                   # Classes de base (App, Controller, Database)
│   ├── Controllers/            # Contrôleurs (logique métier)
│   ├── Models/                 # Modèles (accès aux données)
│   └── Views/                  # Vues (templates HTML)
├── config/                     # Configuration (base de données)
├── database/                   # Schéma SQL
└── data/                       # Dossier pour les fichiers d'import
```

## Prérequis

- PHP 8.1+
- MySQL / MariaDB
- Apache avec `mod_rewrite` activé

## Déploiement sur XAMPP

### Étape 1 : Activer `mod_rewrite`

1. Ouvrir `C:\xampp\apache\conf\httpd.conf`
2. Trouver la ligne `#LoadModule rewrite_module modules/mod_rewrite.so`
3. Retirer le `#` pour la décommenter :
   ```
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
4. Trouver la section `<Directory "C:/xampp/htdocs">` et changer `AllowOverride None` en :
   ```
   AllowOverride All
   ```
5. Redémarrer Apache depuis le panneau de contrôle XAMPP

### Étape 2 : Copier le projet

Copier tout le dossier du projet dans XAMPP :
```
C:\xampp\htdocs\operation\
```

La structure doit être :
```
C:\xampp\htdocs\operation\
├── .htaccess               ← redirige vers public/
├── public\
│   ├── index.php
│   ├── .htaccess
│   └── assets\
├── app\
├── config\
├── database\
└── data\
```

### Étape 3 : Créer la base de données

1. Ouvrir **phpMyAdmin** : `http://localhost/phpmyadmin`
2. Cliquer sur **Importer**
3. Sélectionner le fichier `database/schema.sql`
4. Cliquer sur **Exécuter**

Ou via le terminal MySQL de XAMPP :
```bash
C:\xampp\mysql\bin\mysql.exe -u root < C:\xampp\htdocs\operation\database\schema.sql
```

### Étape 4 : Accéder à l'application

Ouvrir dans le navigateur :
```
http://localhost/operation/
```

### Configuration de la base de données

Par défaut, le projet utilise `root` sans mot de passe (configuration standard XAMPP).
Pour personnaliser, définir les variables d'environnement ou modifier `config/database.php` :

| Variable     | Défaut         | Description           |
|-------------|----------------|-----------------------|
| `DB_HOST`   | `localhost`    | Serveur MySQL         |
| `DB_NAME`   | `traficbureau` | Nom de la base        |
| `DB_USER`   | `root`         | Utilisateur MySQL     |
| `DB_PASS`   | *(vide)*       | Mot de passe MySQL    |

## Utilisation avec le serveur PHP intégré

```bash
cd public
php -S localhost:8000
```

Accéder à `http://localhost:8000`.

## Routes

| URL               | Contrôleur           | Action   | Description                    |
|--------------------|---------------------|----------|--------------------------------|
| `/`               | HomeController       | index    | Menu principal                 |
| `/bureau`         | BureauController     | index    | Liste des bureaux              |
| `/operations`     | OperationController  | index    | Liste des opérations           |
| `/carnet`         | CarnetController     | index    | Commandes carnets de chèque    |
| `/cnep`           | CnepController       | index    | Opérations CNEP                |
| `/trafic/detail`  | TraficController     | detail   | Trafic bureau détail           |
| `/trafic/global`  | TraficController     | global   | Trafic bureau global           |
| `/data/import`    | DataController       | import   | Importation des données        |

## Fonctionnalités

- Recherche en temps réel avec surbrillance
- Export Excel, PDF et TXT
- Filtres multi-sélection (Trafic)
- Statistiques dynamiques
- Design responsive (dark theme)

## Améliorations par rapport à l'original

| Avant                          | Après (MVC)                        |
|--------------------------------|------------------------------------|
| Fichiers monolithiques         | Séparation Model/View/Controller   |
| `mysqli_connect()` partout     | PDO singleton centralisé           |
| CSS/JS dupliqué                | Assets partagés                    |
| Accès direct aux fichiers      | Front controller + routage         |
| Pas de namespaces              | Autoloading PSR-4                  |
| Requêtes SQL inline            | Modèles avec méthodes dédiées      |

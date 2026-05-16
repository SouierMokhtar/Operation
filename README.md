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

## Installation

1. **Cloner le projet** :
   ```bash
   git clone https://github.com/SouierMokhtar/algerie-poste-mvc.git
   cd algerie-poste-mvc
   ```

2. **Créer la base de données** :
   ```bash
   mysql -u root < database/schema.sql
   ```

3. **Configurer la connexion** dans `config/database.php` :
   ```php
   return [
       'host'     => 'localhost',
       'dbname'   => 'traficbureau',
       'username' => 'root',
       'password' => '',
       'charset'  => 'utf8mb4',
   ];
   ```

4. **Configurer Apache** pour pointer le DocumentRoot vers le dossier `public/`.

5. **Activer `mod_rewrite`** :
   ```bash
   sudo a2enmod rewrite
   sudo systemctl restart apache2
   ```

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

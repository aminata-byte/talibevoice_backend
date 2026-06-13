# TalibeVoice — API Backend

API RESTful pour la plateforme numérique de recensement, cartographie, gestion et suivi des talibés et des daaras au Sénégal.

## Technologies

- Laravel 11
- PostgreSQL
- Laravel Sanctum (authentification)
- PHP 8.3

## Prérequis

- PHP >= 8.2
- Composer
- PostgreSQL

## Installation

```bash
git clone https://github.com/ton-username/talibevoice_backend.git
cd talibevoice_backend
composer install
cp .env.example .env
php artisan key:generate
```

Configure `.env` :

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=talibevoice_db
DB_USERNAME=postgres
DB_PASSWORD=ton_mot_de_passe
```

```bash
php artisan migrate --seed
php artisan serve
```

## Comptes de test

| Rôle  | Email                 | Mot de passe |
| ----- | --------------------- | ------------ |
| Admin | admin@talibevoice.sn  | password     |
| Agent | moussa@talibevoice.sn | password     |

| Partenaire          | Code        |
| ------------------- | ----------- |
| École Polytechnique | EPD-2024-X1 |
| Sonatel Academy     | SON-2024-X2 |
| ONG Espoir Sénégal  | ESP-2024-X3 |

## Routes API principales

### Publiques

| Méthode | Route                        | Description            |
| ------- | ---------------------------- | ---------------------- |
| GET     | /api/daaras                  | Liste des daaras       |
| GET     | /api/daaras/{id}/besoins     | Besoins d'un daara     |
| GET     | /api/stats                   | Statistiques globales  |
| POST    | /api/dons                    | Soumettre un don       |
| POST    | /api/partenaires/login       | Login partenaire       |
| POST    | /api/partenaires/candidature | Candidature partenaire |

### Admin (Bearer token)

| Méthode | Route                        | Description          |
| ------- | ---------------------------- | -------------------- |
| GET     | /api/admin/daaras            | Liste des daaras     |
| GET     | /api/admin/talibes           | Liste des talibés    |
| GET     | /api/admin/dons              | Liste des dons       |
| POST    | /api/admin/dons/{id}/valider | Valider un don       |
| GET     | /api/admin/formations        | Liste des formations |
| POST    | /api/admin/utilisateurs      | Créer un agent       |

### Agent (Bearer token)

| Méthode | Route               | Description          |
| ------- | ------------------- | -------------------- |
| POST    | /api/agent/talibes  | Recenser un talibé   |
| POST    | /api/agent/daaras   | Créer un daara       |
| POST    | /api/agent/besoins  | Signaler un besoin   |
| GET     | /api/agent/missions | Mes missions         |
| POST    | /api/agent/rapports | Soumettre un rapport |

## Architecture monorepo TalibeVoice

| App                   | Repo                  | Acteurs              |
| --------------------- | --------------------- | -------------------- |
| App Web Publique      | talibevoice_publicweb | Donateur, Partenaire |
| App Web Admin         | talibevoice_adminweb  | Administrateur       |
| App Mobile PWA        | talibevoice_mobilepwa | AgentDeTerrain       |
| API Backend (ce repo) | talibevoice_backend   | Laravel + PostgreSQL |

## Auteur

Projet de fin de cycle — Licence 3 Génie Logiciel
Institut Supérieur d'Informatique (ISI) — Dakar, Sénégal
© 2025 TalibeVoice

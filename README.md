# DeathSystems App

# Author

Henintsoa **RAMAKAVELO**

**Languages** : [FR](#fr) / [EN](#en)

# FR

Une application web intégrant un chatbot IA avec des capacités d’auto-apprentissage, développée avec Laravel (utilisant Sail pour la Dockerisation) et un backend Python FastAPI pour la gestion et l’entraînement des modèles IA.

---

## Fonctionnalités

* **Système d’entraînement IA**

  * Sauvegarde, évaluation et gestion des dialogues utilisateur/IA.
  * Auto-entraînement du modèle IA à partir de dialogues notés.
  * Utilisation de réponses de secours pour les dialogues mal notés.

* **Gestion des ressources**

  * Gestion des ressources et dialogues importants.
  * `ResourceSelector` pour la sélection de dialogues/ressources pertinents.

* **Dockerisation avec Laravel Sail**

  * Mise en place et orchestration simplifiées de tous les services (Laravel, MySQL, Redis, backend IA Python, etc.) via Docker et Sail.

* **Envoi de mails via SMTP**

  * Envoi de mails depuis l’application (notifications, candidatures, etc.).

* **Adaptateur Terminal (v0.1)**

  * Intégration basique d’une interface de type terminal/console.

* **Fonctionnalités prévues / à venir :**

  * Fenêtre de chatbot permanente dans l’interface.
  * Galerie Windows pour la gestion et l’affichage des ressources liées à l’environnement.
  * Outils de galerie de projets.
  * Générateur de CV.
  * Système AutoApply pour candidatures automatiques.
  * Module de voyance.
  * Migration complète du système Desktop + Windows avec finition.

---

## Prérequis

* [Docker](https://www.docker.com/get-started) & [Docker Compose](https://docs.docker.com/compose/)
* [Git](https://git-scm.com/)
* **Si vous êtes sous Windows :**
  Vous devez utiliser [WSL 2 (Windows Subsystem for Linux)](https://learn.microsoft.com/en-us/windows/wsl/) pour une compatibilité complète avec Docker et Laravel Sail.

---

## Installation

### 1. Cloner le dépôt

```sh
git clone https://github.com/yourusername/deathsystems-app.git
cd deathsystems-app
```

### 2. (Windows uniquement) Ouvrir le projet dans un terminal WSL

* Lancez Ubuntu (ou votre distribution WSL)
* Accédez au dossier du projet (ex : `/mnt/c/Users/votre_nom/deathsystems-app`)

### 3. Copier les fichiers d’environnement

```sh
cp .env.example .env
```

Modifiez le fichier `.env` si nécessaire.

### 4. Construire et lancer les conteneurs Docker avec Sail

```sh
./vendor/bin/sail up -d --build
```

Ou, si Sail n’est pas encore installé :

```sh
composer require laravel/sail --dev
php artisan sail:install
./vendor/bin/sail up -d --build
```

### 5. Installer les dépendances Laravel

```sh
./vendor/bin/sail composer install
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
```

### 6. Installer les dépendances Python

```sh
docker-compose exec uvicorn pip install -r scripts/requirements.txt
```

---

## Utilisation

* Accédez à l’interface Laravel sur [http://localhost](http://localhost)
* Le backend IA est disponible sur [http://localhost:8000](http://localhost:8000) (ou selon votre configuration)
* Utilisez l’interface web pour dialoguer, noter les échanges, et déclencher l’auto-apprentissage
* Vous devez effectuer un `seed` de la base de données avant d’accéder au panneau d’administration, ou créer un compte.
* Le panneau admin est accessible sur [http://localhost/admin](http://localhost/admin)

---

## Remarques

* **Utilisateurs Windows :** utilisez toujours un terminal WSL pour toutes les commandes et opérations sur les fichiers.
* **Laravel Sail** est utilisé pour orchestrer l’environnement Docker de la stack Laravel/PHP.



# [EN](#deathsystems-app)
A web application featuring an AI chatbot with self-training capabilities, built with Laravel (using Sail for Dockerization) and a Python FastAPI backend for AI model management and training.

---

## Features

- **AI Training System**
  - Save, score, and manage user/AI dialogs.
  - Self-train the AI model from rated dialogs.
  - Use recovery answers for low-scoring dialogs.
- **Resource Management**
  - Manage important resources and dialogs.
  - ResourceSelector for dialog/resource selection.
- **Dockerized with Laravel Sail**
  - Easy setup and orchestration of all services (Laravel, MySQL, Redis, Python AI backend, etc.) using Docker and Sail.
- **SMTP Mail Sender**
  - Send emails from the app (for notifications, etc.).
- **Terminal Adapter (v0.1)**
  - Basic terminal/command interface integration.
- **Planned/Upcoming Features:**
  - Permanent chatbot window in the UI.
  - Windows Gallery for managing/viewing Windows-related resources.
  - Project Display gallerie tools.
  - CV maker tool.
  - AutoApply method for automated job applications.
  - Fortune Telling module.
  - Desktop + Windows migration and polish.

---

## Prerequisites

- [Docker](https://www.docker.com/get-started) & [Docker Compose](https://docs.docker.com/compose/)
- [Git](https://git-scm.com/)
- **If you are on Windows:**  
  You must use [WSL 2 (Windows Subsystem for Linux)](https://learn.microsoft.com/en-us/windows/wsl/) for full compatibility with Docker and Laravel Sail.

---

## Installation

### 1. Clone the repository

```sh
git clone https://github.com/yourusername/deathsystems-app.git
cd deathsystems-app
```

### 2. (Windows only) Open your project in a WSL terminal

- Open Ubuntu (or your chosen WSL distro)
- Navigate to your project directory (e.g., `/mnt/c/Users/yourname/deathsystems-app`)

### 3. Copy environment files

```sh
cp .env.example .env
```

Edit `.env` files as needed.

### 4. Build and start Docker containers with Sail

```sh
./vendor/bin/sail up -d --build
```
Or, if you haven't installed Sail yet:
```sh
composer require laravel/sail --dev
php artisan sail:install
./vendor/bin/sail up -d --build
```

### 5. Install Laravel dependencies

```sh
./vendor/bin/sail composer install
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed 
```

### 6. Install Python dependencies

```sh
docker-compose exec uvicorn pip install -r scripts/requirements.txt
```

---

## Usage

- Access the Laravel frontend at [http://localhost](http://localhost)
- The AI backend is available at [http://localhost:8000](http://localhost:8000) (or as configured)
- Use the web interface to chat, rate dialogs, and trigger self-training
- You must seed the database before accessing the admin panel. Or create an account.
- You may find the adminPanel in [http://localhost/admin](http://localhost/admin)

---


## Notes

- **Windows users:** Always use a WSL terminal for all commands and file operations.
- **Laravel Sail** is used for Docker orchestration of the PHP/Laravel stack.

---

## License

MITLicense
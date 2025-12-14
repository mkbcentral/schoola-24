# 🏫 Schoola - Système de Gestion Scolaire

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![Livewire](https://img.shields.io/badge/Livewire-3.5-FB70A9?style=flat)](https://livewire.laravel.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Application web complète de gestion scolaire développée avec Laravel 12, Livewire 3 et Bootstrap 5. Permet la gestion des élèves, inscriptions, paiements, frais scolaires, stocks, salaires et bien plus.

---

## 📋 Table des matières

-   [Fonctionnalités](#-fonctionnalités)
-   [Prérequis](#-prérequis)
-   [Installation](#-installation)
-   [Configuration](#️-configuration)
-   [Utilisation](#-utilisation)
-   [Architecture](#-architecture)
-   [Documentation Modules](#-documentation-modules)
-   [Tests](#-tests)
-   [Déploiement](#-déploiement)
-   [Contribution](#-contribution)
-   [Support](#-support)
-   [Licence](#-licence)

---

## ✨ Fonctionnalités

### 👨‍🎓 Gestion des Élèves

-   ✅ Enregistrement des élèves avec informations complètes
-   ✅ Gestion des responsables/tuteurs
-   ✅ Transfert de classe
-   ✅ Dérogations et exemptions de frais
-   ✅ Historique complet

### 💰 Gestion Financière

-   ✅ Frais scolaires configurables (mensualités, inscriptions)
-   ✅ Paiements multi-devises (USD/CDF)
-   ✅ Régularisations de paiement
-   ✅ Dépôts bancaires
-   ✅ Gestion des dépenses et recettes
-   ✅ Prévisions budgétaires

### 📦 Gestion de Stock

-   ✅ Articles avec catégories
-   ✅ Mouvements d'entrée/sortie
-   ✅ Inventaires physiques
-   ✅ Alertes de stock minimum
-   ✅ Audit trail complet

### 👥 Administration

-   ✅ Multi-écoles avec années scolaires
-   ✅ Gestion des rôles et permissions
-   ✅ Configuration des classes, sections, options
-   ✅ Taux de change dynamique
-   ✅ Navigation personnalisable

### 📊 Rapports & Statistiques

-   ✅ Tableau de bord avec graphiques
-   ✅ Rapports d'inscriptions
-   ✅ Analyses financières
-   ✅ Export PDF/Excel
-   ✅ Reçus de paiement avec QR code

### 📱 Autres

-   ✅ Notifications SMS (Orange API)
-   ✅ Impression de reçus (ESC/POS)
-   ✅ Interface responsive
-   ✅ Mode sombre

---

## 🔧 Prérequis

### Logiciels requis

-   **PHP** >= 8.2
-   **Composer** >= 2.0
-   **Node.js** >= 18.x
-   **NPM** >= 9.x
-   **MySQL** >= 8.0 ou **PostgreSQL** >= 13
-   **Redis** (optionnel, recommandé pour production)

### Extensions PHP requises

```bash
php -m | grep -E "pdo|mbstring|openssl|tokenizer|xml|ctype|json|bcmath|gd|zip"
```

---

## 🚀 Installation

### 1. Cloner le projet

```bash
git clone https://github.com/mkbcentral/schoola-24.git
cd schoola-24
```

### 2. Installer les dépendances

```bash
# Dépendances PHP
composer install

# Dépendances JavaScript
npm install
```

### 3. Configuration de l'environnement

```bash
# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 4. Configurer la base de données

Éditer `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=schoola_db
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

### 5. Migrations et seeders

```bash
# Créer la base de données
php artisan migrate

# (Optionnel) Données de test
php artisan db:seed
```

### 6. Créer le lien de stockage

```bash
php artisan storage:link
```

### 7. Compiler les assets

```bash
# Développement
npm run dev

# Production
npm run build
```

### 8. Lancer le serveur

```bash
php artisan serve
```

Accédez à l'application : **http://localhost:8000**

---

## ⚙️ Configuration

### Configuration de l'école

Dans `.env`, personnalisez :

```env
SCHOOL_NAME="Mon École"
SCHOOL_ADDRESS="123 Rue de l'École, Kinshasa"
SCHOOL_PHONE="+243 800 000 000"
SCHOOL_EMAIL="contact@monecole.cd"
```

### Configuration SMS (Orange API)

```env
ORANGE_SMS_CLIENT_ID=votre_client_id
ORANGE_SMS_CLIENT_SECRET=votre_secret
ORANGE_SMS_MERCHANT_KEY=votre_merchant_key
ORANGE_SMS_SENDER_NAME="SCHOOLA"
ORANGE_SMS_SENDER_PHONE="+243..."
ENABLE_SMS_NOTIFICATIONS=true
```

### Configuration des taux de change

```env
DEFAULT_CURRENCY=USD
EXCHANGE_RATE_USD_TO_CDF=2850
```

### Configuration du cache (Production)

```env
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

---

## 📖 Utilisation

### Compte par défaut

Après le seeding :

-   **Email** : admin@schoola.cd
-   **Mot de passe** : password

⚠️ **Changez immédiatement ces identifiants en production !**

### Workflow typique

1. **Configuration initiale**

    - Créer l'année scolaire
    - Configurer sections, options, classes
    - Définir les catégories de frais

2. **Inscription d'élèves**

    - Ajouter les responsables
    - Enregistrer les élèves
    - Créer les inscriptions

3. **Gestion des paiements**

    - Configurer les frais scolaires
    - Enregistrer les paiements
    - Imprimer les reçus

4. **Suivi et rapports**
    - Consulter le tableau de bord
    - Générer les rapports
    - Exporter les données

---

## 🏗️ Architecture

### Structure du projet

```
schoola-web/
├── app/
│   ├── Domain/          # Logique métier (DDD)
│   ├── Enums/           # Énumérations
│   ├── Events/          # Événements
│   ├── Http/            # Controllers & Middleware
│   ├── Livewire/        # Composants Livewire
│   ├── Models/          # Modèles Eloquent
│   ├── Services/        # Services métier
│   └── Traits/          # Traits réutilisables
├── database/
│   ├── migrations/      # Migrations DB
│   └── seeders/         # Seeders
├── resources/
│   ├── js/              # JavaScript
│   ├── sass/            # Styles SCSS
│   └── views/           # Blade templates
├── routes/
│   ├── web.php          # Routes web
│   └── api.php          # Routes API
└── tests/               # Tests automatisés
```

### Stack technologique

-   **Backend** : Laravel 12, PHP 8.2
-   **Frontend** : Livewire 3, Alpine.js, Bootstrap 5
-   **Base de données** : MySQL 8.0
-   **Queue** : Redis/Database
-   **PDF** : DomPDF
-   **Excel** : PhpSpreadsheet
-   **SMS** : Orange SMS API
-   **Tests** : Pest PHP

---

## 📚 Documentation Modules

### Module Inscription (Registration)

Documentation complète du système d'inscription des élèves avec architecture services/repository.

-   **[Guide d'utilisation](REGISTRATION_SERVICE_GUIDE.md)** - Guide complet avec exemples
-   **[Résumé technique](REGISTRATION_SERVICE_SUMMARY.md)** - Architecture et statistiques
-   **[Checklist Livewire](REGISTRATION_LIVEWIRE_CHECKLIST.md)** - Implémentation interface
-   **[Fichiers créés](REGISTRATION_FILES_CREATED.md)** - Liste complète des fichiers

**Fonctionnalités:**

-   ✅ Inscription anciens/nouveaux élèves
-   ✅ CRUD complet
-   ✅ Filtrage avancé (section, option, classe, genre, dates)
-   ✅ Statistiques en temps réel (total, par genre, section, option, classe)
-   ✅ Gestion année scolaire automatique

### Autres Modules

-   **[Architecture générale](ARCHITECTURE.md)** - Structure globale du projet
-   **[CSS Architecture](CSS_ARCHITECTURE.md)** - Organisation des styles
-   **[Dashboard Financier](FINANCIAL_DASHBOARD.md)** - Système de reporting
-   **[Gestion des Dépenses](EXPENSE_MANAGEMENT_REFACTORING_GUIDE.md)** - Module expenses
-   **[Migration Guide](MIGRATION_GUIDE.md)** - Guide de migration

---

## 🧪 Tests

### Lancer les tests

```bash
# Tous les tests
php artisan test

# Tests spécifiques
php artisan test --filter=StudentTest

# Avec couverture
php artisan test --coverage
```

### Créer un test

```bash
php artisan make:test PaymentTest
```

---

## 🚢 Déploiement

### Prérequis production

1. Serveur Linux (Ubuntu 22.04 recommandé)
2. Nginx/Apache avec PHP-FPM
3. MySQL/PostgreSQL
4. Redis
5. Supervisor (pour les queues)
6. SSL/TLS (Let's Encrypt)

### Étapes de déploiement

```bash
# 1. Cloner et installer
git clone https://github.com/mkbcentral/schoola-24.git
cd schoola-24
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# 2. Configuration
cp .env.example .env
php artisan key:generate

# 3. Optimisations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 5. Migrations
php artisan migrate --force
```

### Supervisor (Queues)

Créer `/etc/supervisor/conf.d/schoola-worker.conf` :

```ini
[program:schoola-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /chemin/vers/schoola-24/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/chemin/vers/schoola-24/storage/logs/worker.log
stopwaitsecs=3600
```

---

## 🤝 Contribution

Les contributions sont les bienvenues ! Veuillez suivre ces étapes :

1. Fork le projet
2. Créez une branche (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'Add AmazingFeature'`)
4. Pushez vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

### Standards de code

-   PSR-12 pour PHP
-   ESLint pour JavaScript
-   Commentaires en français
-   Tests obligatoires pour les nouvelles fonctionnalités

---

## 📞 Support

-   **Documentation** : [Wiki du projet](https://github.com/mkbcentral/schoola-24/wiki)
-   **Issues** : [GitHub Issues](https://github.com/mkbcentral/schoola-24/issues)
-   **Email** : mkbcentral@gmail.com
-   **Discussions** : [GitHub Discussions](https://github.com/mkbcentral/schoola-24/discussions)

---

## 📄 Licence

Ce projet est sous licence **MIT**. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

---

## 👏 Remerciements

-   [Laravel](https://laravel.com) - Le framework PHP
-   [Livewire](https://livewire.laravel.com) - Composants dynamiques
-   [Bootstrap](https://getbootstrap.com) - Framework CSS
-   Tous les contributeurs du projet

---

**Développé avec ❤️ pour les écoles de la RDC**

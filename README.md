# 🏡 Magi-Keur

**Magi-Keur** est une plateforme centralisée de services automatisés autour de la **location**, de l’**événementiel** et de l’**immobilier**, adaptée au marché **sénégalais** et **francophone**.
Le projet combine un back-end **Symfony 7 / API Platform**, une base de données **PostgreSQL/PostGIS**, un bus temps réel via **Mercure**, et un reverse proxy **Traefik** avec HTTPS local.

---

## 🚀 Fonctionnalités principales

- 🏠 **Immobilier** : gestion des biens, recherche géolocalisée, transactions.
- 🎉 **Événementiel** : réservation d’événements et services associés.
- 📅 **Synchronisation agenda** : gestion des disponibilités en temps réel.
- 💳 **Paiements en ligne** : intégration avec **Stripe** et solutions locales.
- ⚡ **Temps réel** : via **Mercure Hub** (notifications, mises à jour live).
- 📊 **Backoffice administrateur** : EasyAdmin, gestion des utilisateurs, factures, contrats.
- 📄 **Documents PDF** : génération automatisée (contrats, factures, reçus).
- 🗺 **PostGIS** : gestion avancée des coordonnées et zones géographiques.

---

## 🛠️ Stack technique

- [Symfony 7](https://symfony.com/) (PHP 8.2)
- [API Platform](https://api-platform.com/)
- [PostgreSQL 16 + PostGIS](https://postgis.net/)
- [Mercure](https://mercure.rocks/)
- [Traefik 3](https://traefik.io/) (reverse proxy + HTTPS local)
- [Docker & Docker Compose](https://docs.docker.com/)
- [Makefile](./Makefile) pour automatiser les tâches courantes

---

## 📦 Installation

### 1. Prérequis

- [Docker](https://www.docker.com/) (>= 20)
- [Docker Compose](https://docs.docker.com/compose/) (>= v2)
- PHP 8.2.0 or higher;
- GNU Make

### 2. Cloner le projet

```bash
git clone https://github.com/ton-compte/magi-keur.git
cd magi-keur
````

### 3. Lancer l’environnement

```bash
make init
```

➡️ Cela va :

- Construire les images Docker
- Lancer les conteneurs
- Installer les dépendances PHP (Composer)

### 4. Accéder aux services

- 🌍 Application Symfony : [https://magi.localhost](https://magi.localhost)
- 📡 Dashboard Traefik : [https://dashboard.localhost](https://dashboard.localhost)
- 🔔 Mercure Hub : [https://mercure.localhost](https://mercure.localhost)
- 🗄 Base PostgreSQL : `localhost:5432` (`magi_user` / `magi_pass`)

---

## Tests

Execute this command to run tests:

```bash
cd magi-keur/
./bin/phpunit
```

---

## 📖 Commandes utiles

Quelques raccourcis via **Makefile** :

```bash
make up            # Démarrer les services
make down          # Stopper les services
make restart       # Redémarrer l'environnement
make logs          # Suivre les logs
make shell         # Ouvrir un shell dans le conteneur PHP/Symfony
make composer      # Exécuter Composer dans le conteneur
make symfony       # Lancer une commande Symfony CLI
make database      # Créer la base de données
make migrations    # Générer et appliquer les migrations
make fixtures      # Charger les fixtures
make cache         # Vider le cache Symfony
make postgres      # Se connecter à PostgreSQL (psql)
```

---

## 📂 Structure du projet

```bash
magi-keur/
├── app/                  # Code source Symfony
├── docker/               # Dockerfiles personnalisés
│   └── php/Dockerfile
├── certs/                # Certificats SSL locaux (Traefik)
├── dynamic/              # Configuration dynamique Traefik
├── docker-compose.yml    # Orchestration Docker
├── Makefile              # Raccourcis de commandes
└── README.md             # Documentation projet
```

---

## ✅ TODO / Roadmap

- [ ] Implémenter la gestion complète des biens immobiliers (CRUD)
- [ ] Intégration du paiement Stripe et PayDunya
- [ ] Déploiement en staging (Scaleway / OVH / AWS)
- [ ] Mise en place CI/CD (GitHub Actions)
- [ ] Frontend React (future itération)

---

## 📜 Licence

Projet **open source** sous licence MIT.
Développé avec ❤️ pour le marché **Afrique / Francophonie**.

---

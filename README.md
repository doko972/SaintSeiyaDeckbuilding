## FonctionnalitéComplexitéBonus quotidien (daily login)⭐
## Historique des combats⭐
## Classement des joueurs⭐⭐
## Combat PvP en temps réel⭐⭐⭐
## Système de quêtes/missions⭐⭐
## Événements temporaires⭐⭐
## Échange de cartes entre joueurs⭐⭐⭐
## Amélioration/fusion de cartes⭐⭐
## Animations de combat (Canvas/WebGL)⭐⭐⭐
## Application mobile (PWA)⭐⭐

# Installer les dépendances :
```
composer install
npm install
```

cp .env.example .env
php artisan key:generate
```

## Ensuite, configure la base de données dans `.env` :
```
DB_DATABASE=saint_seiya
DB_USERNAME=root
DB_PASSWORD=


##  Lancer les seeders
php artisan db:seed

## Ou pour tout faire d'un coup (migrate + seed) :
php artisan migrate:fresh --seed

## Lien symbolique pour le storage (images)
php artisan storage:link

## Compiler les assets
npm run build

##  Lancer le serveur
php artisan serve

# Pour l'exemple : 
## Supprimer les anciens fichiers seeder
rm database/seeders/CardSeeder.php
rm database/seeders/AttackSeeder.php


## Réinitialiser la base et relancer toutes les migrations

saint seiya image galaxycard
# Installation Back Korrigans

## Préparation
- Créer un `files/php-tweak.ini` à partir du modèle `files/php-tweak.skel` (+adapter)
- Créer un `www/html/includes/params.php` à partir du modèle `www/html/includes/params.skel` (+adapter)
- Créer un `.env` à partir du modèle `env.skel` (+adapter)
- Renseigner les variables dans `.env`

## Démarrage
- Lancer un `docker-compose build` pour le 1er lancement
- Lancer ensuite un `docker-compose up -d`

## Dépannage
- Si besoin de repartir de zéro, après un `docker-compose down`, virer `data/mysql`et `data/www/slim`
- Relancer un `docker-compose up -d`

# Mini-caisse

Une petite application simple permettant de prendre les commandes rapidement pour des événements locaux (fêtes, kermesses, etc.), et ainsi raccourcir la file d'attente aux caisses.

## Installation

- cloner le dépôt
- avoir _Docker_ installé sur sa machine
- `docker-compose up` (option ` -d` si tu ne souhaite pas voir les logs => daemon détaché)
- lancer la _migration_ `docker-compose exec backend php artisan migrate`, explications ⬇️
  - `docker-compose exec` permet d'exécuter une commande dans un container
  - `backend` nom du service dans lequel exécuter la commande
  - `php artisan migrate` la commande à exécuter
- lancer le _seeding_ `docker-compose exec backend php artisan db:seed`
- si on veut supprimer les tables puis les remplir à nouveau : `docker-compose exec backend php artisan migrate:fresh --seed`

## Tests

- pour lancer les tests, exécuter la commande `docker-compose exec backend php artisan test`
- il y a des tests sur l'API, les routes et les permissions de l'interface d'administration
- côté frontend, je n'ai pas encore mis en place les tests

## URLs

- frontend : http://localhost:3000
  - seule la prise de commande est fonctionnelle
- backend :
  - api : http://localhost:8001/api
  - admin : http://localhost:8001/admin/

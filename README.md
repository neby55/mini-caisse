# Mini-caisse

Une petite application simple permettant de prendre les commandes rapidement pour des événements locaux (fêtes, kermesses, etc.), et ainsi raccourcir la file d'attente aux caisses.

## Installation

- cloner le dépôt
- avoir _Docker_ installé sur sa machine
- `docker-compose up` (option ` -d` si tu ne souhaite pas voir les logs => daemon détaché)
- lancer la _migration_ `docker-compose exec backend php artisan migrate`
  - `docker-compose exec` permet d'exécuter une commande dans un container
  - `backend` nom du service dans lequel exécuter la commande
  - `php artisan migrate` la commande à exécuter
- lancer le _seeding_ `docker-compose exec backend php artisan db:seed`
- si on veut supprimer les tables puis les remplir à nouveau : `docker-compose exec backend php artisan migrate:fresh --seed`
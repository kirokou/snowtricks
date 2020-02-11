
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/95d6153ada5e4255a026e33d61a390d5)](https://app.codacy.com/manual/borgine/snowtricks?utm_source=github.com&utm_medium=referral&utm_content=kirokou/snowtricks&utm_campaign=Badge_Grade_Dashboard)

## Lancement server
symfony server:start

## Make CRUD
php bin/console make:crud

## Create or finish branc in gitflow
git flow feature start branchname
git flow feature finish branchname

## Les fixtures
composer require --dev orm-fixtures
php bin/console doctrine:fixtures:load
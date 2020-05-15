
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/95d6153ada5e4255a026e33d61a390d5)](https://app.codacy.com/manual/borgine/snowtricks?utm_source=github.com&utm_medium=referral&utm_content=kirokou/snowtricks&utm_campaign=Badge_Grade_Dashboard)

# CONTEXTE
Projet 6 de mon parcours Développeur d'application PHP/Symfony chez OpenClassrooms.
Création d'un Porfolio via une architecture MVC Orienté objet.

## Project summary
Jimmy Sweat est un entrepreneur ambitieux passionné de snowboard. Son objectif est la création d'un site collaboratif pour faire connaître ce sport auprès du grand public et aider à l'apprentissage des figures (tricks).
Il souhaite capitaliser sur du contenu apporté par les internautes afin de développer un contenu riche et suscitant l’intérêt des utilisateurs du site. Par la suite, Jimmy souhaite développer un business de mise en relation avec les marques de snowboard grâce au trafic que le contenu aura généré.
Pour ce projet, nous allons nous concentrer sur la création technique du site pour Jimmy.

## Project needs
Vous êtes chargé de développer le site répondant aux besoins de Jimmy. Vous devez ainsi implémenter les fonctionnalités suivantes : 

- un annuaire des figures de snowboard. Vous pouvez vous inspirer de la liste des figures sur Wikipédia. Contentez-vous d'intégrer 10 figures, le reste sera saisi par les internautes ;
- la gestion des figures (création, modification, consultation) ;
- un espace de discussion commun à toutes les figures.

Pour implémenter ces fonctionnalités, vous devez créer les pages suivantes :
- la page d’accueil où figurera la liste des figures ; 
- la page de création d'une nouvelle figure ;
- la page de modification d'une figure ;
- la page de présentation d’une figure (contenant l’espace de discussion commun autour d’une figure).

## Deliverables
Un lien vers l’ensemble du projet (fichiers PHP/HTML/JS/CSS…) sur un repository GitHub.
L’ensemble des diagrammes demandés (modèles de données, classes, use cases, séquentiels).
Les issues sur le repository GitHub.
Les instructions pour installer le projet (dans un fichier README à la racine du projet).
Jeu de données initiales avec l’ensemble des figures de snowboard.
Lien vers les analyses SensioLabsInsight, Codacy ou Codeclimate (via une médaille dans le README, par exemple).

# HOW INSTALL THIS PROJECT 

## Required and technical environment
> Language => PHP 7.2.14

> Database => MySQL 5.7.25

> Web Server => Apache 2.2.34

> Symfony => 4.14.4

> Bootstrap => 4

> Composer => 1.8.5 

> Yarn => 1.22.4

## Step 1: clone the projet
    git clone https://github.com/kirokou/snowtricks.git

## Step 2: install composer
https://getcomposer.org/download/

## Step 3: download back dependencies 
    composer install

## Step 4: webpack encore
    composer require webpack-bundle
    yarn install

## Step 5: config .env
    For macOs: DATABASE_URL=mysql://root:root@127.0.0.1:8889/snowtricks
    For Others: DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/snowtricks

## Step 6: create DB
    php bin/console d:d:c

## Step 7: update schema (create tables)
    php bin/console d:s:u -f
    OR
    php bin/console make:migration
    php bin/console doctrine:migrations:migrate

## Step 8: load fixtures
    php bin/console do:fi:lo  

## Step 9: start server
    symfony serve

## Step 10: default user
<table>
    <thead>
        <tr>
            <th>pseudo</th>
            <th align="center">password</th>
            <th align="right">role</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>user@gmail.com</td>
            <td align="center">openclassrooms-P6</td>
            <td align="right">ROLE_USER</td>
        </tr>
        <tr>
            <td>admin@gmail.com</td>
            <td align="center">openclassrooms-P6</td>
            <td align="right">ROLE_ADMIN</td>
        </tr>
    </tbody>
</table>

# UML DIAGRAMM
<a href="public/diagrammes">click here</a>
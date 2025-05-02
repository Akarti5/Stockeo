# Gestion de Stock des Produits - Application Web Complète

Ce projet est une application web complète pour la gestion de stock des produits, comprenant une interface utilisateur frontale développée avec Vue.js et un backend API développé avec PHP. Les données sont persistées dans une base de données PostgreSQL.

## Fonctionnalités Principales

* **Ajout de Produits :** Permet d'ajouter de nouveaux produits au stock en spécifiant la désignation, le prix et la quantité.
* **Listage et Mise à Jour des Produits :** Affiche la liste des produits en stock avec la possibilité de modifier leurs informations et de les supprimer.
* **Bilan des Produits :** Fournit un aperçu du stock avec le montant minimal, maximal et total des produits, visualisé à l'aide d'un graphique.

## Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre système :

* **Node.js et npm (ou yarn/pnpm) :** Nécessaires pour le développement du frontend Vue.js. Vous pouvez les télécharger depuis [https://nodejs.org/](https://nodejs.org/).
* **PHP :** Nécessaire pour exécuter le backend API.
* **Serveur Web Local (Optionnel pour le développement backend) :** Un serveur web comme Apache ou Nginx est recommandé pour servir le backend PHP. **XAMPP** ([https://www.apachefriends.org/fr/index.html]) est une solution populaire qui inclut Apache, PHP et MySQL (bien que ce projet utilise PostgreSQL).
* **PostgreSQL :** La base de données utilisée pour stocker les informations des produits. Vous pouvez la télécharger depuis [https://www.postgresql.org/download/](https://www.postgresql.org/download/).
* **Composer (Optionnel) :** Un gestionnaire de dépendances pour PHP, bien que ce projet simple puisse ne pas en nécessiter beaucoup. Vous pouvez l'installer depuis [https://getcomposer.org/](https://getcomposer.org/).
* **VS Code (ou un autre éditeur de code) :** Recommandé pour le développement.
* **Git :** Pour le contrôle de version.

## Installation et Configuration

Suivez ces étapes pour configurer et exécuter l'application sur votre machine locale :

### 1. Configuration du Backend PHP

* **Placez le dossier `backend` dans le répertoire racine des documents web de votre serveur local.**
    * **Pour XAMPP :** Le répertoire racine est généralement `htdocs` (par exemple, `C:\xampp\htdocs\`). Vous devrez donc placer le dossier `backend` dans `C:\xampp\htdocs\backend\`.
    * **Pour MAMP :** Le répertoire racine peut être `/Applications/MAMP/htdocs/`.
    * **Pour d'autres serveurs web :** Consultez la documentation de votre serveur pour connaître le répertoire racine des documents web.
* **Configurez la connexion à la base de données PostgreSQL :**
    * Modifiez le fichier `backend/config.php` avec les informations de connexion correctes pour votre instance PostgreSQL (hôte, nom de la base de données, utilisateur, mot de passe). Assurez-vous que la base de données spécifiée existe et que l'utilisateur a les droits d'accès nécessaires.

### 2. Configuration du Frontend Vue.js

* **Naviguez vers le répertoire racine du projet dans votre terminal :**
    ```bash
    cd Gestion-des-stocks-produits
    ```
* **Installez les dépendances du projet :**
    ```bash
    npm install
    ```
* **Démarrez le serveur de développement :**
    ```bash
    npm run dev
    ```
    Cela lancera l'application frontend sur une URL locale (généralement `http://localhost:5173/`).

### 3. Configuration de la Base de Données PostgreSQL

* Assurez-vous que PostgreSQL est en cours d'exécution sur votre machine.
* Créez une base de données nommée (par exemple) `gsp_database`.
* Créez une table nommée `Produit` avec les colonnes suivantes (et les types appropriés) :
    * `numProduit` (INT PRIMARY KEY AUTO_INCREMENT)
    * `design` (VARCHAR)
    * `prix` (DECIMAL)
    * `quantite` (INT)

## Utilisation

1.  Une fois que le serveur de développement Vue.js est en cours d'exécution, ouvrez votre navigateur à l'URL fournie.
2.  Vous devriez voir l'interface utilisateur de l'application avec les liens de navigation pour "Ajouter", "Lister & Modifier" et "Bilan & Graphe".
3.  Utilisez les différents menus pour interagir avec la gestion de stock.

## Déploiement

Les étapes de déploiement dépendront de votre environnement d'hébergement. Généralement, cela impliquera de construire le frontend (`npm run build`) et de configurer votre serveur web pour servir les fichiers statiques du frontend (dans le dossier `dist` qui sera créé) et exécuter le backend PHP. Assurez-vous que votre serveur a accès à la base de données PostgreSQL.

## Contribution

Les contributions sont les bienvenues ! Si vous souhaitez améliorer ce projet, n'hésitez pas à créer des pull requests.

## Licence

[Indiquez ici la licence de votre projet, par exemple MIT]
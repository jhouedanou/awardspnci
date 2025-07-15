# Thème WordPress Feane Restaurant

Ce thème WordPress est basé sur le design HTML Feane et a été converti pour être entièrement compatible avec WordPress et l'éditeur Gutenberg.

## 🚀 Fonctionnalités

### ✅ Compatibilité WordPress
- **WordPress 5.0+** avec support complet de Gutenberg
- **Custom Post Types** pour les plats et témoignages
- **Widgets personnalisés** pour le footer
- **Personnalisation du thème** via l'API WordPress Customizer
- **Support multilingue** avec les fichiers de traduction

### 🎨 Blocs Gutenberg Personnalisés
- **Bloc Hero Section** : Section d'accueil avec image de fond, titre, description et bouton
- **Bloc Menu Grid** : Affichage des plats avec filtres par catégorie
- **Bloc Testimonials** : Carousel de témoignages clients
- **Bloc Booking Form** : Formulaire de réservation avec carte Google Maps

### 📝 Custom Post Types
- **Plats (Menu Items)** : Gestion des plats avec prix, catégories et images
- **Témoignages** : Gestion des avis clients avec photos

### ⚙️ Personnalisation
- **Logo personnalisable** via l'API WordPress
- **Images de fond** configurables
- **Informations de contact** modifiables
- **Réseaux sociaux** configurables
- **Heures d'ouverture** personnalisables

## 📦 Installation

### 1. Prérequis
- WordPress 5.0 ou supérieur
- PHP 7.4 ou supérieur
- Serveur web (Apache/Nginx)

### 2. Installation du thème
1. Téléchargez le dossier du thème
2. Uploadez-le dans `/wp-content/themes/`
3. Activez le thème depuis l'administration WordPress
4. Allez dans **Apparence > Personnaliser** pour configurer le thème

### 3. Configuration initiale

#### Créer les pages nécessaires
Créez les pages suivantes :
- **Accueil** : Page d'accueil avec les blocs Gutenberg
- **Menu** : Page pour afficher tous les plats
- **À propos** : Page de présentation du restaurant
- **Réserver** : Page avec le formulaire de réservation
- **Commander en ligne** : Page pour les commandes

#### Configurer le menu principal
1. Allez dans **Apparence > Menus**
2. Créez un nouveau menu
3. Ajoutez les pages créées
4. Assignez le menu à l'emplacement "Menu Principal"

#### Ajouter des plats
1. Allez dans **Plats > Ajouter**
2. Remplissez :
   - **Titre** : Nom du plat
   - **Description** : Description détaillée
   - **Extrait** : Description courte
   - **Image à la une** : Photo du plat
   - **Prix** : Prix du plat
   - **Catégorie** : Burger, Pizza, Pasta, Fries

#### Ajouter des témoignages
1. Allez dans **Témoignages > Ajouter**
2. Remplissez :
   - **Titre** : Nom du client
   - **Contenu** : Témoignage
   - **Image à la une** : Photo du client
   - **Position** : Poste/fonction du client (optionnel)

## 🎯 Utilisation des Blocs Gutenberg

### Bloc Hero Section
```
[feane/hero-section]
- Titre : Titre principal de la section
- Description : Texte descriptif
- Texte du bouton : Texte du bouton d'action
- URL du bouton : Lien du bouton
- Image de fond : Image d'arrière-plan
```

### Bloc Menu Grid
```
[feane/menu-grid]
- Titre : Titre de la section menu
- Catégorie : Filtrer par catégorie (optionnel)
- Nombre de plats : Nombre de plats à afficher
- Afficher les filtres : Activer/désactiver les filtres
```

### Bloc Testimonials
```
[feane/testimonials]
- Titre : Titre de la section témoignages
- Nombre de témoignages : Nombre à afficher
- Carousel : Activer/désactiver le carousel
```

### Bloc Booking Form
```
[feane/booking-form]
- Titre : Titre du formulaire
- Afficher la carte : Activer/désactiver Google Maps
```

## 🎨 Personnalisation

### Via l'API Customizer
Allez dans **Apparence > Personnaliser** pour modifier :

#### Section Hero
- Image de fond du hero

#### Section À propos
- Titre de la section
- Texte descriptif
- Image de la section

#### Informations de contact
- Adresse et lien Google Maps
- Numéro de téléphone
- Adresse email

#### Réseaux sociaux
- Facebook, Twitter, LinkedIn, Instagram, Pinterest

#### Heures d'ouverture
- Jours d'ouverture
- Horaires

#### Footer
- Description du footer

### Via les fichiers CSS
Le thème utilise les fichiers CSS existants :
- `css/style.css` : Styles principaux
- `css/responsive.css` : Styles responsives
- `css/bootstrap.css` : Framework Bootstrap

## 🔧 Développement

### Structure des fichiers
```
feane/
├── style.css                 # Informations du thème
├── functions.php             # Fonctions principales
├── index.php                 # Template principal
├── header.php                # En-tête du site
├── footer.php                # Pied de page
├── page.php                  # Template des pages
├── single.php                # Template des articles
├── sidebar.php               # Sidebar
├── archive-menu_item.php     # Archive des plats
├── single-menu_item.php      # Page individuelle d'un plat
├── inc/
│   ├── blocks.php            # Blocs Gutenberg
│   └── customizer.php        # Personnalisation
├── css/                      # Fichiers CSS existants
├── js/                       # Fichiers JS existants
└── images/                   # Images existantes
```

### Ajouter de nouveaux blocs Gutenberg
1. Modifiez `inc/blocks.php`
2. Ajoutez la fonction de rendu
3. Enregistrez le bloc dans `feane_register_blocks()`

### Personnaliser les styles
1. Modifiez `css/style.css` pour les styles principaux
2. Ajoutez des styles spécifiques dans `style.css` (fichier principal du thème)

## 🌐 Support multilingue

Le thème est prêt pour la traduction avec :
- Fonctions `__()` et `_e()` pour les textes
- Fichiers `.pot` pour les traductions
- Support des langues RTL

## 📱 Responsive Design

Le thème est entièrement responsive grâce à :
- Bootstrap 4
- CSS media queries
- Images adaptatives
- Navigation mobile

## 🔒 Sécurité

Le thème respecte les bonnes pratiques de sécurité WordPress :
- Échappement des données avec `esc_html()`, `esc_url()`, etc.
- Validation des entrées utilisateur
- Protection contre les injections SQL
- Nonces pour les formulaires

## 📞 Support

Pour toute question ou problème :
1. Vérifiez la documentation WordPress
2. Consultez les logs d'erreur
3. Testez avec un thème par défaut
4. Contactez le développeur

## 📄 Licence

Ce thème est basé sur le design Feane et adapté pour WordPress. Respectez les licences originales des ressources utilisées.

---

**Version** : 1.0  
**Dernière mise à jour** : 2024  
**Compatibilité WordPress** : 5.0+  
**Compatibilité PHP** : 7.4+ 
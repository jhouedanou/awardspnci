# Système de Vote - Awards & Concours

Un plugin WordPress complet pour gérer des systèmes de vote pour des awards et concours photo avec gestion backend et frontend.

## 🚀 Fonctionnalités

### Backend (Administration)
- **Gestion des catégories** : Créer, modifier, supprimer des catégories de vote
- **Gestion des candidats** : Ajouter des candidats avec photos, biographies et galeries
- **Tableau de bord** : Statistiques en temps réel des votes
- **Résultats** : Visualisation des résultats avec export CSV
- **Paramètres** : Configuration du système (périodes de vote, notifications, etc.)
- **Sécurité** : Validation des données, nonces, limitation des votes

### Frontend (Public)
- **Grille des catégories** : Affichage responsive des catégories disponibles
- **Vote par catégorie** : Interface intuitive pour voter
- **Modales détaillées** : Informations complètes sur les candidats
- **Résultats en temps réel** : Affichage des résultats avec graphiques
- **Responsive design** : Compatible mobile et desktop

### Fonctionnalités avancées
- **Système de notifications** : Emails automatiques après vote
- **Galerie d'images** : Support multi-images par candidat
- **Périodes de vote** : Contrôle des dates de début et fin
- **Limitation des votes** : Un vote par utilisateur par catégorie
- **Export des données** : CSV des résultats
- **Hooks et filtres** : Extensibilité complète

## 📋 Prérequis

- WordPress 5.0 ou supérieur
- PHP 7.4 ou supérieur
- MySQL 5.6 ou supérieur
- jQuery (inclus avec WordPress)

## 🔧 Installation

1. **Télécharger le plugin**
   ```bash
   # Cloner le repository
   git clone [url-du-repo] wp-content/plugins/voting-system
   
   # Ou télécharger et extraire dans wp-content/plugins/
   ```

2. **Activer le plugin**
   - Aller dans l'administration WordPress
   - Naviguer vers Plugins > Plugins installés
   - Activer "Système de Vote - Awards & Concours"

3. **Configuration initiale**
   - Le plugin crée automatiquement les tables de base de données
   - Créer des pages par défaut pour le vote et les résultats
   - Configurer les paramètres dans "Votes & Awards > Paramètres"

## 🎯 Utilisation

### Shortcodes disponibles

#### Afficher le système de vote
```php
[voting_system]
```

#### Afficher les résultats
```php
[voting_results]
```

#### Afficher une catégorie spécifique
```php
[voting_system category="1"]
```

#### Afficher les résultats d'une catégorie
```php
[voting_results category="1"]
```

### Pages par défaut créées

Le plugin crée automatiquement :
- **Vote** : Page principale pour voter
- **Résultats** : Page pour afficher les résultats

### Gestion des catégories

1. Aller dans "Votes & Awards > Catégories"
2. Cliquer sur "Ajouter une catégorie"
3. Remplir les informations :
   - **Nom** : Nom de la catégorie
   - **Slug** : URL-friendly (auto-généré)
   - **Description** : Description de la catégorie
   - **Image** : Image représentative
   - **Période de vote** : Date de début et fin
   - **Statut** : Actif/Inactif
   - **Votes max par utilisateur** : Limite de votes

### Gestion des candidats

1. Aller dans "Votes & Awards > Candidats"
2. Cliquer sur "Ajouter un candidat"
3. Remplir les informations :
   - **Nom** : Nom du candidat
   - **Photo** : Photo principale
   - **Biographie** : Description du candidat
   - **Catégorie** : Catégorie d'appartenance
   - **Galerie** : Images supplémentaires (JSON)
   - **Ordre d'affichage** : Position dans la liste

## 🔌 Hooks et Filtres

### Actions disponibles

```php
// Après un vote enregistré
do_action('voting_system_vote_cast', $vote_id, $user_id, $candidate_id, $category_id);

// Après suppression d'un vote
do_action('voting_system_vote_removed', $vote_id, $user_id, $candidate_id, $category_id);

// Avant l'affichage des catégories
do_action('voting_system_before_categories_display');

// Après l'affichage des catégories
do_action('voting_system_after_categories_display');
```

### Filtres disponibles

```php
// Modifier les données d'une catégorie
apply_filters('voting_system_category_data', $category_data);

// Modifier les données d'un candidat
apply_filters('voting_system_candidate_data', $candidate_data);

// Modifier le message de confirmation de vote
apply_filters('voting_system_vote_confirmation_message', $message, $candidate, $category);

// Modifier les paramètres par défaut
apply_filters('voting_system_default_settings', $settings);
```

## 🎨 Personnalisation

### CSS personnalisé

Ajouter du CSS personnalisé dans les paramètres du plugin :

```css
/* Personnaliser les couleurs */
:root {
    --voting-primary-color: #your-color;
    --voting-secondary-color: #your-color;
    --voting-success-color: #your-color;
}

/* Personnaliser les cartes */
.voting-category-card {
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}
```

### Templates personnalisés

Créer des templates dans votre thème :

```php
// Dans votre thème
wp-content/themes/your-theme/voting-system/
├── category-card.php
├── candidate-card.php
├── results-item.php
└── modal-candidate.php
```

### Fonctions personnalisées

```php
// Ajouter des fonctionnalités personnalisées
add_action('voting_system_vote_cast', function($vote_id, $user_id, $candidate_id, $category_id) {
    // Votre code personnalisé
    send_custom_notification($user_id, $candidate_id);
}, 10, 4);
```

## 🔒 Sécurité

### Mesures de sécurité implémentées

- **Nonces WordPress** : Protection CSRF
- **Validation des données** : Sanitisation et validation
- **Limitation des votes** : Un vote par utilisateur par catégorie
- **Vérification des permissions** : Contrôle d'accès
- **Protection contre les votes multiples** : Vérification IP et session
- **Validation des périodes** : Contrôle des dates de vote

### Bonnes pratiques

1. **Maintenir à jour** : Mettre à jour WordPress et le plugin
2. **Sauvegardes** : Sauvegarder régulièrement la base de données
3. **Monitoring** : Surveiller les logs de vote
4. **Permissions** : Limiter l'accès aux fonctions d'administration

## 📊 Base de données

### Tables créées

```sql
-- Table des catégories
wp_vote_categories (
    id, name, slug, description, image, 
    start_date, end_date, status, max_votes_per_user,
    created_at, updated_at
)

-- Table des candidats
wp_vote_candidates (
    id, name, photo, biography, category_id,
    gallery, status, display_order,
    created_at, updated_at
)

-- Table des votes
wp_vote_votes (
    id, user_id, candidate_id, category_id,
    ip_address, user_agent, voted_at
)
```

### Options WordPress

```php
// Paramètres du système
get_option('voting_system_settings');

// Pages par défaut
get_option('voting_system_pages');

// CSS personnalisé
get_option('voting_system_custom_css');

// Logs de vote
get_option('voting_system_logs');
```

## 🐛 Dépannage

### Problèmes courants

1. **Les votes ne s'enregistrent pas**
   - Vérifier que l'utilisateur est connecté
   - Vérifier les permissions
   - Vérifier les logs d'erreur

2. **Les images ne s'affichent pas**
   - Vérifier les permissions des dossiers
   - Vérifier les URLs des images
   - Vérifier la taille des fichiers

3. **Erreurs AJAX**
   - Vérifier la console JavaScript
   - Vérifier les logs PHP
   - Vérifier la configuration du serveur

### Mode debug

Activer le mode debug WordPress :

```php
// Dans wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## 📈 Performance

### Optimisations recommandées

1. **Cache** : Utiliser un plugin de cache (WP Rocket, W3 Total Cache)
2. **Images** : Optimiser les images (WebP, compression)
3. **Base de données** : Indexer les tables de vote
4. **CDN** : Utiliser un CDN pour les assets

### Monitoring

```php
// Vérifier les performances
$start_time = microtime(true);
// Votre code
$end_time = microtime(true);
$execution_time = $end_time - $start_time;
```

## 🤝 Contribution

### Développement

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

### Tests

```bash
# Lancer les tests
phpunit tests/

# Tests de sécurité
phpcs --standard=WordPress

# Tests de performance
php -d memory_limit=512M test-performance.php
```

## 📄 Licence

Ce projet est sous licence GPL v2 ou ultérieure.

## 👨‍💻 Auteur

**Jean Luc Houedanou**
- Email : jeanluc@houedanou.com
- Site web : [votre-site.com]

## 🙏 Remerciements

- WordPress Community
- Contributeurs du projet
- Testeurs et utilisateurs

## 📞 Support

Pour obtenir de l'aide :

1. **Documentation** : Consulter ce README
2. **Issues** : Ouvrir une issue sur GitHub
3. **Email** : jeanluc@houedanou.com
4. **Forum** : [Forum de support]

---

**Version** : 1.0.0  
**Dernière mise à jour** : 2024  
**Compatibilité WordPress** : 5.0+  
**Compatibilité PHP** : 7.4+ 
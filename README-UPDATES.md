# Système de mise à jour automatique - Thème Aiko by Mediapilote

## Vue d'ensemble

Ce système permet de déployer automatiquement des mises à jour de votre thème WordPress vers des installations distantes. Il comprend :

1. **Un serveur de mise à jour centralisé** qui distribue les nouvelles versions
2. **Un client intégré au thème** qui vérifie et télécharge les mises à jour
3. **Des scripts de déploiement automatisé** pour simplifier les releases

## Architecture

```
Votre serveur principal
├── update-server.php      # API de vérification des versions
├── download.php          # Distribution sécurisée des archives
└── releases/
    ├── mediapilote-v1.0.1.zip
    ├── mediapilote-v1.0.2.zip
    └── ...

Sites clients
└── wp-content/themes/mediapilote/
    ├── inc/theme-updater.php    # Client de mise à jour
    ├── inc/update-config.php    # Configuration
    └── functions.php           # Intégration WordPress
```

## Installation

### 1. Configuration du serveur de mise à jour

1. **Uploadez les fichiers serveur** sur votre serveur web :

   ```bash
   # Créez un dossier dédié, par exemple :
   /public_html/theme-updates/
   ├── update-server.php
   ├── download.php
   └── releases/ (créez ce dossier)
   ```

2. **Modifiez les configurations** dans `update-server.php` et `download.php` :
   ```php
   // Dans update-server.php et download.php
   define('UPDATE_KEY', 'votre_cle_secrete_unique_2024'); // Changez cette clé !
   define('UPDATE_PATH', 'https://aiko.mediapilote-laval.fr/');
   ```

### 2. Configuration du thème

1. **Modifiez le fichier de configuration** `inc/update-config.php` :

   ```php
   'update_server' => [
       'url' => 'https://votre-domaine.com/theme-updates/', // Votre URL
   ],
   'license_key' => 'votre_cle_secrete_unique_2024', // Même clé que le serveur
   ```

2. **Le système est automatiquement activé** via `functions.php`

### 3. Configuration du script de déploiement

Modifiez `deploy-theme.sh` :

```bash
SERVER_PATH="/path/to/your/update/server" # Chemin vers votre serveur
```

## Utilisation

### Déploiement d'une nouvelle version

1. **Développez vos modifications** dans le thème

2. **Lancez le script de déploiement** :

   ```bash
   # Depuis le répertoire du thème
   ./deploy-theme.sh 1.0.2

   # Ou laissez le script détecter la version automatiquement
   ./deploy-theme.sh
   ```

Le script va :

- Mettre à jour les numéros de version dans tous les fichiers
- Créer une archive ZIP du thème
- Générer les checksums de sécurité
- Faire un commit Git avec tag
- Optionnellement déployer vers votre serveur

### Gestion des mises à jour côté client

#### Vérification automatique

Les sites clients vérifieront automatiquement les mises à jour **toutes les 12 heures**.

#### Vérification manuelle

Les administrateurs peuvent forcer la vérification via :

1. **Dashboard WordPress** → **Apparence** → **Mises à jour**
2. Cliquer sur "Vérifier les mises à jour"

#### Interface d'administration

Le thème ajoute une page **Apparence** → **Mises à jour** où les administrateurs peuvent :

- Voir la version actuelle
- Configurer leur clé de licence
- Forcer la vérification des mises à jour

## Sécurité

### Clé de licence

- Changez **obligatoirement** la clé par défaut
- Utilisez une clé longue et complexe
- La même clé doit être configurée sur le serveur ET dans le thème

### Token de téléchargement

- Les téléchargements utilisent un token temporaire (valide 1 heure)
- Le token est généré à partir de la clé de licence et de la date

### Validation des requêtes

- Vérification du slug du thème
- Validation de la clé de licence
- Contrôle de l'origine des requêtes

## Personnalisation

### Fréquence de vérification

Modifiez dans `inc/update-config.php` :

```php
'update_check_interval' => 6 * HOUR_IN_SECONDS, // Vérifier toutes les 6h
```

### Notifications

```php
'notify_on_update' => true,           // Notifications WordPress
'admin_email_notification' => true,   // Email aux admins
```

### Sauvegarde automatique

```php
'backup_before_update' => true,       // Backup avant mise à jour
'max_backup_files' => 3,             // Nombre de backups conservés
```

## Workflow de développement recommandé

1. **Développement local** : Travaillez sur votre copie locale
2. **Tests** : Testez sur un environnement de staging
3. **Versioning** : Incrémentez la version dans `style.css`
4. **Déploiement** : Lancez `./deploy-theme.sh`
5. **Distribution** : Les clients recevront automatiquement la mise à jour

## Structure des versions

Utilisez le [Semantic Versioning](https://semver.org/lang/fr/) :

- **1.0.0** : Version majeure (changements incompatibles)
- **1.1.0** : Version mineure (nouvelles fonctionnalités compatibles)
- **1.1.1** : Version de correction (corrections de bugs)

## Dépannage

### "Aucune mise à jour disponible"

1. Vérifiez que la version sur le serveur est supérieure à la version installée
2. Validez la configuration des URLs et clés de licence
3. Consultez les logs WordPress (si `WP_DEBUG` activé)

### "Erreur de téléchargement"

1. Vérifiez que le fichier ZIP existe dans le dossier `releases/`
2. Contrôlez les permissions des fichiers serveur
3. Validez que l'URL du serveur est accessible

### "Clé de licence invalide"

1. Vérifiez que la même clé est configurée partout
2. Attention aux espaces ou caractères spéciaux
3. Rechargez la configuration si nécessaire

## Support

Pour toute question ou problème :

- **Auteur** : Emmanuel Claude / Mediapilote
- **Email** : contact@mediapilote.com
- **Documentation** : Consultez les commentaires dans le code

---

_Ce système de mise à jour a été conçu pour être sécurisé, fiable et facile à utiliser. N'hésitez pas à l'adapter selon vos besoins spécifiques._

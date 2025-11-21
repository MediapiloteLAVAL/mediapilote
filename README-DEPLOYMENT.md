# 🚀 **Guide de déploiement du système de mise à jour - Aiko by Mediapilote**

## ✅ **Système fonctionnel !**

Votre système de mise à jour automatique fonctionne parfaitement :
- ✅ Détection des mises à jour
- ✅ Téléchargement depuis le serveur
- ✅ Authentification par licence
- ✅ Affichage correct du nom "Aiko by Mediapilote"

## 🔧 **Résolution du problème de permissions**

### **Erreur rencontrée :**
```
Impossible de déplacer l'ancienne version vers le répertoire upgrade-temp-backup
```

### **Solutions :**

#### **1. Sur serveur de production :**
```bash
# Permissions correctes pour les thèmes WordPress
chmod -R 755 /path/to/wp-content/themes/
chmod -R 755 /path/to/wp-content/upgrade*
chown -R www-data:www-data /path/to/wp-content/themes/ # Sur Ubuntu/Debian
# ou
chown -R apache:apache /path/to/wp-content/themes/ # Sur CentOS/RHEL
```

#### **2. Via FTP/cPanel :**
- Dossier `wp-content/themes/` : **755**
- Fichiers dans le thème : **644**
- Dossiers `wp-content/upgrade*` : **755**

#### **3. Via WordPress (wp-config.php) :**
```php
// Forcer les permissions correctes
define('FS_METHOD', 'direct');
define('FS_CHMOD_DIR', (0755 & ~ umask()));
define('FS_CHMOD_FILE', (0644 & ~ umask()));
```

## 📋 **Checklist de déploiement**

### **Serveur de mise à jour (aiko.mediapilote-laval.fr) :**
- [x] `update-server.php` configuré
- [x] `download.php` fonctionnel  
- [x] Dossier `releases/` avec le thème v1.0.1
- [x] Clé de licence : `mp_aiko_theme_2024_d7828cf73ad6513d1ab5cd54d9a985c9`

### **Sites clients :**
- [x] Fichiers du système intégrés au thème
- [x] Configuration dans `inc/update-config.php`
- [x] Permissions correctes sur les dossiers
- [x] Affichage du nom personnalisé

## 🎯 **Test final**

1. **Corriger les permissions** (commandes ci-dessus)
2. **Relancer la mise à jour** depuis `wp-admin/update-core.php`
3. **Vérifier** que la version passe de 1.0.0 à 1.0.1

## 🚀 **Déploiement sur sites de production**

Une fois les permissions corrigées, vous pourrez :

1. **Uploader une nouvelle version** (ex: 1.0.2) sur le serveur
2. **Les sites clients détecteront automatiquement** la mise à jour
3. **Mise à jour en un clic** depuis l'administration WordPress

---

**Système créé par Emmanuel Claude / Mediapilote**  
**Date : 20 novembre 2025**
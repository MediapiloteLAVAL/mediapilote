# RÉSOLUTION DE L'ERREUR DU BLOC

## ✅ Problème résolu

L'erreur "ce bloc a rencontré une erreur et ne peut pas être prévisualisé" était causée par un conflit entre `block.json` et l'enregistrement manuel dans le fichier JavaScript.

## 🔧 Corrections apportées

1. **Suppression de block.json** - Ce fichier créait un conflit avec l'enregistrement manuel
2. **Mise à jour de block.js** - Ajout des métadonnées du bloc (title, icon, category)
3. **Mise à jour de blockEntete.php** - Enregistrement manuel complet des scripts et styles

## 📝 Structure finale

```
blockEntete/
├── blockEntete.php   ✅ Enregistrement + rendu serveur
├── block.js          ✅ Interface Gutenberg (éditeur)
├── style.css         ✅ Styles frontend et éditeur
├── editor.css        ✅ Styles spécifiques éditeur
├── README.md         📄 Documentation
├── INSTALLATION.md   📄 Guide d'installation
└── example.php       📄 Exemples
```

## 🚀 Test du bloc

1. **Actualiser WordPress** :
   - Videz le cache du navigateur (Cmd+Shift+R)
   - Rechargez la page de l'éditeur

2. **Ajouter le bloc** :
   - Cliquez sur "+" dans l'éditeur
   - Recherchez "Entête Hero"
   - Le bloc devrait s'afficher sans erreur

3. **Vérifier les fonctionnalités** :
   - ✅ Édition du titre (cliquez dessus)
   - ✅ Édition de la description (cliquez dessus)
   - ✅ Sélection de l'image de fond (barre latérale)
   - ✅ Configuration du bouton (barre latérale)

## 🔍 En cas de problème persistant

Si le bloc affiche toujours une erreur :

1. **Vérifier la console du navigateur** (F12 > Console)
   - Recherchez les erreurs JavaScript
   - Notez le message d'erreur exact

2. **Vérifier les fichiers** :
   ```bash
   ls -la blocks/blockEntete/
   ```
   Assurez-vous que tous les fichiers sont présents

3. **Vider le cache WordPress** :
   - Si vous utilisez un plugin de cache, videz-le
   - Désactivez temporairement le cache

4. **Vérifier les dépendances** :
   - Le bloc utilise : wp-blocks, wp-element, wp-block-editor, wp-components, wp-i18n
   - Ces dépendances sont natives de WordPress 5.8+

## 📞 Débogage

Pour activer le mode debug dans WordPress, ajoutez dans `wp-config.php` :
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('SCRIPT_DEBUG', true);
```

Puis rechargez l'éditeur et consultez le fichier `wp-content/debug.log` pour voir les erreurs PHP éventuelles.

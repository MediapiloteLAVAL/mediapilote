# Installation et Activation du Bloc Entête Hero

## 🚀 Activation

Le bloc est automatiquement activé car il est déjà référencé dans le fichier `autoload_blocks.php`.

### Vérification

1. Allez dans l'admin WordPress
2. Créez ou éditez une page
3. Cliquez sur "+" pour ajouter un bloc
4. Recherchez "Entête Hero" ou tapez "entete"
5. Le bloc devrait apparaître dans la catégorie "Design"

## 🔧 Configuration

### Étape 1 : Ajouter le bloc
- Dans l'éditeur Gutenberg, cliquez sur "+"
- Recherchez "Entête Hero"
- Le bloc sera automatiquement ajouté en pleine largeur

### Étape 2 : Personnaliser le contenu
1. **Titre H1** : Cliquez directement sur le titre pour le modifier
2. **Description** : Cliquez sur la description pour la modifier
3. **Image de fond** : 
   - Ouvrez la barre latérale (⚙️ Paramètres)
   - Cliquez sur "Choisir une image"
   - Sélectionnez une image de votre médiathèque
4. **Bouton** :
   - Dans la barre latérale, modifiez le texte du bouton
   - Ajoutez l'URL de destination

## 📝 Conseils d'utilisation

### Image de fond recommandée
- **Dimensions** : 1920x1080px minimum
- **Format** : JPG ou PNG
- **Poids** : Optimisé pour le web (< 500Ko)
- **Style** : Photo avec contraste suffisant pour le texte blanc

### Titre
- Gardez-le court et impactant (2 lignes max)
- Le retour à la ligne est supporté
- Taille automatique : 94px sur desktop, responsive sur mobile

### Description
- Maximum 2-3 lignes recommandé
- Texte en majuscules automatique
- Largeur max : 971px

### Bouton
- Texte court et incitatif (ex: "En savoir +", "Découvrir", "Contact")
- Peut pointer vers n'importe quelle URL (interne ou externe)

## 🎨 Personnalisation avancée

### Modifier les styles
Éditez le fichier `style.css` pour personnaliser :
- Les couleurs
- Les tailles de police
- Les espacements
- Les effets de hover
- Les breakpoints responsive

### Modifier les lignes décoratives
Dans `style.css`, section `.hero-banner__decorative-lines` :
```css
.hero-banner__line--main {
    width: 260px;  /* Largeur de la ligne principale */
    height: 16px;  /* Hauteur de la ligne principale */
}
```

### Ajouter un slider
Pour transformer ce bloc en slider, vous pouvez :
1. Ajouter une bibliothèque comme Swiper.js
2. Modifier `block.js` pour gérer plusieurs slides
3. Adapter le rendu PHP pour afficher plusieurs slides

## 🐛 Dépannage

### Le bloc n'apparaît pas
1. Vérifiez que le fichier `autoload_blocks.php` contient bien la référence au bloc
2. Videz le cache de WordPress
3. Vérifiez la console navigateur pour les erreurs JS

### Les styles ne s'appliquent pas
1. Videz le cache du navigateur
2. Régénérez les assets si vous utilisez Gulp/Webpack
3. Vérifiez que les fichiers CSS sont bien chargés (inspecteur)

### L'image de fond ne s'affiche pas
1. Vérifiez que l'image est bien uploadée dans la médiathèque
2. Vérifiez les permissions des fichiers
3. Inspectez l'élément pour voir si l'URL est correcte

## 📦 Structure des fichiers

```
blockEntete/
├── README.md           # Documentation complète
├── INSTALLATION.md     # Ce fichier
├── block.json          # Configuration du bloc (moderne)
├── blockEntete.php     # Enregistrement et rendu serveur
├── block.js            # Interface Gutenberg
├── style.css           # Styles frontend
├── editor.css          # Styles éditeur
└── example.php         # Exemples d'utilisation
```

## 🔄 Mises à jour futures

Pour ajouter de nouvelles fonctionnalités :
1. Ajoutez l'attribut dans `block.json`
2. Ajoutez le champ dans `block.js` (interface d'édition)
3. Utilisez l'attribut dans `blockEntete.php` (rendu)
4. Stylisez dans `style.css`

## 🌐 Compatibilité

- ✅ WordPress 5.8+
- ✅ PHP 7.4+
- ✅ Gutenberg natif
- ✅ FSE (Full Site Editing)
- ✅ Responsive (desktop, tablet, mobile)
- ✅ Compatible avec tous les thèmes WordPress modernes

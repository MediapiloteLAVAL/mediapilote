# Bloc Entête Hero

## Description
Bloc Gutenberg natif FSE pour afficher un hero banner en pleine largeur avec :
- Un titre H1 éditable
- Une description éditable
- Un bouton avec lien et texte personnalisables
- Une image de fond personnalisable
- Des lignes décoratives
- Design responsive

## Utilisation

### Dans l'éditeur Gutenberg
1. Cliquez sur le bouton "+" pour ajouter un bloc
2. Recherchez "Entête Hero" ou "mediapilote/entete"
3. Le bloc sera ajouté en pleine largeur automatiquement

### Personnalisation
- **Titre** : Cliquez directement sur le titre pour le modifier
- **Description** : Cliquez directement sur la description pour la modifier
- **Image de fond** : Utilisez la barre latérale (Inspector) > "Choisir une image"
- **Texte du bouton** : Modifiez dans la barre latérale
- **Lien du bouton** : Ajoutez l'URL dans la barre latérale

## Caractéristiques techniques

### Attributs
- `title` (string) : Titre H1 du hero banner
- `description` (string) : Description/sous-titre
- `buttonText` (string) : Texte du bouton CTA
- `buttonUrl` (string) : URL du lien du bouton
- `backgroundImageId` (number) : ID de l'image de fond
- `backgroundImageUrl` (string) : URL de l'image de fond
- `align` (string) : Alignement (par défaut: 'full')

### Support
- Alignement en pleine largeur (full)
- Pas de HTML personnalisé
- Rendu côté serveur (PHP)

### Fichiers
- `blockEntete.php` : Enregistrement et rendu du bloc
- `block.js` : Interface d'édition Gutenberg
- `style.css` : Styles frontend et éditeur
- `editor.css` : Styles spécifiques à l'éditeur

## Design basé sur Figma
Ce bloc a été créé à partir d'une maquette Figma avec les caractéristiques suivantes :
- Hauteur : 1080px
- Overlay sombre avec dégradé
- Lignes décoratives blanches (260px de largeur)
- Titre : Inter Regular, 94px, line-height 113px
- Description : Inter Regular, 30px, uppercase, letter-spacing 1.5px
- Bouton : 260x52px, bordure blanche 3px, effet hover

## Responsive
- Desktop (1920px+) : Design complet
- Tablet (768px-1199px) : Réduction des tailles de police
- Mobile (<768px) : Adaptation complète pour petits écrans

## Notes de développement
- Bloc 100% natif Gutenberg (pas de ACF)
- Compatible FSE (Full Site Editing)
- Utilise les composants WordPress standard (RichText, MediaUpload, etc.)
- Rendu côté serveur pour de meilleures performances

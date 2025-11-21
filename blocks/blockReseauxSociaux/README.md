# Bloc Réseaux Sociaux

## Description

Ce bloc permet d'afficher une section de réseaux sociaux avec un design élégant inspiré de votre design Figma. Les réseaux sociaux sont configurés via les champs ACF dans les options du thème.

## Configuration des réseaux sociaux

### 1. Accès aux options

Allez dans **WordPress Admin > Options > Réseaux sociaux**

### 2. Ajouter des réseaux sociaux

Pour chaque réseau social, vous devez renseigner :

- **Nom** : Le nom du réseau social (ex: Facebook, LinkedIn, Instagram)
- **Icône** : Une image SVG ou PNG (maximum 100x100px)
- **Lien** : L'URL complète vers votre profil sur ce réseau

### 3. Recommandations pour les icônes

- Utilisez de préférence des icônes SVG pour une meilleure qualité
- Taille recommandée : 40x40px ou 50x50px
- Les icônes seront automatiquement converties en blanc dans le design

## Utilisation du bloc

### 1. Ajouter le bloc

Dans l'éditeur Gutenberg :

1. Cliquez sur le bouton "+"
2. Recherchez "Bloc Réseaux Sociaux"
3. Sélectionnez le bloc dans la catégorie "Design"

### 2. Personnalisation

Dans la barre latérale droite, vous pouvez modifier :

#### Couleur de fond

- Sélectionnez la couleur de fond de la section
- Couleur par défaut : #2d3037 (gris foncé)

#### Hauteur du bloc

- Ajustez la hauteur de la section (100px à 500px)
- Hauteur par défaut : 200px

## Design et responsive

### Structure visuelle

Le bloc reprend fidèlement le design Figma avec :

- Lignes décoratives horizontales et verticales
- Icônes des réseaux sociaux centrées
- Effet de grille élégant
- Transitions au survol

### Responsive

- **Desktop** : Affichage normal avec toutes les décorations
- **Tablette** : Réduction à 80% de la taille
- **Mobile** : Réduction à 60% de la taille

### Limitations

- Maximum 4 réseaux sociaux affichés (pour respecter le design)
- Les icônes sont automatiquement converties en blanc

## Structure des fichiers

```
blocks/blockReseauxSociaux/
├── blockReseauxSociaux.php  # Enregistrement du bloc et rendu PHP
├── block.js                 # Code JavaScript pour l'éditeur Gutenberg
├── style.css               # Styles CSS pour le frontend
├── editor.css              # Styles CSS pour l'éditeur
└── README.md               # Cette documentation
```

## Dépannage

### Les réseaux sociaux ne s'affichent pas

1. Vérifiez que des réseaux sociaux sont configurés dans **Options > Réseaux sociaux**
2. Vérifiez que chaque réseau a bien un nom, une icône et un lien
3. Vérifiez que le plugin ACF est activé

### Les icônes ne s'affichent pas

1. Vérifiez que les images sont bien uploadées dans la médiathèque
2. Vérifiez le format des images (SVG, PNG, JPG acceptés)
3. Vérifiez la taille des images (maximum 100x100px)

### Le bloc ne s'affiche pas dans Gutenberg

1. Vérifiez que le fichier `autoload_blocks.php` inclut bien le nouveau bloc
2. Videz le cache si vous utilisez un plugin de cache
3. Vérifiez les erreurs JavaScript dans la console du navigateur

## Support technique

Pour toute question ou problème technique, contactez l'équipe de développement Médiapilote.

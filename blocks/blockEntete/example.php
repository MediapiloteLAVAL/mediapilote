<!-- 
Exemple d'utilisation du bloc Entête Hero dans l'éditeur WordPress

Ce bloc peut être ajouté manuellement dans l'éditeur Gutenberg ou programmatiquement via PHP.
-->

<!-- wp:mediapilote/entete {
  "title":"H1 - The quick brown fox jumps over the lazy dog",
  "description":"Glad to see you! You're one password away from creating something amazing",
  "buttonText":"En savoir +",
  "buttonUrl":"#",
  "backgroundImageUrl":"",
  "backgroundImageId":0,
  "align":"full"
} /-->

<!-- 
Exemple avec une image de fond :
-->

<!-- wp:mediapilote/entete {
  "title":"Bienvenue sur notre site",
  "description":"Découvrez nos services et notre expertise",
  "buttonText":"Découvrir",
  "buttonUrl":"/services",
  "backgroundImageUrl":"https://example.com/image.jpg",
  "backgroundImageId":123,
  "align":"full"
} /-->

<?php
/**
 * Exemple d'insertion programmatique du bloc en PHP
 */

// Méthode 1 : Utiliser do_blocks() avec le contenu du bloc
$block_content = '<!-- wp:mediapilote/entete {"title":"Mon titre","description":"Ma description","buttonText":"Cliquez ici","buttonUrl":"/contact","align":"full"} /-->';
echo do_blocks($block_content);

// Méthode 2 : Utiliser render_block() avec les attributs
$block = array(
    'blockName' => 'mediapilote/entete',
    'attrs' => array(
        'title' => 'Mon titre personnalisé',
        'description' => 'Ma description personnalisée',
        'buttonText' => 'En savoir plus',
        'buttonUrl' => '/about',
        'backgroundImageUrl' => 'https://example.com/hero-bg.jpg',
        'backgroundImageId' => 456,
        'align' => 'full'
    ),
    'innerBlocks' => array(),
    'innerHTML' => '',
    'innerContent' => array()
);
echo render_block($block);

// Méthode 3 : Appeler directement la fonction de rendu (non recommandé mais possible)
$attributes = array(
    'title' => 'Titre direct',
    'description' => 'Description directe',
    'buttonText' => 'Bouton',
    'buttonUrl' => '/link',
    'backgroundImageUrl' => '',
    'backgroundImageId' => 0
);
echo mediapilote_render_block_entete($attributes);
?>

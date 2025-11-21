<?php
/**
 * Autoload des blocs personnalisés
 * 
 * @package MediaPilote
 * @since 1.0.0
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Charger tous les blocs personnalisés
$blocks_dir = get_template_directory() . '/blocks/';

// Bloc Bannière
if (file_exists($blocks_dir . 'blockBanniere/blockBanniere.php')) {
    require_once $blocks_dir . 'blockBanniere/blockBanniere.php';
}

// Bloc Actualités
if (file_exists($blocks_dir . 'blockActualites/blockActualites.php')) {
    require_once $blocks_dir . 'blockActualites/blockActualites.php';
}

// Bloc Bouton
if (file_exists($blocks_dir . 'blockBouton/blockBouton.php')) {
    require_once $blocks_dir . 'blockBouton/blockBouton.php';
}

// Bloc Citation
if (file_exists($blocks_dir . 'blockCitation/blockCitation.php')) {
    require_once $blocks_dir . 'blockCitation/blockCitation.php';
}

// Bloc Engagements
if (file_exists($blocks_dir . 'blockEngagements/blockEngagements.php')) {
    require_once $blocks_dir . 'blockEngagements/blockEngagements.php';
}

// Bloc Entête
if (file_exists($blocks_dir . 'blockEntete/blockEntete.php')) {
    require_once $blocks_dir . 'blockEntete/blockEntete.php';
}

// Bloc Image Texte
if (file_exists($blocks_dir . 'blockImageTexte/blockImageTexte.php')) {
    require_once $blocks_dir . 'blockImageTexte/blockImageTexte.php';
}

// Bloc Implantation
if (file_exists($blocks_dir . 'blockImplantation/blockImplantation.php')) {
    require_once $blocks_dir . 'blockImplantation/blockImplantation.php';
}

// Bloc Liste
if (file_exists($blocks_dir . 'blockListe/blockListe.php')) {
    require_once $blocks_dir . 'blockListe/blockListe.php';
}

// Bloc Partenaires
if (file_exists($blocks_dir . 'blockPartenaires/blockPartenaires.php')) {
    require_once $blocks_dir . 'blockPartenaires/blockPartenaires.php';
}

// Bloc Preuve Efficacité
if (file_exists($blocks_dir . 'blockPreuveEfficacite/blockPreuveEfficacite.php')) {
    require_once $blocks_dir . 'blockPreuveEfficacite/blockPreuveEfficacite.php';
}

// Bloc Produits
if (file_exists($blocks_dir . 'blockProduits/blockProduits.php')) {
    require_once $blocks_dir . 'blockProduits/blockProduits.php';
}

// Bloc Recherche Produit
if (file_exists($blocks_dir . 'blockRechercheProduit/blockRechercheProduit.php')) {
    require_once $blocks_dir . 'blockRechercheProduit/blockRechercheProduit.php';
}

// Bloc Répertoire Preuves
if (file_exists($blocks_dir . 'blockRepertoirePreuves/blockRepertoirePreuves.php')) {
    require_once $blocks_dir . 'blockRepertoirePreuves/blockRepertoirePreuves.php';
}

// Bloc Sélection Produit
if (file_exists($blocks_dir . 'blockSelectionProduit/blockSelectionProduit.php')) {
    require_once $blocks_dir . 'blockSelectionProduit/blockSelectionProduit.php';
}

// Bloc Témoignages
if (file_exists($blocks_dir . 'blockTemoignages/blockTemoignages.php')) {
    require_once $blocks_dir . 'blockTemoignages/blockTemoignages.php';
}

// Bloc USP
if (file_exists($blocks_dir . 'blockUSP/blockUSP.php')) {
    require_once $blocks_dir . 'blockUSP/blockUSP.php';
}

// Bloc Informations Produits
if (file_exists($blocks_dir . 'blockInformationsProduits/blockInformationsProduits.php')) {
    require_once $blocks_dir . 'blockInformationsProduits/blockInformationsProduits.php';
}

// Bloc Texte Simple
if (file_exists($blocks_dir . 'blockTexteSimple/blockTexteSimple.php')) {
    require_once $blocks_dir . 'blockTexteSimple/blockTexteSimple.php';
}

// Bloc Texte
if (file_exists($blocks_dir . 'BlocTexte/BlocTexte.php')) {
    require_once $blocks_dir . 'BlocTexte/BlocTexte.php';
}

// Bloc Affichage des Actualités (NOUVEAU)
if (file_exists($blocks_dir . 'BlocAffichageActualites/BlocAffichageActualites.php')) {
    require_once $blocks_dir . 'BlocAffichageActualites/BlocAffichageActualites.php';
}

// Bloc produits similaires
if (file_exists($blocks_dir . 'blockProduitsSimilaires/blockProduitsSimilaires.php')) {
    require_once $blocks_dir . 'blockProduitsSimilaires/blockProduitsSimilaires.php';
}

// Bloc Mise en avant
if (file_exists($blocks_dir . 'blockMiseEnAvant/blockMiseEnAvant.php')) {
    require_once $blocks_dir . 'blockMiseEnAvant/blockMiseEnAvant.php';
}

// Bloc Articles Recommandés
if (file_exists($blocks_dir . 'blockArticlesRecommandes/blockArticlesRecommandes.php')) {
    require_once $blocks_dir . 'blockArticlesRecommandes/blockArticlesRecommandes.php';
}

//Bloc coordonnées
if (file_exists($blocks_dir . 'blocCoordonnees/blocCoordonnees.php')) {
    require_once $blocks_dir . 'blocCoordonnees/blocCoordonnees.php';
}

// Bloc Carte
if (file_exists($blocks_dir . 'blockCarte/blockCarte.php')) {
    require_once $blocks_dir . 'blockCarte/blockCarte.php';
}

// Bloc CTA
if (file_exists($blocks_dir . 'blockCTA/blockCTA.php')) {
    require_once $blocks_dir . 'blockCTA/blockCTA.php';
}

// Bloc Slider d'Activités
if (file_exists($blocks_dir . 'blockSliderActivites/blockSliderActivites.php')) {
    require_once $blocks_dir . 'blockSliderActivites/blockSliderActivites.php';
}

// Bloc Remontée d'Actualités
if (file_exists($blocks_dir . 'blockRemonteeActualites/blockRemonteeActualites.php')) {
    require_once $blocks_dir . 'blockRemonteeActualites/blockRemonteeActualites.php';
}

// Bloc Contact
if (file_exists($blocks_dir . 'blockContact/blockContact.php')) {
    require_once $blocks_dir . 'blockContact/blockContact.php';
}

// Bloc Chiffres Clés
if (file_exists($blocks_dir . 'blockChiffresCles/blockChiffresCles.php')) {
    require_once $blocks_dir . 'blockChiffresCles/blockChiffresCles.php';
}

// Bloc Images
if (file_exists($blocks_dir . 'blockImages/blockImages.php')) {
    require_once $blocks_dir . 'blockImages/blockImages.php';
}

// Bloc Slider Confiance
if (file_exists($blocks_dir . 'blockSliderConfiance/blockSliderConfiance.php')) {
    require_once $blocks_dir . 'blockSliderConfiance/blockSliderConfiance.php';
}

// Bloc Texte en Colonnes
if (file_exists($blocks_dir . 'blockTexteColonnes/blockTexteColonnes.php')) {
    require_once $blocks_dir . 'blockTexteColonnes/blockTexteColonnes.php';
}

// Bloc Réseaux Sociaux
if (file_exists($blocks_dir . 'blockReseauxSociaux/blockReseauxSociaux.php')) {
    require_once $blocks_dir . 'blockReseauxSociaux/blockReseauxSociaux.php';
}
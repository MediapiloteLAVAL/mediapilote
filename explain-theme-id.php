<?php
// Test pour voir comment WordPress identifie les thèmes

echo "=== IDENTIFICATION DES THÈMES WORDPRESS ===

";

// Simuler les fonctions WordPress
function get_template_simulation() {
    return "mediapilote"; // Nom du dossier
}

function get_stylesheet_simulation() {
    return "mediapilote"; // Généralement identique sauf pour les thèmes enfants
}

// Comment WordPress construit les identifiants
$template = get_template_simulation();
$stylesheet = get_stylesheet_simulation();

echo "Template (dossier): $template
";
echo "Stylesheet: $stylesheet
";
echo "Identifiant WordPress: $stylesheet/style.css

";

// Dans le système de mise à jour WordPress
echo "Dans \$transient->response:
";
echo "Clé utilisée: \"$stylesheet/style.css\"

";

// Vérification avec wp_get_themes() (simulation)
echo "Structure attendue par WordPress:
";
echo "wp-content/themes/mediapilote/
";
echo "├── style.css (métadonnées du thème)
";
echo "├── functions.php
";
echo "├── index.php
";
echo "└── ...

";

echo "✅ Cest pourquoi notre système utilise: mediapilote/style.css
";
?>

<?php
/**
 * Script pour corriger automatiquement les permissions du thème
 * À exécuter si les mises à jour échouent à cause des permissions
 */

echo "🔧 Correction des permissions du thème...\n";

$theme_dir = __DIR__;
$wp_content_dir = dirname(dirname(__DIR__));

// Obtenir l'utilisateur/groupe du processus PHP
$current_user = posix_getpwuid(posix_geteuid())['name'];
$current_group = posix_getgrgid(posix_getegid())['name'];

echo "👤 Utilisateur actuel : $current_user:$current_group\n";

// Commande pour changer le propriétaire
$chown_command = "sudo chown -R $current_user:$current_group " . escapeshellarg($theme_dir);
echo "🔄 Exécution : $chown_command\n";

exec($chown_command, $output, $return_var);

if ($return_var === 0) {
    echo "✅ Permissions corrigées avec succès !\n";
    
    // Vérifier les permissions finales
    $stat = stat($theme_dir);
    $permissions = substr(sprintf('%o', $stat['mode']), -4);
    echo "📁 Permissions du dossier : $permissions\n";
    
} else {
    echo "❌ Erreur lors de la correction des permissions\n";
    echo "💡 Exécutez manuellement : $chown_command\n";
}

echo "\n🚀 Vous pouvez maintenant tenter la mise à jour !\n";
?>
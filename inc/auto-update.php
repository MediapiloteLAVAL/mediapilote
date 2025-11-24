<?php
/**
 * Script de mise à jour automatique du thème Mediapilote depuis GitHub
 * Accessible uniquement aux administrateurs
 */


function mediapilote_render_auto_update_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Accès refusé');
    }
    $theme = wp_get_theme();
    $current_version = $theme->get('Version');
    $repo_api_url = 'https://api.github.com/repos/MediapiloteLAVAL/mediapilote/releases/latest';
    $headers = array(
        'Accept' => 'application/vnd.github.v3+json',
        'User-Agent' => 'WordPress/' . get_bloginfo('version'),
    );
    if (defined('GITHUB_TOKEN') && GITHUB_TOKEN) {
        $headers['Authorization'] = 'token ' . GITHUB_TOKEN;
    }
    $args = array(
        'headers' => $headers,
        'timeout' => 15,
    );
    $response = wp_remote_get($repo_api_url, $args);
    $latest_version = false;
    $zip_url = false;
    if (!is_wp_error($response)) {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body);
        if (isset($data->tag_name) && isset($data->assets[0]->browser_download_url)) {
            $latest_version = ltrim($data->tag_name, 'v');
            $zip_url = $data->assets[0]->browser_download_url;
        }
    }
    $can_update = $latest_version && version_compare($current_version, $latest_version, '<') && $zip_url;
    ?>
    <div>
        <p>Version installée : <strong><?php echo esc_html($current_version); ?></strong></p>
        <p>Dernière version disponible : <strong><?php echo esc_html($latest_version ?: 'Non disponible'); ?></strong></p>
        <?php if ($can_update): ?>
            <form method="post" style="display:inline-block; margin-right:10px;">
                <?php wp_nonce_field('mediapilote_auto_update', 'mediapilote_auto_update_nonce'); ?>
                <input type="hidden" name="zip_url" value="<?php echo esc_url($zip_url); ?>">
                <button type="submit" class="button button-primary" name="do_update" value="1">Lancer la mise à jour</button>
            </form>
            <form method="post" style="display:inline-block;">
                <?php wp_nonce_field('mediapilote_auto_update', 'mediapilote_auto_update_nonce'); ?>
                <input type="hidden" name="zip_url" value="<?php echo esc_url($zip_url); ?>">
                <button type="submit" class="button" name="test_update" value="1">Tester la mise à jour (simulation)</button>
            </form>
        <?php elseif ($latest_version): ?>
            <div class="notice notice-success"><p>Le thème est déjà à jour.</p></div>
        <?php else: ?>
            <div class="notice notice-error"><p>Impossible de récupérer la dernière version.</p></div>
        <?php endif; ?>
        <?php
        if (isset($_POST['do_update']) && check_admin_referer('mediapilote_auto_update', 'mediapilote_auto_update_nonce')) {
            $zip_url = esc_url_raw($_POST['zip_url']);
            $result = mediapilote_perform_auto_update($zip_url);
            echo '<div style="margin-top:20px">' . $result . '</div>';
        }
        if (isset($_POST['test_update']) && check_admin_referer('mediapilote_auto_update', 'mediapilote_auto_update_nonce')) {
            $zip_url = esc_url_raw($_POST['zip_url']);
            $result = mediapilote_simulate_auto_update($zip_url);
            echo '<div style="margin-top:20px">' . $result . '</div>';
        }
        ?>
    </div>
    <?php
}

// Simulation de la mise à jour (aucune action sur le système de fichiers)
function mediapilote_simulate_auto_update($zip_url) {
    if (!class_exists('ZipArchive')) {
        return '<div class="notice notice-error"><p>Erreur : L’extension PHP ZipArchive n’est pas disponible.</p></div>';
    }
    $theme = wp_get_theme();
    $theme_slug = $theme->get_stylesheet();
    $theme_dir = get_theme_root($theme_slug) . '/' . $theme_slug;
    $backup_dir = $theme_dir . '-backup-' . date('Ymd-His');
    $tmp_zip = $theme_dir . '-update.zip';

    $steps = [];
    $steps[] = 'Téléchargement du zip depuis : <code>' . esc_html($zip_url) . '</code>';
    $steps[] = 'Le zip serait enregistré temporairement sous : <code>' . esc_html($tmp_zip) . '</code>';
    $steps[] = 'Le dossier du thème actuel serait sauvegardé sous : <code>' . esc_html($backup_dir) . '</code>';
    $steps[] = 'Un nouveau dossier <code>' . esc_html($theme_dir) . '</code> serait créé.';
    $steps[] = 'Décompression du zip dans le dossier du thème.';
    $steps[] = 'Suppression du zip temporaire.';
    $steps[] = 'En cas d’erreur, restauration du backup.';

    $html = '<div class="notice notice-info"><p><strong>Simulation de la mise à jour :</strong></p><ul>';
    foreach ($steps as $step) {
        $html .= '<li>' . $step . '</li>';
    }
    $html .= '</ul><p>Aucune modification réelle n’a été effectuée.</p></div>';
    return $html;
}

function mediapilote_perform_auto_update($zip_url) {
    if (!class_exists('ZipArchive')) {
        return '<div class="notice notice-error"><p>Erreur : L’extension PHP ZipArchive n’est pas disponible.</p></div>';
    }
    $theme = wp_get_theme();
    $theme_slug = $theme->get_stylesheet();
    $theme_dir = get_theme_root($theme_slug) . '/' . $theme_slug;
    $backup_dir = $theme_dir . '-backup-' . date('Ymd-His');
    $tmp_zip = $theme_dir . '-update.zip';

    // Télécharger le zip
    $response = wp_remote_get($zip_url, array('timeout' => 60));
    if (is_wp_error($response)) {
        return '<div class="notice notice-error"><p>Erreur lors du téléchargement du zip.</p></div>';
    }
    $zip_data = wp_remote_retrieve_body($response);
    if (!$zip_data) {
        return '<div class="notice notice-error"><p>Le zip téléchargé est vide.</p></div>';
    }
    file_put_contents($tmp_zip, $zip_data);

    // Sauvegarde du dossier actuel
    if (!rename($theme_dir, $backup_dir)) {
        @unlink($tmp_zip);
        return '<div class="notice notice-error"><p>Impossible de sauvegarder l’ancien dossier du thème.</p></div>';
    }
    // Zipper le backup puis supprimer le dossier
    $backup_zip = $backup_dir . '.zip';
    $zip_backup = new ZipArchive();
    if ($zip_backup->open($backup_zip, ZipArchive::CREATE) === TRUE) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($backup_dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($files as $file) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($backup_dir) + 1);
            if ($file->isDir()) {
                $zip_backup->addEmptyDir($relativePath);
            } else {
                $zip_backup->addFile($filePath, $relativePath);
            }
        }
        $zip_backup->close();
        // Supprimer le dossier backup après archivage
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($backup_dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($it as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($backup_dir);
    }
    mkdir($theme_dir);

    // Dézipper en évitant le sous-dossier
    $zip = new ZipArchive();
    if ($zip->open($tmp_zip) === TRUE) {
        // Chercher le préfixe commun (ex: mediapilote/)
        $firstEntry = $zip->getNameIndex(0);
        $prefix = '';
        if ($firstEntry && strpos($firstEntry, '/') !== false) {
            $prefix = explode('/', $firstEntry)[0] . '/';
        }
        // Extraire tous les fichiers sans le préfixe
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entry = $zip->getNameIndex($i);
            if (substr($entry, -1) === '/') continue; // ignorer les dossiers
            $relativePath = $prefix ? substr($entry, strlen($prefix)) : $entry;
            if ($relativePath === '' || strpos($relativePath, '../') !== false) continue;
            $targetPath = $theme_dir . '/' . $relativePath;
            $dir = dirname($targetPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            copy('zip://' . $tmp_zip . '#' . $entry, $targetPath);
        }
        $zip->close();
        @unlink($tmp_zip);
        return '<div class="notice notice-success"><p>Mise à jour réussie ! Ancienne version sauvegardée dans :<br><code>' . esc_html($backup_zip) . '</code></p></div>';
    } else {
        // Restauration en cas d’échec
        @unlink($tmp_zip);
        @rmdir($theme_dir);
        rename($backup_dir, $theme_dir);
        return '<div class="notice notice-error"><p>Erreur lors de la décompression du zip. Restauration de l’ancienne version.</p></div>';
    }
}

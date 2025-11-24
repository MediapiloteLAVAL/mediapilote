<?php
/**
 * Page d'administration : Vérification de la dernière version du thème Mediapilote
 */


add_action('admin_menu', 'mediapilote_add_update_page');
add_filter('parent_file', 'mediapilote_update_menu_bubble');

function mediapilote_update_menu_bubble($parent_file) {
    global $menu;
    $menu_slug = 'mediapilote-update';
    $bubble = '';
    // Vérifier si une mise à jour est dispo
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
        'timeout' => 5,
    );
    $response = wp_remote_get($repo_api_url, $args);
    $latest_version = false;
    if (!is_wp_error($response)) {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body);
        if (isset($data->tag_name)) {
            $latest_version = ltrim($data->tag_name, 'v');
        }
    }
    if ($latest_version && version_compare($current_version, $latest_version, '<')) {
        $bubble = ' <span class="update-plugins count-1" style="vertical-align:middle;"><span class="plugin-count" style="background:#d63638;color:#fff;">1</span></span>';
    }
    foreach ($menu as $k => $item) {
        if (isset($item[2]) && $item[2] === $menu_slug) {
            if ($bubble && strpos($menu[$k][0], 'update-plugins') === false) {
                $menu[$k][0] .= $bubble;
            }
        }
    }
    return $parent_file;
}

function mediapilote_add_update_page() {
    add_menu_page(
        'Mise à jour du thème',
        'Mise à jour',
        'manage_options',
        'mediapilote-update',
        'mediapilote_render_update_page',
        'dashicons-update',
        99
    );
}

function mediapilote_render_update_page() {
    // Bloc vérification version (reprend l'ancien affichage)
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
        'timeout' => 10,
    );
    $response = wp_remote_get($repo_api_url, $args);
    $latest_version = false;
    $release_url = false;
    if (!is_wp_error($response)) {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body);
        if (isset($data->tag_name)) {
            $latest_version = ltrim($data->tag_name, 'v');
            $release_url = $data->html_url;
        }
    }
    ?>
    <div class="wrap">
        <h1>Mise à jour du thème Mediapilote</h1>
        <h2>Vérification de la version</h2>
        <table class="form-table">
            <tr>
                <th>Version installée</th>
                <td><strong><?php echo esc_html($current_version); ?></strong></td>
            </tr>
            <tr>
                <th>Dernière version disponible</th>
                <td>
                    <?php if ($latest_version): ?>
                        <strong><?php echo esc_html($latest_version); ?></strong>
                        <a href="<?php echo esc_url($release_url); ?>" target="_blank" class="button">Voir la release</a>
                    <?php else: ?>
                        <span style="color:red">Impossible de récupérer la version distante.</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        <?php if ($latest_version && version_compare($current_version, $latest_version, '<')): ?>
            <div class="notice notice-warning" style="margin-top:20px;"><p>Une nouvelle version du thème est disponible !</p></div>
        <?php elseif ($latest_version): ?>
            <div class="notice notice-success" style="margin-top:20px;"><p>Votre thème est à jour.</p></div>
        <?php endif; ?>
        <hr style="margin:40px 0;">
        <?php
        // Inclure le bloc de mise à jour automatique (reprend le contenu de la page auto-update)
        if (function_exists('mediapilote_render_auto_update_page')) {
            mediapilote_render_auto_update_page();
        } else {
            echo '<div class="notice notice-error"><p>Le module de mise à jour automatique est manquant.</p></div>';
        }
        ?>
    </div>
    <?php
}

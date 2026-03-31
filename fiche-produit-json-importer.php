<?php
/*
Plugin Name: Fiche Produit JSON Importer
*/

add_action('admin_menu', function () {
    add_menu_page('Import JSON Produits', 'Import JSON Fiches Produits', 'manage_options', 'fp-json-import', 'fp_json_import_page');
});

function fp_json_import_page()
{
    ?>
    <div class="wrap">
        <h1>Importer fiches produits (JSON)</h1>

        <form method="post" enctype="multipart/form-data">
            <h3>Coller JSON</h3>
            <textarea name="json_data" rows="10" style="width:100%;"></textarea>

            <h3>OU importer fichier JSON</h3>
            <input type="file" name="json_file" accept=".json">

            <br><br>
            <button type="submit" name="import">Importer</button>
        </form>
    </div>
    <?php

    if (isset($_POST['import'])) {

        $json = '';

        if (!empty($_POST['json_data'])) {
            $json = stripslashes($_POST['json_data']);
        }

        if (!empty($_FILES['json_file']['tmp_name'])) {
            $json = file_get_contents($_FILES['json_file']['tmp_name']);
        }

        if (empty($json)) {
            echo "<p style='color:red;'>❌ Aucun JSON fourni</p>";
            return;
        }

        $data = json_decode($json, true);

        if (!$data) {
            echo "<p style='color:red;'>❌ JSON invalide</p>";
            return;
        }

        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        foreach ($data as $item) {

            $post_id = wp_insert_post([
                'post_title' => $item['post_title'],
                'post_type' => 'fiche_produit',
                'post_status' => 'publish'
            ]);

            if (!$post_id)
                continue;

            // Champs simples
            $fields = [
                'badge',
                'titre_principal',
                'prix_affiche',
                'image_alt',
                'lien_amazon',
                'lien_retour_guide',
                'avis_stella',
                'titre_revue_detail',
                'intro_detaillee',
                'mention_affiliation',
                'contenu_design',
                'contenu_rangement',
                'contenu_miroir',
                'contenu_connectique',
                'synthese_avis',
                'point_vigilance',
                'conclusion'
            ];

            foreach ($fields as $field) {
                if (isset($item[$field])) {
                    update_post_meta($post_id, $field, $item[$field]);
                }
            }

            // Repeatables simples
            $repeatables = [
                'avantages',
                'inconvenients',
                'points_forts',
                'contenu_colis',
                'avis_points',
                'public_cible'
            ];

            foreach ($repeatables as $field) {
                if (!empty($item[$field])) {
                    update_post_meta($post_id, $field, $item[$field]);
                }
            }

            // Caracteristiques (HTML auto)
            if (!empty($item['caracteristiques'])) {
                $formatted = [];

                foreach ($item['caracteristiques'] as $c) {
                    if (!empty($c['nom']) && !empty($c['valeur'])) {
                        $formatted[] = '<strong>' . esc_html($c['nom']) . ' :</strong> ' . esc_html($c['valeur']);
                    }
                }

                update_post_meta($post_id, 'caracteristiques', $formatted);
            }

            // Raisons choix (HTML auto)
            if (!empty($item['raisons_choix'])) {
                $formatted = [];

                foreach ($item['raisons_choix'] as $r) {
                    if (!empty($r['titre']) && !empty($r['description'])) {
                        $formatted[] = '<strong>' . esc_html($r['titre']) . ' :</strong> ' . esc_html($r['description']);
                    }
                }

                update_post_meta($post_id, 'raisons_choix', $formatted);
            }

            // IMAGE (featured)
            if (!empty($item['image'])) {
                $image_id = fp_import_image($item['image'], $post_id);
                if ($image_id) {
                    set_post_thumbnail($post_id, $image_id);
                }
            }

            // Taxonomies
            $taxonomies = ['marque', 'style', 'type_de_produit', 'gamme_de_prix'];

            foreach ($taxonomies as $tax) {
                if (!empty($item[$tax])) {
                    wp_set_object_terms($post_id, $item[$tax], $tax);
                }
            }
        }

        echo "<p style='color:green;'>✅ Import terminé</p>";
    }
}

// IMAGE FIX
function fp_import_image($url, $post_id)
{

    if (empty($url))
        return false;

    // Nettoyage URL
    $url = esc_url_raw($url);

    // Téléchargement
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        error_log('Erreur téléchargement image');
        return false;
    }

    $body = wp_remote_retrieve_body($response);

    if (empty($body)) {
        error_log('Image vide');
        return false;
    }

    // Nom fichier propre
    $filename = basename(parse_url($url, PHP_URL_PATH));
    if (!$filename) {
        $filename = 'image-' . time() . '.jpg';
    }

    // Upload
    $upload = wp_upload_bits($filename, null, $body);

    if ($upload['error']) {
        error_log('Erreur upload');
        return false;
    }

    // Création attachment
    $wp_filetype = wp_check_filetype($upload['file']);

    $attachment = [
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_status' => 'inherit'
    ];

    $attach_id = wp_insert_attachment($attachment, $upload['file'], $post_id);

    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
    wp_update_attachment_metadata($attach_id, $attach_data);

    return $attach_id;
}

//////////////////////////////////////////////////
// SHORTCODES ELEMENTOR
//////////////////////////////////////////////////

// CARACTERISTIQUES
add_shortcode('fiche_caracteristiques', function () {

    global $post;
    if (!$post)
        return '';

    $items = get_post_meta($post->ID, 'caracteristiques', true);
    if (empty($items))
        return '';

    ob_start();
    ?>

    <ul class="fp-list">
        <?php foreach ($items as $item): ?>
            <li>✔ <?php echo wp_kses_post($item); ?></li>
        <?php endforeach; ?>
    </ul>

    <?php
    return ob_get_clean();
});

// RAISONS
add_shortcode('fiche_raisons', function () {

    global $post;
    if (!$post)
        return '';

    $items = get_post_meta($post->ID, 'raisons_choix', true);
    if (empty($items))
        return '';

    ob_start();
    ?>

    <ul class="fp-list">
        <?php foreach ($items as $item): ?>
            <li>⭐ <?php echo wp_kses_post($item); ?></li>
        <?php endforeach; ?>
    </ul>

    <?php
    return ob_get_clean();
});
add_action('wp_head', function () {
    ?>
    <style>
        .fp-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .fp-list li {
            padding: 10px 12px;
            margin-bottom: 6px;
            background: #fafafa;
            border-radius: 8px;
            font-size: 14px;
        }

        .fp-list strong {
            font-weight: 600;
        }
    </style>
    <?php
});
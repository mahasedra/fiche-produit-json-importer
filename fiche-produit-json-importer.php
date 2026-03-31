<?php
/*
Plugin Name: Fiche Produit JSON Importer
*/

add_action('admin_menu', function () {
    add_menu_page('Import JSON Produits', 'Import JSON Fiches Produits', 'manage_options', 'fp-json-import', 'fp_json_import_page');
});

function fp_json_import_page() {
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

        // 1. Si textarea
        if (!empty($_POST['json_data'])) {
            $json = stripslashes($_POST['json_data']);
        }

        // 2. Si fichier upload
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

            if (!$post_id) continue;

            // Champs simples
            $fields = [
                'badge','titre_principal','prix_affiche','image_alt',
                'lien_amazon','lien_retour_guide','avis_stella',
                'titre_revue_detail','intro_detaillee','mention_affiliation',
                'contenu_design','contenu_rangement','contenu_miroir',
                'contenu_connectique','synthese_avis','point_vigilance','conclusion'
            ];

            foreach ($fields as $field) {
                if (isset($item[$field])) {
                    update_post_meta($post_id, $field, $item[$field]);
                }
            }

            // Repeatables simples
            $repeatables = [
                'avantages','inconvenients','points_forts',
                'contenu_colis','avis_points','public_cible'
            ];

            foreach ($repeatables as $field) {
                if (!empty($item[$field])) {
                    update_post_meta($post_id, $field, $item[$field]);
                }
            }

            // Caracteristiques (transform object -> string)
            if (!empty($item['caracteristiques'])) {
                $formatted = [];

                foreach ($item['caracteristiques'] as $c) {
                    if (!empty($c['nom']) && !empty($c['valeur'])) {
                        $formatted[] = '<strong>' . esc_html($c['nom']) . ' :</strong> ' . esc_html($c['valeur']);
                    }
                }

                update_post_meta($post_id, 'caracteristiques', $formatted);
            }

            // Raisons choix (transform object -> string)
            if (!empty($item['raisons_choix'])) {
                $formatted = [];

                foreach ($item['raisons_choix'] as $r) {
                    if (!empty($r['titre']) && !empty($r['description'])) {
                        $formatted[] = '<strong>' . esc_html($r['titre']) . ' :</strong> ' . esc_html($r['description']);
                    }
                }

                update_post_meta($post_id, 'raisons_choix', $formatted);
            }

            // IMAGE (featured image FIX)
            if (!empty($item['image'])) {
                $image_id = fp_import_image($item['image'], $post_id);
                if ($image_id) {
                    set_post_thumbnail($post_id, $image_id);
                }
            }

            // Taxonomies
            $taxonomies = ['marque','style','type_de_produit','gamme_de_prix'];

            foreach ($taxonomies as $tax) {
                if (!empty($item[$tax])) {
                    wp_set_object_terms($post_id, $item[$tax], $tax);
                }
            }
        }

        echo "<p style='color:green;'>✅ Import terminé</p>";
    }
}

// Fonction image FIABLE
function fp_import_image($url, $post_id) {

    $tmp = download_url($url);

    if (is_wp_error($tmp)) return false;

    $file_array = [
        'name' => basename($url),
        'tmp_name' => $tmp
    ];

    $id = media_handle_sideload($file_array, $post_id);

    if (is_wp_error($id)) {
        @unlink($tmp);
        return false;
    }

    return $id;
}
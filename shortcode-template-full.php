<?php

add_shortcode('fiche_produit_full', function () {

    global $post;
    if (!$post)
        return '';

    // META
    $badge = get_post_meta($post->ID, 'badge', true);
    $titre = get_post_meta($post->ID, 'titre_principal', true);
    $prix = get_post_meta($post->ID, 'prix_affiche', true);
    $avis = get_post_meta($post->ID, 'avis_stella', true);

    $avantages = get_post_meta($post->ID, 'avantages', true);
    $inconvenients = get_post_meta($post->ID, 'inconvenients', true);
    $points_forts = get_post_meta($post->ID, 'points_forts', true);

    $caracteristiques = get_post_meta($post->ID, 'caracteristiques', true);
    $contenu_colis = get_post_meta($post->ID, 'contenu_colis', true);

    $conclusion = get_post_meta($post->ID, 'conclusion', true);
    $lien_amazon = get_post_meta($post->ID, 'lien_amazon', true);
    $lien_retour = get_post_meta($post->ID, 'lien_retour_guide', true);

    $image = get_the_post_thumbnail_url($post->ID, 'large');

    ob_start();
    ?>
    <style>
        .fp-full {
            max-width: 1100px;
            margin: auto;
            padding: 40px 20px;
        }

        .fp-hero {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .fp-img {
            width: 100%;
            border-radius: 20px;
        }

        .fp-badge {
            background: #eee;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: bold;
        }

        .fp-price {
            font-size: 22px;
            color: #ff6b00;
            font-weight: bold;
        }

        .fp-avis {
            background: #fafafa;
            padding: 20px;
            border-radius: 12px;
            margin-top: 10px;
        }

        .fp-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 40px;
        }

        .fp-box {
            padding: 20px;
            border-radius: 12px;
        }

        .success {
            background: #eafaf1;
        }

        .danger {
            background: #fdecea;
        }

        .fp-cta {
            margin: 30px 0;
            display: flex;
            gap: 10px;
        }

        .fp-cta a {
            padding: 12px 20px;
            border-radius: 30px;
            background: #000;
            color: #fff;
            text-decoration: none;
        }

        .fp-grid-small {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .fp-card {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 10px;
        }

        .fp-table {
            margin-top: 30px;
        }

        .fp-row {
            border-bottom: 1px solid #eee;
            padding: 8px 0;
        }

        .fp-conclusion {
            margin-top: 30px;
            background: #fafafa;
            padding: 20px;
            border-radius: 12px;
        }
    </style>
    <div class="fp-full">

        <!-- HERO -->
        <div class="fp-hero">

            <div>
                <img src="<?php echo esc_url($image); ?>" class="fp-img">
            </div>

            <div>
                <span class="fp-badge"><?php echo esc_html($badge); ?></span>
                <h1><?php echo esc_html($titre); ?></h1>
                <div class="fp-price"><?php echo esc_html($prix); ?></div>

                <div class="fp-avis">
                    <?php echo wp_kses_post($avis); ?>
                </div>
            </div>

        </div>

        <!-- AVANTAGES / INCONVENIENTS -->
        <div class="fp-grid-2">

            <div class="fp-box success">
                <h3>✅ Avantages</h3>
                <ul>
                    <?php foreach ((array) $avantages as $item): ?>
                        <li><?php echo esc_html($item); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="fp-box danger">
                <h3>❌ Inconvénients</h3>
                <ul>
                    <?php foreach ((array) $inconvenients as $item): ?>
                        <li><?php echo esc_html($item); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

        </div>

        <!-- CTA -->
        <div class="fp-cta">
            <a href="<?php echo esc_url($lien_amazon); ?>" target="_blank">Voir sur Amazon</a>
            <a href="<?php echo esc_url($lien_retour); ?>">Retour au guide</a>
        </div>

        <!-- POINTS FORTS -->
        <div class="fp-grid-small">
            <?php foreach ((array) $points_forts as $item): ?>
                <div class="fp-card"><?php echo esc_html($item); ?></div>
            <?php endforeach; ?>
        </div>

        <!-- CARACTERISTIQUES -->
        <div class="fp-table">
            <?php foreach ((array) $caracteristiques as $item): ?>
                <div class="fp-row"><?php echo wp_kses_post($item); ?></div>
            <?php endforeach; ?>
        </div>

        <!-- CONTENU COLIS -->
        <div class="fp-list">
            <?php foreach ((array) $contenu_colis as $item): ?>
                <div>✔ <?php echo esc_html($item); ?></div>
            <?php endforeach; ?>
        </div>

        <!-- CONCLUSION -->
        <div class="fp-conclusion">
            <?php echo wp_kses_post($conclusion); ?>
        </div>

    </div>

    <?php
    return ob_get_clean();

});
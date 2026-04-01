<?php

add_shortcode('fiche_produit_full', function () {

    global $post;
    if (!$post)
        return '';

    // ===== HERO SECTION =====
    $badge = get_post_meta($post->ID, 'badge', true);
    $titre = get_post_meta($post->ID, 'titre_principal', true);
    $prix = get_post_meta($post->ID, 'prix_affiche', true);
    $image = get_the_post_thumbnail_url($post->ID, 'large');
    $lien_retour = get_post_meta($post->ID, 'lien_retour_guide', true);

    // ===== STELLA REVIEW =====
    $titre_stella = get_post_meta($post->ID, 'titre_stella', true) ?: '💕 L\'avis de Stella (et de ses parents)';
    $avis_stella = get_post_meta($post->ID, 'avis_stella', true);

    // ===== PROS / CONS =====
    $avantages = (array) get_post_meta($post->ID, 'avantages', true);
    $inconvenients = (array) get_post_meta($post->ID, 'inconvenients', true);

    // ===== DETAILED REVIEW & AFFILIATION =====
    $titre_revue_detail = get_post_meta($post->ID, 'titre_revue_detail', true) ?: '🔍 Revue détaillée';
    $intro_detaillee = get_post_meta($post->ID, 'intro_detaillee', true);
    $mention_affiliation = get_post_meta($post->ID, 'mention_affiliation', true) ?: '⚠️ Cette page peut contenir des liens d\'affiliation. Nous pouvons percevoir une commission sans frais supplémentaires pour vous.';

    // ===== STRONG POINTS =====
    $titre_points_forts = get_post_meta($post->ID, 'titre_points_forts', true) ?: '✨ Les points forts';
    $points_forts = (array) get_post_meta($post->ID, 'points_forts', true);

    // ===== CONTENT SECTIONS =====
    $contenu_design = get_post_meta($post->ID, 'contenu_design', true);
    $contenu_rangement = get_post_meta($post->ID, 'contenu_rangement', true);
    $contenu_miroir = get_post_meta($post->ID, 'contenu_miroir', true);
    $contenu_connectique = get_post_meta($post->ID, 'contenu_connectique', true);

    // ===== WHY CHOOSE =====
    $titre_pourquoi = get_post_meta($post->ID, 'titre_pourquoi_choisir', true) ?: 'Pourquoi nous l\'avons choisi ?';
    $raisons_choix = (array) get_post_meta($post->ID, 'raisons_choix', true);

    // ===== REVIEW SUMMARY =====
    $synthese_avis = get_post_meta($post->ID, 'synthese_avis', true);

    // ===== CAUTION POINT (SINGULAR) =====
    $point_vigilance = get_post_meta($post->ID, 'point_vigilance', true);

    // ===== SPECS & PACKAGE =====
    $titre_caracteristiques = get_post_meta($post->ID, 'titre_caracteristiques', true) ?: '📋 Caractéristiques principales';
    $caracteristiques = (array) get_post_meta($post->ID, 'caracteristiques', true);
    
    $titre_contenu_colis = get_post_meta($post->ID, 'titre_contenu_colis', true) ?: '📦 Contenu du colis';
    $contenu_colis = (array) get_post_meta($post->ID, 'contenu_colis', true);

    // ===== REVIEW POINTS =====
    $avis_points = (array) get_post_meta($post->ID, 'avis_points', true);

    // ===== PUBLIC CIBLE =====
    $public_cible = (array) get_post_meta($post->ID, 'public_cible', true);

    // ===== CONCLUSION =====
    $titre_conclusion = get_post_meta($post->ID, 'titre_conclusion', true) ?: '🎯 Notre Verdict';
    $conclusion = get_post_meta($post->ID, 'conclusion', true);

    // ===== CTA =====
    $lien_amazon = get_post_meta($post->ID, 'lien_amazon', true);


    ob_start();
    ?>
    <style>
        /* ==================== BASE ==================== */
        .fp {
            --primary: #1a1a1a;
            --accent: #ff6b00;
            --success: #38a169;
            --danger: #e53e3e;
            --warning: #ed8936;
            
            --bg-primary: #ffffff;
            --bg-secondary: #f7f7f7;
            --bg-surface: #fafafa;
            --bg-success-light: rgba(56, 161, 105, 0.08);
            --bg-danger-light: rgba(229, 62, 62, 0.08);
            --bg-accent-light: rgba(255, 107, 0, 0.05);
            
            --text-primary: #1a1a1a;
            --text-muted: #666666;
            --text-success: #22543d;
            --text-danger: #742a2a;
            
            --border-light: #eeeeee;
            --border-medium: #dddddd;
            
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: var(--text-primary);
        }

        .fp * {
            box-sizing: border-box;
        }

        /* ==================== TYPOGRAPHY ==================== */
        .fp h1 {
            font-size: 2.5rem;
            font-weight: 900;
            line-height: 1.2;
            margin: 0 0 1.5rem 0;
        }

        .fp h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 2.5rem 0 1.25rem 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .fp h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0 0 0.75rem 0;
        }

        .fp h4 {
            font-size: 1rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
        }

        .fp p {
            line-height: 1.6;
            color: var(--text-muted);
            margin: 1rem 0;
        }

        .fp ul, .fp ol {
            list-style: none;
            padding: 0;
            margin: 1rem 0;
        }

        .fp li {
            margin: 0.75rem 0;
            padding-left: 1.75rem;
            position: relative;
            line-height: 1.6;
            color: var(--text-muted);
        }

        /* ==================== BREADCRUMB ==================== */
        .fp-breadcrumb {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .fp-breadcrumb:hover {
            background: var(--bg-surface);
            color: var(--accent);
        }

        /* ==================== HERO SECTION ==================== */
        .fp-hero {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
            align-items: start;
        }

        .fp-hero-image {
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: block;
            object-fit: cover;
        }

        .fp-hero-content {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .fp-badge {
            background: var(--bg-surface);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 700;
            display: inline-block;
            width: fit-content;
            font-size: 0.85rem;
            color: var(--accent);
            border: 1px solid var(--accent);
        }

        .fp-price {
            font-size: 2rem;
            color: var(--accent);
            font-weight: 900;
            line-height: 1;
        }

        /* ==================== STELLA REVIEW BOX ==================== */
        .fp-stella-review {
            background: var(--bg-accent-light);
            border: 2px solid var(--accent);
            color: var(--text-primary);
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(255, 107, 0, 0.08);
        }

        .fp-stella-review h3 {
            color: var(--accent);
            margin-top: 0;
            font-size: 1.1rem;
        }

        .fp-stella-review p {
            color: var(--text-muted);
            margin: 0.75rem 0;
            font-size: 0.95rem;
        }

        .fp-stella-review p:first-of-type {
            margin-top: 0;
        }

        /* ==================== PROS / CONS GRID ==================== */
        .fp-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin: 3rem 0;
        }

        .fp-box {
            padding: 2rem;
            border-radius: 16px;
            border: 1px solid transparent;
        }

        .fp-box h3 {
            margin-top: 0;
            margin-bottom: 1rem;
        }

        .fp-box-success {
            background: var(--bg-success-light);
            border-color: var(--success);
        }

        .fp-box-success h3 {
            color: var(--success);
        }

        .fp-box-success li {
            color: var(--text-primary);
        }

        .fp-box-success li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: var(--success);
            font-weight: bold;
            font-size: 1.1em;
        }

        .fp-box-danger {
            background: var(--bg-danger-light);
            border-color: var(--danger);
        }

        .fp-box-danger h3 {
            color: var(--danger);
        }

        .fp-box-danger li {
            color: var(--text-primary);
        }

        .fp-box-danger li:before {
            content: "✕";
            position: absolute;
            left: 0;
            color: var(--danger);
            font-weight: bold;
            font-size: 1.1em;
        }

        /* ==================== AFFILIATION MENTION ==================== */
        .fp-affiliation {
            background: rgba(237, 137, 54, 0.08);
            border-left: 4px solid var(--warning);
            padding: 1rem 1.5rem;
            border-radius: 8px;
            font-size: 0.9rem;
            color: var(--text-muted);
            margin: 2rem 0;
        }

        /* ==================== CTA BUTTONS ==================== */
        .fp-cta {
            display: flex;
            gap: 1.25rem;
            flex-wrap: wrap;
            margin: 2.5rem 0;
        }

        .fp-cta-centered {
            justify-content: center;
            margin-top: 3rem;
            padding-top: 2rem;
        }

        .fp-btn {
            padding: 1rem 2rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-block;
            cursor: pointer;
            border: none;
        }

        .fp-btn-primary {
            background: var(--primary);
            color: white;
        }

        .fp-btn-primary:hover {
            background: var(--accent);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 0, 0.2);
        }

        .fp-btn-secondary {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .fp-btn-secondary:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: var(--bg-surface);
        }

        /* ==================== GRID SMALL (STRONG POINTS) ==================== */
        .fp-grid-small {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .fp-card {
            background: var(--bg-surface);
            padding: 1.25rem;
            border-radius: 12px;
            font-weight: 500;
            text-align: center;
            border: 1px solid var(--border-light);
            color: var(--text-primary);
            transition: all 0.3s;
        }

        .fp-card:hover {
            box-shadow: 0 4px 12px rgba(255, 107, 0, 0.1);
            transform: translateY(-2px);
            border-color: var(--accent);
        }

        /* ==================== POSITIVE/CAUTION POINTS ==================== */
        .fp-points-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .fp-point {
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border-light);
        }

        .fp-point-positive {
            background: var(--bg-success-light);
            border-color: var(--success);
        }

        .fp-point-positive strong {
            color: var(--success);
        }

        .fp-point-caution {
            background: rgba(237, 137, 54, 0.08);
            border-color: var(--warning);
        }

        .fp-point-caution strong {
            color: var(--warning);
        }

        .fp-point p {
            margin: 0.75rem 0 0 0;
            font-size: 0.9rem;
        }

        /* ==================== WHY CHOOSE (REASONS) ==================== */
        .fp-reasons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .fp-reason {
            background: var(--bg-surface);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border-light);
        }

        .fp-reason-number {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--accent);
            opacity: 0.2;
            margin-bottom: 0.5rem;
        }

        .fp-reason h4 {
            color: var(--text-primary);
            margin: 0.5rem 0;
        }

        .fp-reason p {
            font-size: 0.9rem;
            margin: 0;
        }

        /* ==================== SPECS TABLE ==================== */
        .fp-specs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin: 2rem 0;
        }

        .fp-specs-box {
            background: var(--bg-surface);
            padding: 2rem;
            border-radius: 16px;
            border: 1px solid var(--border-light);
        }

        .fp-specs-box h4 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            margin-top: 0;
            color: var(--text-primary);
        }

        .fp-spec-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 0.75rem;
            margin-bottom: 0.75rem;
            border-bottom: 1px solid var(--border-light);
            font-size: 0.95rem;
        }

        .fp-spec-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .fp-spec-label {
            color: var(--text-muted);
            font-weight: 500;
        }

        .fp-spec-value {
            font-weight: 600;
            color: var(--text-primary);
            text-align: right;
        }

        .fp-specs-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .fp-specs-box li {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin: 0.75rem 0;
            padding: 0;
            padding-left: 0;
        }

        .fp-specs-box li:before {
            content: "✓";
            font-weight: bold;
            color: var(--success);
            flex-shrink: 0;
            font-size: 1.1em;
        }

        /* ==================== SECTION INTRO ==================== */
        .fp-section-intro {
            background: var(--bg-accent-light);
            border-left: 4px solid var(--accent);
            padding: 1.5rem;
            border-radius: 8px;
            margin: 2rem 0;
        }

        .fp-section-intro p {
            margin: 0;
            font-size: 1rem;
        }

        /* ==================== CONCLUSION ==================== */
        .fp-conclusion {
            margin: 3rem 0;
            background: var(--accent);
            color: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(255, 107, 0, 0.15);
        }

        .fp-conclusion h2 {
            color: white;
            margin-top: 0;
        }

        .fp-conclusion p {
            color: rgba(255,255,255,0.95);
        }

        .fp-conclusion a {
            color: white;
            font-weight: 600;
            text-decoration: underline;
        }

        /* ==================== TAGS/PILLS ==================== */
        .fp-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin: 1rem 0;
        }

        .fp-tag {
            background: var(--bg-success-light);
            color: var(--success);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            border: 1px solid var(--success);
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 1024px) {
            .fp-hero {
                gap: 2rem;
            }

            .fp h1 {
                font-size: 2rem;
            }

            .fp h2 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .fp {
                padding: 20px;
            }

            .fp h1 {
                font-size: 1.75rem;
            }

            .fp h2 {
                font-size: 1.25rem;
            }

            .fp-hero {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .fp-grid-2 {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .fp-specs {
                grid-template-columns: 1fr;
            }

            .fp-cta {
                flex-direction: column;
            }

            .fp-btn {
                width: 100%;
                text-align: center;
            }

            .fp-grid-small {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }

            .fp-reasons {
                grid-template-columns: 1fr;
            }

            .fp-points-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="fp">

        <!-- BREADCRUMB -->
        <a href="<?php echo esc_url($lien_retour); ?>" class="fp-breadcrumb">
            ← Retour au guide
        </a>

        <!-- HERO SECTION -->
        <div class="fp-hero">
            <div>
                <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($titre); ?>" class="fp-hero-image">
            </div>

            <div class="fp-hero-content">
                <?php if ($badge): ?>
                    <span class="fp-badge"><?php echo esc_html($badge); ?></span>
                <?php endif; ?>

                <h1><?php echo esc_html($titre); ?></h1>

                <?php if ($prix): ?>
                    <div class="fp-price"><?php echo esc_html($prix); ?></div>
                <?php endif; ?>

                <!-- STELLA REVIEW BOX -->
                <?php if ($avis_stella): ?>
                    <div class="fp-stella-review">
                        <h3><?php echo esc_html($titre_stella); ?></h3>
                        <?php echo wp_kses_post($avis_stella); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- PROS / CONS SECTION -->
        <div class="fp-grid-2">
            <?php if (!empty($avantages)): ?>
                <div class="fp-box fp-box-success">
                    <h3>✅ Avantages</h3>
                    <ul>
                        <?php foreach ($avantages as $item): ?>
                            <li><?php echo esc_html($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($inconvenients)): ?>
                <div class="fp-box fp-box-danger">
                    <h3>❌ Inconvénients</h3>
                    <ul>
                        <?php foreach ($inconvenients as $item): ?>
                            <li><?php echo esc_html($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

        <!-- DETAILED REVIEW SECTION -->
        <?php if ($titre_revue_detail || $intro_detaillee): ?>
            <div>
                <?php if ($titre_revue_detail): ?>
                    <h2><?php echo esc_html($titre_revue_detail); ?></h2>
                <?php endif; ?>
                
                <?php if ($intro_detaillee): ?>
                    <p><?php echo wp_kses_post($intro_detaillee); ?></p>
                <?php endif; ?>

                <?php if ($mention_affiliation): ?>
                    <div class="fp-affiliation">
                        <?php echo wp_kses_post($mention_affiliation); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- CTA BUTTONS (TOP) -->
        <div class="fp-cta">
            <a href="<?php echo esc_url($lien_amazon); ?>" target="_blank" rel="noopener noreferrer nofollow" class="fp-btn fp-btn-primary">
                🛒 Voir sur Amazon
            </a>
            <a href="<?php echo esc_url($lien_retour); ?>" class="fp-btn fp-btn-secondary">
                ← Retour au guide
            </a>
        </div>

        <!-- STRONG POINTS SECTION -->
        <?php if (!empty($points_forts)): ?>
            <div>
                <h2><?php echo esc_html($titre_points_forts); ?></h2>
                <div class="fp-grid-small">
                    <?php foreach ($points_forts as $item): ?>
                        <div class="fp-card"><?php echo esc_html($item); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- CONTENT: DESIGN -->
        <?php if ($contenu_design): ?>
            <div>
                <h2>🎨 Design & Esthétique</h2>
                <?php echo wp_kses_post($contenu_design); ?>
            </div>
        <?php endif; ?>

        <!-- CONTENT: RANGEMENT -->
        <?php if ($contenu_rangement): ?>
            <div>
                <h2>📦 Rangement & Organisation</h2>
                <?php echo wp_kses_post($contenu_rangement); ?>
            </div>
        <?php endif; ?>

        <!-- CONTENT: MIROIR -->
        <?php if ($contenu_miroir): ?>
            <div>
                <h2>💡 Miroir & Éclairage</h2>
                <?php echo wp_kses_post($contenu_miroir); ?>
            </div>
        <?php endif; ?>

        <!-- CONTENT: CONNECTIQUE -->
        <?php if ($contenu_connectique): ?>
            <div>
                <h2>🔌 Connectique & Fonctionnalités</h2>
                <?php echo wp_kses_post($contenu_connectique); ?>
            </div>
        <?php endif; ?>

        <!-- WHY CHOOSE SECTION -->
        <?php if (!empty($raisons_choix)): ?>
            <div>
                <h2><?php echo esc_html($titre_pourquoi); ?></h2>
                <div class="fp-reasons">
                    <?php foreach ($raisons_choix as $idx => $raison): 
                        $titre_raison = is_array($raison) ? ($raison['titre'] ?? '') : '';
                        $desc_raison = is_array($raison) ? ($raison['description'] ?? '') : $raison;
                    ?>
                        <div class="fp-reason">
                            <div class="fp-reason-number">0<?php echo $idx + 1; ?></div>
                            <h4><?php echo esc_html($titre_raison); ?></h4>
                            <p><?php echo esc_html($desc_raison); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- REVIEW SUMMARY -->
        <?php if ($synthese_avis): ?>
            <div class="fp-section-intro">
                <p><?php echo wp_kses_post($synthese_avis); ?></p>
            </div>
        <?php endif; ?>

        <!-- CAUTION POINT (SINGULAR) -->
        <?php if ($point_vigilance): ?>
            <div class="fp-point fp-point-caution" style="margin: 2rem 0;">
                <strong>⚠️ Point de vigilance</strong>
                <p><?php echo esc_html($point_vigilance); ?></p>
            </div>
        <?php endif; ?>

        <!-- SPECS & PACKAGE SECTION -->
        <?php if (!empty($caracteristiques) || !empty($contenu_colis)): ?>
            <div class="fp-specs">
                <?php if (!empty($caracteristiques)): ?>
                    <div class="fp-specs-box">
                        <h4><?php echo esc_html($titre_caracteristiques); ?></h4>
                        <div>
                            <?php foreach ($caracteristiques as $item): ?>
                                <div class="fp-spec-row">
                                    <?php if (is_array($item)): ?>
                                        <span class="fp-spec-label"><?php echo esc_html($item['nom'] ?? ''); ?></span>
                                        <span class="fp-spec-value"><?php echo esc_html($item['valeur'] ?? ''); ?></span>
                                    <?php else: ?>
                                        <?php echo wp_kses_post($item); ?>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($contenu_colis)): ?>
                    <div class="fp-specs-box">
                        <h4><?php echo esc_html($titre_contenu_colis); ?></h4>
                        <ul>
                            <?php foreach ($contenu_colis as $item): ?>
                                <li><?php echo esc_html($item); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- REVIEW POINTS TAGS -->
        <?php if (!empty($avis_points)): ?>
            <div>
                <h2>💬 Points clés du produit</h2>
                <div class="fp-tags">
                    <?php foreach ($avis_points as $tag): ?>
                        <span class="fp-tag"><?php echo esc_html($tag); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- PUBLIC CIBLE SECTION -->
        <?php if (!empty($public_cible)): ?>
            <div>
                <h2>👥 Public concerné</h2>
                <ul>
                    <?php foreach ($public_cible as $item): ?>
                        <li><?php echo esc_html($item); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- CONCLUSION BOX -->
        <?php if ($conclusion): ?>
            <div class="fp-conclusion">
                <h2><?php echo esc_html($titre_conclusion); ?></h2>
                <?php echo wp_kses_post($conclusion); ?>
            </div>
        <?php endif; ?>

        <!-- BOTTOM CTA -->
        <div class="fp-cta fp-cta-centered">
            <a href="<?php echo esc_url($lien_amazon); ?>" target="_blank" rel="noopener noreferrer nofollow" class="fp-btn fp-btn-primary">
                🛒 Voir sur Amazon
            </a>
            <a href="<?php echo esc_url($lien_retour); ?>" class="fp-btn fp-btn-secondary">
                ← Retour au guide
            </a>
        </div>

    </div>

    <?php
    return ob_get_clean();

});
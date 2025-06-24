<?php
/**
 * Main template file - Homepage
 */

get_header(); ?>

<!-- Hero Section -->
<section class="hero" role="banner">
    <?php 
    $hero_video = get_theme_mod('hero_video_url');
    $hero_image = get_theme_mod('hero_background_image');
    ?>
    
    <?php if ($hero_video) : ?>
        <div class="hero-background">
            <video autoplay muted loop playsinline>
                <source src="<?php echo esc_url($hero_video); ?>" type="video/mp4">
            </video>
        </div>
    <?php elseif ($hero_image) : ?>
        <div class="hero-background" style="background-image: url('<?php echo esc_url($hero_image); ?>');"></div>
    <?php else : ?>
        <div class="hero-background" style="background-image: url('<?php echo esc_url(get_template_directory_uri() . '/assets/images/hero-default.jpg'); ?>');"></div>
    <?php endif; ?>

    <div class="hero-content">
        <h1><?php echo get_theme_mod('hero_title', get_bloginfo('name')); ?></h1>
        <p class="hero-subtitle"><?php echo get_theme_mod('hero_subtitle', get_bloginfo('description')); ?></p>
        
        <?php 
        $rotating_texts = get_theme_mod('hero_rotating_texts', 'Filmmaker|Artist|Visionary');
        if ($rotating_texts) :
            $texts = explode('|', $rotating_texts);
            if (count($texts) > 1) : ?>
                <div class="rotating-text" aria-live="polite">
                    <?php foreach ($texts as $index => $text) : ?>
                        <span <?php echo $index === 0 ? 'style="opacity: 1;"' : ''; ?>>
                            <?php echo esc_html(trim($text)); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif;
        endif; ?>
    </div>
</section>

<!-- Featured Films Section -->
<section class="section section-white">
    <div class="container">
        <div class="section-header" style="text-align: center; margin-bottom: var(--space-60);">
            <h2><?php esc_html_e('Featured Films', 'saab'); ?></h2>
            <p><?php esc_html_e('Discover the most significant works from Jocelyne Saab\'s cinematic journey', 'saab'); ?></p>
        </div>

        <div class="films-grid">
            <?php
            $featured_films = new WP_Query(array(
                'post_type' => 'film',
                'posts_per_page' => 6,
                'meta_key' => '_saab_featured',
                'meta_value' => '1',
                'orderby' => 'date',
                'order' => 'DESC',
            ));

            if ($featured_films->have_posts()) :
                while ($featured_films->have_posts()) : $featured_films->the_post();
                    get_template_part('template-parts/film-card');
                endwhile;
                wp_reset_postdata();
            else :
                // Fallback to latest films if no featured films
                $latest_films = new WP_Query(array(
                    'post_type' => 'film',
                    'posts_per_page' => 6,
                    'orderby' => 'date',
                    'order' => 'DESC',
                ));
                
                if ($latest_films->have_posts()) :
                    while ($latest_films->have_posts()) : $latest_films->the_post();
                        get_template_part('template-parts/film-card');
                    endwhile;
                    wp_reset_postdata();
                endif;
            endif;
            ?>
        </div>

        <div style="text-align: center; margin-top: var(--space-50);">
            <a href="<?php echo esc_url(get_post_type_archive_link('film')); ?>" class="btn btn-large">
                <?php esc_html_e('View All Films', 'saab'); ?>
            </a>
        </div>
    </div>
</section>

<!-- Latest News Section -->
<section class="section section-cream">
    <div class="container">
        <div class="section-header" style="text-align: center; margin-bottom: var(--space-60);">
            <h2><?php esc_html_e('Latest News', 'saab'); ?></h2>
            <p><?php esc_html_e('Stay updated with exhibitions, screenings, and announcements', 'saab'); ?></p>
        </div>

        <div class="news-grid">
            <?php
            $latest_news = new WP_Query(array(
                'post_type' => 'news',
                'posts_per_page' => 3,
                'orderby' => 'date',
                'order' => 'DESC',
            ));

            if ($latest_news->have_posts()) :
                while ($latest_news->have_posts()) : $latest_news->the_post(); ?>
                    <article class="news-card">
                        <div class="news-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('news-featured'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="news-content">
                            <div class="news-meta">
                                <time datetime="<?php echo get_the_date('c'); ?>">
                                    <?php echo get_the_date(); ?>
                                </time>
                                <?php 
                                $news_categories = get_the_terms(get_the_ID(), 'news_category');
                                if ($news_categories && !is_wp_error($news_categories)) :
                                    echo ' • ' . esc_html($news_categories[0]->name);
                                endif;
                                ?>
                            </div>
                            <h3>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <div class="news-excerpt">
                                <?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="btn btn-small">
                                <?php esc_html_e('Read More', 'saab'); ?>
                            </a>
                        </div>
                    </article>
                <?php endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>

        <div style="text-align: center; margin-top: var(--space-50);">
            <a href="<?php echo esc_url(get_post_type_archive_link('news')); ?>" class="btn">
                <?php esc_html_e('View All News', 'saab'); ?>
            </a>
        </div>
    </div>
</section>

<!-- Partners Section -->
<section class="section section-white">
    <div class="container">
        <div class="section-header" style="text-align: center; margin-bottom: var(--space-60);">
            <h2><?php esc_html_e('Partners & Collaborators', 'saab'); ?></h2>
        </div>

        <div class="partner-grid">
            <?php
            $partners = new WP_Query(array(
                'post_type' => 'partner',
                'posts_per_page' => 8,
                'orderby' => 'menu_order',
                'order' => 'ASC',
            ));

            if ($partners->have_posts()) :
                while ($partners->have_posts()) : $partners->the_post(); ?>
                    <div class="partner-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php 
                            $partner_website = get_post_meta(get_the_ID(), '_saab_partner_website', true);
                            if ($partner_website) : ?>
                                <a href="<?php echo esc_url($partner_website); ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   aria-label="<?php echo esc_attr(sprintf(__('Visit %s website', 'saab'), get_the_title())); ?>">
                                    <?php the_post_thumbnail('partner-logo', array('class' => 'partner-logo')); ?>
                                </a>
                            <?php else : ?>
                                <?php the_post_thumbnail('partner-logo', array('class' => 'partner-logo')); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <h4><?php the_title(); ?></h4>
                    </div>
                <?php endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
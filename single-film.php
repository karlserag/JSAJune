<?php
/**
 * Single Film Template
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<!-- Film Hero -->
<div class="hero film-hero">
    <?php if (has_post_thumbnail()) : ?>
        <div class="hero-background" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'film-hero')); ?>');"></div>
    <?php endif; ?>
    <div class="hero-content">
        <h1><?php the_title(); ?></h1>
        <?php 
        $year = get_post_meta(get_the_ID(), '_saab_film_year', true);
        $duration = get_post_meta(get_the_ID(), '_saab_film_duration', true);
        if ($year || $duration) : ?>
            <p class="hero-subtitle">
                <?php if ($year) echo esc_html($year); ?>
                <?php if ($year && $duration) echo ' • '; ?>
                <?php if ($duration) echo esc_html($duration); ?>
            </p>
        <?php endif; ?>
    </div>
</div>

<div class="section section-white">
    <div class="container">
        <?php saab_breadcrumbs(); ?>
        
        <div class="grid grid-12" style="gap: var(--space-60);">
            <!-- Main Content -->
            <div style="grid-column: 1 / span 8;">
                
                <!-- Film Video -->
                <?php 
                $video_url = get_post_meta(get_the_ID(), '_saab_film_video_url', true);
                if ($video_url) : ?>
                    <div class="film-video" style="margin-bottom: var(--space-50);">
                        <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                            <?php if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) : ?>
                                <?php
                                // Extract YouTube video ID
                                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $matches);
                                $video_id = isset($matches[1]) ? $matches[1] : '';
                                if ($video_id) : ?>
                                    <iframe src="https://www.youtube.com/embed/<?php echo esc_attr($video_id); ?>?rel=0" 
                                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                                            frameborder="0" 
                                            allowfullscreen
                                            title="<?php echo esc_attr(get_the_title()); ?>">
                                    </iframe>
                                <?php endif; ?>
                            <?php elseif (strpos($video_url, 'vimeo.com') !== false) : ?>
                                <?php
                                // Extract Vimeo video ID
                                preg_match('/vimeo\.com\/(\d+)/', $video_url, $matches);
                                $video_id = isset($matches[1]) ? $matches[1] : '';
                                if ($video_id) : ?>
                                    <iframe src="https://player.vimeo.com/video/<?php echo esc_attr($video_id); ?>" 
                                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                                            frameborder="0" 
                                            allowfullscreen
                                            title="<?php echo esc_attr(get_the_title()); ?>">
                                    </iframe>
                                <?php endif; ?>
                            <?php else : ?>
                                <video controls style="width: 100%; height: 100%;">
                                    <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                                    <?php esc_html_e('Your browser does not support the video tag.', 'saab'); ?>
                                </video>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Synopsis -->
                <?php 
                $synopsis = get_post_meta(get_the_ID(), '_saab_film_synopsis', true);
                if ($synopsis) : ?>
                    <div class="film-synopsis" style="margin-bottom: var(--space-50);">
                        <h3><?php esc_html_e('Synopsis', 'saab'); ?></h3>
                        <div class="synopsis-content">
                            <?php echo wp_kses_post($synopsis); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Film Stills Carousel -->
                <?php 
                $film_stills = get_post_meta(get_the_ID(), '_saab_film_stills', true);
                if (!empty($film_stills) && is_array($film_stills)) : ?>
                    <div class="film-stills-section" style="margin-bottom: var(--space-50);">
                        <h3><?php esc_html_e('Film Stills', 'saab'); ?></h3>
                        <div class="film-stills-swiper swiper-container">
                            <div class="swiper-wrapper">
                                <?php foreach ($film_stills as $still_id) : 
                                    $img_url = wp_get_attachment_image_url($still_id, 'gallery-thumb');
                                    if ($img_url) : ?>
                                        <div class="swiper-slide">
                                            <img src="<?php echo esc_url($img_url); ?>" alt="<?php esc_attr_e('Film still', 'saab'); ?>" loading="lazy" />
                                        </div>
                                    <?php endif;
                                endforeach; ?>
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next" tabindex="0" role="button" aria-label="<?php esc_attr_e('Next slide', 'saab'); ?>"></div>
                            <div class="swiper-button-prev" tabindex="0" role="button" aria-label="<?php esc_attr_e('Previous slide', 'saab'); ?>"></div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Main Content -->
                <div class="film-content">
                    <?php the_content(); ?>
                </div>

                <!-- Credits -->
                <?php 
                $credits = get_post_meta(get_the_ID(), '_saab_film_credits', true);
                if ($credits && is_array($credits)) : ?>
                    <div class="film-credits" style="margin-top: var(--space-50);">
                        <h3><?php esc_html_e('Credits', 'saab'); ?></h3>
                        <div class="credits-list">
                            <?php foreach ($credits as $credit) : ?>
                                <div class="credit-item">
                                    <strong><?php echo esc_html($credit['role']); ?>:</strong>
                                    <span><?php echo esc_html($credit['name']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div style="grid-column: 9 / span 4;">
                <div class="film-sidebar">
                    
                    <!-- Film Details -->
                    <div class="sidebar-section">
                        <h4><?php esc_html_e('Film Details', 'saab'); ?></h4>
                        <div class="film-meta">
                            <?php if ($year) : ?>
                                <div class="meta-item">
                                    <strong><?php esc_html_e('Year:', 'saab'); ?></strong>
                                    <span><?php echo esc_html($year); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($duration) : ?>
                                <div class="meta-item">
                                    <strong><?php esc_html_e('Duration:', 'saab'); ?></strong>
                                    <span><?php echo esc_html($duration); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php 
                            $format = get_post_meta(get_the_ID(), '_saab_film_format', true);
                            if ($format) : ?>
                                <div class="meta-item">
                                    <strong><?php esc_html_e('Format:', 'saab'); ?></strong>
                                    <span><?php echo esc_html($format); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php 
                            $director = get_post_meta(get_the_ID(), '_saab_film_director', true);
                            $dop = get_post_meta(get_the_ID(), '_saab_film_dop', true);
                            $editor = get_post_meta(get_the_ID(), '_saab_film_editor', true);
                            if ($director) : ?>
                                <div class="meta-item">
                                    <strong><?php esc_html_e('Director:', 'saab'); ?></strong>
                                    <span><?php echo esc_html($director); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($dop) : ?>
                                <div class="meta-item">
                                    <strong><?php esc_html_e('DOP:', 'saab'); ?></strong>
                                    <span><?php echo esc_html($dop); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($editor) : ?>
                                <div class="meta-item">
                                    <strong><?php esc_html_e('Editor:', 'saab'); ?></strong>
                                    <span><?php echo esc_html($editor); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Genres -->
                    <?php 
                    $genres = get_the_terms(get_the_ID(), 'genre');
                    if ($genres && !is_wp_error($genres)) : ?>
                        <div class="sidebar-section">
                            <h4><?php esc_html_e('Genres', 'saab'); ?></h4>
                            <ul class="term-list">
                                <?php foreach ($genres as $genre) : ?>
                                    <li>
                                        <a href="<?php echo esc_url(get_term_link($genre)); ?>">
                                            <?php echo esc_html($genre->name); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Languages -->
                    <?php 
                    $languages = get_the_terms(get_the_ID(), 'film_language');
                    if ($languages && !is_wp_error($languages)) : ?>
                        <div class="sidebar-section">
                            <h4><?php esc_html_e('Languages', 'saab'); ?></h4>
                            <ul class="term-list">
                                <?php foreach ($languages as $language) : ?>
                                    <li>
                                        <a href="<?php echo esc_url(get_term_link($language)); ?>">
                                            <?php echo esc_html($language->name); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Share Buttons -->
                    <div class="sidebar-section">
                        <h4><?php esc_html_e('Share', 'saab'); ?></h4>
                        <div class="share-buttons">
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                               target="_blank" rel="noopener" class="share-btn share-twitter">
                                <?php esc_html_e('Twitter', 'saab'); ?>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                               target="_blank" rel="noopener" class="share-btn share-facebook">
                                <?php esc_html_e('Facebook', 'saab'); ?>
                            </a>
                            <a href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>" 
                               class="share-btn share-email">
                                <?php esc_html_e('Email', 'saab'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Films -->
<section class="section section-cream">
    <div class="container">
        <h3><?php esc_html_e('Related Films', 'saab'); ?></h3>
        
        <div class="films-grid">
            <?php
            $related_films = new WP_Query(array(
                'post_type' => 'film',
                'posts_per_page' => 3,
                'post__not_in' => array(get_the_ID()),
                'tax_query' => array(
                    'relation' => 'OR',
                    array(
                        'taxonomy' => 'genre',
                        'field' => 'term_id',
                        'terms' => wp_list_pluck($genres ?: array(), 'term_id'),
                    ),
                    array(
                        'taxonomy' => 'film_language',
                        'field' => 'term_id',
                        'terms' => wp_list_pluck($languages ?: array(), 'term_id'),
                    ),
                ),
                'orderby' => 'rand',
            ));

            if ($related_films->have_posts()) :
                while ($related_films->have_posts()) : $related_films->the_post();
                    get_template_part('template-parts/film-card');
                endwhile;
                wp_reset_postdata();
            else :
                // Fallback to latest films if no related films found
                $latest_films = new WP_Query(array(
                    'post_type' => 'film',
                    'posts_per_page' => 3,
                    'post__not_in' => array(get_the_ID()),
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
    </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
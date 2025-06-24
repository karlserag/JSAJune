<?php
/**
 * Film Card Template Part
 */

$year = get_post_meta(get_the_ID(), '_saab_film_year', true);
$duration = get_post_meta(get_the_ID(), '_saab_film_duration', true);
$genres = get_the_terms(get_the_ID(), 'genre');
$languages = get_the_terms(get_the_ID(), 'film_language');

// Build classes for filtering
$filter_classes = '';
if ($genres && !is_wp_error($genres)) {
    $genre_slugs = array_map(function($genre) { return 'genre-' . $genre->slug; }, $genres);
    $filter_classes .= implode(' ', $genre_slugs) . ' ';
}
if ($languages && !is_wp_error($languages)) {
    $language_slugs = array_map(function($lang) { return 'language-' . $lang->slug; }, $languages);
    $filter_classes .= implode(' ', $language_slugs);
}
?>

<article class="film-card <?php echo esc_attr(trim($filter_classes)); ?>" data-film-id="<?php the_ID(); ?>">
    <div class="film-poster">
        <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(sprintf(__('View film: %s', 'saab'), get_the_title())); ?>">
                <?php the_post_thumbnail('film-poster', array('loading' => 'lazy')); ?>
            </a>
        <?php else : ?>
            <a href="<?php the_permalink(); ?>" class="no-poster" aria-label="<?php echo esc_attr(sprintf(__('View film: %s', 'saab'), get_the_title())); ?>">
                <div class="poster-placeholder">
                    <span><?php esc_html_e('No Image', 'saab'); ?></span>
                </div>
            </a>
        <?php endif; ?>
        
        <div class="film-overlay">
            <h3 class="film-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
            <?php if ($year) : ?>
                <p class="film-year"><?php echo esc_html($year); ?></p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="film-meta">
        <?php if ($duration) : ?>
            <span class="duration" title="<?php esc_attr_e('Duration', 'saab'); ?>">
                <span class="sr-only"><?php esc_html_e('Duration:', 'saab'); ?></span>
                <?php echo esc_html($duration); ?>
            </span>
        <?php endif; ?>
        
        <?php if ($genres && !is_wp_error($genres)) : ?>
            <span class="format" title="<?php esc_attr_e('Genre', 'saab'); ?>">
                <span class="sr-only"><?php esc_html_e('Genre:', 'saab'); ?></span>
                <?php echo esc_html($genres[0]->name); ?>
            </span>
        <?php endif; ?>
    </div>
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Movie",
        "name": "<?php echo esc_js(get_the_title()); ?>",
        "url": "<?php echo esc_url(get_permalink()); ?>",
        <?php if ($year) : ?>"dateCreated": "<?php echo esc_js($year); ?>",<?php endif; ?>
        <?php if ($duration) : ?>"duration": "<?php echo esc_js($duration); ?>",<?php endif; ?>
        <?php if (has_post_thumbnail()) : ?>"image": "<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'film-poster')); ?>",<?php endif; ?>
        "director": {
            "@type": "Person",
            "name": "Jocelyne Saab"
        }
    }
    </script>
</article>
<?php
/**
 * Hero Section Template Part - Video First Approach
 * Used in: index.php and other pages
 * Updated: 2025-06-21 04:44:04 UTC by karlserag
 */

// Get hero content from customizer or page fields
$hero_video = get_theme_mod('hero_video_url');
$hero_image = get_theme_mod('hero_background_image'); // Fallback only
$hero_title = get_theme_mod('hero_title', get_bloginfo('name'));
$hero_subtitle = get_theme_mod('hero_subtitle', get_bloginfo('description'));
$rotating_texts = get_theme_mod('hero_rotating_texts', 'Filmmaker|Artist|Visionary');

// For single pages, check for page-specific hero content
if (is_page() || is_single()) {
    $page_hero_video = get_post_meta(get_the_ID(), '_saab_hero_video', true);
    $page_hero_image = get_post_meta(get_the_ID(), '_saab_hero_image', true);
    $page_hero_title = get_post_meta(get_the_ID(), '_saab_hero_title', true);
    $page_hero_subtitle = get_post_meta(get_the_ID(), '_saab_hero_subtitle', true);
    
    if ($page_hero_video) $hero_video = $page_hero_video;
    if ($page_hero_image) $hero_image = $page_hero_image;
    if ($page_hero_title) $hero_title = $page_hero_title;
    if ($page_hero_subtitle) $hero_subtitle = $page_hero_subtitle;
}

// Default hero video URL (replace with your actual video)
if (!$hero_video && !$hero_image) {
    $hero_video = get_template_directory_uri() . '/assets/videos/hero-default.mp4';
}
?>

<section class="hero" role="banner">
    <?php if ($hero_video) : ?>
        <div class="hero-background hero-video">
            <video autoplay muted loop playsinline preload="metadata">
                <source src="<?php echo esc_url($hero_video); ?>" type="video/mp4">
                <!-- Fallback image if video fails to load -->
                <?php if ($hero_image) : ?>
                    <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($hero_title); ?>" loading="lazy" />
                <?php endif; ?>
                <p><?php esc_html_e('Your browser does not support the video tag.', 'saab'); ?></p>
            </video>
        </div>
    <?php elseif ($hero_image) : ?>
        <div class="hero-background hero-image" style="background-image: url('<?php echo esc_url($hero_image); ?>');"></div>
    <?php else : ?>
        <!-- Fallback dark background with pattern -->
        <div class="hero-background hero-fallback"></div>
    <?php endif; ?>

    <div class="hero-content">
        <h1><?php echo esc_html($hero_title); ?></h1>
        <p class="hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
        
        <?php if ($rotating_texts && is_front_page()) :
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
        
        <!-- Optional: Video controls for accessibility -->
        <?php if ($hero_video) : ?>
            <div class="hero-video-controls">
                <button id="hero-video-toggle" class="video-toggle-btn" aria-label="<?php esc_attr_e('Pause/Play background video', 'saab'); ?>">
                    <span class="pause-icon">⏸</span>
                    <span class="play-icon" style="display: none;">▶</span>
                </button>
            </div>
        <?php endif; ?>
    </div>
</section>
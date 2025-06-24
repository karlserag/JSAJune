<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <meta name="format-detection" content="telephone=no">
    
    <?php if (is_singular('film')) : ?>
        <meta property="og:type" content="video.movie">
        <meta property="og:title" content="<?php echo esc_attr(get_the_title()); ?>">
        <meta property="og:description" content="<?php echo esc_attr(wp_strip_all_tags(get_post_meta(get_the_ID(), '_saab_film_synopsis', true))); ?>">
        <?php if (has_post_thumbnail()) : ?>
            <meta property="og:image" content="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>">
        <?php endif; ?>
    <?php endif; ?>
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Skip to content for accessibility -->
<a class="skip-link screen-reader-text" href="#main">
    <?php esc_html_e('Skip to content', 'saab'); ?>
</a>

<header class="site-header" id="site-header" role="banner">
    <div class="container">
        <div class="header-content">
            <!-- Logo -->
            <div class="site-logo">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <h1 class="site-title"><?php bloginfo('name'); ?></h1>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Primary Navigation -->
            <nav class="main-nav" id="main-nav" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', 'saab'); ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container' => false,
                    'menu_class' => 'nav-menu',
                    'fallback_cb' => 'saab_fallback_menu',
                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                ));
                ?>
            </nav>

            <!-- Language Switcher -->
            <div class="language-switcher" role="navigation" aria-label="<?php esc_attr_e('Language Selection', 'saab'); ?>">
                <?php if (function_exists('pll_the_languages')) : ?>
                    <?php pll_the_languages(array(
                        'show_flags' => 1,
                        'show_names' => 1,
                        'display_names_as' => 'slug',
                        'force_home' => 0
                    )); ?>
                <?php elseif (function_exists('icl_get_languages')) : ?>
                    <?php
                    $languages = icl_get_languages('skip_missing=0&orderby=code');
                    if (!empty($languages)) :
                        foreach ($languages as $l) : ?>
                            <a href="<?php echo esc_url($l['url']); ?>" 
                               class="<?php echo $l['active'] ? 'current-lang' : ''; ?>"
                               hreflang="<?php echo esc_attr($l['language_code']); ?>">
                                <?php echo esc_html(strtoupper($l['language_code'])); ?>
                            </a>
                        <?php endforeach;
                    endif; ?>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/en/')); ?>" 
                       class="<?php echo (strpos(get_locale(), 'en') === 0) ? 'current-lang' : ''; ?>"
                       hreflang="en">EN</a>
                    <a href="<?php echo esc_url(home_url('/fr/')); ?>" 
                       class="<?php echo (strpos(get_locale(), 'fr') === 0) ? 'current-lang' : ''; ?>"
                       hreflang="fr">FR</a>
                    <a href="<?php echo esc_url(home_url('/ar/')); ?>" 
                       class="<?php echo (strpos(get_locale(), 'ar') === 0) ? 'current-lang' : ''; ?>"
                       hreflang="ar">AR</a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" 
                    id="mobile-menu-toggle" 
                    aria-expanded="false" 
                    aria-controls="menu-overlay"
                    aria-label="<?php esc_attr_e('Toggle Navigation Menu', 'saab'); ?>">
                <span class="hamburger-icon" aria-hidden="true">
                    <span class="bar bar1"></span>
                    <span class="bar bar2"></span>
                    <span class="bar bar3"></span>
                </span>
            </button>
        </div>
    </div>
    
    <!-- Full Menu Overlay -->
    <nav id="menu-overlay" class="menu-overlay" aria-hidden="true" tabindex="-1" role="navigation">
        <div class="menu-overlay-inner">
            <button class="menu-overlay-close" id="menu-overlay-close" aria-label="<?php esc_attr_e('Close Menu', 'saab'); ?>">&times;</button>
            <div class="menu-overlay-menu animated-menu">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container' => false,
                    'menu_class' => 'nav-menu overlay-nav-menu',
                    'fallback_cb' => 'saab_fallback_menu',
                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                ));
                ?>
            </div>
        </div>
    </nav>
</header>

<main id="main" class="site-main" role="main">

<?php
/**
 * Fallback menu if no menu is assigned
 */
function saab_fallback_menu() {
    echo '<ul class="nav-menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'saab') . '</a></li>';
    echo '<li><a href="' . esc_url(get_post_type_archive_link('film')) . '">' . esc_html__('Films', 'saab') . '</a></li>';
    echo '<li><a href="' . esc_url(get_post_type_archive_link('news')) . '">' . esc_html__('News', 'saab') . '</a></li>';
    echo '<li><a href="' . esc_url(get_post_type_archive_link('workshop')) . '">' . esc_html__('Workshops', 'saab') . '</a></li>';
    echo '<li><a href="' . esc_url(get_post_type_archive_link('publication')) . '">' . esc_html__('Publications', 'saab') . '</a></li>';
    echo '</ul>';
}
?>
<?php
/**
 * Jocelyne Saab Theme Functions - COMPLETE FILE
 * Following Prince Claus Fund design principles
 * Author: Karl Serag (karlserag)
 * Date: 2025-06-21 10:15:24 UTC
 * Version: 1.0.1 - Fixed meta box errors
 */

// Load SEO functions
require_once get_template_directory() . '/functions-seo.php';

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function saab_theme_setup() {
    // Make theme available for translation
    load_theme_textdomain('saab', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Enable support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for core custom background feature
    add_theme_support('custom-background', apply_filters('saab_custom_background_args', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    )));

    // HTML5 markup support for search form, comment form, and comments
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add theme support for Post Formats
    add_theme_support('post-formats', array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
        'status',
        'audio',
        'chat',
    ));

    // Set up the WordPress core custom header feature
    add_theme_support('custom-header', apply_filters('saab_custom_header_args', array(
        'default-image'          => '',
        'default-text-color'     => '000000',
        'width'                  => 1920,
        'height'                 => 1080,
        'flex-height'            => true,
        'wp-head-callback'       => 'saab_header_style',
    )));

    // Add support for Block Styles
    add_theme_support('wp-block-styles');

    // Add support for full and wide align images
    add_theme_support('align-wide');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Enqueue editor styles
    add_editor_style('assets/css/editor-style.css');

    // Add support for responsive embedded content
    add_theme_support('responsive-embeds');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'saab'),
        'footer'  => esc_html__('Footer Menu', 'saab'),
        'social'  => esc_html__('Social Links Menu', 'saab'),
    ));

    // Add image sizes
    add_image_size('film-poster', 400, 600, true);           // For film posters
    add_image_size('film-poster-large', 800, 1200, true);    // Large film posters
    add_image_size('news-featured', 800, 450, true);         // News featured images
    add_image_size('news-thumbnail', 400, 225, true);        // News thumbnails
    add_image_size('partner-logo', 300, 150, false);         // Partner logos
    add_image_size('hero-background', 1920, 1080, true);     // Hero backgrounds
    add_image_size('gallery-thumb', 300, 300, true);         // Gallery thumbnails
    add_image_size('event-featured', 600, 400, true);        // Event images
}
add_action('after_setup_theme', 'saab_theme_setup');

// Set the content width in pixels, based on the theme's design and stylesheet
function saab_content_width() {
    $GLOBALS['content_width'] = apply_filters('saab_content_width', 1200);
}
add_action('after_setup_theme', 'saab_content_width', 0);

// Enqueue scripts and styles
function saab_scripts() {
    // Main stylesheet
    wp_enqueue_style('saab-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Main CSS file
    wp_enqueue_style('saab-main-css', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0');
    
    // Google Fonts
    wp_enqueue_style('saab-fonts', 'https://fonts.googleapis.com/css2?family=Epilogue:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500&family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap', array(), null);
    
    // Font Awesome (for icons)
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');
    
    // Main JavaScript
    wp_enqueue_script('saab-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
    
    // Localize script for AJAX
    wp_localize_script('saab-main', 'saabAjax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('saab_nonce'),
        'strings' => array(
            'error' => esc_html__('An error occurred. Please try again.', 'saab'),
            'loading' => esc_html__('Loading...', 'saab'),
            'load_more' => esc_html__('Load More', 'saab'),
            'no_more' => esc_html__('No more items to load.', 'saab'),
        ),
    ));
    
    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'saab_scripts');

// Admin styles
function saab_admin_styles() {
    wp_enqueue_style('saab-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), '1.0.0');
    wp_enqueue_script('media-upload');
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'saab_admin_styles');

// Custom Post Types
function saab_register_post_types() {
    // Films
    register_post_type('film', array(
        'labels' => array(
            'name' => 'Films',
            'singular_name' => 'Film',
            'menu_name' => 'Films',
            'add_new' => 'Add New Film',
            'add_new_item' => 'Add New Film',
            'edit_item' => 'Edit Film',
            'new_item' => 'New Film',
            'view_item' => 'View Film',
            'view_items' => 'View Films',
            'search_items' => 'Search Films',
            'not_found' => 'No films found',
            'not_found_in_trash' => 'No films found in trash',
            'all_items' => 'All Films',
            'archives' => 'Film Archives',
            'attributes' => 'Film Attributes',
            'insert_into_item' => 'Insert into film',
            'uploaded_to_this_item' => 'Uploaded to this film',
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'films', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-video-alt3',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
        'show_in_rest' => true,
        'rest_base' => 'films',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    ));

    // News
    register_post_type('news', array(
        'labels' => array(
            'name' => 'News',
            'singular_name' => 'News Article',
            'menu_name' => 'News',
            'add_new' => 'Add News',
            'add_new_item' => 'Add New News Article',
            'edit_item' => 'Edit News Article',
            'new_item' => 'New News Article',
            'view_item' => 'View News Article',
            'view_items' => 'View News',
            'search_items' => 'Search News',
            'not_found' => 'No news found',
            'not_found_in_trash' => 'No news found in trash',
            'all_items' => 'All News',
            'archives' => 'News Archives',
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'news', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-megaphone',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments', 'revisions'),
        'show_in_rest' => true,
        'rest_base' => 'news',
    ));

    // Trainings and Workshops (renamed from Workshops)
    register_post_type('training_workshop', array(
        'labels' => array(
            'name' => __('Trainings and Workshops', 'saab'),
            'singular_name' => __('Training/Workshop', 'saab'),
            'menu_name' => __('Trainings & Workshops', 'saab'),
            'add_new' => __('Add Training/Workshop', 'saab'),
            'add_new_item' => __('Add New Training/Workshop', 'saab'),
            'edit_item' => __('Edit Training/Workshop', 'saab'),
            'new_item' => __('New Training/Workshop', 'saab'),
            'view_item' => __('View Training/Workshop', 'saab'),
            'view_items' => __('View Trainings & Workshops', 'saab'),
            'search_items' => __('Search Trainings & Workshops', 'saab'),
            'not_found' => __('No trainings or workshops found', 'saab'),
            'not_found_in_trash' => __('No trainings or workshops found in trash', 'saab'),
            'all_items' => __('All Trainings & Workshops', 'saab'),
            'archives' => __('Trainings & Workshops Archives', 'saab'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'trainings-workshops', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 7,
        'menu_icon' => 'dashicons-welcome-learn-more',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
        'show_in_rest' => true,
        'rest_base' => 'trainings-workshops',
    ));

    // Publications
    register_post_type('publication', array(
        'labels' => array(
            'name' => 'Publications',
            'singular_name' => 'Publication',
            'menu_name' => 'Publications',
            'add_new' => 'Add Publication',
            'add_new_item' => 'Add New Publication',
            'edit_item' => 'Edit Publication',
            'new_item' => 'New Publication',
            'view_item' => 'View Publication',
            'view_items' => 'View Publications',
            'search_items' => 'Search Publications',
            'not_found' => 'No publications found',
            'not_found_in_trash' => 'No publications found in trash',
            'all_items' => 'All Publications',
            'archives' => 'Publication Archives',
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'publications', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 8,
        'menu_icon' => 'dashicons-book',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
        'show_in_rest' => true,
        'rest_base' => 'publications',
    ));

    // Partners
    register_post_type('partner', array(
        'labels' => array(
            'name' => 'Partners',
            'singular_name' => 'Partner',
            'menu_name' => 'Partners',
            'add_new' => 'Add Partner',
            'add_new_item' => 'Add New Partner',
            'edit_item' => 'Edit Partner',
            'new_item' => 'New Partner',
            'view_item' => 'View Partner',
            'view_items' => 'View Partners',
            'search_items' => 'Search Partners',
            'not_found' => 'No partners found',
            'not_found_in_trash' => 'No partners found in trash',
            'all_items' => 'All Partners',
            'archives' => 'Partner Archives',
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'partners', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 9,
        'menu_icon' => 'dashicons-groups',
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'revisions'),
        'show_in_rest' => true,
        'rest_base' => 'partners',
    ));

    // Screenings CPT
    register_post_type('screening', array(
        'labels' => array(
            'name' => __('Screenings', 'saab'),
            'singular_name' => __('Screening', 'saab'),
            'menu_name' => __('Screenings', 'saab'),
            'add_new' => __('Add Screening', 'saab'),
            'add_new_item' => __('Add New Screening', 'saab'),
            'edit_item' => __('Edit Screening', 'saab'),
            'new_item' => __('New Screening', 'saab'),
            'view_item' => __('View Screening', 'saab'),
            'view_items' => __('View Screenings', 'saab'),
            'search_items' => __('Search Screenings', 'saab'),
            'not_found' => __('No screenings found', 'saab'),
            'not_found_in_trash' => __('No screenings found in trash', 'saab'),
            'all_items' => __('All Screenings', 'saab'),
            'archives' => __('Screenings Archives', 'saab'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'screenings', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 11,
        'menu_icon' => 'dashicons-tickets-alt',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
        'show_in_rest' => true,
        'rest_base' => 'screenings',
    ));
}
add_action('init', 'saab_register_post_types');

// Custom taxonomies
function saab_register_taxonomies() {
    // Film genres
    register_taxonomy('genre', 'film', array(
        'labels' => array(
            'name' => 'Genres',
            'singular_name' => 'Genre',
            'menu_name' => 'Genres',
            'all_items' => 'All Genres',
            'edit_item' => 'Edit Genre',
            'view_item' => 'View Genre',
            'update_item' => 'Update Genre',
            'add_new_item' => 'Add New Genre',
            'new_item_name' => 'New Genre Name',
            'parent_item' => 'Parent Genre',
            'parent_item_colon' => 'Parent Genre:',
            'search_items' => 'Search Genres',
            'popular_items' => 'Popular Genres',
            'not_found' => 'No genres found',
        ),
        'hierarchical' => true,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'genre', 'with_front' => false),
        'query_var' => true,
    ));

    // Film languages
    register_taxonomy('film_language', 'film', array(
        'labels' => array(
            'name' => 'Languages',
            'singular_name' => 'Language',
            'menu_name' => 'Languages',
            'all_items' => 'All Languages',
            'edit_item' => 'Edit Language',
            'view_item' => 'View Language',
            'update_item' => 'Update Language',
            'add_new_item' => 'Add New Language',
            'new_item_name' => 'New Language Name',
            'search_items' => 'Search Languages',
            'popular_items' => 'Popular Languages',
            'not_found' => 'No languages found',
        ),
        'hierarchical' => false,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'language', 'with_front' => false),
        'query_var' => true,
    ));

    // News categories
    register_taxonomy('news_category', 'news', array(
        'labels' => array(
            'name' => 'News Categories',
            'singular_name' => 'News Category',
            'menu_name' => 'Categories',
            'all_items' => 'All Categories',
            'edit_item' => 'Edit Category',
            'view_item' => 'View Category',
            'update_item' => 'Update Category',
            'add_new_item' => 'Add New Category',
            'new_item_name' => 'New Category Name',
            'parent_item' => 'Parent Category',
            'parent_item_colon' => 'Parent Category:',
            'search_items' => 'Search Categories',
            'popular_items' => 'Popular Categories',
            'not_found' => 'No categories found',
        ),
        'hierarchical' => true,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'news-category', 'with_front' => false),
        'query_var' => true,
    ));

    // Partnership types
    register_taxonomy('partnership_type', 'partner', array(
        'labels' => array(
            'name' => 'Partnership Types',
            'singular_name' => 'Partnership Type',
            'menu_name' => 'Types',
            'all_items' => 'All Types',
            'edit_item' => 'Edit Type',
            'view_item' => 'View Type',
            'update_item' => 'Update Type',
            'add_new_item' => 'Add New Type',
            'new_item_name' => 'New Type Name',
            'parent_item' => 'Parent Type',
            'parent_item_colon' => 'Parent Type:',
            'search_items' => 'Search Types',
            'popular_items' => 'Popular Types',
            'not_found' => 'No types found',
        ),
        'hierarchical' => true,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'partnership-type', 'with_front' => false),
        'query_var' => true,
    ));

    // Event types
    register_taxonomy('event_type', 'event', array(
        'labels' => array(
            'name' => 'Event Types',
            'singular_name' => 'Event Type',
            'menu_name' => 'Types',
            'all_items' => 'All Types',
            'edit_item' => 'Edit Type',
            'view_item' => 'View Type',
            'update_item' => 'Update Type',
            'add_new_item' => 'Add New Type',
            'new_item_name' => 'New Type Name',
            'search_items' => 'Search Types',
            'popular_items' => 'Popular Types',
            'not_found' => 'No types found',
        ),
        'hierarchical' => true,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'event-type', 'with_front' => false),
        'query_var' => true,
    ));
}
add_action('init', 'saab_register_taxonomies');

// Custom Walker for Navigation Menu
class Saab_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    // Start the element output
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ($depth) ? str_repeat($t, $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        // Add active class for current menu item
        if (in_array('current-menu-item', $classes) || in_array('current-menu-parent', $classes)) {
            $classes[] = 'active';
        }
        
        // Add dropdown class if has children
        if (in_array('menu-item-has-children', $classes)) {
            $classes[] = 'has-dropdown';
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        $item_output = isset($args->before) ? $args->before ?? '' : '';
        $item_output .= '<a' . $attributes . '>';
        $item_output .= (isset($args->link_before) ? $args->link_before ?? '' : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after ?? '' : '');
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after ?? '' : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    // Start the sub-menu
    public function start_lvl(&$output, $depth = 0, $args = null) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);
        $output .= "{$n}{$indent}<ul class=\"sub-menu\">{$n}";
    }
    
    // End the sub-menu
    public function end_lvl(&$output, $depth = 0, $args = null) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);
        $output .= "$indent</ul>{$n}";
    }
    
    // End the element
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $output .= "</li>{$n}";
    }
}

// Add meta boxes for custom fields
function saab_add_meta_boxes() {
    // Film meta box
    add_meta_box(
        'film-details',
        'Film Details',
        'saab_film_meta_box',
        'film',
        'normal',
        'high'
    );
    
    // Trainings and Workshops meta box
    add_meta_box(
        'training_workshop-details',
        __('Training/Workshop Details', 'saab'),
        'saab_training_workshop_meta_box',
        'training_workshop',
        'normal',
        'high'
    );
    
    // Screening meta box
    add_meta_box(
        'screening-details',
        __('Screening Details', 'saab'),
        'saab_screening_meta_box',
        'screening',
        'normal',
        'high'
    );
    
    // Publication meta box
    add_meta_box(
        'publication-details',
        'Publication Details',
        'saab_publication_meta_box',
        'publication',
        'normal',
        'high'
    );
    
    // Partner meta box
    add_meta_box(
        'partner-details',
        'Partner Details',
        'saab_partner_meta_box',
        'partner',
        'normal',
        'high'
    );
    
    // Event meta box
    add_meta_box(
        'event-details',
        'Event Details',
        'saab_event_meta_box',
        'event',
        'normal',
        'high'
    );
    
    // News meta box
    add_meta_box(
        'news-details',
        'News Details',
        'saab_news_meta_box',
        'news',
        'normal',
        'high'
    );
    
    // Hero meta box for pages
    add_meta_box(
        'hero-settings',
        'Hero Section Settings',
        'saab_hero_meta_box',
        array('page', 'film', 'news'),
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'saab_add_meta_boxes');

// News meta box callback
function saab_news_meta_box($post) {
    wp_nonce_field('saab_news_meta_box', 'saab_news_meta_box_nonce');
    
    $featured = get_post_meta($post->ID, '_saab_news_featured', true);
    $source = get_post_meta($post->ID, '_saab_news_source', true);
    $external_url = get_post_meta($post->ID, '_saab_news_external_url', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="news_featured">Featured News</label></th>
            <td>
                <input type="checkbox" id="news_featured" name="news_featured" value="1" <?php checked($featured, 1); ?> />
                <label for="news_featured">Mark as featured news</label>
            </td>
        </tr>
        <tr>
            <th><label for="news_source">News Source</label></th>
            <td><input type="text" id="news_source" name="news_source" value="<?php echo esc_attr($source); ?>" placeholder="e.g., The Guardian, BBC" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="news_external_url">External URL</label></th>
            <td><input type="url" id="news_external_url" name="news_external_url" value="<?php echo esc_attr($external_url); ?>" placeholder="https://..." class="regular-text" /></td>
        </tr>
    </table>
    <?php
}

// Film meta box callback
function saab_film_meta_box($post) {
    wp_nonce_field('saab_film_meta_box', 'saab_film_meta_box_nonce');
    
    $year = get_post_meta($post->ID, '_saab_film_year', true);
    $duration = get_post_meta($post->ID, '_saab_film_duration', true);
    $format = get_post_meta($post->ID, '_saab_film_format', true);
    $country = get_post_meta($post->ID, '_saab_film_country', true);
    $language = get_post_meta($post->ID, '_saab_film_language', true);
    $production = get_post_meta($post->ID, '_saab_film_production', true);
    $trailer_url = get_post_meta($post->ID, '_saab_film_trailer_url', true);
    $awards = get_post_meta($post->ID, '_saab_film_awards', true);
    $director = get_post_meta($post->ID, '_saab_film_director', true);
    $dop = get_post_meta($post->ID, '_saab_film_dop', true);
    $editor = get_post_meta($post->ID, '_saab_film_editor', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="film_year">Year</label></th>
            <td><input type="number" id="film_year" name="film_year" value="<?php echo esc_attr($year); ?>" min="1900" max="<?php echo date('Y'); ?>" class="small-text" /></td>
        </tr>
        <tr>
            <th><label for="film_duration">Duration</label></th>
            <td><input type="text" id="film_duration" name="film_duration" value="<?php echo esc_attr($duration); ?>" placeholder="e.g., 90 min" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="film_format">Format</label></th>
            <td>
                <select id="film_format" name="film_format" class="regular-text">
                    <option value="">Select Format</option>
                    <option value="documentary" <?php selected($format, 'documentary'); ?>>Documentary</option>
                    <option value="feature" <?php selected($format, 'feature'); ?>>Feature Film</option>
                    <option value="short" <?php selected($format, 'short'); ?>>Short Film</option>
                    <option value="experimental" <?php selected($format, 'experimental'); ?>>Experimental</option>
                    <option value="animation" <?php selected($format, 'animation'); ?>>Animation</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="film_country">Country</label></th>
            <td><input type="text" id="film_country" name="film_country" value="<?php echo esc_attr($country); ?>" placeholder="e.g., Lebanon, France" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="film_language">Language</label></th>
            <td><input type="text" id="film_language" name="film_language" value="<?php echo esc_attr($language); ?>" placeholder="e.g., Arabic, French" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="film_production">Production</label></th>
            <td><input type="text" id="film_production" name="film_production" value="<?php echo esc_attr($production); ?>" placeholder="Production company" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="film_trailer_url">Trailer URL</label></th>
            <td><input type="url" id="film_trailer_url" name="film_trailer_url" value="<?php echo esc_attr($trailer_url); ?>" placeholder="https://..." class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="film_awards">Awards</label></th>
            <td><textarea id="film_awards" name="film_awards" rows="3" cols="50" class="large-text"><?php echo esc_textarea($awards); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="film_director">Director</label></th>
            <td><input type="text" id="film_director" name="film_director" value="<?php echo esc_attr($director); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="film_dop">DOP (Director of Photography)</label></th>
            <td><input type="text" id="film_dop" name="film_dop" value="<?php echo esc_attr($dop); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="film_editor">Editor</label></th>
            <td><input type="text" id="film_editor" name="film_editor" value="<?php echo esc_attr($editor); ?>" class="regular-text" /></td>
        </tr>
    </table>
    <?php
}

// Trainings and Workshops meta box callback
function saab_training_workshop_meta_box($post) {
    wp_nonce_field('saab_training_workshop_meta_box', 'saab_training_workshop_meta_box_nonce');
    $date = get_post_meta($post->ID, '_saab_training_workshop_date', true);
    $time = get_post_meta($post->ID, '_saab_training_workshop_time', true);
    $location = get_post_meta($post->ID, '_saab_training_workshop_location', true);
    $duration = get_post_meta($post->ID, '_saab_training_workshop_duration', true);
    $price = get_post_meta($post->ID, '_saab_training_workshop_price', true);
    $capacity = get_post_meta($post->ID, '_saab_training_workshop_capacity', true);
    $project_manager = get_post_meta($post->ID, '_saab_training_workshop_project_manager', true);
    $trainers = get_post_meta($post->ID, '_saab_training_workshop_trainers', true);
    $trainer = get_post_meta($post->ID, '_saab_training_workshop_trainer', true);
    $gallery = get_post_meta($post->ID, '_saab_training_workshop_gallery', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="training_workshop_date"><?php _e('Date', 'saab'); ?></label></th>
            <td><input type="date" id="training_workshop_date" name="training_workshop_date" value="<?php echo esc_attr($date); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="training_workshop_time"><?php _e('Time', 'saab'); ?></label></th>
            <td><input type="text" id="training_workshop_time" name="training_workshop_time" value="<?php echo esc_attr($time); ?>" placeholder="e.g., 2:00 PM - 6:00 PM" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="training_workshop_location"><?php _e('Location', 'saab'); ?></label></th>
            <td><input type="text" id="training_workshop_location" name="training_workshop_location" value="<?php echo esc_attr($location); ?>" placeholder="e.g., Beirut, Lebanon" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="training_workshop_duration"><?php _e('Duration', 'saab'); ?></label></th>
            <td><input type="text" id="training_workshop_duration" name="training_workshop_duration" value="<?php echo esc_attr($duration); ?>" placeholder="e.g., 4 hours, 2 days" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="training_workshop_price"><?php _e('Price', 'saab'); ?></label></th>
            <td><input type="text" id="training_workshop_price" name="training_workshop_price" value="<?php echo esc_attr($price); ?>" placeholder="e.g., $100, Free" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="training_workshop_capacity"><?php _e('Capacity', 'saab'); ?></label></th>
            <td><input type="number" id="training_workshop_capacity" name="training_workshop_capacity" value="<?php echo esc_attr($capacity); ?>" placeholder="e.g., 20" class="small-text" /></td>
        </tr>
        <tr>
            <th><label for="training_workshop_project_manager"><?php _e('Project Manager', 'saab'); ?></label></th>
            <td><input type="text" id="training_workshop_project_manager" name="training_workshop_project_manager" value="<?php echo esc_attr($project_manager); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="training_workshop_trainers"><?php _e('Trainers (comma-separated)', 'saab'); ?></label></th>
            <td><textarea id="training_workshop_trainers" name="training_workshop_trainers" rows="2" class="large-text"><?php echo esc_textarea($trainers); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="training_workshop_trainer"><?php _e('Trainer (single)', 'saab'); ?></label></th>
            <td><input type="text" id="training_workshop_trainer" name="training_workshop_trainer" value="<?php echo esc_attr($trainer); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="training_workshop_gallery"><?php _e('Gallery Images', 'saab'); ?></label></th>
            <td>
                <input type="hidden" id="training_workshop_gallery" name="training_workshop_gallery" value="<?php echo esc_attr($gallery); ?>" />
                <button type="button" class="button" id="training_workshop_gallery_button"><?php _e('Add Images', 'saab'); ?></button>
                <div id="training_workshop_gallery_preview">
                    <?php
                    if ($gallery) {
                        $ids = explode(',', $gallery);
                        foreach ($ids as $id) {
                            $img = wp_get_attachment_image($id, 'gallery-thumb');
                            if ($img) echo $img;
                        }
                    }
                    ?>
                </div>
            </td>
        </tr>
    </table>
    <script>
    jQuery(document).ready(function($) {
        $('#training_workshop_gallery_button').click(function(e) {
            e.preventDefault();
            var frame = wp.media({
                title: '<?php _e('Select Gallery Images', 'saab'); ?>',
                multiple: true,
                library: { type: 'image' }
            });
            frame.on('select', function() {
                var selection = frame.state().get('selection');
                var ids = [];
                var preview = '';
                selection.each(function(attachment) {
                    ids.push(attachment.id);
                    preview += '<img src="' + attachment.attributes.sizes.thumbnail.url + '" style="max-width:100px; margin:2px;" />';
                });
                $('#training_workshop_gallery').val(ids.join(','));
                $('#training_workshop_gallery_preview').html(preview);
            });
            frame.open();
        });
    });
    </script>
    <?php
}

// Screening meta box callback
function saab_screening_meta_box($post) {
    wp_nonce_field('saab_screening_meta_box', 'saab_screening_meta_box_nonce');
    $date = get_post_meta($post->ID, '_saab_screening_date', true);
    $time = get_post_meta($post->ID, '_saab_screening_time', true);
    $location = get_post_meta($post->ID, '_saab_screening_location', true);
    $venue = get_post_meta($post->ID, '_saab_screening_venue', true);
    $film = get_post_meta($post->ID, '_saab_screening_film', true);
    $ticket_url = get_post_meta($post->ID, '_saab_screening_ticket_url', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="screening_date"><?php _e('Screening Date', 'saab'); ?></label></th>
            <td><input type="date" id="screening_date" name="screening_date" value="<?php echo esc_attr($date); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="screening_time"><?php _e('Screening Time', 'saab'); ?></label></th>
            <td><input type="text" id="screening_time" name="screening_time" value="<?php echo esc_attr($time); ?>" placeholder="e.g., 7:00 PM" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="screening_location"><?php _e('Location', 'saab'); ?></label></th>
            <td><input type="text" id="screening_location" name="screening_location" value="<?php echo esc_attr($location); ?>" placeholder="City, Country" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="screening_venue"><?php _e('Venue', 'saab'); ?></label></th>
            <td><input type="text" id="screening_venue" name="screening_venue" value="<?php echo esc_attr($venue); ?>" placeholder="Venue name" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="screening_film"><?php _e('Film', 'saab'); ?></label></th>
            <td><input type="text" id="screening_film" name="screening_film" value="<?php echo esc_attr($film); ?>" placeholder="Film title" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="screening_ticket_url"><?php _e('Ticket URL', 'saab'); ?></label></th>
            <td><input type="url" id="screening_ticket_url" name="screening_ticket_url" value="<?php echo esc_attr($ticket_url); ?>" placeholder="https://..." class="regular-text" /></td>
        </tr>
    </table>
    <?php
}

// Publication meta box callback
function saab_publication_meta_box($post) {
    wp_nonce_field('saab_publication_meta_box', 'saab_publication_meta_box_nonce');
    
    $author = get_post_meta($post->ID, '_saab_publication_author', true);
    $year = get_post_meta($post->ID, '_saab_publication_year', true);
    $publisher = get_post_meta($post->ID, '_saab_publication_publisher', true);
    $isbn = get_post_meta($post->ID, '_saab_publication_isbn', true);
    $pages = get_post_meta($post->ID, '_saab_publication_pages', true);
    $language = get_post_meta($post->ID, '_saab_publication_language', true);
    $purchase_url = get_post_meta($post->ID, '_saab_publication_purchase_url', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="publication_author">Author(s)</label></th>
            <td><input type="text" id="publication_author" name="publication_author" value="<?php echo esc_attr($author); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="publication_year">Publication Year</label></th>
            <td><input type="number" id="publication_year" name="publication_year" value="<?php echo esc_attr($year); ?>" min="1900" max="<?php echo date('Y'); ?>" class="small-text" /></td>
        </tr>
        <tr>
            <th><label for="publication_publisher">Publisher</label></th>
            <td><input type="text" id="publication_publisher" name="publication_publisher" value="<?php echo esc_attr($publisher); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="publication_isbn">ISBN</label></th>
            <td><input type="text" id="publication_isbn" name="publication_isbn" value="<?php echo esc_attr($isbn); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="publication_pages">Pages</label></th>
            <td><input type="number" id="publication_pages" name="publication_pages" value="<?php echo esc_attr($pages); ?>" class="small-text" /></td>
        </tr>
        <tr>
            <th><label for="publication_language">Language</label></th>
            <td><input type="text" id="publication_language" name="publication_language" value="<?php echo esc_attr($language); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="publication_purchase_url">Purchase URL</label></th>
            <td><input type="url" id="publication_purchase_url" name="publication_purchase_url" value="<?php echo esc_attr($purchase_url); ?>" class="regular-text" /></td>
        </tr>
    </table>
    <?php
}

// Partner meta box callback
function saab_partner_meta_box($post) {
    wp_nonce_field('saab_partner_meta_box', 'saab_partner_meta_box_nonce');
    
    $website = get_post_meta($post->ID, '_saab_partner_website', true);
    $contact_email = get_post_meta($post->ID, '_saab_partner_contact_email', true);
    $contact_phone = get_post_meta($post->ID, '_saab_partner_contact_phone', true);
    $location = get_post_meta($post->ID, '_saab_partner_location', true);
    $partnership_since = get_post_meta($post->ID, '_saab_partner_since', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="partner_website">Website URL</label></th>
            <td><input type="url" id="partner_website" name="partner_website" value="<?php echo esc_attr($website); ?>" placeholder="https://example.com" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="partner_contact_email">Contact Email</label></th>
            <td><input type="email" id="partner_contact_email" name="partner_contact_email" value="<?php echo esc_attr($contact_email); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="partner_contact_phone">Contact Phone</label></th>
            <td><input type="tel" id="partner_contact_phone" name="partner_contact_phone" value="<?php echo esc_attr($contact_phone); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="partner_location">Location</label></th>
            <td><input type="text" id="partner_location" name="partner_location" value="<?php echo esc_attr($location); ?>" placeholder="City, Country" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="partner_since">Partnership Since</label></th>
            <td><input type="number" id="partner_since" name="partner_since" value="<?php echo esc_attr($partnership_since); ?>" min="1900" max="<?php echo date('Y'); ?>" class="small-text" /></td>
        </tr>
    </table>
    <?php
}

// Event meta box callback
function saab_event_meta_box($post) {
    wp_nonce_field('saab_event_meta_box', 'saab_event_meta_box_nonce');
    
    $date = get_post_meta($post->ID, '_saab_event_date', true);
    $time = get_post_meta($post->ID, '_saab_event_time', true);
    $location = get_post_meta($post->ID, '_saab_event_location', true);
    $venue = get_post_meta($post->ID, '_saab_event_venue', true);
    $ticket_url = get_post_meta($post->ID, '_saab_event_ticket_url', true);
    $price = get_post_meta($post->ID, '_saab_event_price', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="event_date">Event Date</label></th>
            <td><input type="date" id="event_date" name="event_date" value="<?php echo esc_attr($date); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="event_time">Event Time</label></th>
            <td><input type="text" id="event_time" name="event_time" value="<?php echo esc_attr($time); ?>" placeholder="e.g., 7:00 PM" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="event_location">Location</label></th>
            <td><input type="text" id="event_location" name="event_location" value="<?php echo esc_attr($location); ?>" placeholder="City, Country" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="event_venue">Venue</label></th>
            <td><input type="text" id="event_venue" name="event_venue" value="<?php echo esc_attr($venue); ?>" placeholder="Venue name" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="event_ticket_url">Ticket URL</label></th>
            <td><input type="url" id="event_ticket_url" name="event_ticket_url" value="<?php echo esc_attr($ticket_url); ?>" placeholder="https://..." class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="event_price">Ticket Price</label></th>
            <td><input type="text" id="event_price" name="event_price" value="<?php echo esc_attr($price); ?>" placeholder="e.g., $25, Free" class="regular-text" /></td>
        </tr>
    </table>
    <?php
}

// Hero meta box callback
function saab_hero_meta_box($post) {
    wp_nonce_field('saab_hero_meta_box', 'saab_hero_meta_box_nonce');
    
    $hero_video = get_post_meta($post->ID, '_saab_hero_video', true);
    $hero_image = get_post_meta($post->ID, '_saab_hero_image', true);
    $hero_title = get_post_meta($post->ID, '_saab_hero_title', true);
    $hero_subtitle = get_post_meta($post->ID, '_saab_hero_subtitle', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="hero_video">Hero Video URL</label></th>
            <td><input type="url" id="hero_video" name="hero_video" value="<?php echo esc_attr($hero_video); ?>" placeholder="https://..." class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="hero_image">Hero Background Image</label></th>
            <td>
                <input type="hidden" id="hero_image" name="hero_image" value="<?php echo esc_attr($hero_image); ?>" />
                <button type="button" class="button" id="hero_image_button">Choose Image</button>
                <div id="hero_image_preview">
                    <?php if ($hero_image) : ?>
                        <img src="<?php echo esc_url($hero_image); ?>" style="max-width: 300px; height: auto;" />
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <tr>
            <th><label for="hero_title">Custom Hero Title</label></th>
            <td><input type="text" id="hero_title" name="hero_title" value="<?php echo esc_attr($hero_title); ?>" placeholder="Leave empty to use page title" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="hero_subtitle">Hero Subtitle</label></th>
            <td><textarea id="hero_subtitle" name="hero_subtitle" rows="3" cols="50" class="large-text" placeholder="Optional subtitle"><?php echo esc_textarea($hero_subtitle); ?></textarea></td>
        </tr>
    </table>
    
    <script>
    jQuery(document).ready(function($) {
        $('#hero_image_button').click(function(e) {
            e.preventDefault();
            var image_frame = wp.media({
                title: 'Select Hero Image',
                multiple: false,
                library: {
                    type: 'image',
                }
            });
            image_frame.on('select', function() {
                var selection = image_frame.state().get('selection');
                var attachment = selection.first().toJSON();
                $('#hero_image').val(attachment.url);
                $('#hero_image_preview').html('<img src="' + attachment.url + '" style="max-width: 300px; height: auto;" />');
            });
            image_frame.open();
        });
    });
    </script>
    <?php
}

// Save meta box data
function saab_save_meta_boxes($post_id) {
    // Check if user has permission to edit the post
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // If this is an autosave, don't do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // News meta box
    if (isset($_POST['saab_news_meta_box_nonce']) && wp_verify_nonce($_POST['saab_news_meta_box_nonce'], 'saab_news_meta_box')) {
        if (isset($_POST['news_featured'])) {
            update_post_meta($post_id, '_saab_news_featured', 1);
        } else {
            delete_post_meta($post_id, '_saab_news_featured');
        }
        if (isset($_POST['news_source'])) {
            update_post_meta($post_id, '_saab_news_source', sanitize_text_field($_POST['news_source']));
        }
        if (isset($_POST['news_external_url'])) {
            update_post_meta($post_id, '_saab_news_external_url', esc_url_raw($_POST['news_external_url']));
        }
    }

    // Film meta box
    if (isset($_POST['saab_film_meta_box_nonce']) && wp_verify_nonce($_POST['saab_film_meta_box_nonce'], 'saab_film_meta_box')) {
        $film_fields = array('film_year', 'film_duration', 'film_format', 'film_country', 'film_language', 'film_production', 'film_trailer_url', 'film_awards', 'film_director', 'film_dop', 'film_editor');
        foreach ($film_fields as $field) {
            if (isset($_POST[$field])) {
                $value = ($field === 'film_trailer_url') ? esc_url_raw($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_saab_' . $field, $value);
            }
        }
    }
    
    // Trainings and Workshops meta box
    if (isset($_POST['saab_training_workshop_meta_box_nonce']) && wp_verify_nonce($_POST['saab_training_workshop_meta_box_nonce'], 'saab_training_workshop_meta_box')) {
        $fields = array('training_workshop_date', 'training_workshop_time', 'training_workshop_location', 'training_workshop_duration', 'training_workshop_price', 'training_workshop_capacity', 'training_workshop_project_manager', 'training_workshop_trainers', 'training_workshop_trainer', 'training_workshop_gallery');
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = ($field === 'training_workshop_gallery') ? sanitize_text_field($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_saab_' . $field, $value);
            }
        }
    }
    
    // Screening meta box
    if (isset($_POST['saab_screening_meta_box_nonce']) && wp_verify_nonce($_POST['saab_screening_meta_box_nonce'], 'saab_screening_meta_box')) {
        $fields = array('screening_date', 'screening_time', 'screening_location', 'screening_venue', 'screening_film', 'screening_ticket_url');
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = ($field === 'screening_ticket_url') ? esc_url_raw($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_saab_' . $field, $value);
            }
        }
    }
    
    // Publication meta box
    if (isset($_POST['saab_publication_meta_box_nonce']) && wp_verify_nonce($_POST['saab_publication_meta_box_nonce'], 'saab_publication_meta_box')) {
        $publication_fields = array('publication_author', 'publication_year', 'publication_publisher', 'publication_isbn', 'publication_pages', 'publication_language', 'publication_purchase_url');
        foreach ($publication_fields as $field) {
            if (isset($_POST[$field])) {
                $value = ($field === 'publication_purchase_url') ? esc_url_raw($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_saab_' . $field, $value);
            }
        }
    }
    
    // Partner meta box
    if (isset($_POST['saab_partner_meta_box_nonce']) && wp_verify_nonce($_POST['saab_partner_meta_box_nonce'], 'saab_partner_meta_box')) {
        $partner_fields = array('partner_website', 'partner_contact_email', 'partner_contact_phone', 'partner_location', 'partner_since');
        foreach ($partner_fields as $field) {
            if (isset($_POST[$field])) {
                if ($field === 'partner_website') {
                    $value = esc_url_raw($_POST[$field]);
                } elseif ($field === 'partner_contact_email') {
                    $value = sanitize_email($_POST[$field]);
                } else {
                    $value = sanitize_text_field($_POST[$field]);
                }
                update_post_meta($post_id, '_saab_' . $field, $value);
            }
        }
    }
    
    // Event meta box
    if (isset($_POST['saab_event_meta_box_nonce']) && wp_verify_nonce($_POST['saab_event_meta_box_nonce'], 'saab_event_meta_box')) {
        $event_fields = array('event_date', 'event_time', 'event_location', 'event_venue', 'event_ticket_url', 'event_price');
        foreach ($event_fields as $field) {
            if (isset($_POST[$field])) {
                $value = ($field === 'event_ticket_url') ? esc_url_raw($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_saab_' . $field, $value);
            }
        }
    }
    
    // Hero meta box
    if (isset($_POST['saab_hero_meta_box_nonce']) && wp_verify_nonce($_POST['saab_hero_meta_box_nonce'], 'saab_hero_meta_box')) {
        if (isset($_POST['hero_video'])) {
            update_post_meta($post_id, '_saab_hero_video', esc_url_raw($_POST['hero_video']));
        }
        if (isset($_POST['hero_image'])) {
            update_post_meta($post_id, '_saab_hero_image', esc_url_raw($_POST['hero_image']));
        }
        if (isset($_POST['hero_title'])) {
            update_post_meta($post_id, '_saab_hero_title', sanitize_text_field($_POST['hero_title']));
        }
        if (isset($_POST['hero_subtitle'])) {
            update_post_meta($post_id, '_saab_hero_subtitle', sanitize_textarea_field($_POST['hero_subtitle']));
        }
    }
}
add_action('save_post', 'saab_save_meta_boxes');

// Widget areas
function saab_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'saab'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'saab'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    // Footer widget areas
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar(array(
            'name'          => sprintf(esc_html__('Footer %d', 'saab'), $i),
            'id'            => 'footer-' . $i,
            'description'   => sprintf(esc_html__('Footer widget area %d.', 'saab'), $i),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));
    }
}
add_action('widgets_init', 'saab_widgets_init');

// Customizer
function saab_customize_register($wp_customize) {
    // Hero Section
    $wp_customize->add_section('hero_section', array(
        'title'    => __('Hero Section', 'saab'),
        'priority' => 30,
    ));

    // Hero Video URL
    $wp_customize->add_setting('hero_video_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('hero_video_url', array(
        'label'    => __('Hero Video URL', 'saab'),
        'section'  => 'hero_section',
        'type'     => 'url',
        'description' => __('URL to MP4 video file for hero background', 'saab'),
    ));

    // Hero Background Image
    $wp_customize->add_setting('hero_background_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_background_image', array(
        'label'    => __('Hero Background Image (Fallback)', 'saab'),
        'section'  => 'hero_section',
        'description' => __('Fallback image if video is not available', 'saab'),
    )));

    // Hero Title
    $wp_customize->add_setting('hero_title', array(
        'default'           => get_bloginfo('name'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hero_title', array(
        'label'    => __('Hero Title', 'saab'),
        'section'  => 'hero_section',
        'type'     => 'text',
    ));

    // Hero Subtitle
    $wp_customize->add_setting('hero_subtitle', array(
        'default'           => get_bloginfo('description'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('hero_subtitle', array(
        'label'    => __('Hero Subtitle', 'saab'),
        'section'  => 'hero_section',
        'type'     => 'textarea',
    ));

    // Rotating Text
    $wp_customize->add_setting('hero_rotating_texts', array(
        'default'           => 'Filmmaker|Artist|Visionary',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hero_rotating_texts', array(
        'label'       => __('Rotating Text (separated by |)', 'saab'),
    ));
}

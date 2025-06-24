<?php
// JSON-LD Structured Data
function saab_add_json_ld() {
    if (is_singular('film')) {
        $film_data = array(
            "@context" => "https://schema.org",
            "@type" => "Movie",
            "name" => get_the_title(),
            "dateCreated" => get_post_meta(get_the_ID(), '_saab_film_year', true),
            "duration" => get_post_meta(get_the_ID(), '_saab_film_duration', true),
            "description" => get_post_meta(get_the_ID(), '_saab_film_synopsis', true),
            "image" => get_the_post_thumbnail_url(get_the_ID(), 'large'),
            "director" => get_post_meta(get_the_ID(), '_saab_film_director', true),
            "directorOfPhotography" => get_post_meta(get_the_ID(), '_saab_film_dop', true),
            "editor" => get_post_meta(get_the_ID(), '_saab_film_editor', true),
        );
        echo '<script type="application/ld+json">' . json_encode($film_data) . '</script>';
    } elseif (is_singular('news')) {
        $news_data = array(
            "@context" => "https://schema.org",
            "@type" => "NewsArticle",
            "headline" => get_the_title(),
            "datePublished" => get_the_date('c'),
            "description" => get_the_excerpt(),
            "image" => get_the_post_thumbnail_url(get_the_ID(), 'large'),
            "author" => get_the_author(),
        );
        echo '<script type="application/ld+json">' . json_encode($news_data) . '</script>';
    } elseif (is_singular('event')) {
        $event_data = array(
            "@context" => "https://schema.org",
            "@type" => "Event",
            "name" => get_the_title(),
            "startDate" => get_post_meta(get_the_ID(), '_saab_event_date', true),
            "location" => get_post_meta(get_the_ID(), '_saab_event_location', true),
            "description" => get_the_excerpt(),
            "image" => get_the_post_thumbnail_url(get_the_ID(), 'large'),
        );
        echo '<script type="application/ld+json">' . json_encode($event_data) . '</script>';
    } elseif (is_singular('training_workshop')) {
        $workshop_data = array(
            "@context" => "https://schema.org",
            "@type" => "Event",
            "name" => get_the_title(),
            "startDate" => get_post_meta(get_the_ID(), '_saab_training_workshop_date', true),
            "location" => get_post_meta(get_the_ID(), '_saab_training_workshop_location', true),
            "description" => get_the_excerpt(),
            "image" => get_the_post_thumbnail_url(get_the_ID(), 'large'),
            "organizer" => get_post_meta(get_the_ID(), '_saab_training_workshop_project_manager', true),
            "performer" => get_post_meta(get_the_ID(), '_saab_training_workshop_trainers', true),
        );
        echo '<script type="application/ld+json">' . json_encode($workshop_data) . '</script>';
    } elseif (is_singular('publication')) {
        $pub_data = array(
            "@context" => "https://schema.org",
            "@type" => "Book",
            "name" => get_the_title(),
            "author" => get_post_meta(get_the_ID(), '_saab_publication_author', true),
            "datePublished" => get_post_meta(get_the_ID(), '_saab_publication_year', true),
            "publisher" => get_post_meta(get_the_ID(), '_saab_publication_publisher', true),
            "isbn" => get_post_meta(get_the_ID(), '_saab_publication_isbn', true),
            "numberOfPages" => get_post_meta(get_the_ID(), '_saab_publication_pages', true),
            "description" => get_the_excerpt(),
            "image" => get_the_post_thumbnail_url(get_the_ID(), 'large'),
        );
        echo '<script type="application/ld+json">' . json_encode($pub_data) . '</script>';
    } elseif (is_singular('screening')) {
        $screening_data = array(
            "@context" => "https://schema.org",
            "@type" => "ScreeningEvent",
            "name" => get_the_title(),
            "startDate" => get_post_meta(get_the_ID(), '_saab_screening_date', true),
            "location" => get_post_meta(get_the_ID(), '_saab_screening_location', true),
            "description" => get_the_excerpt(),
            "image" => get_the_post_thumbnail_url(get_the_ID(), 'large'),
            "performer" => get_post_meta(get_the_ID(), '_saab_screening_film', true),
        );
        echo '<script type="application/ld+json">' . json_encode($screening_data) . '</script>';
    } elseif (is_singular('partner')) {
        $partner_data = array(
            "@context" => "https://schema.org",
            "@type" => "Organization",
            "name" => get_the_title(),
            "url" => get_post_meta(get_the_ID(), '_saab_partner_website', true),
            "description" => get_the_excerpt(),
            "image" => get_the_post_thumbnail_url(get_the_ID(), 'large'),
            "email" => get_post_meta(get_the_ID(), '_saab_partner_contact_email', true),
            "telephone" => get_post_meta(get_the_ID(), '_saab_partner_contact_phone', true),
            "location" => get_post_meta(get_the_ID(), '_saab_partner_location', true),
        );
        echo '<script type="application/ld+json">' . json_encode($partner_data) . '</script>';
    }
}
add_action('wp_head', 'saab_add_json_ld');

// Meta tags for SEO and social sharing
function saab_add_meta_tags() {
    if (is_singular()) {
        global $post;
        $title = get_the_title();
        $desc = get_the_excerpt();
        $img = get_the_post_thumbnail_url($post->ID, 'large');
        $url = get_permalink();
        echo '<meta property="og:title" content="' . esc_attr($title) . '" />\n';
        echo '<meta property="og:description" content="' . esc_attr($desc) . '" />\n';
        if ($img) echo '<meta property="og:image" content="' . esc_url($img) . '" loading="lazy" />\n';
        echo '<meta property="og:url" content="' . esc_url($url) . '" />\n';
        echo '<meta name="twitter:card" content="summary_large_image" />\n';
        echo '<meta name="twitter:title" content="' . esc_attr($title) . '" />\n';
        echo '<meta name="twitter:description" content="' . esc_attr($desc) . '" />\n';
        if ($img) echo '<meta name="twitter:image" content="' . esc_url($img) . '" loading="lazy" />\n';
    }
}
add_action('wp_head', 'saab_add_meta_tags', 5);

// Enhanced image optimization
function saab_add_image_sizes() {
    add_image_size('film-poster', 400, 600, true);
    add_image_size('news-featured', 800, 450, true);
    add_image_size('partner-logo', 300, 150, false);
}
add_action('after_setup_theme', 'saab_add_image_sizes');
<?php
/**
 * Single Training/Workshop Template
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<!-- Hero Section -->
<div class="hero training-hero">
    <?php if (has_post_thumbnail()) : ?>
        <div class="hero-background" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'hero-background')); ?>');"></div>
    <?php endif; ?>
    <div class="hero-content">
        <h1><?php the_title(); ?></h1>
        <?php 
        $date = get_post_meta(get_the_ID(), '_saab_training_workshop_date', true);
        $location = get_post_meta(get_the_ID(), '_saab_training_workshop_location', true);
        if ($date || $location) : ?>
            <p class="hero-subtitle">
                <?php if ($date) echo esc_html($date); ?>
                <?php if ($date && $location) echo ' • '; ?>
                <?php if ($location) echo esc_html($location); ?>
            </p>
        <?php endif; ?>
    </div>
</div>

<div class="section section-white">
    <div class="container">
        <?php if (function_exists('saab_breadcrumbs')) saab_breadcrumbs(); ?>
        <div class="grid grid-12" style="gap: var(--space-60);">
            <!-- Main Content -->
            <div style="grid-column: 1 / span 8;">
                <!-- Workshop Gallery Carousel -->
                <?php 
                $gallery = get_post_meta(get_the_ID(), '_saab_training_workshop_gallery', true);
                if (!empty($gallery)) :
                    $ids = explode(',', $gallery);
                    if (!empty($ids)) : ?>
                    <div class="workshop-gallery-section" style="margin-bottom: var(--space-50);">
                        <h3><?php esc_html_e('Gallery', 'saab'); ?></h3>
                        <div class="workshop-gallery-swiper swiper-container">
                            <div class="swiper-wrapper">
                                <?php foreach ($ids as $img_id) : 
                                    $img_url = wp_get_attachment_image_url($img_id, 'gallery-thumb');
                                    if ($img_url) : ?>
                                        <div class="swiper-slide">
                                            <img src="<?php echo esc_url($img_url); ?>" alt="<?php esc_attr_e('Workshop image', 'saab'); ?>" loading="lazy" />
                                        </div>
                                    <?php endif;
                                endforeach; ?>
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next" tabindex="0" role="button" aria-label="<?php esc_attr_e('Next slide', 'saab'); ?>"></div>
                            <div class="swiper-button-prev" tabindex="0" role="button" aria-label="<?php esc_attr_e('Previous slide', 'saab'); ?>"></div>
                        </div>
                    </div>
                <?php endif; endif; ?>

                <!-- Main Content -->
                <div class="training-content">
                    <?php the_content(); ?>
                </div>

                <!-- Details -->
                <div class="training-details" style="margin-top: var(--space-50);">
                    <h3><?php esc_html_e('Details', 'saab'); ?></h3>
                    <ul>
                        <?php 
                        $time = get_post_meta(get_the_ID(), '_saab_training_workshop_time', true);
                        $duration = get_post_meta(get_the_ID(), '_saab_training_workshop_duration', true);
                        $price = get_post_meta(get_the_ID(), '_saab_training_workshop_price', true);
                        $capacity = get_post_meta(get_the_ID(), '_saab_training_workshop_capacity', true);
                        $project_manager = get_post_meta(get_the_ID(), '_saab_training_workshop_project_manager', true);
                        $trainers = get_post_meta(get_the_ID(), '_saab_training_workshop_trainers', true);
                        $trainer = get_post_meta(get_the_ID(), '_saab_training_workshop_trainer', true);
                        if ($date) echo '<li><strong>' . esc_html__('Date:', 'saab') . '</strong> ' . esc_html($date) . '</li>';
                        if ($time) echo '<li><strong>' . esc_html__('Time:', 'saab') . '</strong> ' . esc_html($time) . '</li>';
                        if ($location) echo '<li><strong>' . esc_html__('Location:', 'saab') . '</strong> ' . esc_html($location) . '</li>';
                        if ($duration) echo '<li><strong>' . esc_html__('Duration:', 'saab') . '</strong> ' . esc_html($duration) . '</li>';
                        if ($price) echo '<li><strong>' . esc_html__('Price:', 'saab') . '</strong> ' . esc_html($price) . '</li>';
                        if ($capacity) echo '<li><strong>' . esc_html__('Capacity:', 'saab') . '</strong> ' . esc_html($capacity) . '</li>';
                        if ($project_manager) echo '<li><strong>' . esc_html__('Project Manager:', 'saab') . '</strong> ' . esc_html($project_manager) . '</li>';
                        if ($trainers) echo '<li><strong>' . esc_html__('Trainers:', 'saab') . '</strong> ' . nl2br(esc_html($trainers)) . '</li>';
                        if ($trainer) echo '<li><strong>' . esc_html__('Trainer:', 'saab') . '</strong> ' . esc_html($trainer) . '</li>';
                        ?>
                    </ul>
                </div>
            </div>
            <!-- Sidebar (optional, can be added as needed) -->
        </div>
    </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?> 
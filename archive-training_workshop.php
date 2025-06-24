<?php
/**
 * Archive template for Trainings and Workshops
 */

get_header(); ?>

<div class="hero archive-hero" style="height: 60vh;">
    <div class="hero-background" style="background-image: url('<?php echo esc_url(get_template_directory_uri() . '/assets/images/trainings-hero.jpg'); ?>');"></div>
    <div class="hero-content">
        <h1><?php esc_html_e('Trainings & Workshops', 'saab'); ?></h1>
        <p class="hero-subtitle"><?php esc_html_e('Explore our educational programs and workshops', 'saab'); ?></p>
    </div>
</div>

<div class="section section-white">
    <div class="container">
        <?php saab_breadcrumbs(); ?>
        <div class="films-grid" id="trainings-grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/film-card'); ?>
                <?php endwhile; ?>
            <?php else : ?>
                <p><?php esc_html_e('No trainings or workshops found.', 'saab'); ?></p>
            <?php endif; ?>
        </div>
        <?php if (function_exists('wp_pagenavi')) : ?>
            <?php wp_pagenavi(); ?>
        <?php else : ?>
            <div class="pagination">
                <?php
                echo paginate_links(array(
                    'prev_text' => '&laquo; ' . __('Previous', 'saab'),
                    'next_text' => __('Next', 'saab') . ' &raquo;',
                    'type' => 'list',
                ));
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?> 
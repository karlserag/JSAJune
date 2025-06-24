<?php
/**
 * Partner Card Template Part
 * Used in: index.php, archive-partner.php
 */

$partner_website = get_post_meta(get_the_ID(), '_saab_partner_website', true);
$partnership_type = get_the_terms(get_the_ID(), 'partnership_type');
?>

<article class="partner-card" data-partner-id="<?php the_ID(); ?>">
    <?php if (has_post_thumbnail()) : ?>
        <?php if ($partner_website) : ?>
            <a href="<?php echo esc_url($partner_website); ?>" 
               target="_blank" 
               rel="noopener noreferrer"
               aria-label="<?php echo esc_attr(sprintf(__('Visit %s website', 'saab'), get_the_title())); ?>">
                <?php the_post_thumbnail('partner-logo', array('class' => 'partner-logo', 'loading' => 'lazy', 'alt' => get_the_title())); ?>
            </a>
        <?php else : ?>
            <?php the_post_thumbnail('partner-logo', array('class' => 'partner-logo', 'loading' => 'lazy', 'alt' => get_the_title())); ?>
        <?php endif; ?>
    <?php endif; ?>
    
    <h4><?php the_title(); ?></h4>
    
    <?php if ($partnership_type && !is_wp_error($partnership_type)) : ?>
        <p class="partnership-type"><?php echo esc_html($partnership_type[0]->name); ?></p>
    <?php endif; ?>
    
    <?php if ($partner_website) : ?>
        <a href="<?php echo esc_url($partner_website); ?>" 
           class="partner-link btn btn-small"
           target="_blank" 
           rel="noopener noreferrer">
            <?php esc_html_e('Visit Website', 'saab'); ?>
        </a>
    <?php endif; ?>
</article>
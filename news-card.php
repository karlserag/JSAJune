<?php
/**
 * News Card Template Part
 * Used in: index.php, archive-news.php
 */

$news_categories = get_the_terms(get_the_ID(), 'news_category');
?>

<article class="news-card" data-news-id="<?php the_ID(); ?>">
    <div class="news-image">
        <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('news-featured', array('loading' => 'lazy', 'alt' => get_the_title())); ?>
            </a>
        <?php endif; ?>
    </div>
    
    <div class="news-content">
        <div class="news-meta">
            <time datetime="<?php echo get_the_date('c'); ?>">
                <?php echo get_the_date(); ?>
            </time>
            <?php if ($news_categories && !is_wp_error($news_categories)) : ?>
                <span class="news-category">
                    • <?php echo esc_html($news_categories[0]->name); ?>
                </span>
            <?php endif; ?>
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
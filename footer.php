</main>

<footer class="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer-content">
            <!-- About Section -->
            <div class="footer-section">
                <h3><?php esc_html_e('About Jocelyne Saab', 'saab'); ?></h3>
                <p><?php echo get_theme_mod('footer_about_text', __('Jocelyne Saab (1948-2019) was a pioneering Lebanese filmmaker and artist whose work explored themes of war, memory, and identity in the Middle East through an innovative cinematic lens.', 'saab')); ?></p>
                
                <?php if (get_theme_mod('footer_about_link')) : ?>
                    <a href="<?php echo esc_url(get_theme_mod('footer_about_link')); ?>" class="btn btn-small">
                        <?php esc_html_e('Learn More', 'saab'); ?>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Quick Links -->
            <div class="footer-section">
                <h3><?php esc_html_e('Explore', 'saab'); ?></h3>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'container' => false,
                    'menu_class' => 'footer-menu',
                    'fallback_cb' => 'saab_footer_fallback_menu',
                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                ));
                ?>
            </div>

            <!-- Archive Links -->
            <div class="footer-section">
                <h3><?php esc_html_e('Archives', 'saab'); ?></h3>
                <ul class="footer-menu">
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('film')); ?>"><?php esc_html_e('Films', 'saab'); ?></a></li>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('news')); ?>"><?php esc_html_e('News', 'saab'); ?></a></li>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('workshop')); ?>"><?php esc_html_e('Workshops', 'saab'); ?></a></li>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('publication')); ?>"><?php esc_html_e('Publications', 'saab'); ?></a></li>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('event')); ?>"><?php esc_html_e('Events', 'saab'); ?></a></li>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('partner')); ?>"><?php esc_html_e('Partners', 'saab'); ?></a></li>
                </ul>
            </div>

            <!-- Newsletter & Social -->
            <div class="footer-section">
                <h3><?php esc_html_e('Stay Connected', 'saab'); ?></h3>
                <p><?php esc_html_e('Subscribe to our newsletter for updates on exhibitions, screenings, and new releases.', 'saab'); ?></p>
                
                <form class="newsletter-form" action="#" method="post" aria-label="<?php esc_attr_e('Newsletter Subscription', 'saab'); ?>">
                    <label for="newsletter-email" class="sr-only"><?php esc_html_e('Email Address', 'saab'); ?></label>
                    <input type="email" 
                           id="newsletter-email"
                           name="newsletter_email"
                           placeholder="<?php esc_attr_e('Your email address', 'saab'); ?>" 
                           required
                           aria-describedby="newsletter-description">
                    <button type="submit" class="btn">
                        <?php esc_html_e('Subscribe', 'saab'); ?>
                    </button>
                </form>
                <p id="newsletter-description" class="sr-only">
                    <?php esc_html_e('Enter your email to receive updates about Jocelyne Saab\'s work and legacy.', 'saab'); ?>
                </p>

                <!-- Social Icons -->
                <div class="social-icons">
                    <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Follow us on Instagram', 'saab'); ?>">
                        <i class="fab fa-instagram" aria-hidden="true"></i>
                    </a>
                    <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Follow us on Facebook', 'saab'); ?>">
                        <i class="fab fa-facebook-f" aria-hidden="true"></i>
                    </a>
                    <a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Follow us on LinkedIn', 'saab'); ?>">
                        <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> 
               <?php bloginfo('name'); ?>. 
               <?php esc_html_e('All rights reserved.', 'saab'); ?>
               <?php if (get_theme_mod('footer_copyright_text')) : ?>
                   <?php echo esc_html(get_theme_mod('footer_copyright_text')); ?>
               <?php endif; ?>
            </p>
            
            <?php if (function_exists('yoast_breadcrumb') || get_theme_mod('show_footer_credits', true)) : ?>
                <p class="footer-credits">
                    <?php esc_html_e('Website by', 'saab'); ?> 
                    <a href="https://github.com/karlserag" target="_blank" rel="noopener">Karl Serag</a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

<?php
/**
 * Footer fallback menu
 */
function saab_footer_fallback_menu() {
    echo '<ul class="footer-menu">';
    echo '<li><a href="' . esc_url(home_url('/about/')) . '">' . esc_html__('About', 'saab') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/contact/')) . '">' . esc_html__('Contact', 'saab') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/privacy/')) . '">' . esc_html__('Privacy', 'saab') . '</a></li>';
    echo '</ul>';
}
?>

</body>
</html>
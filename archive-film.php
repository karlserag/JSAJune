<?php
/**
 * Archive template for Films
 */

get_header(); ?>

<div class="hero archive-hero" style="height: 60vh;">
    <div class="hero-background" style="background-image: url('<?php echo esc_url(get_template_directory_uri() . '/assets/images/films-hero.jpg'); ?>');"></div>
    <div class="hero-content">
        <h1><?php esc_html_e('Films', 'saab'); ?></h1>
        <p class="hero-subtitle"><?php esc_html_e('A cinematic journey through time, memory, and identity', 'saab'); ?></p>
    </div>
</div>

<div class="section section-white">
    <div class="container">
        <?php saab_breadcrumbs(); ?>
        
        <!-- Film Filters -->
        <div class="film-filters">
            <button class="filter-btn active" data-filter="all" data-type="genre">
                <?php esc_html_e('All Films', 'saab'); ?>
            </button>
            
            <?php
            $genres = get_terms(array(
                'taxonomy' => 'genre',
                'hide_empty' => true,
            ));
            
            if ($genres && !is_wp_error($genres)) :
                foreach ($genres as $genre) : ?>
                    <button class="filter-btn" data-filter="<?php echo esc_attr($genre->slug); ?>" data-type="genre">
                        <?php echo esc_html($genre->name); ?>
                    </button>
                <?php endforeach;
            endif; ?>
            
            <!-- Language Filters -->
            <?php
            $languages = get_terms(array(
                'taxonomy' => 'film_language',
                'hide_empty' => true,
            ));
            
            if ($languages && !is_wp_error($languages)) : ?>
                <div class="filter-separator"></div>
                <?php foreach ($languages as $language) : ?>
                    <button class="filter-btn" data-filter="<?php echo esc_attr($language->slug); ?>" data-type="language">
                        <?php echo esc_html($language->name); ?>
                    </button>
                <?php endforeach;
            endif; ?>
        </div>

        <!-- Films Grid -->
        <div class="films-grid" id="films-grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/film-card'); ?>
                <?php endwhile; ?>
            <?php else : ?>
                <p><?php esc_html_e('No films found.', 'saab'); ?></p>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
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

<script>
// Film filtering functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const filmsGrid = document.getElementById('films-grid');
    let currentGenre = 'all';
    let currentLanguage = 'all';
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            const type = this.getAttribute('data-type');
            
            // Update active states within the same type
            document.querySelectorAll(`[data-type="${type}"]`).forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Update current filters
            if (type === 'genre') {
                currentGenre = filter;
            } else if (type === 'language') {
                currentLanguage = filter;
            }
            
            // Show loading state
            filmsGrid.classList.add('loading');
            
            // AJAX request
            const formData = new FormData();
            formData.append('action', 'filter_films');
            formData.append('genre', currentGenre);
            formData.append('language', currentLanguage);
            formData.append('nonce', saabAjax.nonce);
            
            fetch(saabAjax.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                filmsGrid.innerHTML = data;
                filmsGrid.classList.remove('loading');
                
                // Update URL without reload
                const url = new URL(window.location);
                if (currentGenre !== 'all') url.searchParams.set('genre', currentGenre);
                else url.searchParams.delete('genre');
                if (currentLanguage !== 'all') url.searchParams.set('language', currentLanguage);
                else url.searchParams.delete('language');
                window.history.pushState({}, '', url);
            })
            .catch(error => {
                console.error('Error:', error);
                filmsGrid.classList.remove('loading');
            });
        });
    });
});
</script>

<?php get_footer(); ?>
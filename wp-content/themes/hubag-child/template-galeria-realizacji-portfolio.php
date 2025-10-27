<?php
/**
 * Template Name: Galeria realizacji - Portfolio
 * Description: Szablon strony wyświetlający posty typu portfolio w układzie dwukolumnowym
 *
 * @package BeTheme
 * @subpackage Templates
 * @version 1.0
 */

get_header();
?>

<style>
/* Portfolio Grid Styles */
.portfolio-archive-wrapper {
    padding: 40px 0;
}

.portfolio-header {
    margin-bottom: 40px;
    text-align: center;
}

.portfolio-header h1 {
    font-size: 36px;
    font-weight: 700;
    position: relative;
    display: inline-block;
    padding-bottom: 15px;
    margin-bottom: 15px;
}

.portfolio-header h1::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: #ee7700; /* BeTheme primary color */
}

.portfolio-description {
    max-width: 800px;
    margin: 0 auto 30px;
    font-size: 16px;
    line-height: 1.6;
    color: #626262;
}

/* Portfolio Items */
.portfolio-grid {
    margin-bottom: 50px;
}

.portfolio-item {
    margin-bottom: 30px;
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    background-color: #fff;
}

.portfolio-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}

.portfolio-image {
    position: relative;
    overflow: hidden;
    height: 240px;
    background-position: center;
    background-size: cover;
    border-radius: 8px 8px 0 0;
}

.portfolio-image::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.7) 100%);
    z-index: 1;
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.portfolio-item:hover .portfolio-image::before {
    opacity: 1;
}

.portfolio-title {
    position: absolute;
    bottom: 20px;
    left: 20px;
    right: 20px;
    z-index: 2;
    color: #fff;
    font-size: 22px;
    font-weight: 600;
    line-height: 1.3;
    margin: 0;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
    transition: transform 0.3s ease;
}

.portfolio-item:hover .portfolio-title {
    transform: translateY(-5px);
}

.portfolio-content {
    padding: 20px;
}

.portfolio-meta {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    color: #777;
    font-size: 14px;
}

.portfolio-meta i {
    margin-right: 5px;
    color: #555;
}

.portfolio-meta-author {
    margin-right: 15px;
    display: flex;
    align-items: center;
}

.portfolio-meta-date {
    display: flex;
    align-items: center;
}

.portfolio-categories, .portfolio-tags {
    margin-bottom: 12px;
}

.portfolio-categories a, .portfolio-tags a {
    display: inline-block;
    padding: 3px 8px;
    margin-right: 5px;
    margin-bottom: 5px;
    font-size: 12px;
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.portfolio-categories a {
    background-color: rgba(0, 149, 235, 0.1);
    color: #ee7700;
}

.portfolio-categories a:hover {
    background-color: rgba(0, 149, 235, 0.2);
}

.portfolio-tags a {
    background-color: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

.portfolio-tags a:hover {
    background-color: rgba(108, 117, 125, 0.2);
}

.portfolio-excerpt {
    margin-bottom: 15px;
    color: #555;
    line-height: 1.5;
}

.portfolio-readmore {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    background: #ee7700;
    color: #fff !important;
    border-radius: 4px;
    font-weight: 500;
    text-decoration: none !important;
    transition: all 0.3s ease;
}

.portfolio-readmore:hover {
    background: #bf6102;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 149, 235, 0.3);
}

.portfolio-readmore i {
    margin-right: 5px;
    transition: transform 0.3s ease;
}

.portfolio-readmore:hover i {
    transform: translateX(3px);
}

/* Pagination */
.portfolio-pagination {
    margin: 40px 0;
    text-align: center;
}

.portfolio-pagination .page-numbers {
    display: inline-block;
    padding: 8px 15px;
    margin: 0 2px;
    border-radius: 4px;
    background: #f8f9fa;
    color: #495057;
    text-decoration: none;
    transition: all 0.3s ease;
}

.portfolio-pagination .page-numbers:hover {
    background: #e9ecef;
}

.portfolio-pagination .page-numbers.current {
    background: #ee7700;
    color: #fff;
}

/* No posts message */
.no-portfolio-posts {
    text-align: center;
    padding: 50px 20px;
    background: #f8f9fa;
    border-radius: 8px;
    margin: 30px 0;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.no-portfolio-posts h3 {
    margin-bottom: 15px;
    color: #495057;
    font-size: 24px;
}

.no-portfolio-posts p {
    margin-bottom: 20px;
    color: #6c757d;
    font-size: 16px;
}

.back-to-home {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    background: #ee7700;
    color: #fff !important;
    border-radius: 4px;
    text-decoration: none !important;
    transition: all 0.3s ease;
}

.back-to-home:hover {
    background: #bf6102;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 149, 235, 0.3);
}

.back-to-home i {
    margin-right: 8px;
}
</style>

<div id="Content">
    <div class="content_wrapper clearfix">
        <div class="sections_group">
            <div class="entry-content" itemprop="mainContentOfPage">
                <div class="section mcb-section">
                    <div class="section_wrapper mcb-section-inner">
                        <div class="container portfolio-archive-wrapper">
                            
                            <?php
                            // Setup query
                            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                            $args = array(
                                'post_type' => 'portfolio',
                                'posts_per_page' => 6,
                                'paged' => $paged
                            );
                            $portfolio_query = new WP_Query($args);

                            if ($portfolio_query->have_posts()) : ?>
                                <div class="portfolio-grid">
                                    <div class="row">
                                        <?php while ($portfolio_query->have_posts()) : $portfolio_query->the_post(); ?>
                                            <div class="col-md-6">
                                                <article id="post-<?php the_ID(); ?>" <?php post_class('portfolio-item'); ?>>
                                                    <a href="<?php the_permalink(); ?>"><div class="portfolio-image" style="background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>');">
                                                        <h2 class="portfolio-title">
                                                            <?php the_title(); ?>
                                                        </h2>
                                                        </div></a>
                                                    
                                                    <div class="portfolio-content">
                                                        <div class="portfolio-meta">
                                                            <div class="portfolio-meta-author">
                                                                <i class="icon-user"></i> <?php the_author(); ?>
                                                            </div>
                                                            <div class="portfolio-meta-date">
                                                                <i class="icon-clock"></i> <?php echo get_the_date('j M Y'); ?>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="portfolio-categories">
                                                            <?php 
                                                            $categories = get_the_terms(get_the_ID(), 'portfolio-types');
                                                            if ($categories && !is_wp_error($categories)) {
                                                                foreach ($categories as $category) {
                                                                    echo '<a href="' . esc_url(get_term_link($category)) . '"><i class="icon-tag"></i> ' . esc_html($category->name) . '</a>';
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                        
                                                        <div class="portfolio-tags">
                                                            <?php 
                                                            $tags = get_the_terms(get_the_ID(), 'portfolio-tags');
                                                            if ($tags && !is_wp_error($tags)) {
                                                                foreach ($tags as $tag) {
                                                                    echo '<a href="' . esc_url(get_term_link($tag)) . '"><i class="icon-tag-1"></i> ' . esc_html($tag->name) . '</a>';
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                        
                                                        <div class="portfolio-excerpt">
                                                            <?php echo wp_trim_words(get_the_excerpt(), 10); ?>
                                                        </div>
                                                        
                                                        <a href="<?php the_permalink(); ?>" class="portfolio-readmore">
                                                            <i class="icon-right-open"></i> Czytaj więcej
                                                        </a>
                                                    </div>
                                                </article>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                                
                                <div class="portfolio-pagination">
                                    <?php
                                    echo paginate_links(array(
                                        'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                                        'format' => '?paged=%#%',
                                        'current' => max(1, get_query_var('paged')),
                                        'total' => $portfolio_query->max_num_pages,
                                        'prev_text' => '<i class="icon-left-open"></i> Poprzednia',
                                        'next_text' => 'Następna <i class="icon-right-open"></i>',
                                    ));
                                    ?>
                                </div>
                            <?php else : ?>
                                <div class="no-portfolio-posts">
                                    <h3>Przepraszamy, ale aktualnie w tej sekcji nie ma żadnych wpisów.</h3>
                                    <p>Wróć za jakiś czas albo wróć teraz do</p>
                                    <a href="<?php echo esc_url(home_url()); ?>" class="back-to-home">
                                        <i class="icon-home"></i> Strony Głównej
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php wp_reset_postdata(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
<?php
/**
 * Szablon wyświetlania stron (post_type=page) dla kategorii "page_tag"
 * Plik: taxonomy-page_tag.php
 */

get_header(); ?>

<div class="site-content">
    <div class="container">

        <!-- Pętla -->
        <?php if ( have_posts() ) : ?>
            <div class="page-category-list"><div class="row">
                <?php while ( have_posts() ) : the_post(); ?>
                    <div class="col-sm-12 col-md-6"><article id="post-<?php the_ID(); ?>" <?php post_class('page-category-item'); ?>>
                        
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="page-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail( 'full' ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <hr class="hr_excerpt_oferta" style="margin: 0 auto 15px auto; color: #fff; background-color: #fff; height: 3px;">
                        <h2 class="entry-title page-category-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <hr class="hr_excerpt_oferta" style="margin: 0 auto 15px auto; color: #fff; background-color: #fff; height: 3px;">
                        <?php if ( has_excerpt() ) : ?>
                            <div class="entry-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            <hr class="hr_excerpt_oferta" style="margin: 0 auto 15px auto; color: #fff; background-color: #fff; height: 3px;">
                            <a href="<?php the_permalink(); ?>" class="page-category-button"><b>Czytaj więcej:</b> <?php the_title(); ?></a>
                        <?php endif; ?>

                    </article></div>
                <?php endwhile; ?>
            </div>
            </div>

            <!-- Paginacja -->
    <div class="container pagination-container">
        <p><i class="fas fa-info-circle"></i> Paginacja strony | Przejdź <b class="themecolor">do następnej strony</b></p>
    <div class="row">
        <div class="col-sm-12">
            <nav class="pagination-wrapper" aria-label="Nawigacja po stronach">
                <?php
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => '<i class="fas fa-chevron-left"></i> Poprzednia',
                    'next_text' => 'Następna <i class="fas fa-chevron-right"></i>',
                    'screen_reader_text' => ' ',
                ) );
                ?>
            </nav>
        </div>
    </div>
    </div>

        <?php else : ?>
            <p><?php _e( 'Brak stron w tej kategorii.', 'betheme' ); ?></p>
        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>
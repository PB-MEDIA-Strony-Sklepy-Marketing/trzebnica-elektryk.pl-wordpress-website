<?php
/* Template Name: Oferta-not-include-betheme-builder */
// Page template Oferta z wyłączonym wyświetlaniem contentu z BeTheme Builder
get_header();
?>

<div id="Content">
    <div class="content_wrapper clearfix">
        <div class="sections_group">
            <div class="entry-content" itemprop="mainContentOfPage">

                <?php
                // Sekcja hero z miniaturą strony – po ID strony
                $current_id = get_queried_object_id();
                $thumb_url  = '';
                $thumb_alt  = '';
                $thumb_w    = '';
                $thumb_h    = '';

                if ( $current_id && has_post_thumbnail( $current_id ) ) {
                    $thumb_id  = get_post_thumbnail_id( $current_id );
                    $img_full  = wp_get_attachment_image_src( $thumb_id, 'full' );

                    if ( is_array( $img_full ) && ! empty( $img_full[0] ) ) {
                        $thumb_url = $img_full[0];
                        $thumb_w   = ! empty( $img_full[1] ) ? (int) $img_full[1] : '';
                        $thumb_h   = ! empty( $img_full[2] ) ? (int) $img_full[2] : '';
                    }

                    $alt_meta = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true );
                    $thumb_alt = $alt_meta !== '' ? $alt_meta : get_the_title( $current_id );
                }
                ?>

                <?php if ( $thumb_url ) : ?>
                <div class="container text-center">
                    <div class="row align-items-start">
                        <div class="col">
                            <div class="column one single-photo-wrapper image" style="margin-bottom: 60px;">
                                <div class="image_frame scale-with-grid ">
                                    <div class="image_wrapper">
                                        <a href="<?php echo esc_url( $thumb_url ); ?>" rel="lightbox" data-type="image">
                                            <div class="mask"></div>
                                            <img
                                                src="<?php echo esc_url( $thumb_url ); ?>"
                                                class="scale-with-grid wp-post-image" width="1102" alt="<?php echo esc_attr( $thumb_alt ); ?>" loading="lazy">
                                        </a>
                                        <div class="image_links">
                                            <a href="<?php echo esc_url( $thumb_url ); ?>" class="zoom" rel="lightbox" data-type="image">
                                                <i class="icon-search" aria-hidden="true"></i><span class="screen-reader-text"><?php esc_html_e( 'Powiększ obraz', 'betheme' ); ?></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <section style="background-color: #220302; background-repeat:no-repeat;background-position:center top;">
                    <div class="container text-center">
                        <div class="row align-items-start">
                            <div class="col">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="section section-page-footer">
                    <div class="section_wrapper clearfix">
                        <div class="column one page-pager">
                            <?php
                            wp_link_pages( array(
                                'before'         => '<div class="pager-single">',
                                'after'          => '</div>',
                                'link_before'    => '<span>',
                                'link_after'     => '</span>',
                                'next_or_number' => 'number',
                            ) );
                            ?>
                        </div>
                    </div>
                </div>

            </div><!-- .entry-content -->

            <?php if ( function_exists('mfn_opts_get') && mfn_opts_get( 'page-comments' ) ) : ?>
                <div class="section section-page-comments">
                    <div class="section_wrapper clearfix">
                        <div class="column one comments">
                            <?php comments_template( '', true ); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div><!-- .sections_group -->

        <?php get_sidebar(); ?>

    </div><!-- .content_wrapper -->
</div><!-- #Content -->

<?php
// Sekcja z dodatkowymi stronami (losowo, filtr po tagu i kategorii dla post_type=page)
$posts = get_posts( array(
    'post_type'           => 'page',
    'posts_per_page'      => 3,
    'orderby'             => 'rand',
    'ignore_sticky_posts' => true,
    'suppress_filters'    => true,
    'tax_query'           => array(
        'relation' => 'AND',
        array(
            'taxonomy' => 'page_category', // Nasza osobna taksonomia kategorii stron
            'field'    => 'slug',          // Można użyć też 'term_id'
            'terms'    => array( 'oferta-voltmont' ), // ← SLUG kategorii, nie nazwa!
        ),
        array(
            'taxonomy' => 'page_tag',      // Nasza osobna taksonomia tagów stron
            'field'    => 'slug',
            'terms'    => array( 'oferta' ), // ← SLUG tagu
        ),
    ),
) );

if ( $posts ) : ?>
    <div class="section mcb-section mcb-section-87788b067" style="padding-top:60px; padding-bottom:60px; background-color: #220302; background-repeat:no-repeat;background-position:center top;">
        <div class="container text-center">
            <div class="row align-items-start">
                <div class="col">
                    <div class="section_wrapper mcb-section-inner">
                        <div class="wrap mcb-wrap mcb-wrap-do0sd2lws one valign-top clearfix">
                            <div class="mcb-wrap-inner">
                                <div class="column mcb-column mcb-item-lwsxleh16 one-sixth column_placeholder"><div class="placeholder">&nbsp;</div></div>
                                <div class="column mcb-column mcb-item-3kh6g7rcl two-third column_column">
                                    <div class="column_attr clearfix align_center animate bounceIn" data-anim-type="bounceIn">
                                        <h2><?php echo wp_kses_post( sprintf( __( 'Zobacz także <span class="themecolor">inne</span> nasze usługi', 'betheme' ) ) ); ?></h2>
                                        <hr class="no_line" style="margin: 0 auto 15px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wrap mcb-wrap mcb-wrap-nie3iksry one valign-top clearfix">
                            <div class="mcb-wrap-inner">
                                <?php
                                foreach ( $posts as $post ) :
                                    setup_postdata( $post );
                                    $item_id    = get_the_ID();
                                    $item_title = get_the_title( $item_id );
                                    $item_thumb_url = '';
                                    $item_thumb_w   = '';
                                    $item_thumb_h   = '';
                                    $item_alt       = '';

                                    if ( has_post_thumbnail( $item_id ) ) {
                                        $item_thumb_id = get_post_thumbnail_id( $item_id );
                                        $item_img      = wp_get_attachment_image_src( $item_thumb_id, 'large' );

                                        if ( is_array( $item_img ) && ! empty( $item_img[0] ) ) {
                                            $item_thumb_url = $item_img[0];
                                            $item_thumb_w   = ! empty( $item_img[1] ) ? (int) $item_img[1] : '';
                                            $item_thumb_h   = ! empty( $item_img[2] ) ? (int) $item_img[2] : '';
                                        }

                                        $item_alt_meta = get_post_meta( $item_thumb_id, '_wp_attachment_image_alt', true );
                                        $item_alt      = $item_alt_meta !== '' ? $item_alt_meta : $item_title;
                                    }

                                    // Pobierz kategorie i tagi dla page
                                    $categories = get_the_terms( $item_id, 'page_category' );
                                    $tags       = get_the_terms( $item_id, 'page_tag' );
                                ?>
                                <div class="column mcb-column mcb-item-k815gyool one-third column_sliding_box margin_oferta">
                                    <div class="sliding_box">
                                        <div class="animate bounceIn" data-anim-type="bounceIn">
                                            <a href="<?php the_permalink(); ?>">
                                                <div class="photo_wrapper">
                                                    <?php if ( $item_thumb_url ) : ?>
                                                        <img
                                                            class="scale-with-grid efektopacity"
                                                            src="<?php echo esc_url( $item_thumb_url ); ?>"
                                                            <?php if ( $item_thumb_w ) : ?>width="<?php echo esc_attr( $item_thumb_w ); ?>"<?php endif; ?>
                                                            <?php if ( $item_thumb_h ) : ?>height="<?php echo esc_attr( $item_thumb_h ); ?>"<?php endif; ?>
                                                            alt="<?php echo esc_attr( $item_alt ); ?>"
                                                            loading="lazy"
                                                        >
                                                    <?php else : ?>
                                                        <img
                                                            class="scale-with-grid"
                                                            src="<?php echo esc_url( get_template_directory_uri() . '/images/placeholders/placeholder.png' ); ?>"
                                                            alt="<?php echo esc_attr( $item_title ); ?>"
                                                            loading="lazy"
                                                        >
                                                    <?php endif; ?>
                                                </div>
                                                <div class="desc_wrapper">
                                                    <h4 class="bolded_oferta"><?php echo esc_html( $item_title ); ?></h4>
                                                    <!-- Nowa sekcja kategorii i tagów w dwóch kolumnach -->
                                                    <div class="taxonomy-col-cats">
                                                        <span style="color: #fff;"><b>Kategorie: </b></span>
                                                        <?php
                                                        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                                                            foreach ( $categories as $cat ) {
                                                                echo '<span class="taxonomy_excerpt_oferta">' . esc_html( $cat->name ) . '</span>';
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="taxonomy-col-tags">
                                                        <span style="color: #fff;"><b>Tagi: </b></span>
                                                        <?php
                                                        if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
                                                            foreach ( $tags as $tag ) {
                                                                echo '<span class="taxonomy_excerpt_oferta">' . esc_html( $tag->name ) . '</span>';
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                    <hr class="hr_excerpt_oferta">
                                                    <p class="excerpt_petla_oferta"><?php echo excerpt(20); ?></p>
                                                    <a href="<?php the_permalink(); ?>" class="link-1hubag-oferta"><b>Przejdź do:</b> <?php echo esc_html( $item_title ); ?></a>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach;
                                wp_reset_postdata();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php wp_reset_postdata(); ?>
<?php endif; ?>

<section class="section mcb-section mfn-default-section mcb-section-12454ca43 dark" style="padding-top:0px;padding-bottom:60px">
    <div class="container text-center">
        <div class="row align-items-start">
            <div class="col">
                <div class="mcb-background-overlay"></div>
                <div class="section_wrapper mfn-wrapper-for-wraps mcb-section-inner mcb-section-inner-12454ca43 background-zamow-rozmowe-oferta">
                    <div class="wrap mcb-wrap mcb-wrap-8afc50e78 two-fifth tablet-two-fifth laptop-two-fifth mobile-one valign-top vb-item clearfix" data-desktop-col="two-fifth" data-laptop-col="laptop-two-fifth" data-tablet-col="tablet-two-fifth" data-mobile-col="mobile-one" style="padding:;background-color:">
                        <div class="mcb-wrap-inner mcb-wrap-inner-8afc50e78 mfn-module-wrapper mfn-wrapper-for-wraps">
                            <div class="mcb-wrap-background-overlay"></div>
                            <div class="column mcb-column mcb-item-8aa947069 one laptop-one tablet-one mobile-one column_column animate vb-item bounceInDown" style="" data-anim-type="bounceInDown">
                                <div class="mcb-column-inner mfn-module-wrapper mcb-column-inner-8aa947069 mcb-item-column-inner">
                                    <div class="column_attr mfn-inline-editor clearfix" style="">
                                        <h3><strong>Voltmont - Instalacje elektryczne</strong></h3>
                                        <hr class="hr_excerpt_oferta" style="margin: 0 auto 15px auto; color: #fff; background-color: #fff; height: 3px;">
                                        <h5>Voltmont Trzebnica Elektryk<br>ul. Stefana Żeromskiego 1<br> 55-100 Trzebnica</h5>
                                        <hr class="hr_excerpt_oferta" style="margin: 0 auto 15px auto; color: #fff; background-color: #fff; height: 3px;">
                                        <h4><i class="icon-phone"></i><a href="tel:+48691594820" style="color: #fff;"> +48 691 594 820</a></h4>
                                        <h4><i class="icon-mail-line"></i> <a href="mailto:biuro@trzebnica-elektryk.pl" style="color: #fff;">biuro@trzebnica-elektryk.pl</a></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wrap mcb-wrap mcb-wrap-6f20e1bd9 three-fifth tablet-three-fifth laptop-three-fifth mobile-one valign-top vb-item clearfix" data-desktop-col="three-fifth" data-laptop-col="laptop-three-fifth" data-tablet-col="tablet-three-fifth" data-mobile-col="mobile-one" style="padding:;background-color:">
                        <div class="mcb-wrap-inner mcb-wrap-inner-6f20e1bd9 mfn-module-wrapper mfn-wrapper-for-wraps">
                            <div class="mcb-wrap-background-overlay"></div>
                            <div class="column mcb-column mcb-item-3c65087f6 one laptop-one tablet-one mobile-one column_column animate vb-item bounceIn" style="" data-anim-type="bounceIn">
                                <div class="mcb-column-inner mfn-module-wrapper mcb-column-inner-3c65087f6 mcb-item-column-inner">
                                    <div class="column_attr mfn-inline-editor clearfix" style="">
                                        <h2>Zamów <span style="color: #220302;">rozmowę</span> z nami!</h2>
                                        <p>Twój zaufany elektryk w Trzebnicy i całym Dolnym Śląsku. <br>Zadzwoń już dziś i umów się na bezpłatną wycenę!</p>
                                        <hr class="hr_excerpt_oferta" style="margin: 0 auto 15px auto; color: #fff; background-color: #fff; height: 3px;">
                                        <div><?php echo do_shortcode('[contact-form-7 id="9a19c07" title="Zamów rozmowę - formularz kontaktowy oferta page bottom"]'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer();
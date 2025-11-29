<?php 

// get posts
$posts = get_posts(array(
    'post_type'         => 'page',
    'orderby'   => 'ASC',
    'posts_per_page'    => '-1',
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
));

if( $posts ): ?>

<div class="wrap mcb-wrap mcb-wrap-nie3iksry one valign-top clearfix"><div class="mcb-wrap-inner">
    <!-- WRAPPER: użyj tego w miejscu, gdzie masz loop - przykład z 3 kolumnami per row -->
<div class="row g-4 align-items-stretch">

    <?php foreach( $posts as $post ): 

        setup_postdata( $post )

        ?>

  <!-- WordPress loop: przykładowa pojedyncza kolumna -->
  <div class="col-12 col-md-6 col-lg-4 cc-eq-col pb-5">
    <article class="cc-card d-flex flex-column h-100 shadow-sm">
      <a href="<?php the_permalink(); ?>" class="cc-card-link d-flex flex-column h-100 text-decoration-none">
        <div class="cc-photo-wrapper">
          <img
            src="<?php echo esc_url( get_the_post_thumbnail_url() ); ?>"
            alt="<?php echo esc_attr( get_the_title() ); ?>"
            class="cc-img"
          >
        </div>

        <div class="cc-desc-wrapper p-3 mt-auto">
          <h4 class="cc-title h5 mb-2"><?php the_title(); ?></h4>
          <p class="cc-excerpt mb-0 text-muted"><?php echo wp_kses_post( excerpt(18) ); ?></p>
        </div>
      </a>
      <a href="<?php the_permalink(); ?>" class="link-1hubag-oferta ofertatekstprzycisk"><b>Przejdź do:</b> <?php the_title(); ?></a>
    </article>
  </div>
  <!-- /kolumna -->

    <?php endforeach; ?>

   </div></div></div>

    <?php wp_reset_postdata(); ?>

<?php endif; ?> 
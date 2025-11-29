<?php
/**
 * The Header for our theme.
 * Fixed layout: Header -> Subheader -> Content (Natural Block Flow)
 * Uses Bootstrap classes for layout stability.
 *
 * @package Betheme
 * @author Muffin group
 */

// featured image: parallax

$class = '';
$data_parallax = array();

if (mfn_opts_get('img-subheader-attachment') == 'parallax') {
	$class = 'bg-parallax';

	if (mfn_opts_get('parallax') == 'stellar') {
		$data_parallax['key'] = 'data-stellar-background-ratio';
		$data_parallax['value'] = '0.5';
	} else {
		$data_parallax['key'] = 'data-enllax-ratio';
		$data_parallax['value'] = '0.3';
	}
}
?>

<!-- 
    FIX: Wrapper główny.
-->
<div id="Header_wrapper" class="<?php echo esc_attr($class); ?> d-flex flex-column w-100 position-relative" <?php if ($data_parallax) { printf('%s="%.1f"', $data_parallax['key'], $data_parallax['value']);} ?>>

	<?php
		if ('mhb' == mfn_header_style()) {

			// mfn_header action for header builder plugin
			do_action('mfn_header');
			echo mfn_slider();

		} else {

			/* 
             * FIX HEADER:
             * Ustawiamy position: relative, ale najważniejsze to z-index.
             */
			echo '<header id="Header" class="w-100" style="position: relative !important; top: 0; left: 0; z-index: 500; display: block !important;">';

				if ( has_nav_menu('skip-links-menu') ) {
					mfn_wp_accessibility_skip_links();
				}

				if ( 'header-creative' != mfn_header_style(true) ) {
					// NOT header creative
					if( 'header-shop' == mfn_header_style(true) ){
						get_template_part('includes/header', 'style-shop');
					} elseif( 'header-shop-split' == mfn_header_style(true) ){
						get_template_part('includes/header', 'style-shop-split');
					} else {
						get_template_part('includes/header', 'top-area');
					}
				}

				if ( 'header-below' != mfn_header_style(true) ) {
					// header below (Slider inside Header)
					echo mfn_slider();
				}

			echo '</header>';

		}
	?>

	<?php
		/* 
         * LOGIKA SUBHEADER
         */
		if ( 'intro' != get_post_meta( mfn_ID(), 'mfn-post-template', true ) ){
			if( 'all' != mfn_opts_get('subheader') ){
				if( ! get_post_meta( mfn_ID(), 'mfn-post-hide-title', true ) ){

					$subheader_advanced = mfn_opts_get('subheader-advanced');

                    /* 
                     * STYLE FIX - ROZWIĄZANIE PROBLEMU:
                     * Zamiast paddingu, używamy margin-top.
                     * Jeśli Header jest "fixed" (przyklejony), Subheader wejdzie pod niego.
                     * Dlatego dodajemy "margin-top: 160px" (średnia wysokość menu + top bar).
                     * Dodatkowo dodajemy pusty div (spacer), który wypchnie treść w dół.
                     */
                    $subheader_style = 'position: relative; z-index: 10; padding: 170px 0 60px 0 !important; margin-top: 0;';
                    
                    // Jeśli nagłówek jest "sticky" w opcjach motywu, musimy dodać margines
                    // Wymuszamy margines 130px (wysokość headera) na start, żeby odkleić się od góry.
                    $spacer_style = 'display: block; width: 100%; visibility: hidden;';

					if (is_search()) {

                        // Pusty DIV wypychający, jeśli header jest fixed
                        echo '<div class="header-spacer d-none d-lg-block" style="' . $spacer_style . '"></div>';

						echo '<div id="Subheader" class="w-100 d-block" style="' . $subheader_style . '">';
							echo '<div class="container">';
								echo '<div class="column one">';

									if ( ! empty($_GET['s']) ) {
										global $wp_query;
										$total_results = $wp_query->found_posts;
									} else {
										$total_results = 0;
									}

									$translate['search-results'] = mfn_opts_get('translate') ? mfn_opts_get('translate-search-results', 'results found for:') : __('results found for:', 'betheme');
									echo '<h1 class="title">'. esc_html($total_results) .' '. esc_html($translate['search-results']) .' '. ( ! empty($_GET['s']) ? esc_html(stripslashes($_GET['s'])) : '' ) .'</h1>';

								echo '</div>';
							echo '</div>';
						echo '</div>';

					} elseif ( ! mfn_slider_isset() || isset( $subheader_advanced['slider-show'] ) ) {

						// subheader logic (standard BeTheme)
						$subheader_options = mfn_opts_get('subheader');

						if (is_home() && ! get_option('page_for_posts') && ! mfn_opts_get('blog-page')) {
							$subheader_show = false;
						} elseif (is_array($subheader_options) && isset($subheader_options[ 'hide-subheader' ])) {
							$subheader_show = false;
						} elseif (get_post_meta(mfn_ID(), 'mfn-post-hide-title', true)) {
							$subheader_show = false;
						} else {
							$subheader_show = true;
						}

						// title
						if (is_array($subheader_options) && isset($subheader_options[ 'hide-title' ])) {
							$title_show = false;
						} else {
							$title_show = true;
						}

						// breadcrumbs
						if (is_array($subheader_options) && isset($subheader_options[ 'hide-breadcrumbs' ])) {
							$breadcrumbs_show = false;
						} else {
							$breadcrumbs_show = true;
						}

						if (is_array($subheader_advanced) && isset($subheader_advanced[ 'breadcrumbs-link' ])) {
							$breadcrumbs_link = 'has-link';
						} else {
							$breadcrumbs_link = 'no-link';
						}

						// output
						if ($subheader_show) {

                            // Pusty DIV wypychający (widoczny tylko na desktopie gdzie menu jest duże)
                            echo '<div class="header-spacer d-none d-lg-block" style="' . $spacer_style . '"></div>';
                            // Mniejszy spacer na mobile
                            echo '<div class="header-spacer-mobile d-block d-lg-none" style="height: 80px;"></div>';

							echo '<div id="Subheader" class="w-100 d-block" style="' . $subheader_style . '">';
								echo '<div class="container">';
									echo '<div class="column one">';

										if ($title_show) {
											$title_tag = mfn_opts_get('subheader-title-tag', 'h1');
											echo '<'. esc_attr($title_tag) .' class="title">'. wp_kses(mfn_page_title(), mfn_allowed_html()) .'</'. esc_attr($title_tag) .'>';
										}

										if ($breadcrumbs_show) {
											$params = array( 'classes' => $breadcrumbs_link);
											mfn_breadcrumbs($params);
										}

									echo '</div>';
								echo '</div>';
							echo '</div>';

						}
					}

				}
			}
		}
	?>

</div>
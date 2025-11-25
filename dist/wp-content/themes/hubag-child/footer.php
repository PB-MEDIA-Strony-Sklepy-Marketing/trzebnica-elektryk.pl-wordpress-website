<?php
/**
 * The template for displaying the footer.
 *
 * @package Betheme
 * @author Muffin group
 * @link https://muffingroup.com
 */

$back_to_top_class = mfn_opts_get('back-top-top');

if ($back_to_top_class == 'hide') {
	$back_to_top_position = false;
} elseif (strpos($back_to_top_class, 'sticky') !== false) {
	$back_to_top_position = 'body';
} elseif (mfn_opts_get('footer-hide') == 1) {
	$back_to_top_position = 'footer';
} else {
	$back_to_top_position = 'copyright';
}
?>

<?php do_action('mfn_hook_content_after'); ?>

<?php if ('hide' != mfn_opts_get('footer-style')): ?>

	<footer id="Footer" class="clearfix">

		<?php if ($footer_call_to_action = mfn_opts_get('footer-call-to-action')): ?>
		<div class="footer_action">
			<div class="container">
				<div class="column one column_column">
					<?php echo do_shortcode($footer_call_to_action); ?>
				</div>
			</div>
		</div>
		<?php endif; ?>

<div class="headerstopka">

<!--Content before waves-->
<div class="inner-headerstopka flexstopka">
<!--Just the logo.. Don't mind this-->
</div>

<!--Waves Container-->
<div>
<svg class="wavesstopka" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
<defs>
<path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
</defs>
<g class="parallaxstopka">
<use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7)" />
<use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
<use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
<use xlink:href="#gentle-wave" x="48" y="7" fill="#fff" />
</g>
</svg>
</div>
<!--Waves end-->

</div>
<!--Header ends-->


		<?php if (mfn_opts_get('footer-hide') != 1): ?>

			<div class="footer_copy">
				<div class="container">
					<div class="column one">

						<?php
							if ($back_to_top_position == 'copyright') {
								echo '<a id="back_to_top" class="button button_js" href=""><i class="icon-up-open-big"></i></a>';
							}
						?>

						<div class="copyright">
							<?php
								if (mfn_opts_get('footer-copy')) {
									echo do_shortcode(mfn_opts_get('footer-copy'));
								} else {
									echo '&copy; '. esc_html(date('Y')) .' '. esc_html(get_bloginfo('name')) .'. All Rights Reserved. <a target="_blank" rel="dofollow" href="https://www.pbmediaonline.pl">PB MEDIA Studio Strony & Sklepy Internetowe</a>';
								}
							?>
						</div>

						<?php
							if (has_nav_menu('social-menu-bottom')) {
								mfn_wp_social_menu_bottom();
							} else {
								get_template_part('includes/include', 'social');
							}
						?>

					</div>
				</div>
			</div>

		<?php endif; ?>

		<?php
			if ($back_to_top_position == 'footer') {
				echo '<a id="back_to_top" class="button button_js in_footer" href=""><i class="icon-up-open-big"></i></a>';
			}
		?>

	</footer>
<?php endif; ?>

</div>

<?php
	// side slide menu
	if (mfn_opts_get('responsive-mobile-menu')) {
		get_template_part('includes/header', 'side-slide');
	}
?>

<?php
	if ($back_to_top_position == 'body') {
		echo '<a id="back_to_top" class="button button_js '. esc_attr($back_to_top_class) .'" href=""><i class="icon-up-open-big"></i></a>';
	}
?>

<?php if (mfn_opts_get('popup-contact-form')): ?>
	<div id="popup_contact">
		<a class="button button_js" href="#"><i class="<?php echo esc_attr(mfn_opts_get('popup-contact-form-icon', 'icon-mail-line')); ?>"></i></a>
		<div class="popup_contact_wrapper">
			<?php echo do_shortcode(mfn_opts_get('popup-contact-form')); ?>
			<span class="arrow"></span>
		</div>
	</div>
<?php endif; ?>

<?php do_action('mfn_hook_bottom'); ?>

<?php wp_footer(); ?>

</body>
</html>

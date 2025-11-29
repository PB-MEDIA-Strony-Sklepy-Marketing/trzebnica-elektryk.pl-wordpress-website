<?php
/**
 * Custom Shortcodes Collection
 *
 * Reusable shortcodes for Voltmont website
 * Use in posts, pages, and Muffin Builder
 *
 * @package Hubag_Child
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

/**
 * Emergency Contact Bar Shortcode
 *
 * Usage: [voltmont_emergency_contact]
 *
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function voltmont_emergency_contact_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'text' => 'Pogotowie elektryczne 24h',
            'phone' => '+48 691 594 820',
            'show_phone' => 'yes',
            'show_email' => 'yes'
        ),
        $atts,
        'voltmont_emergency_contact'
    );

    ob_start();
    ?>
    <div class="voltmont-emergency-bar">
        <div class="voltmont-emergency-bar__icon">
            <i class="fas fa-bolt" aria-hidden="true"></i>
        </div>
        <div class="voltmont-emergency-bar__content">
            <h3 class="voltmont-emergency-bar__title"><?php echo esc_html($atts['text']); ?></h3>
            <?php if ($atts['show_phone'] === 'yes') : ?>
                <p class="voltmont-emergency-bar__phone">
                    <i class="fas fa-phone"></i>
                    <a href="tel:<?php echo esc_attr(str_replace(' ', '', $atts['phone'])); ?>">
                        <?php echo esc_html($atts['phone']); ?>
                    </a>
                </p>
            <?php endif; ?>
            <?php if ($atts['show_email'] === 'yes') : ?>
                <p class="voltmont-emergency-bar__email">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:biuro@trzebnica-elektryk.pl">
                        biuro@trzebnica-elektryk.pl
                    </a>
                </p>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('voltmont_emergency_contact', 'voltmont_emergency_contact_shortcode');

/**
 * Service Icon Box Shortcode
 *
 * Usage: [voltmont_service_box icon="fas fa-bolt" title="Title" description="Description"]
 *
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function voltmont_service_box_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'icon' => 'fas fa-bolt',
            'title' => 'Tytuł usługi',
            'description' => 'Opis usługi',
            'link' => '',
            'link_text' => 'Dowiedz się więcej'
        ),
        $atts,
        'voltmont_service_box'
    );

    ob_start();
    ?>
    <div class="voltmont-service-box">
        <div class="voltmont-service-box__icon">
            <i class="<?php echo esc_attr($atts['icon']); ?>" aria-hidden="true"></i>
        </div>
        <h3 class="voltmont-service-box__title"><?php echo esc_html($atts['title']); ?></h3>
        <p class="voltmont-service-box__description"><?php echo esc_html($atts['description']); ?></p>
        <?php if (!empty($atts['link'])) : ?>
            <a href="<?php echo esc_url($atts['link']); ?>" class="voltmont-service-box__link">
                <?php echo esc_html($atts['link_text']); ?>
                <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('voltmont_service_box', 'voltmont_service_box_shortcode');

/**
 * Pricing Box Shortcode
 *
 * Usage: [voltmont_pricing title="Pakiet Basic" price="500" period="zł" features="Feature 1,Feature 2,Feature 3"]
 *
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function voltmont_pricing_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'title' => 'Pakiet',
            'price' => '0',
            'period' => 'zł',
            'features' => '',
            'button_text' => 'Zamów teraz',
            'button_link' => '#modal-opened',
            'featured' => 'no'
        ),
        $atts,
        'voltmont_pricing'
    );

    $featured_class = $atts['featured'] === 'yes' ? ' voltmont-pricing--featured' : '';
    $features_array = !empty($atts['features']) ? explode(',', $atts['features']) : array();

    ob_start();
    ?>
    <div class="voltmont-pricing<?php echo esc_attr($featured_class); ?>">
        <?php if ($atts['featured'] === 'yes') : ?>
            <div class="voltmont-pricing__badge">Polecane</div>
        <?php endif; ?>
        <h3 class="voltmont-pricing__title"><?php echo esc_html($atts['title']); ?></h3>
        <div class="voltmont-pricing__price">
            <span class="voltmont-pricing__amount"><?php echo esc_html($atts['price']); ?></span>
            <span class="voltmont-pricing__period"><?php echo esc_html($atts['period']); ?></span>
        </div>
        <?php if (!empty($features_array)) : ?>
            <ul class="voltmont-pricing__features">
                <?php foreach ($features_array as $feature) : ?>
                    <li><?php echo esc_html(trim($feature)); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <a href="<?php echo esc_url($atts['button_link']); ?>" class="voltmont-pricing__button">
            <?php echo esc_html($atts['button_text']); ?>
        </a>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('voltmont_pricing', 'voltmont_pricing_shortcode');

/**
 * Team Member Box Shortcode
 *
 * Usage: [voltmont_team_member name="Jan Kowalski" position="Elektryk" photo="url" phone="+48 123 456 789"]
 *
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function voltmont_team_member_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'name' => 'Imię Nazwisko',
            'position' => 'Stanowisko',
            'photo' => '',
            'phone' => '',
            'email' => ''
        ),
        $atts,
        'voltmont_team_member'
    );

    ob_start();
    ?>
    <div class="voltmont-team-member">
        <?php if (!empty($atts['photo'])) : ?>
            <div class="voltmont-team-member__photo">
                <img src="<?php echo esc_url($atts['photo']); ?>"
                     alt="<?php echo esc_attr($atts['name']); ?>"
                     loading="lazy">
            </div>
        <?php endif; ?>
        <div class="voltmont-team-member__info">
            <h3 class="voltmont-team-member__name"><?php echo esc_html($atts['name']); ?></h3>
            <p class="voltmont-team-member__position"><?php echo esc_html($atts['position']); ?></p>
            <?php if (!empty($atts['phone'])) : ?>
                <p class="voltmont-team-member__phone">
                    <i class="fas fa-phone" aria-hidden="true"></i>
                    <a href="tel:<?php echo esc_attr(str_replace(' ', '', $atts['phone'])); ?>">
                        <?php echo esc_html($atts['phone']); ?>
                    </a>
                </p>
            <?php endif; ?>
            <?php if (!empty($atts['email'])) : ?>
                <p class="voltmont-team-member__email">
                    <i class="fas fa-envelope" aria-hidden="true"></i>
                    <a href="mailto:<?php echo esc_attr($atts['email']); ?>">
                        <?php echo esc_html($atts['email']); ?>
                    </a>
                </p>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('voltmont_team_member', 'voltmont_team_member_shortcode');

/**
 * Stats Counter Shortcode
 *
 * Usage: [voltmont_counter number="500" suffix="+" label="Zadowolonych klientów"]
 *
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function voltmont_counter_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'number' => '0',
            'suffix' => '',
            'label' => 'Licznik',
            'icon' => 'fas fa-check-circle'
        ),
        $atts,
        'voltmont_counter'
    );

    ob_start();
    ?>
    <div class="voltmont-counter">
        <div class="voltmont-counter__icon">
            <i class="<?php echo esc_attr($atts['icon']); ?>" aria-hidden="true"></i>
        </div>
        <div class="voltmont-counter__number" data-count="<?php echo esc_attr($atts['number']); ?>">
            <?php echo esc_html($atts['number']); ?><?php echo esc_html($atts['suffix']); ?>
        </div>
        <p class="voltmont-counter__label"><?php echo esc_html($atts['label']); ?></p>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('voltmont_counter', 'voltmont_counter_shortcode');

/**
 * Business Hours Shortcode
 *
 * Usage: [voltmont_business_hours]
 *
 * @return string HTML output
 */
function voltmont_business_hours_shortcode() {
    $hours = array(
        'Poniedziałek' => '08:00 - 17:00',
        'Wtorek' => '08:00 - 17:00',
        'Środa' => '08:00 - 17:00',
        'Czwartek' => '08:00 - 17:00',
        'Piątek' => '08:00 - 17:00',
        'Sobota' => 'Nieczynne',
        'Niedziela' => 'Nieczynne'
    );

    ob_start();
    ?>
    <div class="voltmont-business-hours">
        <h3 class="voltmont-business-hours__title">Godziny otwarcia</h3>
        <ul class="voltmont-business-hours__list">
            <?php foreach ($hours as $day => $time) : ?>
                <li class="voltmont-business-hours__item">
                    <span class="voltmont-business-hours__day"><?php echo esc_html($day); ?></span>
                    <span class="voltmont-business-hours__time"><?php echo esc_html($time); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
        <p class="voltmont-business-hours__note">
            <i class="fas fa-bolt" aria-hidden="true"></i>
            Pogotowie elektryczne dostępne 24h
        </p>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('voltmont_business_hours', 'voltmont_business_hours_shortcode');

/**
 * Google Maps Shortcode
 *
 * Usage: [voltmont_map height="400"]
 *
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function voltmont_map_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'height' => '400',
            'zoom' => '13'
        ),
        $atts,
        'voltmont_map'
    );

    $map_url = 'https://maps.app.goo.gl/uWX3H3oYdpkx4wAY6';

    ob_start();
    ?>
    <div class="voltmont-map" style="height: <?php echo esc_attr($atts['height']); ?>px;">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2500.123!2d17.0628!3d51.3094!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNTHCsDE4JzMzLjgiTiAxN8KwMDMnNDYuMSJF!5e0!3m2!1spl!2spl!4v1234567890"
            width="100%"
            height="100%"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Mapa lokalizacji Voltmont - Instalacje Elektryczne">
        </iframe>
        <a href="<?php echo esc_url($map_url); ?>" class="voltmont-map__link" target="_blank" rel="noopener">
            Otwórz w Google Maps
        </a>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('voltmont_map', 'voltmont_map_shortcode');

/**
 * Testimonial Shortcode
 *
 * Usage: [voltmont_testimonial author="Jan Kowalski" rating="5" content="Treść opinii"]
 *
 * @param array $atts Shortcode attributes
 * @param string $content Shortcode content
 * @return string HTML output
 */
function voltmont_testimonial_shortcode($atts, $content = '') {
    $atts = shortcode_atts(
        array(
            'author' => 'Klient',
            'position' => '',
            'rating' => '5',
            'date' => ''
        ),
        $atts,
        'voltmont_testimonial'
    );

    $rating = min(5, max(1, intval($atts['rating'])));

    ob_start();
    ?>
    <div class="voltmont-testimonial">
        <div class="voltmont-testimonial__rating">
            <?php for ($i = 0; $i < 5; $i++) : ?>
                <i class="<?php echo $i < $rating ? 'fas' : 'far'; ?> fa-star" aria-hidden="true"></i>
            <?php endfor; ?>
        </div>
        <div class="voltmont-testimonial__content">
            <?php echo wp_kses_post(wpautop($content)); ?>
        </div>
        <div class="voltmont-testimonial__author">
            <strong><?php echo esc_html($atts['author']); ?></strong>
            <?php if (!empty($atts['position'])) : ?>
                <span class="voltmont-testimonial__position"><?php echo esc_html($atts['position']); ?></span>
            <?php endif; ?>
            <?php if (!empty($atts['date'])) : ?>
                <span class="voltmont-testimonial__date"><?php echo esc_html($atts['date']); ?></span>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('voltmont_testimonial', 'voltmont_testimonial_shortcode');

/**
 * CTA Button Shortcode
 *
 * Usage: [voltmont_cta_button text="Skontaktuj się" link="#modal-opened" icon="fas fa-phone"]
 *
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function voltmont_cta_button_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'text' => 'Skontaktuj się z nami',
            'link' => '#modal-opened',
            'icon' => 'fas fa-phone',
            'style' => 'primary', // primary, secondary, outline
            'size' => 'normal' // small, normal, large
        ),
        $atts,
        'voltmont_cta_button'
    );

    $classes = 'voltmont-cta-button';
    $classes .= ' voltmont-cta-button--' . sanitize_html_class($atts['style']);
    $classes .= ' voltmont-cta-button--' . sanitize_html_class($atts['size']);

    ob_start();
    ?>
    <a href="<?php echo esc_url($atts['link']); ?>" class="<?php echo esc_attr($classes); ?>">
        <?php if (!empty($atts['icon'])) : ?>
            <i class="<?php echo esc_attr($atts['icon']); ?>" aria-hidden="true"></i>
        <?php endif; ?>
        <span><?php echo esc_html($atts['text']); ?></span>
    </a>
    <?php
    return ob_get_clean();
}
add_shortcode('voltmont_cta_button', 'voltmont_cta_button_shortcode');

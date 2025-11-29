<?php
/**
 * Voltmont Cache Cleaner
 *
 * Czyści wszystkie cache Voltmont po aktualizacji plików inc/
 *
 * UŻYCIE:
 * 1. Załaduj ten plik przez WP Admin -> Wygląd -> Edytor motywów
 * 2. Lub uruchom przez WP-CLI: wp eval-file clear-voltmont-cache.php
 * 3. Lub dodaj jako stronę administracyjną (kod poniżej)
 *
 * BEZPIECZEŃSTWO: Usuń ten plik po użyciu lub zabezpiecz hasłem!
 *
 * @package Hubag_Child
 * @since 2.0.0
 */

// Zabezpieczenie - tylko dla administratorów
if (!defined('ABSPATH')) {
    // Jeśli uruchamiasz bezpośrednio, załaduj WordPress
    require_once('../../../../wp-load.php');
}

// Sprawdź uprawnienia
if (!current_user_can('manage_options')) {
    wp_die('Brak uprawnień do czyszczenia cache.');
}

/**
 * Wyczyść wszystkie cache Voltmont
 */
function voltmont_manual_cache_clear() {
    global $wpdb;

    echo '<h1>Czyszczenie cache Voltmont</h1>';
    echo '<div style="max-width: 800px; margin: 20px auto; padding: 20px; background: #f0f0f1; border-left: 4px solid #4d81e9;">';

    // 1. Usuń transients Voltmont
    echo '<h2>1. Usuwanie transientów Voltmont...</h2>';
    $deleted_transients = $wpdb->query(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_voltmont_%' OR option_name LIKE '_transient_timeout_voltmont_%'"
    );
    echo "<p>✅ Usunięto {$deleted_transients} transientów Voltmont</p>";

    // 2. Wyczyść object cache
    echo '<h2>2. Czyszczenie object cache...</h2>';
    $flushed = wp_cache_flush();
    echo $flushed ? '<p>✅ Object cache wyczyszczony</p>' : '<p>⚠️ Object cache nie wymaga czyszczenia</p>';

    // 3. Wyczyść cache motywu BeTheme (jeśli dostępne)
    echo '<h2>3. Czyszczenie cache BeTheme...</h2>';
    if (function_exists('mfn_clear_cache')) {
        mfn_clear_cache();
        echo '<p>✅ Cache BeTheme wyczyszczony</p>';
    } else {
        echo '<p>ℹ️ BeTheme cache nie wymaga czyszczenia</p>';
    }

    // 4. Wyczyść opcache PHP (jeśli dostępne)
    echo '<h2>4. Czyszczenie OPcache PHP...</h2>';
    if (function_exists('opcache_reset')) {
        opcache_reset();
        echo '<p>✅ OPcache PHP wyczyszczony</p>';
    } else {
        echo '<p>ℹ️ OPcache nie jest włączony</p>';
    }

    // 5. Wyczyść cache wtyczek popularnych
    echo '<h2>5. Czyszczenie cache wtyczek...</h2>';

    // WP Super Cache
    if (function_exists('wp_cache_clear_cache')) {
        wp_cache_clear_cache();
        echo '<p>✅ WP Super Cache wyczyszczony</p>';
    }

    // W3 Total Cache
    if (function_exists('w3tc_flush_all')) {
        w3tc_flush_all();
        echo '<p>✅ W3 Total Cache wyczyszczony</p>';
    }

    // WP Rocket
    if (function_exists('rocket_clean_domain')) {
        rocket_clean_domain();
        echo '<p>✅ WP Rocket cache wyczyszczony</p>';
    }

    // LiteSpeed Cache
    if (class_exists('LiteSpeed_Cache_API') && method_exists('LiteSpeed_Cache_API', 'purge_all')) {
        LiteSpeed_Cache_API::purge_all();
        echo '<p>✅ LiteSpeed Cache wyczyszczony</p>';
    }

    // 6. Flush rewrite rules
    echo '<h2>6. Odświeżanie reguł permalink...</h2>';
    flush_rewrite_rules();
    echo '<p>✅ Reguły permalink odświeżone</p>';

    // 7. Podsumowanie
    echo '<h2>✅ Zakończono czyszczenie cache!</h2>';
    echo '<p><strong>Następne kroki:</strong></p>';
    echo '<ul>';
    echo '<li>Sprawdź czy strona działa poprawnie</li>';
    echo '<li>Wyczyść cache przeglądarki (Ctrl+F5)</li>';
    echo '<li>Sprawdź schema.org w Google Rich Results Test</li>';
    echo '<li>Przetestuj shortcodes na testowej stronie</li>';
    echo '<li><strong style="color: #d63638;">USUŃ ten plik (clear-voltmont-cache.php) ze względów bezpieczeństwa!</strong></li>';
    echo '</ul>';

    echo '</div>';
}

// Uruchom czyszczenie
voltmont_manual_cache_clear();

/**
 * OPCJONALNIE: Dodaj jako stronę administracyjną
 *
 * Odkomentuj poniższy kod i dodaj do functions.php:
 * require_once get_stylesheet_directory() . '/clear-voltmont-cache.php';
 */

/*
function voltmont_add_cache_clear_page() {
    add_management_page(
        'Wyczyść Cache Voltmont',
        'Wyczyść Cache Voltmont',
        'manage_options',
        'voltmont-clear-cache',
        'voltmont_manual_cache_clear'
    );
}
add_action('admin_menu', 'voltmont_add_cache_clear_page');
*/

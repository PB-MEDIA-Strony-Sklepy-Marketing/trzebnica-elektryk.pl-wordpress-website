<?php
/**
 * Contact Form 7 Custom Validation
 *
 * Enhanced validation rules for Contact Form 7
 * Includes phone number, Polish postal code, NIP validation
 *
 * @package Hubag_Child
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

/**
 * Validate Polish phone number
 *
 * @param WPCF7_Validation $result Validation result
 * @param WPCF7_FormTag $tag Form tag
 * @return WPCF7_Validation Modified validation result
 */
function voltmont_validate_phone($result, $tag) {
    $name = $tag->name;

    if (isset($_POST[$name]) && !empty($_POST[$name])) {
        $phone = sanitize_text_field($_POST[$name]);

        // Remove spaces, dashes, parentheses
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);

        // Check if it matches Polish phone format
        // Allows: +48 xxx xxx xxx, 48 xxx xxx xxx, xxx xxx xxx, xxxxxxxxx
        $pattern = '/^(\+48|48)?[1-9]\d{8}$/';

        if (!preg_match($pattern, $phone)) {
            $result->invalidate($tag, 'Nieprawidłowy numer telefonu. Podaj polski numer telefonu (9 cyfr).');
        }
    }

    return $result;
}
add_filter('wpcf7_validate_tel', 'voltmont_validate_phone', 10, 2);
add_filter('wpcf7_validate_tel*', 'voltmont_validate_phone', 10, 2);

/**
 * Validate Polish postal code
 *
 * @param WPCF7_Validation $result Validation result
 * @param WPCF7_FormTag $tag Form tag
 * @return WPCF7_Validation Modified validation result
 */
function voltmont_validate_postal_code($result, $tag) {
    $name = $tag->name;

    // Only validate if field name contains 'postal' or 'kod'
    if (stripos($name, 'postal') === false && stripos($name, 'kod') === false) {
        return $result;
    }

    if (isset($_POST[$name]) && !empty($_POST[$name])) {
        $postal_code = sanitize_text_field($_POST[$name]);

        // Polish postal code format: XX-XXX
        $pattern = '/^\d{2}-\d{3}$/';

        if (!preg_match($pattern, $postal_code)) {
            $result->invalidate($tag, 'Nieprawidłowy kod pocztowy. Użyj formatu: XX-XXX (np. 55-100).');
        }
    }

    return $result;
}
add_filter('wpcf7_validate_text', 'voltmont_validate_postal_code', 10, 2);
add_filter('wpcf7_validate_text*', 'voltmont_validate_postal_code', 10, 2);

/**
 * Validate Polish NIP (tax identification number)
 *
 * @param WPCF7_Validation $result Validation result
 * @param WPCF7_FormTag $tag Form tag
 * @return WPCF7_Validation Modified validation result
 */
function voltmont_validate_nip($result, $tag) {
    $name = $tag->name;

    // Only validate if field name contains 'nip'
    if (stripos($name, 'nip') === false) {
        return $result;
    }

    if (isset($_POST[$name]) && !empty($_POST[$name])) {
        $nip = sanitize_text_field($_POST[$name]);

        // Remove spaces and dashes
        $nip = preg_replace('/[\s\-]/', '', $nip);

        // NIP must be 10 digits
        if (!preg_match('/^\d{10}$/', $nip)) {
            $result->invalidate($tag, 'NIP musi składać się z 10 cyfr.');
            return $result;
        }

        // Validate NIP checksum
        $weights = array(6, 5, 7, 2, 3, 4, 5, 6, 7);
        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += $nip[$i] * $weights[$i];
        }

        $checksum = $sum % 11;

        if ($checksum != $nip[9]) {
            $result->invalidate($tag, 'Nieprawidłowy numer NIP. Sprawdź poprawność wprowadzonych danych.');
        }
    }

    return $result;
}
add_filter('wpcf7_validate_text', 'voltmont_validate_nip', 10, 2);
add_filter('wpcf7_validate_text*', 'voltmont_validate_nip', 10, 2);

/**
 * Enhanced email validation (block disposable emails)
 *
 * @param WPCF7_Validation $result Validation result
 * @param WPCF7_FormTag $tag Form tag
 * @return WPCF7_Validation Modified validation result
 */
function voltmont_validate_email($result, $tag) {
    $name = $tag->name;

    if (isset($_POST[$name]) && !empty($_POST[$name])) {
        $email = sanitize_email($_POST[$name]);

        // List of disposable email domains
        $disposable_domains = array(
            'tempmail.com',
            '10minutemail.com',
            'guerrillamail.com',
            'mailinator.com',
            'throwaway.email',
            'temp-mail.org'
        );

        $domain = substr(strrchr($email, "@"), 1);

        if (in_array($domain, $disposable_domains)) {
            $result->invalidate($tag, 'Proszę użyć stałego adresu e-mail (wykryto tymczasową skrzynkę).');
        }

        // Check for common typos in popular domains
        $typo_domains = array(
            'gmail.co' => 'gmail.com',
            'gmial.com' => 'gmail.com',
            'gmai.com' => 'gmail.com',
            'outlok.com' => 'outlook.com',
            'outloo.com' => 'outlook.com',
            'wp.pl' => 'wp.pl',
            'o2.pl' => 'o2.pl'
        );

        if (isset($typo_domains[$domain])) {
            $result->invalidate($tag, 'Czy chodziło Ci o adres: ' . str_replace($domain, $typo_domains[$domain], $email) . '?');
        }
    }

    return $result;
}
add_filter('wpcf7_validate_email', 'voltmont_validate_email', 10, 2);
add_filter('wpcf7_validate_email*', 'voltmont_validate_email', 10, 2);

/**
 * Validate message length
 *
 * @param WPCF7_Validation $result Validation result
 * @param WPCF7_FormTag $tag Form tag
 * @return WPCF7_Validation Modified validation result
 */
function voltmont_validate_message_length($result, $tag) {
    $name = $tag->name;

    // Only validate if field name contains 'message' or 'wiadomosc'
    if (stripos($name, 'message') === false && stripos($name, 'wiadomosc') === false) {
        return $result;
    }

    if (isset($_POST[$name]) && !empty($_POST[$name])) {
        $message = sanitize_textarea_field($_POST[$name]);

        // Minimum 20 characters
        if (strlen($message) < 20) {
            $result->invalidate($tag, 'Wiadomość jest zbyt krótka. Napisz przynajmniej 20 znaków.');
        }

        // Maximum 2000 characters
        if (strlen($message) > 2000) {
            $result->invalidate($tag, 'Wiadomość jest zbyt długa. Maksymalnie 2000 znaków.');
        }
    }

    return $result;
}
add_filter('wpcf7_validate_textarea', 'voltmont_validate_message_length', 10, 2);
add_filter('wpcf7_validate_textarea*', 'voltmont_validate_message_length', 10, 2);

/**
 * Honeypot anti-spam field
 * Add hidden field to your CF7 form: [text voltmont_honeypot class:hidden]
 */
function voltmont_honeypot_validation($result, $tag) {
    $name = $tag->name;

    if ($name === 'voltmont_honeypot' && !empty($_POST[$name])) {
        // Bot filled the honeypot field
        $result->invalidate($tag, 'Wykryto spam. Jeśli to błąd, skontaktuj się telefonicznie.');
    }

    return $result;
}
add_filter('wpcf7_validate_text', 'voltmont_honeypot_validation', 10, 2);

/**
 * Time-based anti-spam (form must be filled at least 3 seconds)
 */
function voltmont_time_based_validation() {
    if (!isset($_POST['voltmont_form_time'])) {
        return;
    }

    $form_time = intval($_POST['voltmont_form_time']);
    $current_time = time();

    // Form submitted too quickly (less than 3 seconds)
    if (($current_time - $form_time) < 3) {
        add_filter('wpcf7_spam', '__return_true');
    }
}
add_action('wpcf7_before_send_mail', 'voltmont_time_based_validation');

/**
 * Add hidden timestamp field to CF7 forms
 */
function voltmont_add_hidden_timestamp($form) {
    $form .= '<input type="hidden" name="voltmont_form_time" value="' . time() . '">';
    return $form;
}
add_filter('wpcf7_form_elements', 'voltmont_add_hidden_timestamp');

/**
 * Custom error messages (Polish)
 */
function voltmont_custom_error_messages() {
    return array(
        'invalid_required' => 'To pole jest wymagane.',
        'invalid_email' => 'Nieprawidłowy adres e-mail.',
        'invalid_tel' => 'Nieprawidłowy numer telefonu.',
        'invalid_too_long' => 'Pole jest zbyt długie.',
        'invalid_too_short' => 'Pole jest zbyt krótkie.',
        'acceptance_missing' => 'Musisz zaakceptować warunki.',
        'spam' => 'Wykryto spam. Spróbuj ponownie lub skontaktuj się telefonicznie.',
        'validation_error' => 'Wystąpił błąd walidacji. Sprawdź poprawność wypełnionych pól.',
        'mail_sent_ok' => 'Dziękujemy! Twoja wiadomość została wysłana. Odpowiemy najszybciej jak to możliwe.',
        'mail_sent_ng' => 'Wystąpił błąd podczas wysyłania wiadomości. Spróbuj ponownie lub zadzwoń: +48 691 594 820.'
    );
}

/**
 * Apply custom error messages
 */
function voltmont_apply_custom_messages($messages) {
    $custom = voltmont_custom_error_messages();
    return array_merge($messages, $custom);
}
add_filter('wpcf7_messages', 'voltmont_apply_custom_messages');

/**
 * Add UTM parameters to submission
 */
function voltmont_add_utm_to_mail($form_tag) {
    $mail = $form_tag->prop('mail');

    // Get UTM parameters
    $utm_source = isset($_COOKIE['voltmont_utm_source']) ? sanitize_text_field($_COOKIE['voltmont_utm_source']) : 'direct';
    $utm_medium = isset($_COOKIE['voltmont_utm_medium']) ? sanitize_text_field($_COOKIE['voltmont_utm_medium']) : 'none';
    $utm_campaign = isset($_COOKIE['voltmont_utm_campaign']) ? sanitize_text_field($_COOKIE['voltmont_utm_campaign']) : 'none';

    // Add to email body
    $mail['body'] .= "\n\n---\nŹródło: " . $utm_source;
    $mail['body'] .= "\nMedium: " . $utm_medium;
    $mail['body'] .= "\nKampania: " . $utm_campaign;

    $form_tag->set_properties(array('mail' => $mail));

    return $form_tag;
}
add_filter('wpcf7_contact_form', 'voltmont_add_utm_to_mail');

/**
 * Store UTM parameters in cookies
 */
function voltmont_store_utm_params() {
    if (isset($_GET['utm_source'])) {
        setcookie('voltmont_utm_source', sanitize_text_field($_GET['utm_source']), time() + (30 * 24 * 60 * 60), '/');
    }
    if (isset($_GET['utm_medium'])) {
        setcookie('voltmont_utm_medium', sanitize_text_field($_GET['utm_medium']), time() + (30 * 24 * 60 * 60), '/');
    }
    if (isset($_GET['utm_campaign'])) {
        setcookie('voltmont_utm_campaign', sanitize_text_field($_GET['utm_campaign']), time() + (30 * 24 * 60 * 60), '/');
    }
}
add_action('init', 'voltmont_store_utm_params');

<?php
/**
 * FAQ Schema Implementation
 *
 * Automatically generates FAQ schema from custom FAQ post type or ACF fields
 *
 * @package Hubag_Child
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

/**
 * Generate FAQ schema for page
 *
 * @param array $faqs Array of FAQ items [question, answer]
 * @return array FAQPage schema
 */
function voltmont_get_faq_schema($faqs) {
    if (empty($faqs)) {
        return null;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array()
    );

    foreach ($faqs as $faq) {
        if (empty($faq['question']) || empty($faq['answer'])) {
            continue;
        }

        $schema['mainEntity'][] = array(
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => $faq['answer']
            )
        );
    }

    return !empty($schema['mainEntity']) ? $schema : null;
}

/**
 * Extract FAQs from page content (HTML format)
 *
 * Looks for patterns like:
 * <h3>Question?</h3><p>Answer</p>
 * or FAQ blocks
 *
 * @return array|null FAQ items or null
 */
function voltmont_extract_faqs_from_content() {
    global $post;

    if (!$post) {
        return null;
    }

    $content = $post->post_content;
    $faqs = array();

    // Pattern 1: BeTheme FAQ accordion/toggle
    preg_match_all('/\[accordion[^\]]*\](.*?)\[\/accordion\]/s', $content, $accordion_matches);
    if (!empty($accordion_matches[1])) {
        foreach ($accordion_matches[1] as $accordion_content) {
            preg_match_all('/\[accordion_item title="([^"]+)"\](.*?)\[\/accordion_item\]/s', $accordion_content, $item_matches);

            if (!empty($item_matches[1])) {
                for ($i = 0; $i < count($item_matches[1]); $i++) {
                    $faqs[] = array(
                        'question' => wp_strip_all_tags($item_matches[1][$i]),
                        'answer' => wp_strip_all_tags($item_matches[2][$i])
                    );
                }
            }
        }
    }

    // Pattern 2: HTML heading + paragraph
    if (empty($faqs)) {
        preg_match_all('/<h[3-4][^>]*>(.*?)<\/h[3-4]>\s*<p>(.*?)<\/p>/is', $content, $html_matches);

        if (!empty($html_matches[1])) {
            for ($i = 0; $i < count($html_matches[1]); $i++) {
                $question = wp_strip_all_tags($html_matches[1][$i]);
                $answer = wp_strip_all_tags($html_matches[2][$i]);

                // Only include if question ends with "?"
                if (strpos($question, '?') !== false) {
                    $faqs[] = array(
                        'question' => $question,
                        'answer' => $answer
                    );
                }
            }
        }
    }

    return !empty($faqs) ? $faqs : null;
}

/**
 * Output FAQ schema in head
 */
function voltmont_output_faq_schema() {
    if (!is_singular()) {
        return;
    }

    // Try to extract FAQs from content
    $faqs = voltmont_extract_faqs_from_content();

    // If no FAQs found, check for custom meta
    if (empty($faqs)) {
        global $post;
        $custom_faqs = get_post_meta($post->ID, '_voltmont_faqs', true);

        if (!empty($custom_faqs) && is_array($custom_faqs)) {
            $faqs = $custom_faqs;
        }
    }

    // Generate and output schema
    if (!empty($faqs)) {
        $schema = voltmont_get_faq_schema($faqs);

        if ($schema) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
        }
    }
}
add_action('wp_head', 'voltmont_output_faq_schema', 8);

/**
 * Add FAQ meta box to pages
 */
function voltmont_add_faq_meta_box() {
    add_meta_box(
        'voltmont_faq_meta',
        'FAQ dla Schema.org',
        'voltmont_render_faq_meta_box',
        'page',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'voltmont_add_faq_meta_box');

/**
 * Render FAQ meta box
 *
 * @param WP_Post $post Current post object
 */
function voltmont_render_faq_meta_box($post) {
    wp_nonce_field('voltmont_save_faq_meta', 'voltmont_faq_nonce');

    $faqs = get_post_meta($post->ID, '_voltmont_faqs', true);
    $faqs = is_array($faqs) ? $faqs : array();

    ?>
    <div id="voltmont-faq-container">
        <p class="description">
            Dodaj najczęściej zadawane pytania i odpowiedzi. Automatycznie wygenerują schemat FAQ dla Google.
        </p>

        <div id="voltmont-faq-items">
            <?php
            if (!empty($faqs)) {
                foreach ($faqs as $index => $faq) {
                    voltmont_render_faq_item($index, $faq);
                }
            } else {
                voltmont_render_faq_item(0, array('question' => '', 'answer' => ''));
            }
            ?>
        </div>

        <button type="button" class="button button-secondary" id="voltmont-add-faq">
            <span class="dashicons dashicons-plus-alt"></span> Dodaj pytanie
        </button>
    </div>

    <script>
    jQuery(document).ready(function($) {
        var faqIndex = <?php echo count($faqs); ?>;

        $('#voltmont-add-faq').on('click', function() {
            var template = `
                <div class="voltmont-faq-item" style="border: 1px solid #ccc; padding: 15px; margin: 10px 0; background: #f9f9f9;">
                    <p>
                        <label><strong>Pytanie:</strong></label><br>
                        <input type="text" name="voltmont_faq_question[]" class="widefat" placeholder="Jakie są koszty instalacji elektrycznej?" required>
                    </p>
                    <p>
                        <label><strong>Odpowiedź:</strong></label><br>
                        <textarea name="voltmont_faq_answer[]" class="widefat" rows="3" placeholder="Koszty zależą od..." required></textarea>
                    </p>
                    <button type="button" class="button button-link-delete voltmont-remove-faq">Usuń</button>
                </div>
            `;
            $('#voltmont-faq-items').append(template);
            faqIndex++;
        });

        $(document).on('click', '.voltmont-remove-faq', function() {
            $(this).closest('.voltmont-faq-item').remove();
        });
    });
    </script>

    <style>
    .voltmont-faq-item { position: relative; }
    .voltmont-remove-faq { color: #a00; }
    .voltmont-remove-faq:hover { color: #dc3232; }
    </style>
    <?php
}

/**
 * Render single FAQ item
 *
 * @param int $index Item index
 * @param array $faq FAQ data
 */
function voltmont_render_faq_item($index, $faq) {
    ?>
    <div class="voltmont-faq-item" style="border: 1px solid #ccc; padding: 15px; margin: 10px 0; background: #f9f9f9;">
        <p>
            <label><strong>Pytanie:</strong></label><br>
            <input type="text"
                   name="voltmont_faq_question[]"
                   value="<?php echo esc_attr($faq['question']); ?>"
                   class="widefat"
                   placeholder="Jakie są koszty instalacji elektrycznej?"
                   required>
        </p>
        <p>
            <label><strong>Odpowiedź:</strong></label><br>
            <textarea name="voltmont_faq_answer[]"
                      class="widefat"
                      rows="3"
                      placeholder="Koszty zależą od..."
                      required><?php echo esc_textarea($faq['answer']); ?></textarea>
        </p>
        <button type="button" class="button button-link-delete voltmont-remove-faq">Usuń</button>
    </div>
    <?php
}

/**
 * Save FAQ meta
 *
 * @param int $post_id Post ID
 */
function voltmont_save_faq_meta($post_id) {
    // Security checks
    if (!isset($_POST['voltmont_faq_nonce']) || !wp_verify_nonce($_POST['voltmont_faq_nonce'], 'voltmont_save_faq_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save FAQs
    $faqs = array();

    if (isset($_POST['voltmont_faq_question']) && isset($_POST['voltmont_faq_answer'])) {
        $questions = $_POST['voltmont_faq_question'];
        $answers = $_POST['voltmont_faq_answer'];

        foreach ($questions as $index => $question) {
            if (!empty($question) && !empty($answers[$index])) {
                $faqs[] = array(
                    'question' => sanitize_text_field($question),
                    'answer' => sanitize_textarea_field($answers[$index])
                );
            }
        }
    }

    update_post_meta($post_id, '_voltmont_faqs', $faqs);
}
add_action('save_post', 'voltmont_save_faq_meta');

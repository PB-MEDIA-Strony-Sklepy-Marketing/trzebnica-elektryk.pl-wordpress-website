<?php
/**
 * Template Name: Sekcja Portfolio Slider (Premium Tabs + Grid)
 * Description: Slider 3 głównych realizacji + Grid pozostałych projektów.
 */

// 1. GŁÓWNE ZAPYTANIE (Slider - 3 losowe)
$args_main = [
    'post_type'      => 'portfolio',
    'posts_per_page' => 3,
    'orderby'        => 'rand',
    'post_status'    => 'publish',
];

$portfolio_query = new WP_Query($args_main);

// Zbieramy ID postów wyświetlonych w sliderze, aby je wykluczyć z dolnego grida
$excluded_ids = [];
if ($portfolio_query->have_posts()) {
    foreach ($portfolio_query->posts as $post_obj) {
        $excluded_ids[] = $post_obj->ID;
    }
}

// Ikony dla 3 głównych slotów
$slot_icons = [
    'fas fa-drafting-compass',
    'fas fa-hard-hat',
    'fas fa-check-circle'
];

// Ikony losowe dla dolnego grida (rotacja)
$grid_icons = [
    'fas fa-plug', 'fas fa-solar-panel', 'fas fa-charging-station', 
    'fas fa-network-wired', 'fas fa-video', 'fas fa-server'
];
?>

<section id="voltmont-portfolio-section" class="vm-portfolio-section">
    <div class="container fullwidth padding-extech-shortcode-footer2">
        
        <!-- Nagłówek Sekcji -->
        <div class="row justify-content-center mb-5 mx-0">
            <div class="col-lg-12 text-center">
                <h2 class="vm-portfolio-heading">Wybrane <span class="vm-portfolio-highlight">realizacje</span> VOLTMONT</h2>
                <div class="vm-portfolio-divider"></div>
                <p class="vm-portfolio-intro">
                    Zobacz, jak teoria zamienia się w praktykę. Prezentujemy wybrane projekty, które zrealizowaliśmy z najwyższą starannością.
                </p>
            </div>
        </div>

        <!-- CZĘŚĆ 1: SLIDER (Główne 3 realizacje) -->
        <?php if ($portfolio_query->have_posts()) : ?>
            <div class="vm-portfolio-container">
                
                <!-- NAWIGACJA (Ikony / Tytuły) -->
                <div class="vm-portfolio-nav-wrapper">
                    <div class="vm-portfolio-nav" role="tablist">
                        <?php 
                        $i = 0;
                        while ($portfolio_query->have_posts()) : $portfolio_query->the_post(); 
                            $icon = isset($slot_icons[$i]) ? $slot_icons[$i] : 'fas fa-bolt';
                        ?>
                            <button class="vm-portfolio-tab <?php echo $i === 0 ? 'active' : ''; ?>" 
                                    onclick="changeVmPortfolio(<?php echo $i; ?>)"
                                    aria-label="<?php the_title_attribute(); ?>">
                                <div class="vm-ptab-icon">
                                    <i class="<?php echo esc_attr($icon); ?>"></i>
                                </div>
                                <span class="vm-ptab-label"><?php the_title(); ?></span>
                            </button>
                        <?php 
                        $i++;
                        endwhile; 
                        // Resetujemy wskaźnik postów dla treści
                        $portfolio_query->rewind_posts(); 
                        ?>
                    </div>
                </div>

                <!-- TREŚĆ (Panele) -->
                <div class="vm-portfolio-content-wrapper">
                    <?php 
                    $j = 0;
                    while ($portfolio_query->have_posts()) : $portfolio_query->the_post(); 
                        
                        $img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                        if(!$img_url) $img_url = 'https://placehold.co/1920x1080/041028/ffffff?text=Voltmont+Realizacja'; 
                        
                        $link = get_permalink();
                        $excerpt = get_the_excerpt();
                        if (empty($excerpt)) {
                            $excerpt = wp_trim_words(get_the_content(), 20, '...');
                        }
                        $icon = isset($slot_icons[$j]) ? $slot_icons[$j] : 'fas fa-bolt';
                    ?>
                        <div class="vm-portfolio-panel <?php echo $j === 0 ? 'active' : ''; ?>" id="vm-port-panel-<?php echo $j; ?>">
                            <div class="row g-0 align-items-center h-100">
                                
                                <div class="col-lg-5 col-md-12 h-100 position-relative">
                                    <div class="vm-ppanel-image-container">
                                        <a href="<?php echo esc_url($link); ?>" target="_self">
                                            <img src="<?php echo esc_url($img_url); ?>" 
                                                 alt="<?php the_title_attribute(); ?>" 
                                                 class="vm-ppanel-image"
                                                 loading="lazy">
                                        </a>
                                        <div class="vm-ppanel-overlay"></div>
                                        
                                        <?php 
                                        $terms = get_the_terms(get_the_ID(), 'portfolio-types');
                                        if ($terms && !is_wp_error($terms)) : ?>
                                            <div class="vm-ppanel-badge">
                                                <?php echo esc_html($terms[0]->name); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-lg-7 col-md-12">
                                    <div class="vm-ppanel-text">
                                        <div class="vm-ppanel-icon-bg">
                                            <i class="<?php echo esc_attr($icon); ?>"></i>
                                        </div>
                                        <h3 class="vm-ppanel-title"><?php the_title(); ?></h3>
                                        <div class="vm-ppanel-divider"></div>
                                        <div class="vm-ppanel-desc">
                                            <?php echo esc_html( mb_strimwidth( $excerpt, 0, 600, '...' ) ); ?>
                                        </div>
                                        
                                        <a href="<?php echo esc_url($link); ?>" target="_self" class="vm-pbtn-primary">
                                            Zobacz realizację <i class="fas fa-arrow-right ms-2"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php 
                    $j++;
                    endwhile; 
                    ?>
                </div>
            </div>
        <?php else : ?>
            <p class="text-center">Brak realizacji do wyświetlenia.</p>
        <?php endif; wp_reset_postdata(); ?>

        
        <!-- CZĘŚĆ 2: POZOSTAŁE REALIZACJE (Grid 6 kolumn) -->
        <?php
        // 2. DRUGIE ZAPYTANIE (Reszta postów)
        $args_more = [
            'post_type'      => 'portfolio',
            'posts_per_page' => -1, // Wszystkie pozostałe
            'post__not_in'   => $excluded_ids, // Wykluczamy te z slidera
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_status'    => 'publish',
        ];
        
        $more_portfolio_query = new WP_Query($args_more);
        
        if ($more_portfolio_query->have_posts()) : 
        ?>
            <div class="vm-portfolio-more-wrapper mt-5">
                <div class="row justify-content-center mb-4">
                     <div class="col-12 text-center">
                        <h4 class="vm-more-heading">Pozostałe realizacje</h4>
                     </div>
                </div>

                <!-- Grid Container: Bootstrap row with gap (g-4) -->
                <div class="row g-4">
                    <?php 
                    $k = 0;
                    while ($more_portfolio_query->have_posts()) : $more_portfolio_query->the_post(); 
                        // Rotacja ikon dla urozmaicenia
                        $grid_icon = $grid_icons[$k % count($grid_icons)];
                    ?>
                        <!-- Kolumna: Desktop 2 (6 w rzędzie), Tablet 4 (3 w rzędzie), Mobile 6 (2 w rzędzie) -->
                        <div class="col-6 col-md-4 col-lg-2 d-flex align-items-stretch">
                            <a href="<?php the_permalink(); ?>" class="vm-mini-card" title="<?php the_title_attribute(); ?>">
                                <div class="vm-mini-icon">
                                    <i class="<?php echo esc_attr($grid_icon); ?>"></i>
                                </div>
                                <h5 class="vm-mini-title"><?php the_title(); ?></h5>
                                <span class="vm-mini-arrow"><i class="fas fa-chevron-right"></i></span>
                            </a>
                        </div>
                    <?php 
                    $k++;
                    endwhile; 
                    ?>
                </div>
            </div>
        <?php endif; wp_reset_postdata(); ?>

    </div>
</section>

<!-- Skrypt obsługujący przełączanie (Unikalny dla Portfolio) -->
<script>
function changeVmPortfolio(index) {
    const ptabs = document.querySelectorAll('.vm-portfolio-tab');
    const ppanels = document.querySelectorAll('.vm-portfolio-panel');
    
    ptabs.forEach(tab => tab.classList.remove('active'));
    ppanels.forEach(panel => panel.classList.remove('active'));
    
    if(ptabs[index]) ptabs[index].classList.add('active');
    const targetPPanel = document.getElementById('vm-port-panel-' + index);
    if(targetPPanel) targetPPanel.classList.add('active');
}
</script>
<?php
/**
 * Template Name: Sekcja Dlaczego Warto (Bootstrap Fix)
 * Description: Szablon sekcji "Dlaczego warto" oparty na Bootstrapie, niezależny od gridu BeTheme.
 */

// Konfiguracja kafelków
$cards = [
    [
        'icon' => 'icon-users',
        'title' => 'Wykwalifikowani pracownicy',
        'desc' => 'Nasza firma zatrudnia ekspertów gotowych służyć radą i rozwiązać każdy problem.',
        'link' => '/o-firmie/',
    ],
    [
        'icon' => 'icon-chart-bar',
        'title' => 'Stały rozwój',
        'desc' => 'Nasze przedsiębiorstwo stawia na innowacje. Ciągle udoskonalamy metody działania.',
        'link' => '/oferta/',
    ],
    [
        'icon' => 'icon-check',
        'title' => 'Doświadczenie',
        'desc' => 'Od lat z największą pieczą i profesjonalizmem realizujemy inwestycje w regionie.',
        'link' => '/galeria-realizacji/',
    ]
];
?>

<!-- Unikalny Wrapper ID i Klasa, aby odciąć style BeTheme -->
<div id="voltmont-why-us-wrapper" class="vm-why-us-section">
    <div class="container">

        <!-- Grid Kafelków (Bootstrap Row) -->
        <!-- g-4 oznacza gap (odstęp) wielkości 1.5rem między kolumnami -->
        <div class="row g-4">
            
            <?php foreach ($cards as $card): ?>
                <!-- Kolumna: Mobile 100%, Tablet 50%, Desktop 33.33% (3 w rzędzie) -->
                <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch mb-5">
                    
                    <a href="<?php echo esc_url($card['link']); ?>" target="_blank" class="vm-card-link">
                        <div class="vm-card-body">
                            <div class="vm-icon-wrapper">
                                <i class="<?php echo esc_attr($card['icon']); ?>"></i>
                            </div>
                            <div class="vm-content-wrapper">
                                <h4 class="vm-card-title"><?php echo esc_html($card['title']); ?></h4>
                                <p class="vm-card-text"><?php echo esc_html($card['desc']); ?></p>
                            </div>
                            <div class="vm-card-footer">
                                <span class="vm-read-more">Więcej <i class="icon-right-open-mini"></i></span>
                            </div>
                        </div>
                    </a>

                </div>
            <?php endforeach; ?>

        </div>
        
        <!-- Dolny separator -->
        <div class="row mt-5">
            <div class="col-12">
                 <div class="vm-divider full-width mx-auto" style="opacity: 0.1;"></div>
            </div>
        </div>

    </div>
</div>
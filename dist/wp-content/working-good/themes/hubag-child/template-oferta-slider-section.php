<?php
/**
 * Template Name: Sekcja Oferta Slider (Premium Tabs)
 * Description: Nowoczesny slider usług z nawigacją opartą na ikonach.
 */

// Konfiguracja usług (Ikony używają klas FontAwesome, które są standardem w BeTheme)
$services = [
    [
        'id'    => 'domofony',
        'title' => 'Instalacja domofonowa',
        'desc'  => 'Poczucie bezpieczeństwa to fundament. Nowoczesna instalacja domofonowa lub wideodomofonowa zapewnia pełną kontrolę nad dostępem do posesji.',
        'link'  => '/oferta/instalacja-domofonowa/',
        'img'   => 'https://trzebnica-elektryk.pl/wp-content/uploads/2025/09/instalacja-domofonowa.jpg',
        'icon'  => 'fas fa-door-open' // Ikona drzwi/domofonu
    ],
    [
        'id'    => 'rtv',
        'title' => 'RTV, Internet, Alarmy',
        'desc'  => 'Niezawodny internet, czysty odbiór TV i system alarmowy to standard nowoczesnego budynku. Zapewniamy kompleksowe instalacje niskoprądowe.',
        'link'  => '/oferta/rtv-internet-domofony-alarmy/',
        'img'   => 'https://trzebnica-elektryk.pl/wp-content/uploads/2025/09/rtv-internet-domofony.jpg',
        'icon'  => 'fas fa-wifi' // Ikona Wi-Fi
    ],
    [
        'id'    => 'nadzor',
        'title' => 'Nadzór elektryczny',
        'desc'  => 'Profesjonalny nadzór nad instalacjami w obiektach produkcyjnych i usługowych. Zapobiegamy awariom i minimalizujemy ryzyko przestojów.',
        'link'  => '/oferta/nadzor-elektryczny-obiektow-produkcyjno-uslugowych/',
        'img'   => 'https://trzebnica-elektryk.pl/wp-content/uploads/2025/09/nadzor-elektryczny-obiektow-produkcyjnych.jpg',
        'icon'  => 'fas fa-hard-hat' // Ikona kasku/nadzoru
    ],
    [
        'id'    => 'modernizacja',
        'title' => 'Modernizacja instalacji',
        'desc'  => 'Wymiana starych instalacji aluminiowych w blokach z wielkiej płyty. Zwiększamy bezpieczeństwo i dostosowujemy standard do nowoczesnych urządzeń.',
        'link'  => '/oferta/modernizacja-starej-instalacji-w-blokach-mieszkalnych/',
        'img'   => 'https://trzebnica-elektryk.pl/wp-content/uploads/2025/09/modernizacja-starej-instalacji-w-blokach.jpg',
        'icon'  => 'fas fa-tools' // Ikona narzędzi
    ],
    [
        'id'    => 'wlz',
        'title' => 'Wymiana WLZ',
        'desc'  => 'Kompleksowa wymiana Wewnętrznych Linii Zasilających. Kręgosłup energetyczny budynku musi być niezawodny i bezpieczny.',
        'link'  => '/oferta/kompleksowa-wymiana-instalacji-wlz/',
        'img'   => 'https://trzebnica-elektryk.pl/wp-content/uploads/2025/09/kompleksowa-wymiana-wlz.jpg',
        'icon'  => 'fas fa-bolt' // Ikona pioruna/energii
    ],
    [
        'id'    => 'inwestycje',
        'title' => 'Obsługa inwestycji',
        'desc'  => 'Partnerstwo dla deweloperów i architektów. Kompleksowa obsługa inwestycji od strony elektrycznej na terenie Dolnego Śląska.',
        'link'  => '/oferta/kompleksowa-obsluga-inwestycji-od-strony-elektrycznej/',
        'img'   => 'https://trzebnica-elektryk.pl/wp-content/uploads/2025/09/kompleksowa-obsluga-inwestycji.jpg',
        'icon'  => 'fas fa-project-diagram' // Ikona projektu
    ],
    [
        'id'    => 'odgromowa',
        'title' => 'Instalacja odgromowa',
        'desc'  => 'Skuteczna ochrona przed wyładowaniami atmosferycznymi. Zabezpiecz swój dom i urządzenia przed skutkami burz.',
        'link'  => '/oferta/instalacja-odgromowa/',
        'img'   => 'https://trzebnica-elektryk.pl/wp-content/uploads/2025/09/instalacja-odgromowa.jpg',
        'icon'  => 'fas fa-cloud-showers-heavy' // Ikona burzy
    ],
    [
        'id'    => 'smart',
        'title' => 'Instalacja Smart Home',
        'desc'  => 'Inteligentny dom to nie przyszłość, to teraźniejszość. Sterowanie oświetleniem, ogrzewaniem i roletami z poziomu telefonu.',
        'link'  => '/oferta/instalacja-inteligentna-smart/',
        'img'   => 'https://trzebnica-elektryk.pl/wp-content/uploads/2025/09/instalacja-inteligentna-smart.jpg',
        'icon'  => 'fas fa-mobile-alt' // Ikona smartfona
    ],
    [
        'id'    => 'podstawowa',
        'title' => 'Instalacja podstawowa',
        'desc'  => 'Solidne fundamenty elektryki. Profesjonalne projekty i wykonawstwo instalacji w domach jednorodzinnych i mieszkaniach.',
        'link'  => '/oferta/instalacja-elektryczna-podstawowa/',
        'img'   => 'https://trzebnica-elektryk.pl/wp-content/uploads/2025/09/instalacja-elektryczna-podstawowa.jpg',
        'icon'  => 'fas fa-plug' // Ikona wtyczki
    ],
];
?>

<section id="voltmont-services-section" class="vm-offer-section">
    <div class="container">
        
        <!-- Nagłówek Sekcji -->
        <div class="justify-content-center mb-5">
            <div class="col-lg-12 text-center">
                <h2 class="vm-offer-heading text-center">Oferta <span class="vm-text-highlight">firmy</span> VOLTMONT</h2>
                <div class="vm-offer-divider"></div>
                <p class="vm-offer-intro">
                    Kompleksowe usługi elektryczne na najwyższym poziomie. Wybierz interesujący Cię obszar, aby poznać szczegóły.
                </p>
            </div>
        </div>

        <div class="vm-offer-container">
            
            <!-- NAWIGACJA (Ikony) -->
            <div class="vm-offer-nav-wrapper">
                <div class="vm-offer-nav" role="tablist">
                    <?php foreach ($services as $index => $service): ?>
                        <button class="vm-offer-tab <?php echo $index === 0 ? 'active' : ''; ?>" 
                                onclick="changeVmService(<?php echo $index; ?>)"
                                aria-label="<?php echo esc_attr($service['title']); ?>">
                            <div class="vm-tab-icon">
                                <i class="<?php echo esc_attr($service['icon']); ?>"></i>
                            </div>
                            <span class="vm-tab-label"><?php echo esc_html($service['title']); ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- TREŚĆ (Content) -->
            <div class="vm-offer-content-wrapper">
                <?php foreach ($services as $index => $service): ?>
                    <div class="vm-offer-panel <?php echo $index === 0 ? 'active' : ''; ?>" id="vm-panel-<?php echo $index; ?>">
                        <div class="row g-0 align-items-center h-100">
                            
                            <!-- Obrazek (Lewa strona / Góra) -->
                            <div class="col-lg-7 col-md-12 h-100 position-relative">
                                <div class="vm-panel-image-container">
                                    <!-- Link na obrazku też prowadzi do oferty -->
                                    <a href="<?php echo esc_url($service['link']); ?>" target="_self">
                                        <img src="<?php echo esc_url($service['img']); ?>" 
                                             alt="<?php echo esc_attr($service['title']); ?>" 
                                             class="vm-panel-image"
                                             loading="lazy">
                                    </a>
                                    <div class="vm-image-overlay"></div>
                                </div>
                            </div>

                            <!-- Tekst (Prawa strona / Dół) -->
                            <div class="col-lg-5 col-md-12">
                                <div class="vm-panel-text">
                                    <div class="vm-panel-icon-bg">
                                        <i class="<?php echo esc_attr($service['icon']); ?>"></i>
                                    </div>
                                    <h3 class="vm-panel-title"><?php echo esc_html($service['title']); ?></h3>
                                    <div class="vm-panel-divider"></div>
                                    <p class="vm-panel-desc"><?php echo esc_html($service['desc']); ?></p>
                                    
                                    <a href="<?php echo esc_url($service['link']); ?>" target="_self" class="vm-btn-primary">
                                        Szczegóły usługi <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</section>

<!-- Skrypt obsługujący przełączanie (Inline dla wydajności) -->
<script>
function changeVmService(index) {
    // Usuń klasę active ze wszystkich tabów i paneli
    const tabs = document.querySelectorAll('.vm-offer-tab');
    const panels = document.querySelectorAll('.vm-offer-panel');
    
    tabs.forEach(tab => tab.classList.remove('active'));
    panels.forEach(panel => panel.classList.remove('active'));
    
    // Dodaj klasę active do klikniętego taba i odpowiedniego panelu
    if(tabs[index]) tabs[index].classList.add('active');
    const targetPanel = document.getElementById('vm-panel-' + index);
    if(targetPanel) targetPanel.classList.add('active');
}
</script>
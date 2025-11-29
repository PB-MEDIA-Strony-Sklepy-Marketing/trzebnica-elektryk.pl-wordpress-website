<?php
/**
 * Template Name: Service Page Template
 * Description: Professional service page template with schema.org, CTAs, and optimized SEO
 *
 * @package Hubag_Child
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

get_header();
?>

<main class="service-page" role="main" itemscope itemtype="https://schema.org/Service">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <!-- Hero Section -->
        <section class="service-hero">
            <div class="container">
                <div class="service-hero__content">
                    
                    <!-- Breadcrumbs -->
                    <nav class="breadcrumbs" aria-label="Breadcrumb">
                        <ol class="breadcrumbs__list" itemscope itemtype="https://schema.org/BreadcrumbList">
                            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                <a href="<?php echo esc_url(home_url('/')); ?>" itemprop="item">
                                    <span itemprop="name">Home</span>
                                </a>
                                <meta itemprop="position" content="1" />
                            </li>
                            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                <a href="<?php echo esc_url(home_url('/uslugi/')); ?>" itemprop="item">
                                    <span itemprop="name">Usługi</span>
                                </a>
                                <meta itemprop="position" content="2" />
                            </li>
                            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" aria-current="page">
                                <span itemprop="name"><?php the_title(); ?></span>
                                <meta itemprop="position" content="3" />
                            </li>
                        </ol>
                    </nav>
                    
                    <!-- Title & Description -->
                    <div class="service-hero__text">
                        <h1 class="service-hero__title" itemprop="name"><?php the_title(); ?></h1>
                        
                        <?php if (has_excerpt()) : ?>
                            <p class="service-hero__excerpt" itemprop="description">
                                <?php echo esc_html(get_the_excerpt()); ?>
                            </p>
                        <?php endif; ?>
                        
                        <div class="service-hero__meta">
                            <span class="service-hero__meta-item">
                                <svg class="icon" width="20" height="20" aria-hidden="true">
                                    <use xlink:href="#icon-clock"></use>
                                </svg>
                                <span>Czas realizacji: <?php echo esc_html(get_post_meta(get_the_ID(), '_service_duration', true) ?: '2-5 dni'); ?></span>
                            </span>
                            <span class="service-hero__meta-item">
                                <svg class="icon" width="20" height="20" aria-hidden="true">
                                    <use xlink:href="#icon-location"></use>
                                </svg>
                                <span>Trzebnica, Dolny Śląsk</span>
                            </span>
                        </div>
                        
                        <div class="service-hero__actions">
                            <a href="#contact-form" class="button button--primary button--large">
                                Bezpłatna wycena
                            </a>
                            <a href="tel:+48691594820" class="button button--secondary button--large">
                                <svg class="icon" width="20" height="20" aria-hidden="true">
                                    <use xlink:href="#icon-phone"></use>
                                </svg>
                                +48 691 594 820
                            </a>
                        </div>
                    </div>
                    
                    <!-- Featured Image -->
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="service-hero__image">
                            <?php 
                            the_post_thumbnail('large', array(
                                'class' => 'service-hero__img',
                                'itemprop' => 'image',
                                'loading' => 'eager', // Hero image should load immediately
                                'decoding' => 'async'
                            )); 
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        
        <!-- Service Features -->
        <section class="service-features">
            <div class="container">
                <div class="service-features__grid">
                    
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <svg class="icon icon--large" width="48" height="48" aria-hidden="true">
                                <use xlink:href="#icon-certificate"></use>
                            </svg>
                        </div>
                        <h3 class="feature-card__title">Certyfikowani Elektrycy</h3>
                        <p class="feature-card__text">Wszystkie prace wykonują doświadczeni specjaliści z uprawnieniami SEP.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <svg class="icon icon--large" width="48" height="48" aria-hidden="true">
                                <use xlink:href="#icon-warranty"></use>
                            </svg>
                        </div>
                        <h3 class="feature-card__title">Gwarancja na Usługi</h3>
                        <p class="feature-card__text">Udzielamy 24-miesięcznej gwarancji na wszystkie wykonane instalacje.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <svg class="icon icon--large" width="48" height="48" aria-hidden="true">
                                <use xlink:href="#icon-fast"></use>
                            </svg>
                        </div>
                        <h3 class="feature-card__title">Szybka Realizacja</h3>
                        <p class="feature-card__text">Rozpoczynamy pracę w ciągu 48 godzin od potwierdzenia zlecenia.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <svg class="icon icon--large" width="48" height="48" aria-hidden="true">
                                <use xlink:href="#icon-price"></use>
                            </svg>
                        </div>
                        <h3 class="feature-card__title">Przejrzyste Ceny</h3>
                        <p class="feature-card__text">Jasne wyceny bez ukrytych kosztów. Płatność po wykonaniu.</p>
                    </div>
                    
                </div>
            </div>
        </section>
        
        <!-- Main Content -->
        <section class="service-content">
            <div class="container">
                <div class="service-content__layout">
                    
                    <!-- Main Content Area -->
                    <article class="service-content__main" itemprop="description">
                        <?php the_content(); ?>
                    </article>
                    
                    <!-- Sidebar -->
                    <aside class="service-content__sidebar">
                        
                        <!-- Price Card -->
                        <div class="price-card">
                            <div class="price-card__header">
                                <h3 class="price-card__title">Cennik</h3>
                            </div>
                            <div class="price-card__body">
                                <?php 
                                $price_from = get_post_meta(get_the_ID(), '_service_price_from', true);
                                $price_note = get_post_meta(get_the_ID(), '_service_price_note', true);
                                ?>
                                
                                <?php if ($price_from) : ?>
                                    <div class="price-card__price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                        <span class="price-card__label">Od</span>
                                        <span class="price-card__amount" itemprop="price" content="<?php echo esc_attr($price_from); ?>">
                                            <?php echo esc_html(number_format($price_from, 0, ',', ' ')); ?> zł
                                        </span>
                                        <meta itemprop="priceCurrency" content="PLN" />
                                    </div>
                                <?php else : ?>
                                    <p class="price-card__custom">Wycena indywidualna</p>
                                <?php endif; ?>
                                
                                <?php if ($price_note) : ?>
                                    <p class="price-card__note"><?php echo esc_html($price_note); ?></p>
                                <?php endif; ?>
                                
                                <a href="#contact-form" class="button button--primary button--block">
                                    Zapytaj o wycenę
                                </a>
                            </div>
                        </div>
                        
                        <!-- Contact Card -->
                        <div class="contact-card">
                            <h3 class="contact-card__title">Skontaktuj się</h3>
                            <div class="contact-card__item">
                                <svg class="icon" width="20" height="20" aria-hidden="true">
                                    <use xlink:href="#icon-phone"></use>
                                </svg>
                                <a href="tel:+48691594820">+48 691 594 820</a>
                            </div>
                            <div class="contact-card__item">
                                <svg class="icon" width="20" height="20" aria-hidden="true">
                                    <use xlink:href="#icon-email"></use>
                                </svg>
                                <a href="mailto:biuro@trzebnica-elektryk.pl">biuro@trzebnica-elektryk.pl</a>
                            </div>
                            <div class="contact-card__item">
                                <svg class="icon" width="20" height="20" aria-hidden="true">
                                    <use xlink:href="#icon-location"></use>
                                </svg>
                                <span>Trzebnica, Dolny Śląsk</span>
                            </div>
                        </div>
                        
                    </aside>
                    
                </div>
            </div>
        </section>
        
        <!-- FAQ Section (if available) -->
        <?php 
        $faq_items = get_post_meta(get_the_ID(), '_faq_items', true);
        if (!empty($faq_items) && is_array($faq_items)) : 
        ?>
        <section class="service-faq">
            <div class="container">
                <h2 class="section-title">Często zadawane pytania</h2>
                
                <div class="faq-list" itemscope itemtype="https://schema.org/FAQPage">
                    <?php foreach ($faq_items as $index => $faq) : ?>
                        <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                            <button class="faq-item__question" 
                                    aria-expanded="false" 
                                    aria-controls="faq-<?php echo esc_attr($index); ?>"
                                    itemprop="name">
                                <?php echo esc_html($faq['question']); ?>
                                <svg class="faq-item__icon" width="24" height="24" aria-hidden="true">
                                    <use xlink:href="#icon-chevron-down"></use>
                                </svg>
                            </button>
                            <div id="faq-<?php echo esc_attr($index); ?>" 
                                 class="faq-item__answer" 
                                 hidden
                                 itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                <div itemprop="text">
                                    <?php echo wp_kses_post($faq['answer']); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>
        
        <!-- CTA Section -->
        <section class="service-cta">
            <div class="container">
                <div class="cta-box">
                    <h2 class="cta-box__title">Gotowy na rozpoczęcie projektu?</h2>
                    <p class="cta-box__text">
                        Skontaktuj się z nami, aby uzyskać bezpłatną wycenę i profesjonalną poradę.
                    </p>
                    <div class="cta-box__actions">
                        <a href="#contact-form" class="button button--primary button--large">
                            Bezpłatna wycena
                        </a>
                        <a href="tel:+48691594820" class="button button--secondary-outline button--large">
                            Zadzwoń: +48 691 594 820
                        </a>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Contact Form -->
        <section id="contact-form" class="service-contact">
            <div class="container">
                <div class="service-contact__layout">
                    
                    <div class="service-contact__info">
                        <h2>Wyślij zapytanie</h2>
                        <p>Wypełnij formularz, a skontaktujemy się z Tobą w ciągu 24 godzin.</p>
                        
                        <div class="service-contact__features">
                            <div class="contact-feature">
                                <svg class="icon" width="24" height="24" aria-hidden="true">
                                    <use xlink:href="#icon-check"></use>
                                </svg>
                                <span>Odpowiedź w 24h</span>
                            </div>
                            <div class="contact-feature">
                                <svg class="icon" width="24" height="24" aria-hidden="true">
                                    <use xlink:href="#icon-check"></use>
                                </svg>
                                <span>Bezpłatna wycena</span>
                            </div>
                            <div class="contact-feature">
                                <svg class="icon" width="24" height="24" aria-hidden="true">
                                    <use xlink:href="#icon-check"></use>
                                </svg>
                                <span>Profesjonalna obsługa</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="service-contact__form">
                        <form class="contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                            <?php wp_nonce_field('voltmont_contact_form', 'voltmont_contact_nonce'); ?>
                            <input type="hidden" name="action" value="voltmont_contact_form">
                            <input type="hidden" name="service" value="<?php echo esc_attr(get_the_title()); ?>">
                            
                            <div class="form-group">
                                <label for="name" class="form-label">Imię i nazwisko *</label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       class="form-control" 
                                       required 
                                       aria-required="true">
                            </div>
                            
                            <div class="form-group">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       class="form-control" 
                                       required 
                                       aria-required="true">
                            </div>
                            
                            <div class="form-group">
                                <label for="phone" class="form-label">Telefon</label>
                                <input type="tel" 
                                       id="phone" 
                                       name="phone" 
                                       class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="message" class="form-label">Wiadomość *</label>
                                <textarea id="message" 
                                          name="message" 
                                          class="form-control" 
                                          rows="5" 
                                          required 
                                          aria-required="true"></textarea>
                            </div>
                            
                            <div class="form-group form-group--checkbox">
                                <label class="form-checkbox">
                                    <input type="checkbox" 
                                           name="consent" 
                                           required 
                                           aria-required="true">
                                    <span class="form-checkbox__label">
                                        Zgadzam się na przetwarzanie moich danych osobowych zgodnie z 
                                        <a href="<?php echo esc_url(home_url('/polityka-prywatnosci/')); ?>">polityką prywatności</a> *
                                    </span>
                                </label>
                            </div>
                            
                            <button type="submit" class="button button--primary button--large button--block">
                                Wyślij zapytanie
                            </button>
                        </form>
                    </div>
                    
                </div>
            </div>
        </section>
        
        <!-- Related Services -->
        <?php
        $related_services = new WP_Query(array(
            'post_type' => 'page',
            'posts_per_page' => 3,
            'post__not_in' => array(get_the_ID()),
            'meta_key' => '_wp_page_template',
            'meta_value' => 'template-service.php',
            'orderby' => 'rand'
        ));
        
        if ($related_services->have_posts()) :
        ?>
        <section class="related-services">
            <div class="container">
                <h2 class="section-title">Inne usługi</h2>
                
                <div class="service-grid">
                    <?php while ($related_services->have_posts()) : $related_services->the_post(); ?>
                        <article class="service-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="service-card__image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium', array('loading' => 'lazy')); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="service-card__content">
                                <h3 class="service-card__title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                
                                <?php if (has_excerpt()) : ?>
                                    <p class="service-card__excerpt">
                                        <?php echo esc_html(wp_trim_words(get_the_excerpt(), 15)); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" class="service-card__link">
                                    Dowiedz się więcej
                                    <svg class="icon" width="16" height="16" aria-hidden="true">
                                        <use xlink:href="#icon-arrow-right"></use>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            </div>
        </section>
        <?php endif; ?>
        
    <?php endwhile; ?>
    
</main>

<?php
get_footer();

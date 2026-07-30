<?php
function lp(array $lp, string $section, string $key, string $default = ''): string {
    return htmlspecialchars($lp[$section][$key] ?? $default, ENT_QUOTES);
}
function lpRaw(array $lp, string $section, string $key, string $default = ''): string {
    return $lp[$section][$key] ?? $default;
}
$lp = $lp ?? [];
?>
<!-- NAV -->
<nav class="lp-nav" id="lpNav">
    <div class="lp-nav__inner">
        <a href="/" class="lp-nav__logo" aria-label="ECU File Germany">
            <?php if (!empty($site_logo)): ?>
                <img src="<?= \Core\App::url('storage/uploads/logo/' . \Core\View::escape($site_logo)) ?>" alt="Logo" class="lp-logo-img">
            <?php else: ?>
                <i class="fas fa-microchip"></i>
                <span>ECU<strong>File</strong>Germany</span>
            <?php endif; ?>
        </a>
        <div class="lp-nav__actions">
            <a href="/login" class="lp-btn lp-btn--ghost">Sign In</a>
            <a href="/register" class="lp-btn lp-btn--primary">Get Started</a>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="lp-hero" id="home">
    <div class="lp-hero__bg">
        <video class="lp-hero__video" autoplay muted loop playsinline
               poster="https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=1920&q=80">
            <source src="<?= \Core\App::asset('videos/hero-bg.mp4') ?>" type="video/mp4">
        </video>
        <div class="lp-hero__video-overlay"></div>
        <div class="lp-hero__grid"></div>
    </div>
    <div class="lp-container lp-hero__content">
        <div class="lp-hero__badge">
            <span class="lp-hero__badge-dot"></span>
            <?= lp($lp, 'hero', 'badge', 'Professional ECU Tuning File Service') ?>
        </div>
        <h1 class="lp-hero__title">
            <?= lp($lp, 'hero', 'title', 'Precision Tuning.') ?><br>
            <span class="lp-hero__title-accent"><?= lp($lp, 'hero', 'title_accent', 'Maximum Performance.') ?></span>
        </h1>
        <p class="lp-hero__sub">
            <?= lp($lp, 'hero', 'subtitle', 'Upload your ECU file, select your tune level, and get a professionally optimized file back — fast, secure, and engineered for results.') ?>
        </p>
        <div class="lp-hero__cta">
            <a href="/register" class="lp-btn lp-btn--primary lp-btn--lg">
                <i class="fas fa-rocket"></i> <?= lp($lp, 'hero', 'cta_primary', "Start Now — It's Free") ?>
            </a>
            <a href="#how-it-works" class="lp-btn lp-btn--outline lp-btn--lg">
                <?= lp($lp, 'hero', 'cta_secondary', 'How It Works') ?> <i class="fas fa-arrow-down"></i>
            </a>
        </div>
        <div class="lp-hero__stats">
            <div class="lp-hero__stat">
                <span class="lp-hero__stat-value"><?= lp($lp, 'hero', 'stat1_val', '5,000+') ?></span>
                <span class="lp-hero__stat-label"><?= lp($lp, 'hero', 'stat1_lbl', 'Files Processed') ?></span>
            </div>
            <div class="lp-hero__stat-divider"></div>
            <div class="lp-hero__stat">
                <span class="lp-hero__stat-value"><?= lp($lp, 'hero', 'stat2_val', '500+') ?></span>
                <span class="lp-hero__stat-label"><?= lp($lp, 'hero', 'stat2_lbl', 'Vehicle Models') ?></span>
            </div>
            <div class="lp-hero__stat-divider"></div>
            <div class="lp-hero__stat">
                <span class="lp-hero__stat-value"><?= lp($lp, 'hero', 'stat3_val', '24h') ?></span>
                <span class="lp-hero__stat-label"><?= lp($lp, 'hero', 'stat3_lbl', 'Avg. Turnaround') ?></span>
            </div>
        </div>
    </div>
</section>

<!-- SERVICE NOTICE -->
<div class="lp-notice">
    <i class="fas fa-clock"></i>
    <span><?= lp($lp, 'notice', 'text', 'Our file service is currently down. Opening hours: Monday to Saturday 08:00(AM) – 07:00(PM) (UTC+3). Only support will be given on Sunday.') ?></span>
</div>

<!-- HOW IT WORKS -->
<section class="lp-section" id="how-it-works">
    <div class="lp-container">
        <div class="lp-section__header">
            <span class="lp-section__eyebrow"><?= lp($lp, 'how_it_works', 'eyebrow', 'Simple Process') ?></span>
            <h2 class="lp-section__title"><?= lp($lp, 'how_it_works', 'title', 'How It Works') ?></h2>
            <p class="lp-section__sub"><?= lp($lp, 'how_it_works', 'subtitle', 'From file upload to optimized output in three steps.') ?></p>
        </div>
        <div class="lp-steps">
            <div class="lp-step">
                <div class="lp-step__num">01</div>
                <div class="lp-step__icon"><i class="fas fa-upload"></i></div>
                <h3 class="lp-step__title"><?= lp($lp, 'how_it_works', 'step1_title', 'Upload Your File') ?></h3>
                <p class="lp-step__desc"><?= lp($lp, 'how_it_works', 'step1_desc', 'Create a request, select your vehicle details, and upload your original ECU file securely through our platform.') ?></p>
            </div>
            <div class="lp-step__arrow"><i class="fas fa-chevron-right"></i></div>
            <div class="lp-step">
                <div class="lp-step__num">02</div>
                <div class="lp-step__icon"><i class="fas fa-sliders-h"></i></div>
                <h3 class="lp-step__title"><?= lp($lp, 'how_it_works', 'step2_title', 'Choose Your Tune') ?></h3>
                <p class="lp-step__desc"><?= lp($lp, 'how_it_works', 'step2_desc', 'Select from Stage 1, Stage 2, Stage 3, or custom options. Add specific service requests and notes for our engineers.') ?></p>
            </div>
            <div class="lp-step__arrow"><i class="fas fa-chevron-right"></i></div>
            <div class="lp-step">
                <div class="lp-step__num">03</div>
                <div class="lp-step__icon"><i class="fas fa-download"></i></div>
                <h3 class="lp-step__title"><?= lp($lp, 'how_it_works', 'step3_title', 'Download & Drive') ?></h3>
                <p class="lp-step__desc"><?= lp($lp, 'how_it_works', 'step3_desc', 'Receive your professionally tuned file, ready to flash. Track your request status in real-time on your dashboard.') ?></p>
            </div>
        </div>
    </div>
</section>

<!-- SERVICES -->
<section class="lp-section lp-section--alt" id="services">
    <div class="lp-container">
        <div class="lp-section__header">
            <span class="lp-section__eyebrow">What We Offer</span>
            <h2 class="lp-section__title">Tuning Packages</h2>
            <p class="lp-section__sub">Professional ECU remapping for every performance goal.</p>
        </div>
        <div class="lp-cards">
            <div class="lp-card">
                <div class="lp-card__icon lp-card__icon--blue"><i class="fas fa-tachometer-alt"></i></div>
                <h3 class="lp-card__title">Stage 1</h3>
                <p class="lp-card__desc">Software-only optimisation for stock hardware. Improved throttle response, torque, and fuel efficiency with no hardware modifications required.</p>
                <ul class="lp-card__list">
                    <li><i class="fas fa-check"></i> +15–25% power increase</li>
                    <li><i class="fas fa-check"></i> Improved fuel economy</li>
                    <li><i class="fas fa-check"></i> No hardware mods needed</li>
                </ul>
            </div>
            <div class="lp-card lp-card--featured">
                <div class="lp-card__badge">Most Popular</div>
                <div class="lp-card__icon lp-card__icon--orange"><i class="fas fa-fire"></i></div>
                <h3 class="lp-card__title">Stage 2</h3>
                <p class="lp-card__desc">Performance tune built for vehicles with upgraded intake, exhaust, or intercooler. Unlocks the full potential of your hardware upgrades.</p>
                <ul class="lp-card__list">
                    <li><i class="fas fa-check"></i> +25–40% power increase</li>
                    <li><i class="fas fa-check"></i> Optimised for hardware mods</li>
                    <li><i class="fas fa-check"></i> Custom map parameters</li>
                </ul>
            </div>
            <div class="lp-card">
                <div class="lp-card__icon lp-card__icon--red"><i class="fas fa-bolt"></i></div>
                <h3 class="lp-card__title">Stage 3</h3>
                <p class="lp-card__desc">Full race-spec calibration for heavily modified vehicles. Designed for maximum output with custom hardware setups and track use.</p>
                <ul class="lp-card__list">
                    <li><i class="fas fa-check"></i> Maximum performance output</li>
                    <li><i class="fas fa-check"></i> Custom fuelling &amp; timing</li>
                    <li><i class="fas fa-check"></i> Race &amp; track calibration</li>
                </ul>
            </div>
            <div class="lp-card">
                <div class="lp-card__icon lp-card__icon--green"><i class="fas fa-tools"></i></div>
                <h3 class="lp-card__title">More Options</h3>
                <p class="lp-card__desc">DTC fault code removal, EGR/DPF solutions, speed limiter removal, launch control, pop &amp; bang, and more specialist services.</p>
                <ul class="lp-card__list">
                    <li><i class="fas fa-check"></i> DTC &amp; fault code removal</li>
                    <li><i class="fas fa-check"></i> EGR / DPF solutions</li>
                    <li><i class="fas fa-check"></i> Pop &amp; bang, launch control</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- CAR SHOWCASE -->
<?php
$sc = $lp['showcase'] ?? [];
$showcaseImgs = [
    ['src' => $sc['img1_src'] ?? 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=800&q=85&auto=format&fit=crop', 'label' => $sc['img1_label'] ?? 'Stage 3 Performance', 'class' => 'lp-showcase__item lp-showcase__item--tall'],
    ['src' => $sc['img2_src'] ?? 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800&q=85&auto=format&fit=crop', 'label' => $sc['img2_label'] ?? 'Stage 2 Tune', 'class' => 'lp-showcase__item'],
    ['src' => $sc['img3_src'] ?? 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=800&q=85&auto=format&fit=crop', 'label' => $sc['img3_label'] ?? 'DPF / EGR Solutions', 'class' => 'lp-showcase__item'],
    ['src' => $sc['img4_src'] ?? 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=1200&q=85&auto=format&fit=crop', 'label' => $sc['img4_label'] ?? 'Stage 1 Entry Tune', 'class' => 'lp-showcase__item lp-showcase__item--wide'],
];
?>
<section class="lp-showcase" id="showcase">
    <div class="lp-showcase__grid">
        <?php foreach ($showcaseImgs as $img): ?>
        <div class="<?= $img['class'] ?>">
            <img src="<?= htmlspecialchars($img['src'], ENT_QUOTES) ?>" alt="<?= htmlspecialchars($img['label'], ENT_QUOTES) ?>" loading="lazy">
            <div class="lp-showcase__overlay">
                <span><?= htmlspecialchars($img['label'], ENT_QUOTES) ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ABOUT -->
<section class="lp-section lp-section--alt" id="about">
    <div class="lp-container lp-about">
        <div class="lp-about__text">
            <span class="lp-section__eyebrow"><?= lp($lp, 'about', 'eyebrow', 'About Us') ?></span>
            <h2 class="lp-about__heading"><?= lp($lp, 'about', 'heading', 'Who Is ECU File Germany?') ?></h2>
            <p class="lp-about__desc"><?= lp($lp, 'about', 'desc1', '') ?></p>
            <p class="lp-about__desc"><?= lp($lp, 'about', 'desc2', '') ?></p>
            <div class="lp-about__stats">
                <div class="lp-about__stat">
                    <span class="lp-about__stat-val"><?= lp($lp, 'about', 'stat1_val', '15') ?></span>
                    <span class="lp-about__stat-lbl"><?= lp($lp, 'about', 'stat1_lbl', 'Branches') ?></span>
                </div>
                <div class="lp-about__stat">
                    <span class="lp-about__stat-val"><?= lp($lp, 'about', 'stat2_val', '5+') ?></span>
                    <span class="lp-about__stat-lbl"><?= lp($lp, 'about', 'stat2_lbl', 'Years Exp.') ?></span>
                </div>
                <div class="lp-about__stat">
                    <span class="lp-about__stat-val"><?= lp($lp, 'about', 'stat3_val', '4') ?></span>
                    <span class="lp-about__stat-lbl"><?= lp($lp, 'about', 'stat3_lbl', 'Countries') ?></span>
                </div>
                <div class="lp-about__stat">
                    <span class="lp-about__stat-val"><?= lp($lp, 'about', 'stat4_val', '5K+') ?></span>
                    <span class="lp-about__stat-lbl"><?= lp($lp, 'about', 'stat4_lbl', 'Files Done') ?></span>
                </div>
            </div>
        </div>
        <div class="lp-about__visual">
            <div class="lp-about__badge-grid">
                <div class="lp-about__badge"><i class="fas fa-shield-alt"></i><span>Secure Platform</span></div>
                <div class="lp-about__badge"><i class="fas fa-globe-europe"></i><span>Wide EU Network</span></div>
                <div class="lp-about__badge"><i class="fas fa-microchip"></i><span>Expert Engineers</span></div>
                <div class="lp-about__badge"><i class="fas fa-clock"></i><span>Fast Delivery</span></div>
            </div>
        </div>
    </div>
</section>

<?php
$br = $lp['branches'] ?? [];

$branchGroups = [
    ['label' => $br['germany_label'] ?? 'Germany', 'cities' => $br['germany_cities'] ?? 'Bielefeld, Duisburg, Stuttgart, München, Köln'],
    ['label' => $br['belgium_label'] ?? 'Belgium', 'cities' => $br['belgium_cities'] ?? 'Evergem, Aarschot'],
    ['label' => $br['sweden_label']  ?? 'Sweden',  'cities' => $br['sweden_cities']  ?? 'Sweden'],
    ['label' => $br['turkey_label']  ?? 'Turkey',  'cities' => $br['turkey_cities']  ?? 'Konya, Gaziantep, Adana, Nigde, Sirnak, Samsun, Batman'],
];
?>
<!-- BRANCHES -->
<section class="lp-section" id="branches">
    <div class="lp-container">
        <div class="lp-section__header">
            <span class="lp-section__eyebrow"><?= lp($lp, 'branches', 'eyebrow', 'Our Locations') ?></span>
            <h2 class="lp-section__title"><?= lp($lp, 'branches', 'title', 'Our Branches') ?></h2>
            <p class="lp-section__sub"><?= lp($lp, 'branches', 'subtitle', '15 locations across Europe and Turkey — always close to you.') ?></p>
        </div>
        <div class="lp-branches">
            <?php foreach ($branchGroups as $group):
                $cities = array_filter(array_map('trim', explode(',', $group['cities'])));
                $count  = count($cities);
            ?>
            <div class="lp-branch-group">
                <div class="lp-branch-group__header">
                    <i class="fas fa-flag"></i>
                    <span><?= htmlspecialchars($group['label'], ENT_QUOTES) ?></span>
                    <span class="lp-branch-group__count"><?= $count ?> <?= $count === 1 ? 'branch' : 'branches' ?></span>
                </div>
                <ul class="lp-branch-list">
                    <?php foreach ($cities as $city): ?>
                        <li><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($city, ENT_QUOTES) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- WHY US -->
<section class="lp-section lp-section--alt" id="why-us">
    <div class="lp-container">
        <div class="lp-section__header">
            <span class="lp-section__eyebrow">Why Choose Us</span>
            <h2 class="lp-section__title">Built for Professionals</h2>
            <p class="lp-section__sub">A platform engineered around speed, security, and precision.</p>
        </div>
        <div class="lp-features">
            <div class="lp-feature"><div class="lp-feature__icon"><i class="fas fa-shield-alt"></i></div><div><h4 class="lp-feature__title">Secure File Handling</h4><p class="lp-feature__desc">Every file is stored with encryption and only accessible to authorised users. Your data is never shared.</p></div></div>
            <div class="lp-feature"><div class="lp-feature__icon"><i class="fas fa-user-tie"></i></div><div><h4 class="lp-feature__title">Expert Engineers</h4><p class="lp-feature__desc">Our team of experienced ECU specialists handles every file with hands-on precision and technical expertise.</p></div></div>
            <div class="lp-feature"><div class="lp-feature__icon"><i class="fas fa-history"></i></div><div><h4 class="lp-feature__title">Real-Time Tracking</h4><p class="lp-feature__desc">Track the status of every request live from your personal dashboard. No guesswork, full transparency.</p></div></div>
            <div class="lp-feature"><div class="lp-feature__icon"><i class="fas fa-car"></i></div><div><h4 class="lp-feature__title">Massive Vehicle Coverage</h4><p class="lp-feature__desc">Support for hundreds of brands, models, generations, and ECU types — European, Asian, and American vehicles.</p></div></div>
            <div class="lp-feature"><div class="lp-feature__icon"><i class="fas fa-coins"></i></div><div><h4 class="lp-feature__title">Credit-Based System</h4><p class="lp-feature__desc">Flexible credit packages. Pay only for what you use — no subscriptions, no hidden fees, full control.</p></div></div>
            <div class="lp-feature"><div class="lp-feature__icon"><i class="fas fa-headset"></i></div><div><h4 class="lp-feature__title">Dedicated Support</h4><p class="lp-feature__desc">Direct communication with our team via the built-in messaging system on every request you submit.</p></div></div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="lp-cta">
    <div class="lp-cta__bg"><div class="lp-cta__orb"></div></div>
    <div class="lp-container lp-cta__content">
        <h2 class="lp-cta__title"><?= lp($lp, 'cta', 'title', 'Ready to Tune?') ?></h2>
        <p class="lp-cta__sub"><?= lp($lp, 'cta', 'subtitle', 'Create your free account and submit your first request today.') ?></p>
        <div class="lp-cta__actions">
            <a href="/register" class="lp-btn lp-btn--primary lp-btn--lg">
                <i class="fas fa-user-plus"></i> <?= lp($lp, 'cta', 'btn_text', 'Create Free Account') ?>
            </a>
            <a href="/login" class="lp-btn lp-btn--ghost lp-btn--lg">Sign In</a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="lp-footer">
    <div class="lp-container lp-footer__inner">
        <div class="lp-footer__brand">
            <a href="/" class="lp-nav__logo" aria-label="ECU File Germany">
                <?php if (!empty($site_logo)): ?>
                    <img src="<?= \Core\App::url('storage/uploads/logo/' . \Core\View::escape($site_logo)) ?>" alt="Logo" class="lp-logo-img">
                <?php else: ?>
                    <i class="fas fa-microchip"></i>
                    <span>ECU<strong>File</strong>Germany</span>
                <?php endif; ?>
            </a>
            <p><?= lp($lp, 'footer', 'tagline', 'Professional ECU file service for performance enthusiasts and tuning shops.') ?></p>
        </div>
        <div class="lp-footer__links">
            <a href="/login">Sign In</a>
            <a href="/register">Register</a>
        </div>
    </div>
    <div class="lp-footer__bottom">
        <div class="lp-container" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
            <span>&copy; <?= date('Y') ?> ECU File Germany. All rights reserved.</span>
            <span style="font-size:.85rem;opacity:.7;">Web Tasarım <a href="https://hukumdar.com.tr" target="_blank" rel="noopener" style="color:inherit;text-decoration:underline;">Hükümdar Bilişim</a></span>
        </div>
    </div>
</footer>

<?php require_once 'generate-csrf-token.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#10251d">

    <!-- Primary Meta Tags -->
    <title>Register your interest | Irish Barbering Federation</title>
    <meta name="title" content="Register your interest | Irish Barbering Federation">
    <meta name="description" content="Register your interest in joining the Irish Barbering Federation and be one of the first 2,000 founding professional members of IBF 2000.">
    <meta name="keywords" content="join irish barbering federation, IBF 2000, barber membership ireland, register barber ireland, founding professional member">
    <meta name="author" content="Irish Barbering Federation">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://barbersfederationofireland.com/join.php">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://barbersfederationofireland.com/join.php">
    <meta property="og:title" content="Register your interest | Irish Barbering Federation">
    <meta property="og:description" content="Register your interest and be one of the first 2,000 founding professional members of IBF 2000.">
    <meta property="og:image" content="https://barbersfederationofireland.com/images/og-image.png">
    <meta property="og:locale" content="en_IE">
    <meta property="og:site_name" content="Irish Barbering Federation">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://barbersfederationofireland.com/join.php">
    <meta property="twitter:title" content="Register your interest | Irish Barbering Federation">
    <meta property="twitter:description" content="Register your interest and be one of the first 2,000 founding professional members of IBF 2000.">
    <meta property="twitter:image" content="https://barbersfederationofireland.com/images/og-image.png">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/favicon.png">

    <!-- Fonts & Stylesheets -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css">

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "Irish Barbering Federation",
        "alternateName": ["IBF", "Barbers Federation of Ireland"],
        "url": "https://barbersfederationofireland.com",
        "logo": "https://barbersfederationofireland.com/images/logo.png",
        "contactPoint": {
        "@type": "ContactPoint",
        "email": "info@barbersfederationofireland.com",
        "contactType": "customer service"
        }
    }
    </script>
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>
    <header class="site-header">
        <a class="brand" href="index.html" aria-label="Irish Barbering Federation home">
            <span class="brand-mark" aria-hidden="true">IBF<span>✳</span></span>
            <span class="brand-name">IRISH BARBERING<br>FEDERATION</span>
        </a>
        <button class="menu-toggle" aria-expanded="false" aria-controls="site-nav" aria-label="Open navigation"><span></span><span></span></button>
        <nav id="site-nav" class="site-nav" aria-label="Main navigation">
            <a href="index.html#ibf2000">IBF 2000</a>
            <a href="index.html#about">About</a>
            <a href="index.html#work">Our work</a>
            <a href="index.html#community">Community</a>
            <a href="index.html#updates">Updates</a>
            <a class="nav-cta" href="join.php" aria-current="page">Register interest <span aria-hidden="true">↗</span></a>
        </nav>
    </header>

    <main id="main">
        <section class="section join-section">
            <div class="section-kicker"><span>IBF 2000 / REGISTER YOUR INTEREST</span><span class="kicker-line"></span></div>
            <div class="join-grid">
                <div class="join-copy">
                    <h2>Be one of<br><em>the first 2,000.</em></h2>
                    <p class="lead">Add your name to the register of barbers interested in joining the Irish Barbering Federation.</p>
                    <p>Your details will be stored securely. You will be added to a mailing list which will be used to update you about developments with the Irish Barbering Federation, including when enrolment for IBF 2000 opens.</p>
                    <div class="member-price"><div><span>PROFESSIONAL MEMBERSHIP</span><strong>€190 <small>/ year</small></strong></div><p>IBF 2000 campaign membership fee</p></div>
                    <div class="founding-note"><span class="founding-star">✳</span><p><strong>Registering is free.</strong><br>You won’t be asked to pay anything until enrolment opens and member benefits are confirmed.</p></div>
                </div>

                <form id="userForm" class="join-form" action="submit-form.php" method="POST">
                    <div id="formMessage" class="form-message" role="status" hidden></div>
                    <?php echo csrf_field(); ?>

                    <div class="form-field">
                        <label for="name">Name *</label>
                        <input type="text" id="name" name="name" required maxlength="255" autocomplete="name">
                    </div>
                    <div class="form-field">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required maxlength="255" autocomplete="email">
                    </div>
                    <div class="form-field">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" maxlength="50" autocomplete="tel">
                    </div>
                    <div class="form-field">
                        <label for="experience">Years experience</label>
                        <input type="number" id="experience" name="experience" min="0" max="100">
                    </div>

                    <button type="submit" class="button button-lime" id="submitBtn">Register your interest <span aria-hidden="true">↗</span></button>
                    <p class="small-note">By submitting this form you agree to be added to the IBF register of interest and mailing list.</p>
                </form>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <a class="brand footer-brand" href="index.html"><span class="brand-mark">IBF<span>✳</span></span><span class="brand-name">IRISH BARBERING<br>FEDERATION</span></a>
        <span class="footer-motto">A stronger future for barbering in Ireland. · Site design by <a href="mailto:dstuartkelly@gmail.com">Daniel Stuart-Kelly</a></span>
        <span class="copyright">© <span id="year">2026</span> IRISH BARBERING FEDERATION</span>
    </footer>
    <script src="scripts/site.js" defer></script>
    <script src="scripts/form-handler.js"></script>
</body>
</html>

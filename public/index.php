<?php
require_once __DIR__ . '/../lib/bootstrap.php';
$siteName = config('site_name', 'Find Diesel Repair');
$baseUrl = rtrim(config('base_url', 'https://finddieselrepair.com'), '/');
$canonicalUrl = $baseUrl ?: ('https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
$ogImage = $canonicalUrl . '/images/og-image.jpg';
$prefillZip = isset($_GET['zip']) ? preg_replace('/[^0-9]/', '', $_GET['zip']) : '';
$prefillZip = strlen($prefillZip) >= 5 ? substr($prefillZip, 0, 5) : '';
$prefillCity = isset($_GET['city']) ? trim($_GET['city']) : '';
$prefillState = isset($_GET['state']) ? trim($_GET['state']) : '';
$prefillValue = $prefillZip ?: ($prefillCity && $prefillState ? $prefillCity . ', ' . $prefillState : ($prefillCity ?: ''));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Title & Meta -->
    <title><?php echo htmlspecialchars($siteName); ?> | Enter Your ZIP – Find Closest Diesel Repair Shops Near You</title>
    <meta name="description" content="Find diesel repair shops and mobile diesel mechanics near you. Enter your ZIP code for a list sorted by distance. HD trucks, MD trucks, heavy duty, fleet—get service fast.">
    <meta name="keywords" content="diesel repair near me, heavy duty truck repair, medium duty truck repair, HD diesel mechanic, diesel truck repair, mobile diesel repair, fleet diesel">
    <meta name="author" content="<?php echo htmlspecialchars($siteName); ?>">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">

    <?php if ($baseUrl): ?>
    <link rel="canonical" href="<?php echo htmlspecialchars($baseUrl . '/'); ?>">
    <?php endif; ?>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonicalUrl . '/'); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($siteName); ?> | Find Closest Diesel Repair Shops">
    <meta property="og:description" content="Enter your ZIP to find diesel repair facilities and mobile mechanics sorted by distance. HD and MD trucks, heavy duty, fleet service.">
    <meta property="og:image" content="<?php echo htmlspecialchars($ogImage); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="<?php echo htmlspecialchars($siteName); ?>">
    <meta property="og:locale" content="en_US">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($siteName); ?> | Find Closest Diesel Repair">
    <meta name="twitter:description" content="Enter your ZIP to find diesel repair shops for HD and MD trucks, fleet, heavy duty—sorted by distance.">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($ogImage); ?>">

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://cdn.tailwindcss.com" crossorigin>
    <link rel="preconnect" href="https://ajax.googleapis.com" crossorigin>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'fdr-amber': '#d97706',
                        'fdr-amber-dark': '#b45309',
                        'fdr-orange': '#ea580c',
                    }
                }
            },
            safelist: [
                'text-fdr-amber', 'bg-fdr-amber', 'hover:bg-fdr-amber-dark', 'hover:text-fdr-amber',
                'border-fdr-amber', 'text-fdr-orange'
            ]
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Schema: WebSite + SearchAction -->
    <script type="application/ld+json">
    {"@context":"https://schema.org","@type":"WebSite","name":"<?php echo htmlspecialchars($siteName); ?>","url":"<?php echo htmlspecialchars($canonicalUrl); ?>","description":"Find diesel repair shops and mobile diesel mechanics near you. Search by ZIP code.","potentialAction":{"@type":"SearchAction","target":{"@type":"EntryPoint","urlTemplate":"<?php echo htmlspecialchars($canonicalUrl); ?>?zip={search_term_string}"},"query-input":"required name=search_term_string"}}
    </script>
    <!-- Schema: Organization -->
    <script type="application/ld+json">
    {"@context":"https://schema.org","@type":"Organization","name":"<?php echo htmlspecialchars($siteName); ?>","url":"<?php echo htmlspecialchars($canonicalUrl); ?>","description":"The directory for diesel repair shops and mobile diesel mechanics. Find service for HD trucks, MD trucks, and fleet vehicles."}
    </script>
    <!-- Schema: FAQPage -->
    <script type="application/ld+json">
    {"@context":"https://schema.org","@type":"FAQPage","mainEntity":[{"@type":"Question","name":"How much does diesel repair cost?","acceptedAnswer":{"@type":"Answer","text":"Diesel repair costs vary widely. Basic maintenance like oil changes run $150-$300, while injector replacement can cost $1,500-$4,000+. Mobile diesel mechanics typically charge $100-$175 per hour. Get multiple quotes for major repairs."}},{"@type":"Question","name":"Do mobile diesel mechanics come to you?","acceptedAnswer":{"@type":"Answer","text":"Yes. Many diesel repair shops offer mobile service. They come to your yard, shop, jobsite, or roadside. Ideal for HD/MD trucks and fleet vehicles that are difficult to tow."}},{"@type":"Question","name":"What can mobile diesel mechanics fix?","acceptedAnswer":{"@type":"Answer","text":"Mobile diesel techs can handle common repairs on-site: diagnostics, fuel system work, electrical issues, air brake service, DEF problems, minor engine repairs, and routine maintenance. Major engine rebuilds usually require a shop."}},{"@type":"Question","name":"How do I find a reliable diesel mechanic?","acceptedAnswer":{"@type":"Answer","text":"Look for shops with ASE certifications, read Google reviews from truck owners and fleet managers, ask for written estimates, and check if they work on your vehicle type (HD, MD, semi, etc). Our directory lists verified shops with ratings to help you choose."}}]}
    </script>
    <!-- Schema: BreadcrumbList -->
    <script type="application/ld+json">
    {"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":"<?php echo htmlspecialchars($canonicalUrl); ?>"}]}
    </script>
    <style>
        .hero-gradient { background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(217, 119, 6, 0.9) 100%); }
        .search-glow:focus { box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.3); }
        .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.15); }
        /* Fallback for fdr-amber colors (Tailwind CDN may not generate custom color utilities) */
        .text-fdr-amber { color: #d97706 !important; }
        .bg-fdr-amber { background-color: #d97706 !important; }
        .border-fdr-amber { border-color: #d97706 !important; }
        .hover\:text-fdr-amber:hover { color: #d97706 !important; }
        .hover\:bg-fdr-amber-dark:hover { background-color: #b45309 !important; }
        .focus\:text-fdr-amber:focus { color: #d97706 !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="./" class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-8 h-8 text-fdr-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 14V9h4v5"/><rect x="6" y="9" width="12" height="6" rx="1"/><path d="M18 12h2v3h-2"/><circle cx="6.5" cy="18" r="2"/><circle cx="17" cy="18" r="2"/></svg>
                    <span><?php echo htmlspecialchars($siteName); ?></span>
                </a>
                <div class="hidden md:flex items-center space-x-6">
                    <a href="./" class="text-slate-600 hover:text-fdr-amber transition font-medium">Home</a>
                    <a href="#faq" class="text-slate-600 hover:text-fdr-amber transition font-medium">FAQ</a>
                    <a href="#about" class="text-slate-600 hover:text-fdr-amber transition font-medium">About</a>
                    <a href="#get-listed" class="text-slate-600 hover:text-fdr-amber transition font-medium">List Your Business</a>
                </div>
                <button id="mobile-menu-button" class="md:hidden text-slate-600 hover:text-fdr-amber focus:outline-none" aria-label="Toggle menu" aria-expanded="false">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-100">
            <a href="./" class="block py-3 px-4 text-slate-600 hover:bg-slate-50">Home</a>
            <a href="#faq" class="block py-3 px-4 text-slate-600 hover:bg-slate-50">FAQ</a>
            <a href="#about" class="block py-3 px-4 text-slate-600 hover:bg-slate-50">About</a>
            <a href="#get-listed" class="block py-3 px-4 text-slate-600 hover:bg-slate-50">List Your Business</a>
        </div>
    </nav>

    <main>
        <section class="relative text-white text-center bg-slate-700 min-h-[32rem] flex items-center" aria-labelledby="hero-heading">
            <div class="hero-gradient absolute inset-0"></div>
            <div class="relative container mx-auto px-4 py-20 md:py-28 w-full">
                <h1 id="hero-heading" class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight max-w-4xl mx-auto">
                    Enter Your ZIP – Find the <span class="text-amber-300">Closest Diesel Repair</span>
                </h1>
                <p class="mt-4 text-lg md:text-xl max-w-2xl mx-auto text-white/90">
                    Diesel repair shops and mobile mechanics for HD and MD trucks, heavy duty, and fleet. Enter your ZIP or use your location—results sorted by distance.
                </p>

                <div class="mt-8 w-full max-w-lg mx-auto bg-white/10 backdrop-blur-md p-6 rounded-xl border border-white/20">
                    <form class="zip-form space-y-4" id="searchForm" role="search" aria-label="Find diesel repair by ZIP code or city, state">
                        <div>
                            <label for="zipInput" class="sr-only">ZIP code or city, state</label>
                            <input id="zipInput" class="zip-input w-full px-5 py-4 text-lg text-slate-800 rounded-lg border-0 focus:ring-4 focus:ring-white/30 search-glow" type="text" autocomplete="off" placeholder="ZIP code or city, state" name="zipCode" value="<?php echo htmlspecialchars($prefillValue); ?>" required>
                        </div>
                        <fieldset class="text-center text-white">
                            <legend class="mb-2 text-white/80">Search within:</legend>
                            <div class="flex flex-wrap justify-center gap-x-6 gap-y-2">
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="dist" value="25" class="h-4 w-4 text-fdr-amber"> <span>25 mi</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="dist" value="50" checked class="h-4 w-4 text-fdr-amber"> <span>50 mi</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="radio" name="dist" value="100" class="h-4 w-4 text-fdr-amber"> <span>100 mi</span></label>
                            </div>
                        </fieldset>
                        <button type="submit" class="w-full bg-fdr-amber text-white font-bold py-4 px-6 rounded-lg shadow-lg hover:bg-fdr-amber-dark transition">Find Closest Diesel Repair</button>
                    </form>
                    <div class="flex items-center my-4">
                        <div class="flex-grow border-t border-white/30"></div>
                        <span class="flex-shrink mx-4 text-white/70 text-sm">or</span>
                        <div class="flex-grow border-t border-white/30"></div>
                    </div>
                    <button type="button" class="locate w-full bg-white/20 hover:bg-white/30 text-white font-semibold py-3 px-6 rounded-lg flex items-center justify-center gap-2 border border-white/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Use My Location
                    </button>
                </div>

                <!-- Trust indicators -->
                <div class="mt-6 flex flex-wrap justify-center gap-6 text-white/85 text-sm">
                    <span class="flex items-center gap-2"><svg class="w-5 h-5 text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Sorted by distance</span>
                    <span class="flex items-center gap-2"><svg class="w-5 h-5 text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Shops & mobile mechanics</span>
                    <span class="flex items-center gap-2"><svg class="w-5 h-5 text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>HD & MD trucks</span>
                </div>
            </div>
        </section>

        <section id="resCon" class="container mx-auto px-4 py-8" aria-live="polite">
            <div id="resultsSummary" class="mb-4 text-slate-600 font-medium hidden"></div>
            <div class="results-container grid grid-cols-1 md:grid-cols-2 gap-6"></div>
        </section>

        <section id="faq" class="py-16 bg-slate-50 border-t border-slate-200">
            <div class="container mx-auto px-4 max-w-3xl">
                <h2 class="text-2xl font-bold text-slate-800 mb-8 text-center">Frequently Asked Questions</h2>
                <dl class="space-y-6">
                    <div>
                        <dt class="text-lg font-semibold text-slate-800">How much does diesel repair cost?</dt>
                        <dd class="mt-2 text-slate-600">Diesel repair costs vary. Basic maintenance like oil changes run $150–$300; injector replacement can cost $1,500–$4,000+. Mobile diesel mechanics typically charge $100–$175/hr. Get multiple quotes for major repairs.</dd>
                    </div>
                    <div>
                        <dt class="text-lg font-semibold text-slate-800">Do mobile diesel mechanics come to you?</dt>
                        <dd class="mt-2 text-slate-600">Yes. Many diesel shops offer mobile service—they come to your yard, shop, jobsite, or roadside. Ideal for HD/MD trucks and fleet vehicles that are difficult to tow.</dd>
                    </div>
                    <div>
                        <dt class="text-lg font-semibold text-slate-800">What can mobile diesel mechanics fix?</dt>
                        <dd class="mt-2 text-slate-600">Mobile diesel techs handle diagnostics, fuel system work, electrical issues, air brake service, DEF problems, minor engine repairs, and routine maintenance. Major engine rebuilds usually require a shop.</dd>
                    </div>
                    <div>
                        <dt class="text-lg font-semibold text-slate-800">How do I find a reliable diesel mechanic?</dt>
                        <dd class="mt-2 text-slate-600">Look for ASE certifications, read Google reviews from truck owners and fleet managers, ask for written estimates, and check they work on your vehicle type (HD, MD, semi). Our directory lists verified shops with ratings.</dd>
                    </div>
                </dl>
            </div>
        </section>

        <section id="about" class="py-12 bg-white border-t border-slate-200">
            <div class="container mx-auto px-4 max-w-4xl">
                <h2 class="text-3xl font-bold text-center mb-6">About <?php echo htmlspecialchars($siteName); ?></h2>
                <p class="text-slate-600 text-center">
                    <?php echo htmlspecialchars($siteName); ?> helps you find diesel repair facilities sorted by distance. Whether you need service for HD trucks, MD trucks, or fleet vehicles—enter your ZIP code or use your current location to see nearby shops and mobile diesel mechanics.
                </p>
            </div>
        </section>

        <section id="get-listed" class="py-12 bg-slate-100 border-t border-slate-200">
            <div class="container mx-auto px-4 max-w-2xl text-center">
                <h2 class="text-2xl font-bold text-slate-800 mb-3">Own a Diesel Repair Shop?</h2>
                <p class="text-slate-600 mb-6">Get your business in front of drivers who need diesel service. Reach fleet managers, truckers, and HD/MD truck owners searching for repair.</p>
                <a href="mailto:<?php echo htmlspecialchars(config('contact_email', 'list@finddieselrepair.com')); ?>?subject=List%20my%20diesel%20repair%20business" class="inline-block bg-fdr-amber text-white font-semibold px-6 py-3 rounded-lg hover:bg-fdr-amber-dark transition">Get Listed</a>
            </div>
        </section>
    </main>

    <footer class="bg-slate-800 text-white py-10">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap justify-center gap-x-6 gap-y-2 mb-4">
                <a href="./" class="text-slate-300 hover:text-white transition">Home</a>
                <a href="#faq" class="text-slate-300 hover:text-white transition">FAQ</a>
                <a href="#about" class="text-slate-300 hover:text-white transition">About</a>
                <a href="#get-listed" class="text-slate-300 hover:text-white transition">List Your Business</a>
                <a href="mailto:<?php echo htmlspecialchars(config('contact_email', 'contact@finddieselrepair.com')); ?>" class="text-slate-300 hover:text-white transition">Contact</a>
                <a href="privacy.php" class="text-slate-300 hover:text-white transition">Privacy</a>
                <a href="terms.php" class="text-slate-300 hover:text-white transition">Terms</a>
            </div>
            <p class="text-center text-slate-400 text-sm">
                &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteName); ?>. All rights reserved.
            </p>
        </div>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
    $(function(){
        $('#mobile-menu-button').on('click', function(){ $('#mobile-menu').toggleClass('hidden'); $(this).attr('aria-expanded', $('#mobile-menu').hasClass('hidden') ? 'false' : 'true'); });
        $('#mobile-menu a').on('click', function(){ $('#mobile-menu').addClass('hidden'); $('#mobile-menu-button').attr('aria-expanded', 'false'); });

        function buildSearchData(searchValue, distValue) {
            var data = { myDist: distValue || '50' };
            var trimmed = (searchValue || '').trim();
            if (/^\d+$/.test(trimmed)) {
                data.myZip = trimmed;
            } else if (trimmed) {
                var city = trimmed, state = '';
                if (trimmed.indexOf(',') !== -1) {
                    var idx = trimmed.indexOf(',');
                    city = trimmed.substring(0, idx).trim();
                    state = trimmed.substring(idx + 1).trim();
                } else {
                    var parts = trimmed.split(/\s+/);
                    if (parts.length >= 2 && parts[parts.length - 1].length === 2) {
                        city = parts.slice(0, -1).join(' ').trim();
                        state = parts[parts.length - 1];
                    }
                }
                data.city = city;
                data.state = state;
            }
            return data;
        }

        function handleSearchResponse(resp){
            var list = (resp && resp.results) ? resp.results : (Array.isArray(resp) ? resp : []);
            var err = (resp && resp.error) ? resp.error : null;
            var radius = (resp && resp.query && resp.query.radius) ? resp.query.radius : 50;
            $('.results-container').empty();
            $('#resultsSummary').addClass('hidden').text('');
            if (err) { $('.results-container').html('<div class="col-span-full text-center py-8 text-red-600">' + err + '</div>'); return; }
            if (!list.length) { $('.results-container').html('<div class="col-span-full text-center py-8 text-slate-600">No diesel repair facilities in that radius. Try a larger distance.</div>'); return; }
            $('#resultsSummary').removeClass('hidden').text('Showing ' + list.length + ' shop' + (list.length === 1 ? '' : 's') + ' within ' + radius + ' miles');
            $.each(list, function(i, item){ $('.results-container').append(renderCard(item)); });
            $('html, body').animate({ scrollTop: $('#resCon').offset().top - 80 }, 400);
        }

        function renderCard(item){
            var mapUrl = item.PLACE ? 'https://www.google.com/maps/search/?api=1&query=' + (item.LAT || '') + '%2C' + (item.LNG || '') + '&query_place_id=' + (item.PLACE || '') : (item.MAP || '#');
            var distanceHtml = (item.distance_in_miles != null && item.distance_in_miles !== '') ? '<span class="text-slate-500 text-sm font-medium">' + item.distance_in_miles + ' mi away</span>' : '';
            return '<article class="rescard bg-white rounded-xl shadow-sm p-5 flex flex-col justify-between card-hover">' +
                '<div class="rescard-header flex flex-wrap gap-2 text-xs mb-3 items-center justify-between">' +
                '<div class="flex flex-wrap gap-2">' +
                (item.VERIFIED ? '<span class="inline-flex items-center gap-1 bg-green-100 text-green-800 px-2 py-1 rounded-full">Verified</span>' : '') +
                (item.MOBILE ? '<span class="inline-flex items-center bg-purple-100 text-purple-800 px-2 py-1 rounded-full">Mobile Service</span>' : '') +
                '</div>' + distanceHtml + '</div><div class="card-content flex-grow">' +
                '<h3 class="text-lg font-bold text-slate-800">' + (item.SITE ? '<a class="hover:text-fdr-amber transition" href="' + item.SITE + '" target="_blank" rel="noopener">' : '') + (item.NAME || '') + (item.SITE ? '</a>' : '') + '</h3>' +
                (item.ADDRESS ? '<p class="text-sm text-slate-600 mt-1">' + item.ADDRESS + '</p>' : '') +
                '</div><div class="cta mt-4 pt-4 border-t border-slate-200 flex flex-wrap justify-between items-center gap-2">' +
                '<a class="inline-flex items-center gap-1 text-red-600 hover:text-red-700 font-medium text-sm" href="' + mapUrl + '" target="_blank" rel="noopener" aria-label="Get directions on Google Maps">' +
                '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg> Directions</a>' +
                (item.RATE ? '<div class="flex items-center gap-1"><svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><span class="font-bold text-lg">' + item.RATE + '</span></div>' : '') +
                (item.PHONE ? '<a class="bg-fdr-amber text-white px-4 py-2 rounded-lg font-semibold flex items-center gap-2 hover:bg-fdr-amber-dark transition" href="tel:' + item.PHONE.replace(/\D/g,'') + '">Call</a>' : '') +
                '</div></article>';
        }

        <?php if ($prefillValue): ?>
        (function autoSearch(){
            var val = $('#zipInput').val();
            if ((val || '').trim()) {
                $('.results-container').html('<div class="col-span-full text-center py-8"><div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-fdr-amber border-t-transparent"></div><p class="mt-2 text-slate-600">Finding closest diesel repair...</p></div>');
                $.ajax({ url: 'search.php', type: 'POST', data: buildSearchData(val, $('input[name=dist]:checked').val()), dataType: 'json' })
                    .done(handleSearchResponse)
                    .fail(function(){ $('.results-container').html('<div class="col-span-full text-center py-8 text-red-600">Something went wrong. Try again.</div>'); });
            }
        })();
        <?php endif; ?>

        $('#searchForm').submit(function(e){
            e.preventDefault();
            var searchVal = ($('#zipInput').val() || '').trim();
            if (!searchVal) { $('.results-container').html('<div class="col-span-full text-center py-8 text-red-600">Please enter a ZIP code or city, state.</div>'); return; }
            var zipOnly = searchVal.replace(/\D/g,'');
            if (/^\d+$/.test(searchVal) && zipOnly.length < 5) { $('.results-container').html('<div class="col-span-full text-center py-8 text-red-600">Please enter a valid 5-digit ZIP code.</div>'); return; }
            $('.results-container').html('<div class="col-span-full text-center py-8"><div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-fdr-amber border-t-transparent"></div><p class="mt-2 text-slate-600">Finding closest diesel repair...</p></div>');
            $.ajax({ url: 'search.php', type: 'POST', data: buildSearchData(searchVal, $('input[name=dist]:checked').val()), dataType: 'json' })
                .done(handleSearchResponse)
                .fail(function(){ $('.results-container').html('<div class="col-span-full text-center py-8 text-red-600">Something went wrong. Try again.</div>'); });
        });

        $('.locate').click(function(){
            $('.results-container').html('<div class="col-span-full text-center py-8"><div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-fdr-amber border-t-transparent"></div><p class="mt-2 text-slate-600">Getting location...</p></div>');
            if (!navigator.geolocation) { $('.results-container').html('<div class="col-span-full text-center py-8 text-red-600">Geolocation not supported.</div>'); return; }
            navigator.geolocation.getCurrentPosition(
                function(pos){
                    $.ajax({ url: 'search.php', type: 'POST', data: { mylat: pos.coords.latitude, mylng: pos.coords.longitude, myDist: $('input[name=dist]:checked').val() || 50 }, dataType: 'json' })
                        .done(handleSearchResponse)
                        .fail(function(){ $('.results-container').html('<div class="col-span-full text-center py-8 text-red-600">Something went wrong.</div>'); });
                },
                function(){ $('.results-container').html('<div class="col-span-full text-center py-8 text-red-600">Location denied. Enter a ZIP code instead.</div>'); }
            );
        });
    });
    </script>
</body>
</html>

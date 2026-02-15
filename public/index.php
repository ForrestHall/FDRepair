<?php
require_once __DIR__ . '/../lib/bootstrap.php';
$siteName = config('site_name', 'Find Diesel Repair');
$baseUrl = config('base_url', 'https://finddieselrepair.com');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($siteName); ?> | Enter Your ZIP – Find the Closest Diesel Repair</title>
    <meta name="description" content="Enter your ZIP code to find the closest diesel repair facility. See nearby shops and mobile diesel mechanics by distance.">
    <meta name="robots" content="index, follow">
    <?php if ($baseUrl): ?>
    <link rel="canonical" href="<?php echo htmlspecialchars(rtrim($baseUrl, '/') . '/'); ?>">
    <?php endif; ?>
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
    <script type="application/ld+json">
    {"@context":"https://schema.org","@type":"WebSite","name":"<?php echo htmlspecialchars($siteName); ?>","url":"<?php echo htmlspecialchars($baseUrl ?: ('https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'))); ?>","potentialAction":{"@type":"SearchAction","target":{"@type":"EntryPoint","urlTemplate":"<?php echo htmlspecialchars($baseUrl ?: ''); ?>?zip={search_term_string}"},"query-input":"required name=search_term_string"}}
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
                    <svg class="w-8 h-8 text-fdr-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2" ry="2" stroke-width="2"></rect><path d="M16 8h4l3 3v5h-7V8z" stroke-width="2"></path><circle cx="5.5" cy="18.5" r="2.5" stroke-width="2"></circle><circle cx="18.5" cy="18.5" r="2.5" stroke-width="2"></circle></svg>
                    <span><?php echo htmlspecialchars($siteName); ?></span>
                </a>
                <div class="hidden md:flex items-center space-x-6">
                    <a href="./" class="text-fdr-amber font-semibold">Home</a>
                </div>
                <button id="mobile-menu-button" class="md:hidden text-slate-600 hover:text-fdr-amber focus:outline-none" aria-label="Toggle menu" aria-expanded="false">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-100">
            <a href="./" class="block py-3 px-4 text-fdr-amber font-semibold bg-slate-50">Home</a>
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
                    Get a list of diesel repair facilities near you, sorted by distance. Enter your ZIP code or use your location.
                </p>

                <div class="mt-8 w-full max-w-lg mx-auto bg-white/10 backdrop-blur-md p-6 rounded-xl border border-white/20">
                    <form class="zip-form space-y-4" id="searchForm" role="search" aria-label="Find closest diesel repair by ZIP code">
                        <div>
                            <label for="zipInput" class="sr-only">Enter your ZIP code to find the closest diesel repair facility</label>
                            <input id="zipInput" class="zip-input w-full px-5 py-4 text-lg text-slate-800 rounded-lg border-0 focus:ring-4 focus:ring-white/30 search-glow" type="text" maxlength="5" autocomplete="postal-code" inputmode="numeric" pattern="[0-9]{5}" placeholder="Enter your ZIP code" name="zipCode" required>
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
            </div>
        </section>

        <section id="resCon" class="container mx-auto px-4 py-8" aria-live="polite">
            <div class="results-container grid grid-cols-1 md:grid-cols-2 gap-6"></div>
        </section>

        <section class="py-16 bg-white">
            <div class="container mx-auto px-4 max-w-4xl">
                <h2 class="text-3xl font-bold text-center mb-6">Closest Diesel Repair Facilities to You</h2>
                <p class="text-slate-600 text-center">
                    <?php echo htmlspecialchars($siteName); ?> lets you enter your ZIP code and see the closest diesel repair facilities, sorted by distance. Find shops and mobile diesel mechanics near you.
                </p>
            </div>
        </section>
    </main>

    <footer class="bg-slate-800 text-white py-8">
        <div class="container mx-auto px-4 text-center text-slate-400 text-sm">
            &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteName); ?>. All rights reserved.
        </div>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
    $(function(){
        $('#mobile-menu-button').on('click', function(){ $('#mobile-menu').toggleClass('hidden'); $(this).attr('aria-expanded', $('#mobile-menu').hasClass('hidden') ? 'false' : 'true'); });

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
                '</div><div class="cta mt-4 pt-4 border-t border-slate-200 flex justify-between items-center">' +
                '<a class="hover:opacity-80 transition" href="' + mapUrl + '" target="_blank" rel="noopener" aria-label="View on Google Maps">' +
                '<svg class="w-10 h-10 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg></a>' +
                (item.RATE ? '<div class="flex items-center gap-1"><svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><span class="font-bold text-lg">' + item.RATE + '</span></div>' : '') +
                (item.PHONE ? '<a class="bg-fdr-amber text-white px-4 py-2 rounded-lg font-semibold flex items-center gap-2 hover:bg-fdr-amber-dark transition" href="tel:' + item.PHONE.replace(/\D/g,'') + '">Call</a>' : '') +
                '</div></article>';
        }

        function handleSearchResponse(resp){
            var list = (resp && resp.results) ? resp.results : (Array.isArray(resp) ? resp : []);
            var err = (resp && resp.error) ? resp.error : null;
            $('.results-container').empty();
            if (err) { $('.results-container').html('<div class="col-span-full text-center py-8 text-red-600">' + err + '</div>'); return; }
            if (!list.length) { $('.results-container').html('<div class="col-span-full text-center py-8 text-slate-600">No diesel repair facilities in that radius. Try a larger distance.</div>'); return; }
            $.each(list, function(i, item){ $('.results-container').append(renderCard(item)); });
            $('html, body').animate({ scrollTop: $('#resCon').offset().top - 80 }, 400);
        }

        $('#searchForm').submit(function(e){
            e.preventDefault();
            var zip = $('#zipInput').val().replace(/\D/g,'').slice(0,5);
            if (zip.length < 5) { $('.results-container').html('<div class="col-span-full text-center py-8 text-red-600">Please enter a valid 5-digit ZIP code.</div>'); return; }
            $('.results-container').html('<div class="col-span-full text-center py-8"><div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-fdr-amber border-t-transparent"></div><p class="mt-2 text-slate-600">Finding closest diesel repair...</p></div>');
            $.ajax({ url: 'search.php', type: 'POST', data: { myZip: zip, myDist: $('input[name=dist]:checked').val() }, dataType: 'json' })
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

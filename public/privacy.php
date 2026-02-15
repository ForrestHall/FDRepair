<?php
require_once __DIR__ . '/../lib/bootstrap.php';
$siteName = config('site_name', 'Find Diesel Repair');
$baseUrl = rtrim(config('base_url', 'https://finddieselrepair.com'), '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy | <?php echo htmlspecialchars($siteName); ?></title>
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo htmlspecialchars($baseUrl); ?>/privacy.php">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>.text-fdr-amber { color: #d97706; } .hover\:text-fdr-amber:hover { color: #d97706; }</style>
</head>
<body class="bg-slate-50 text-slate-800">
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4">
            <a href="./" class="text-xl font-bold text-slate-800"><?php echo htmlspecialchars($siteName); ?></a>
            <a href="./" class="ml-6 text-slate-600 hover:text-fdr-amber">← Back to Home</a>
        </div>
    </nav>
    <main class="container mx-auto px-4 py-12 max-w-3xl">
        <h1 class="text-3xl font-bold mb-6">Privacy Policy</h1>
        <p class="text-slate-600 mb-4">Last updated: <?php echo date('F j, Y'); ?></p>
        <div class="prose prose-slate max-w-none text-slate-700 space-y-4">
            <p><?php echo htmlspecialchars($siteName); ?> ("we", "our") respects your privacy. This policy describes what information we collect and how we use it.</p>
            <h2 class="text-xl font-semibold mt-6">Information We Collect</h2>
            <p>When you use our site, we may collect your ZIP code or location (with your permission) to show nearby diesel repair facilities. We do not store this information for marketing purposes.</p>
            <h2 class="text-xl font-semibold mt-6">How We Use It</h2>
            <p>We use your location or ZIP code solely to provide search results. We may use cookies or similar technologies for basic site function (e.g. preferences).</p>
            <h2 class="text-xl font-semibold mt-6">Third Parties</h2>
            <p>Our site loads Tailwind CSS and jQuery from CDNs. Search results may include links to external sites (Google Maps, business websites). We are not responsible for their privacy practices.</p>
            <h2 class="text-xl font-semibold mt-6">Contact</h2>
            <p>Questions? <a href="mailto:<?php echo htmlspecialchars(config('contact_email', 'privacy@finddieselrepair.com')); ?>" class="text-fdr-amber hover:underline">Contact us</a>.</p>
        </div>
    </main>
    <footer class="bg-slate-800 text-white py-6 mt-12">
        <div class="container mx-auto px-4 text-center text-slate-400 text-sm">
            <a href="./" class="text-slate-300 hover:text-white">Home</a> · <a href="terms.php" class="text-slate-300 hover:text-white">Terms</a>
            <p class="mt-2">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteName); ?></p>
        </div>
    </footer>
</body>
</html>

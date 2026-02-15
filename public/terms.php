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
    <title>Terms of Service | <?php echo htmlspecialchars($siteName); ?></title>
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo htmlspecialchars($baseUrl); ?>/terms.php">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>.text-fdr-amber { color: #d97706; }</style>
</head>
<body class="bg-slate-50 text-slate-800">
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4">
            <a href="./" class="text-xl font-bold text-slate-800"><?php echo htmlspecialchars($siteName); ?></a>
            <a href="./" class="ml-6 text-slate-600 hover:text-fdr-amber">← Back to Home</a>
        </div>
    </nav>
    <main class="container mx-auto px-4 py-12 max-w-3xl">
        <h1 class="text-3xl font-bold mb-6">Terms of Service</h1>
        <p class="text-slate-600 mb-4">Last updated: <?php echo date('F j, Y'); ?></p>
        <div class="prose prose-slate max-w-none text-slate-700 space-y-4">
            <p>By using <?php echo htmlspecialchars($siteName); ?>, you agree to these terms.</p>
            <h2 class="text-xl font-semibold mt-6">Use of Service</h2>
            <p>This site provides a directory of diesel repair facilities. We strive for accuracy but do not guarantee the correctness, completeness, or availability of any listing. Always verify details with the business before scheduling service.</p>
            <h2 class="text-xl font-semibold mt-6">No Warranty</h2>
            <p>The service is provided "as is." We disclaim all warranties. We are not responsible for the quality of work, pricing, or conduct of any listed business.</p>
            <h2 class="text-xl font-semibold mt-6">Limitation of Liability</h2>
            <p>We are not liable for any damages arising from your use of this site or from contacting or using services of any listed business.</p>
            <h2 class="text-xl font-semibold mt-6">Contact</h2>
            <p>Questions? <a href="mailto:<?php echo htmlspecialchars(config('contact_email', 'legal@finddieselrepair.com')); ?>" class="text-fdr-amber hover:underline">Contact us</a>.</p>
        </div>
    </main>
    <footer class="bg-slate-800 text-white py-6 mt-12">
        <div class="container mx-auto px-4 text-center text-slate-400 text-sm">
            <a href="./" class="text-slate-300 hover:text-white">Home</a> · <a href="privacy.php" class="text-slate-300 hover:text-white">Privacy</a>
            <p class="mt-2">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteName); ?></p>
        </div>
    </footer>
</body>
</html>

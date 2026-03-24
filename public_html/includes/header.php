<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang['lang_code']); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    <!-- Primary SEO -->
    <title><?php echo htmlspecialchars($lang['meta_title']); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($lang['meta_description']); ?>">
    <meta name="keywords"    content="<?php echo htmlspecialchars($lang['meta_keywords']); ?>">
    <meta name="author"      content="CronoTimer">
    <link rel="canonical"    href="https://cronotimer.salernohub.net/?lang=<?php echo $lang_code; ?>">

    <!-- hreflang for i18n SEO -->
    <link rel="alternate" hreflang="it" href="https://cronotimer.salernohub.net/?lang=it">
    <link rel="alternate" hreflang="en" href="https://cronotimer.salernohub.net/?lang=en">
    <link rel="alternate" hreflang="fr" href="https://cronotimer.salernohub.net/?lang=fr">
    <link rel="alternate" hreflang="x-default" href="https://cronotimer.salernohub.net/">

    <!-- Open Graph -->
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="https://cronotimer.salernohub.net/">
    <meta property="og:title"       content="<?php echo htmlspecialchars($lang['meta_title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($lang['meta_description']); ?>">
    <meta property="og:image"       content="https://cronotimer.salernohub.net/assets/img/og-image.png">

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?php echo htmlspecialchars($lang['meta_title']); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($lang['meta_description']); ?>">

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0a0a1a">
    <meta name="apple-mobile-web-app-capable"         content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title"            content="CronoTimer">
    <link rel="apple-touch-icon" href="/assets/img/icon-192.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <?php if ($theme === 'neon'):  ?><link rel="stylesheet" href="assets/css/theme-neon.css"><?php endif; ?>
    <?php if ($theme === 'futuristic'): ?><link rel="stylesheet" href="assets/css/theme-futuristic.css"><?php endif; ?>
</head>
<body class="theme-<?php echo htmlspecialchars($theme); ?>" data-lang="<?php echo $lang_code; ?>" data-lap-label="<?php echo htmlspecialchars($lang['lap_label']); ?>" data-expired="<?php echo htmlspecialchars($lang['timer_expired']); ?>">

<header>
    <div class="container">
        <h1 class="logo-title">Crono<span class="accent">Timer</span></h1>
        <?php include __DIR__ . '/nav.php'; ?>
    </div>
</header>
<main class="container">

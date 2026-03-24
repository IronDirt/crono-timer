<?php
$allowed_langs = ['it', 'en', 'fr'];
$lang_code = $_GET['lang'] ?? 'it';
if (!in_array($lang_code, $allowed_langs)) { $lang_code = 'it'; }

$allowed_themes = ['light', 'dark', 'neon', 'futuristic'];
$theme = $_GET['theme'] ?? 'dark';
if (!in_array($theme, $allowed_themes)) { $theme = 'dark'; }

$lang = include __DIR__ . "/lang/{$lang_code}.php";
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang['lang_code']); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dual Screen - CronoTimer</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <?php if ($theme === 'neon'):  ?><link rel="stylesheet" href="assets/css/theme-neon.css"><?php endif; ?>
    <?php if ($theme === 'futuristic'): ?><link rel="stylesheet" href="assets/css/theme-futuristic.css"><?php endif; ?>
    <style>
        body { overflow: hidden; justify-content: center; }
        main { padding: 0; justify-content: center; align-items: center; display: flex; height: 100vh; width: 100%; flex-direction: column; }
        .timer-display { font-size: 15vw; margin: 0; line-height: 1; }
        .tool-section { display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%; }
        .laps-container { margin-top: 3rem; max-height: 35vh; width: 80vw; max-width: 600px; }
        .lap-item { font-size: 1.5rem; padding: 0.8rem 0; }
        .lap-item .lap-num { font-size: 1.2rem; }
    </style>
</head>
<body class="theme-<?php echo htmlspecialchars($theme); ?>">
<main>
    <div id="stopwatch-section" class="tool-section hidden">
        <div class="timer-display" id="stopwatch-display">00:00:00.00</div>
        <div class="laps-container hidden" id="laps-wrapper">
            <ul id="laps-list"></ul>
        </div>
    </div>
    
    <div id="timer-section" class="tool-section hidden">
        <div class="timer-display" id="timer-display">00:00:00</div>
    </div>
</main>
<script>
    const bc = new BroadcastChannel('cronotimer_sync');
    const stopwatchSection = document.getElementById('stopwatch-section');
    const timerSection = document.getElementById('timer-section');
    const swDisplay = document.getElementById('stopwatch-display');
    const tmrDisplay = document.getElementById('timer-display');
    const lapsWrapper = document.getElementById('laps-wrapper');
    const lapsList = document.getElementById('laps-list');

    bc.onmessage = (e) => {
        const data = e.data;
        if (data.action === 'view') {
            if (data.view === 'stopwatch') {
                stopwatchSection.classList.remove('hidden');
                timerSection.classList.add('hidden');
            } else {
                stopwatchSection.classList.add('hidden');
                timerSection.classList.remove('hidden');
            }
        } else if (data.tool === 'stopwatch') {
            if (data.display) swDisplay.textContent = data.display;
            if (data.action === 'lap') {
                lapsList.innerHTML = data.html;
                if (data.html) lapsWrapper.classList.remove('hidden');
            }
            if (data.action === 'reset') {
                lapsList.innerHTML = '';
                lapsWrapper.classList.add('hidden');
            }
        } else if (data.tool === 'timer') {
            if (data.display) tmrDisplay.textContent = data.display;
        }
    };
    
    // Request initial state from main window if possible or just show default
    bc.postMessage({ action: 'request_state' }); // Main window hasn't implemented response, but switchView is timed.
    stopwatchSection.classList.remove('hidden');
</script>
</body>
</html>

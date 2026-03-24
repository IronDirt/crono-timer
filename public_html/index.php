<?php
// ─── Language Detection ───────────────────────────────────────────────────────
$allowed_langs = ['it', 'en', 'fr'];
$lang_code = $_GET['lang'] ?? ($_COOKIE['lang'] ?? 'it');
if (!in_array($lang_code, $allowed_langs)) { $lang_code = 'it'; }
setcookie('lang', $lang_code, time() + (86400 * 365), '/');
$lang = include __DIR__ . "/lang/{$lang_code}.php";

// ─── Theme Detection ──────────────────────────────────────────────────────────
$allowed_themes = ['light', 'dark', 'neon', 'futuristic'];
$theme = $_GET['theme'] ?? ($_COOKIE['theme'] ?? 'dark');
if (!in_array($theme, $allowed_themes)) { $theme = 'dark'; }
setcookie('theme', $theme, time() + (86400 * 365), '/');

include __DIR__ . '/includes/header.php';
?>

<div id="stopwatch-section" class="tool-section">
    <div class="timer-display" id="stopwatch-display">00:00:00.00</div>
    <div class="controls">
        <button id="stopwatch-start"  class="btn btn-primary"   aria-label="Start/Pause">▶</button>
        <button id="stopwatch-lap"    class="btn btn-secondary" aria-label="Lap"          disabled>⚑</button>
        <button id="stopwatch-reset"  class="btn btn-danger"    aria-label="Reset">↺</button>
    </div>
    <div class="laps-container">
        <ul id="laps-list"></ul>
    </div>
</div>

<div id="timer-section" class="tool-section hidden">
    <div class="timer-display" id="timer-display">00:00:00</div>
    <div class="input-group">
        <div class="input-field">
            <label><?php echo htmlspecialchars($lang['label_hours']); ?></label>
            <input type="number" id="timer-hours"   min="0" max="99" value="0">
        </div>
        <div class="input-field">
            <label><?php echo htmlspecialchars($lang['label_minutes']); ?></label>
            <input type="number" id="timer-minutes" min="0" max="59" value="0">
        </div>
        <div class="input-field">
            <label><?php echo htmlspecialchars($lang['label_seconds']); ?></label>
            <input type="number" id="timer-seconds" min="0" max="59" value="0">
        </div>
    </div>
    <div class="controls">
        <button id="timer-start" class="btn btn-primary"  aria-label="Start/Pause">▶</button>
        <button id="timer-reset" class="btn btn-danger"   aria-label="Reset">↺</button>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

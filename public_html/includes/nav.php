<nav>
    <ul class="nav-links">
        <li><a href="#stopwatch" id="nav-stopwatch" class="active"><?php echo htmlspecialchars($lang['nav_stopwatch']); ?></a></li>
        <li><a href="#timer"     id="nav-timer"><?php echo htmlspecialchars($lang['nav_timer']); ?></a></li>
    </ul>
    <div class="nav-actions">

        <!-- Language Switcher -->
        <div class="dropdown-wrapper" aria-label="<?php echo htmlspecialchars($lang['aria_lang']); ?>">
            <?php
                $current_flag = '🇮🇹';
                if ($lang_code === 'en') $current_flag = '🇬🇧';
                if ($lang_code === 'fr') $current_flag = '🇫🇷';
            ?>
            <button class="dropdown-toggle icon-btn" id="lang-menu-btn" aria-expanded="false" title="<?php echo htmlspecialchars($lang['aria_lang']); ?>"><?php echo $current_flag; ?></button>
            <div class="dropdown-menu horizontal-menu" id="lang-menu">
                <a href="?lang=it<?php echo $theme !== 'dark' ? '&theme='.$theme : ''; ?>" class="lang-btn <?php echo $lang_code==='it' ? 'active':'' ?>">🇮🇹</a>
                <a href="?lang=en<?php echo $theme !== 'dark' ? '&theme='.$theme : ''; ?>" class="lang-btn <?php echo $lang_code==='en' ? 'active':'' ?>">🇬🇧</a>
                <a href="?lang=fr<?php echo $theme !== 'dark' ? '&theme='.$theme : ''; ?>" class="lang-btn <?php echo $lang_code==='fr' ? 'active':'' ?>">🇫🇷</a>
            </div>
        </div>

        <!-- Theme Picker -->
        <div class="dropdown-wrapper" aria-label="<?php echo htmlspecialchars($lang['aria_theme']); ?>">
            <button class="dropdown-toggle icon-btn" id="theme-menu-btn" aria-expanded="false" title="<?php echo htmlspecialchars($lang['aria_theme']); ?>">🎨</button>
            <div class="dropdown-menu horizontal-menu" id="theme-menu">
                <a href="?lang=<?php echo $lang_code; ?>&theme=light"      class="theme-dot theme-dot-light      <?php echo $theme==='light'      ? 'active':'' ?>" title="Light"></a>
                <a href="?lang=<?php echo $lang_code; ?>&theme=dark"       class="theme-dot theme-dot-dark       <?php echo $theme==='dark'       ? 'active':'' ?>" title="Dark"></a>
                <a href="?lang=<?php echo $lang_code; ?>&theme=neon"       class="theme-dot theme-dot-neon       <?php echo $theme==='neon'       ? 'active':'' ?>" title="Neon"></a>
                <a href="?lang=<?php echo $lang_code; ?>&theme=futuristic" class="theme-dot theme-dot-futuristic <?php echo $theme==='futuristic' ? 'active':'' ?>" title="Futuristic"></a>
            </div>
        </div>

        <!-- Fullscreen -->
        <button id="fullscreen-toggle" aria-label="<?php echo htmlspecialchars($lang['aria_fullscreen']); ?>" class="icon-btn" title="Fullscreen">⛶</button>
        
        <!-- Dual Screen -->
        <button id="dual-screen-toggle" aria-label="<?php echo htmlspecialchars($lang['aria_dual_screen'] ?? 'Dual screen'); ?>" class="icon-btn" title="Dual Screen">⧉</button>

        <!-- PWA Install -->
        <button id="pwa-install" aria-label="<?php echo htmlspecialchars($lang['aria_install']); ?>" class="icon-btn hidden" title="Install">⬇</button>
    </div>
</nav>

document.addEventListener('DOMContentLoaded', () => {

    // ─── Constants & Globals ────────────────────────────────────────────────
    const lapLabel = window.CRONO_LAP_LABEL || 'Lap';
    const timerAlertMsg = window.CRONO_EXPIRED || 'Time is up! ⏰';
    const bc = new BroadcastChannel('cronotimer_sync');

    // ─── Dropdowns ──────────────────────────────────────────────────────────
    const dropdownWrappers = document.querySelectorAll('.dropdown-wrapper');
    dropdownWrappers.forEach(wrapper => {
        const toggleBtn = wrapper.querySelector('.dropdown-toggle');
        const menu = wrapper.querySelector('.dropdown-menu');

        if (toggleBtn && menu) {
            toggleBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                document.querySelectorAll('.dropdown-menu').forEach(m => {
                    if (m !== menu) m.classList.remove('show');
                });
                menu.classList.toggle('show');
            });
        }
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.dropdown-wrapper')) {
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));
        }
    });

    // ─── Navigation (Stopwatch vs Timer) ────────────────────────────────────
    const navStopwatch = document.getElementById('nav-stopwatch');
    const navTimer = document.getElementById('nav-timer');
    const stopwatchSection = document.getElementById('stopwatch-section');
    const timerSection = document.getElementById('timer-section');

    const switchView = (target) => {
        bc.postMessage({ action: 'view', view: target });
        if (target === 'stopwatch') {
            stopwatchSection.classList.remove('hidden');
            timerSection.classList.add('hidden');
            if(navStopwatch) navStopwatch.classList.add('active');
            if(navTimer) navTimer.classList.remove('active');
        } else {
            stopwatchSection.classList.add('hidden');
            timerSection.classList.remove('hidden');
            if(navStopwatch) navStopwatch.classList.remove('active');
            if(navTimer) navTimer.classList.add('active');
        }
    };
    if (navStopwatch) navStopwatch.addEventListener('click', (e) => { e.preventDefault(); switchView('stopwatch'); });
    if (navTimer) navTimer.addEventListener('click', (e) => { e.preventDefault(); switchView('timer'); });


    // ─── Dual Screen Toggle ─────────────────────────────────────────────────
    const dualScreenToggle = document.getElementById('dual-screen-toggle');
    if (dualScreenToggle) {
        dualScreenToggle.addEventListener('click', () => {
            const currentLang = document.documentElement.lang || 'en';
            const bodyClass = document.body.className;
            const themeMatch = bodyClass.match(/theme-([a-z]+)/);
            const currentTheme = themeMatch ? themeMatch[1] : 'dark';
            window.open(`dual.php?lang=${currentLang}&theme=${currentTheme}`, 'DualScreen', 'width=800,height=600');
            // Send initial state
            setTimeout(() => {
                switchView(stopwatchSection.classList.contains('hidden') ? 'timer' : 'stopwatch');
            }, 500);
        });
    }

    // ─── Fullscreen Toggle ──────────────────────────────────────────────────
    const fullscreenToggle = document.getElementById('fullscreen-toggle');
    if (fullscreenToggle) {
        fullscreenToggle.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => console.log(err));
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
            }
        });
    }

    // ─── PWA Installation ───────────────────────────────────────────────────
    let deferredPrompt;
    const installBtn = document.getElementById('pwa-install');

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        if (installBtn) installBtn.classList.remove('hidden');
    });

    if (installBtn) {
        installBtn.addEventListener('click', async () => {
            if (!deferredPrompt) return;
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            if (outcome === 'accepted') {
                installBtn.classList.add('hidden');
            }
            deferredPrompt = null;
        });
    }

    // ─── Formatting Utils ───────────────────────────────────────────────────
    const formatTime = (ms, showMs = true) => {
        const h = Math.floor(ms / 3600000);
        const m = Math.floor((ms % 3600000) / 60000);
        const s = Math.floor((ms % 60000) / 1000);
        const msPart = Math.floor((ms % 1000) / 10);

        const pad = n => String(n).padStart(2, '0');
        const base = `${pad(h)}:${pad(m)}:${pad(s)}`;
        return showMs ? `${base}.${pad(msPart)}` : base;
    };


    // ─── Stopwatch Logic ────────────────────────────────────────────────────
    let swRequest, swStart, swElapsed = 0, lapCount = 0;
    let lastSyncTime = 0;
    const SYNC_INTERVAL = 33; // ~30fps sync is enough for dual screen

    const swDisplay = document.getElementById('stopwatch-display');
    const swStartBtn = document.getElementById('stopwatch-start');
    const swLapBtn = document.getElementById('stopwatch-lap');
    const swResetBtn = document.getElementById('stopwatch-reset');
    const lapsList = document.getElementById('laps-list');

    if (swDisplay) swDisplay.style.willChange = 'contents';

    const updateSw = () => {
        const now = Date.now();
        const elapsedSinceStart = now - swStart + swElapsed;
        const displayValue = formatTime(elapsedSinceStart);
        
        // Local UI update
        swDisplay.textContent = displayValue;

        // Throttled sync update
        if (now - lastSyncTime > SYNC_INTERVAL) {
            bc.postMessage({ tool: 'stopwatch', action: 'tick', display: displayValue });
            lastSyncTime = now;
        }

        swRequest = requestAnimationFrame(updateSw);
    };

    if (swStartBtn) {
        swStartBtn.addEventListener('click', () => {
            if (swRequest) {
                // Pause
                cancelAnimationFrame(swRequest);
                swRequest = null;
                swElapsed += Date.now() - swStart;
                swStartBtn.textContent = '▶';
                swStartBtn.title = 'Start';
                swLapBtn.disabled = true;
                // Final sync on pause
                bc.postMessage({ tool: 'stopwatch', action: 'tick', display: formatTime(swElapsed) });
            } else {
                // Start
                swStart = Date.now();
                swRequest = requestAnimationFrame(updateSw);
                swStartBtn.textContent = '⏸';
                swStartBtn.title = 'Pause';
                swLapBtn.disabled = false;
            }
        });
    }

    if (swResetBtn) {
        swResetBtn.addEventListener('click', () => {
            if (swRequest) {
                cancelAnimationFrame(swRequest);
                swRequest = null;
            }
            swElapsed = 0;
            lapCount = 0;
            swDisplay.textContent = '00:00:00.00';
            swStartBtn.textContent = '▶';
            swLapBtn.disabled = true;
            if (lapsList) lapsList.innerHTML = '';
            bc.postMessage({ tool: 'stopwatch', action: 'reset', display: '00:00:00.00' });
        });
    }

    if (swLapBtn) {
        swLapBtn.addEventListener('click', () => {
            const now = Date.now();
            const diff = now - swStart + swElapsed;
            lapCount++;
            const li = document.createElement('li');
            li.className = 'lap-item';
            li.innerHTML = `<span class="lap-num">${lapLabel} ${lapCount}</span> <span class="lap-time">${formatTime(diff)}</span>`;
            lapsList.prepend(li);
            bc.postMessage({ tool: 'stopwatch', action: 'lap', html: lapsList.innerHTML });
        });
    }


    // ─── Countdown Timer Logic ──────────────────────────────────────────────
    let tmrInterval, tmrTotalMs = 0;
    const tmrDisplay = document.getElementById('timer-display');
    const tmrStartBtn = document.getElementById('timer-start');
    const tmrResetBtn = document.getElementById('timer-reset');
    const inpH = document.getElementById('timer-hours');
    const inpM = document.getElementById('timer-minutes');
    const inpS = document.getElementById('timer-seconds');

    const updateTmr = () => {
        if (tmrTotalMs <= 0) {
            clearInterval(tmrInterval);
            tmrInterval = null;
            tmrDisplay.textContent = '00:00:00';
            bc.postMessage({ tool: 'timer', action: 'tick', display: '00:00:00' });
            tmrStartBtn.textContent = '▶';
            alert(timerAlertMsg);
            return;
        }
        tmrTotalMs -= 1000;
        const displayValue = formatTime(tmrTotalMs, false);
        tmrDisplay.textContent = displayValue;
        bc.postMessage({ tool: 'timer', action: 'tick', display: displayValue });
    };

    if (tmrStartBtn) {
        tmrStartBtn.addEventListener('click', () => {
            if (tmrInterval) {
                // Pause
                clearInterval(tmrInterval);
                tmrInterval = null;
                tmrStartBtn.textContent = '▶';
            } else {
                // Start calculation from inputs if total is 0
                if (tmrTotalMs === 0) {
                    const h = parseInt(inpH.value) || 0;
                    const m = parseInt(inpM.value) || 0;
                    const s = parseInt(inpS.value) || 0;
                    tmrTotalMs = (h * 3600 + m * 60 + s) * 1000;
                }
                
                if (tmrTotalMs > 0) {
                    tmrDisplay.textContent = formatTime(tmrTotalMs, false);
                    tmrInterval = setInterval(updateTmr, 1000);
                    tmrStartBtn.textContent = '⏸';
                }
            }
        });
    }

    if (tmrResetBtn) {
        tmrResetBtn.addEventListener('click', () => {
            clearInterval(tmrInterval);
            tmrInterval = null;
            tmrTotalMs = 0;
            tmrDisplay.textContent = '00:00:00';
            tmrStartBtn.textContent = '▶';
            inpH.value = '0';
            inpM.value = '0';
            inpS.value = '0';
            bc.postMessage({ tool: 'timer', action: 'reset', display: '00:00:00' });
        });
    }
});

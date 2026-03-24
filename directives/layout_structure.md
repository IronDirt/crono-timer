# Layout Structure - CronoTimer

## Component Architecture
The site will be built using PHP `include` tags for modularity.

- `public_html/index.php`: The main layout that includes the active view (Timer or Stopwatch).
- `public_html/includes/header.php`: Standard HTML head and top header.
- `public_html/includes/nav.php`: Navigation bar to toggle between tools and toggle theme.
- `public_html/includes/footer.php`: Standard footer with links and copyright.

## DOM Structure (Main Content)
```html
<main id="app-container">
    <section id="stopwatch-view" class="tool-view">
        <div class="display">00:00:00.00</div>
        <div class="controls">
            <!-- Buttons -->
        </div>
        <div class="laps">
            <!-- Lap list -->
        </div>
    </section>
    
    <section id="timer-view" class="tool-view hidden">
        <div class="display">00:00:00</div>
        <div class="input-controls">
            <!-- Time pickers -->
        </div>
        <div class="controls">
            <!-- Buttons -->
        </div>
    </section>
</main>
```

## Responsive Strategy
- Deskstop: Side-by-side or centered large display.
- Mobile: Stacked layout with prominent touch targets.

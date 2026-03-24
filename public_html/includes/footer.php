    </main>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> CronoTimer — <?php echo htmlspecialchars($lang['footer_text']); ?></p>
        </div>
    </footer>
    <script>
        // Pass PHP data to JS
        window.CRONO_LAP_LABEL  = <?php echo json_encode($lang['lap_label']); ?>;
        window.CRONO_EXPIRED    = <?php echo json_encode($lang['timer_expired']); ?>;
    </script>
    <script src="assets/js/main.js"></script>
    <script>
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(() => {});
        }
    </script>
</body>
</html>

# Istruzioni Agente - Sviluppo Web (PHP, HTML, CSS, JS) - Multilingua

Operi all'interno di un'architettura a 3 livelli progettata per creare siti web performanti, modulari, ottimizzati per la SEO e **nativamente multilingua**.

## 1. Architettura a 3 Livelli

**Livello 1: Direttiva (Strategia)**
- Documenti SOP in `directives/` (es. `seo_strategy.md`, `i18n_mapping.md`).
- Definiscono la gerarchia delle lingue e i mercati target.

**Livello 2: Orchestrazione (Logica e Assemblaggio)**
- **Gestione Lingue**: Implementi una logica PHP per rilevare la lingua (tramite parametro URL `?lang=`, sessione o cookie) e caricare il file corrispondente da `public_html/lang/`.
- **Modularità**: Separi l'HTML in componenti PHP (`header.php`, etc.) e usi variabili o costanti per i testi (es. `echo $lang['hero_title'];`).

**Livello 3: Esecuzione (Automazione)**
- Script Python in `execution/`:
    - `optimize_images.py`: Converte in WebP.
    - `minify_assets.py`: Minifica CSS/JS.
    - `check_translations.py`: **(Nuovo)** Verifica che ogni chiave presente in `it.php` esista anche in `en.php` e `fr.php` per evitare testi mancanti.

## 2. Principi di Sviluppo Multilingua

- **Separazione dei Contenuti**: Nessun testo "hardcoded" nei file PHP della struttura. Tutti i testi devono risiedere nei file in `lang/`.
- **Array Associativi**: I file di lingua devono restituire un array PHP per massimizzare la velocità di caricamento.
- **SEO i18n**: Gestisci correttamente i tag `<html lang="...">` e i meta tag `hreflang` nell'header per indicare ai motori di ricerca le versioni correlate della pagina.

## 3. Struttura dei File Aggiornata

```text
project-root/
├── public_html/
│   ├── assets/              # CSS, JS, Immagini
│   ├── includes/            # Snippet PHP (header.php, footer.php)
│   ├── lang/                # FILE DI LINGUA
│   │   ├── it.php           # Traduzioni Italiane
│   │   ├── en.php           # Traduzioni Inglesi
│   │   └── fr.php           # Traduzioni Francesi
│   ├── index.php            # Logica di routing e caricamento lingua
│   └── .htaccess            # Gestione URL (es. /en/home)
├── directives/              # SOP e strategie di contenuto
├── execution/               # Script Python (incluso controllo traduzioni)
├── .tmp/                    # Asset originali
└── brand-guidelines.md
```

## 4. Esempio Implementazione Logica (Livello 2)

Per garantire la massima velocità, la logica di caricamento deve essere minimale:

```php
// Inizio di index.php o di un file config.php
$allowed_langs = ['it', 'en', 'fr'];
$lang_code = $_GET['lang'] ?? 'it';

if (!in_array($lang_code, $allowed_langs)) {
    $lang_code = 'it';
}

$lang = include "lang/{$lang_code}.php";
```

## 5. Loop di Lavoro

1. **Analisi**: Leggi la direttiva e identifica le chiavi di testo necessarie (es. `nav_home`, `cta_contact`).
2. **Setup Lingue**: Crea i tre file in `public_html/lang/` con le traduzioni fornite o generate.
3. **Sviluppo**: Costruisci i componenti PHP richiamando l'array `$lang`.
4. **Validazione**: Esegui `check_translations.py` per assicurarti che non ci siano stringhe dimenticate in una delle tre lingue.
5. **Ottimizzazione**: Esegui gli script di minificazione e compressione immagini.

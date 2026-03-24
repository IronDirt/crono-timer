#!/usr/bin/env python3
import os
import re

def parse_php_array(filepath):
    """
    Legge un file PHP e estrae le chiavi dell'array restituito.
    Si aspetta un file del tipo:
    <?php
    return [
        'key1' => 'value1',
    ];
    """
    keys = set()
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Regex per trovare le chiavi stringa nell'array
        matches = re.findall(r"['\"]([a-zA-Z0-9_]+)['\"]\s*=>", content)
        for match in matches:
            keys.add(match)
            
    except Exception as e:
        print(f"Errore nella lettura del file {filepath}: {e}")
    
    return keys

def main():
    lang_dir = os.path.join(os.path.dirname(__file__), '..', 'public_html', 'lang')
    
    if not os.path.exists(lang_dir):
        print(f"Cartella non trovata: {lang_dir}")
        return

    it_file = os.path.join(lang_dir, 'it.php')
    en_file = os.path.join(lang_dir, 'en.php')
    fr_file = os.path.join(lang_dir, 'fr.php')
    
    if not os.path.exists(it_file):
        print("Il file base it.php non esiste.")
        return

    it_keys = parse_php_array(it_file)
    en_keys = parse_php_array(en_file)
    fr_keys = parse_php_array(fr_file)

    print(f"Trovate {len(it_keys)} chiavi in it.php")

    missing_in_en = it_keys - en_keys
    missing_in_fr = it_keys - fr_keys

    errors = False

    if missing_in_en:
        print("\n[ERRORE] Chiavi mancanti in en.php:")
        for key in missing_in_en:
            print(f"  - {key}")
        errors = True
    else:
        print("Tutte le chiavi presenti in en.php OK.")

    if missing_in_fr:
        print("\n[ERRORE] Chiavi mancanti in fr.php:")
        for key in missing_in_fr:
            print(f"  - {key}")
        errors = True
    else:
        print("Tutte le chiavi presenti in fr.php OK.")

    if not errors:
        print("\n✅ Tutte le traduzioni sono allineate.")
    else:
        print("\n❌ Traduzioni non allineate. Correggi i file mancanti.")
        exit(1)

if __name__ == '__main__':
    main()

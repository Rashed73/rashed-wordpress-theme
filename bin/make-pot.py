#!/usr/bin/env python3
"""Extract the theme's translatable strings into languages/rashid.pot.

Looks for __(), _e(), esc_html__(), esc_html_e(), esc_attr__() and esc_attr_e()
calls whose text domain is 'rashid', in PHP and JavaScript files. Run it after
adding or changing any user-facing string, then update each languages/<lang>.po
(for example with `msgmerge`) and rebuild the .mo files with bin/compile-mo.py.

Usage: bin/make-pot.py
Needs: python3 and `pip install polib`.
"""
import os
import re
import sys

import polib

THEME = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
SKIP_DIRS = {'assets', 'node_modules', 'vendor', 'languages', 'bin', 'docs'}
FUNCTIONS = r'(?:__|_e|esc_html__|esc_html_e|esc_attr__|esc_attr_e)'
STRING = r"""(?:'((?:[^'\\]|\\.)*)'|"((?:[^"\\]|\\.)*)")"""
CALL = re.compile(r'(?:/\*\s*translators:\s*(?P<note>.*?)\*/\s*)?\b' + FUNCTIONS + r'\(\s*' + STRING + r"\s*,\s*'rashid'\s*\)", re.S)


def unescape(single, double):
    if single is not None:
        return re.sub(r"\\(['\\])", r'\1', single)
    return re.sub(r'\\(["\\])', r'\1', double)


def source_files():
    for root, dirs, files in os.walk(THEME):
        dirs[:] = [d for d in dirs if d not in SKIP_DIRS]
        for name in sorted(files):
            if name.endswith(('.php', '.js')):
                yield os.path.join(root, name)


def main():
    pot = polib.POFile()
    pot.metadata = {
        'Project-Id-Version': 'Rashid theme',
        'Report-Msgid-Bugs-To': '',
        'MIME-Version': '1.0',
        'Content-Type': 'text/plain; charset=UTF-8',
        'Content-Transfer-Encoding': '8bit',
        'X-Domain': 'rashid',
    }
    entries = {}
    for path in source_files():
        text = open(path, encoding='utf-8').read()
        for match in CALL.finditer(text):
            msgid = unescape(match.group(2), match.group(3))
            line = text.count('\n', 0, match.start()) + 1
            entry = entries.get(msgid)
            if entry is None:
                entry = polib.POEntry(msgid=msgid, msgstr='')
                entries[msgid] = entry
                pot.append(entry)
            entry.occurrences.append((os.path.relpath(path, THEME), str(line)))
            if match.group('note'):
                entry.comment = match.group('note').strip()
    out = os.path.join(THEME, 'languages', 'rashid.pot')
    pot.save(out)
    print(f'{len(pot)} strings written to {os.path.relpath(out, THEME)}')


if __name__ == '__main__':
    sys.exit(main())

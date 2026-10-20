#!/usr/bin/env python3
"""
Minify CSS read from stdin and write it to stdout.

Deliberately small and conservative: it removes comments and the white space that is
never needed, and leaves strings, url() contents, calc() spacing and selectors
untouched. Run through bin/build-assets.sh, which commits the result.
"""

import re
import sys


def minify(css: str) -> str:
    saved = []

    def keep(text):
        saved.append(text)
        return '\x00%d\x00' % (len(saved) - 1)

    # One left-to-right pass: strings are put aside untouched and comments are dropped. Doing both
    # in one pass matters, because an apostrophe inside a comment must not start a "string".
    css = re.sub(
        r'("(?:\\.|[^"\\])*"|\'(?:\\.|[^\'\\])*\')|/\*.*?\*/',
        lambda m: keep(m.group(1)) if m.group(1) else '',
        css,
        flags=re.S,
    )
    css = re.sub(r'url\([^)]*\)', lambda m: keep(m.group(0)), css)  # unquoted url() contents

    css = re.sub(r'\s+', ' ', css)                         # runs of white space
    css = re.sub(r' ?([{};,>]) ?', r'\1', css)             # around braces, semicolons, commas, child selector
    css = re.sub(r':\s+', ':', css)                        # after a colon: "color: red"
    css = css.replace(';}', '}')                           # last semicolon in a block

    # url() contents were saved after the strings inside them, so restore until nothing is left to restore.
    while '\x00' in css:
        css = re.sub(r'\x00(\d+)\x00', lambda m: saved[int(m.group(1))], css)

    return css.strip()


if __name__ == '__main__':
    sys.stdout.write(minify(sys.stdin.read()) + '\n')

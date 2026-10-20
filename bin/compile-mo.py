#!/usr/bin/env python3
"""Compile every languages/*.po into the .mo file WordPress reads.

Usage: bin/compile-mo.py
Needs: python3 and `pip install polib`.
"""
import glob
import os

import polib

LANGUAGES = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'languages')

for po_path in sorted(glob.glob(os.path.join(LANGUAGES, '*.po'))):
    po = polib.pofile(po_path)
    po.save_as_mofile(po_path[:-3] + '.mo')
    print(f'{os.path.basename(po_path)}: {po.percent_translated()}% translated')

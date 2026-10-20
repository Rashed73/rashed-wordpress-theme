#!/usr/bin/env bash
#
# Quality gate for the theme. Run before every commit.
#
#   1. Every PHP file lints.
#   2. The built CSS/JS match their sources (nobody edited a built file).
#   3. The theme contains no site-specific names, so it stays reusable.
#
# Exits non-zero on the first problem and says what to fix.

set -euo pipefail

THEME_DIR="$(cd "$(dirname "$0")/.." && pwd)"
cd "$THEME_DIR"

fail() { echo "FAIL: $1" >&2; exit 1; }

echo "1/3 PHP lint"
while IFS= read -r -d '' file; do
  php -l "$file" > /dev/null || fail "PHP syntax error in $file"
done < <(find . -name '*.php' -not -path './node_modules/*' -print0)

echo "2/3 Built assets are current"
TMP="$(mktemp -d)"
trap 'rm -rf "$TMP"' EXIT
bin/build-assets.sh "$TMP" > /dev/null
diff -q "$TMP/css/main.css" assets/css/main.css > /dev/null || fail "assets/css/main.css is stale: run bin/build-assets.sh"
diff -q "$TMP/js/main.js"   assets/js/main.js   > /dev/null || fail "assets/js/main.js is stale: run bin/build-assets.sh"

echo "3/3 No site-specific names in the theme"
# style.css is left out: its header names the theme's author, as WordPress requires.
if grep -rIn -i -E 'rashed2dev|rashid-mg|mohamed|rashed' --exclude-dir=languages --exclude=check.sh --exclude=style.css --exclude=README.md . ; then
  fail "site-specific names found; move them to site-content/ or the Customizer"
fi

echo "OK"

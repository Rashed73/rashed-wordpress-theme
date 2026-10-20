# Rashid theme — architecture

A bilingual (English / Arabic RTL) portfolio and services theme. No page builder, no
jQuery. This file is the map; each class and template carries its own comments.

## Layout

```
functions.php                bootstrap only: constants, autoloader, Theme::boot()
src/                         PHP classes (namespace Rashid\), one responsibility per file
  Theme.php                  the single list of modules
  Module.php                 contract: register() hooks the module into WordPress
  Setup/                     theme supports, assets, default clean-up
  Content/                   project post type, fields, value object, queries
  Settings/                  Customizer options (brand, call-to-action text)
  I18n/                      Polylang integration and the language list
  View/                      URL helpers, icons, fallback menu
inc/template-functions.php   one-line global helpers that delegate to src/
header.php footer.php …      templates: layout only, no logic
template-parts/              repeated markup (header, project card, filters, pager …)
assets/css/src/              one CSS file per component, joined into assets/css/main.css
assets/js/src/               one JS file per behaviour, joined into assets/js/main.js
bin/build-assets.sh          builds main.css / main.js from the sources
bin/check.sh                 quality gate: lint, built assets current, no site names
```

## Conventions

- Add behaviour as a `Module` and list it in `Theme::modules()`.
- A template never queries or formats data itself: ask a class (`Project`,
  `ProjectCategories`, `Options`) or use a template part.
- Styles: edit the file for that component under `assets/css/src/`, then run
  `bin/build-assets.sh`. Use tokens from `00-tokens.css`, logical properties
  (`margin-inline-start`, not `margin-left`) and keep each component's media queries
  in its own file.
- Strings shown by the theme use `__()` / `esc_html_e()` with the `rashid` text domain.
- Run `bin/check.sh` before committing.

## Content model

- `portfolio` post type (slug kept from the previous theme) with a `project-cat`
  taxonomy. Structured fields (client, industry, role, technologies, developer, designer,
  website) are defined once in `Content\ProjectFields`.
- Pin featured projects with the "Order" box (lower number first).

## Bilingual behaviour

English is the default language at the site root, Arabic lives under `/ar/` (Polylang).
Pages and projects are translated as separate posts. Owner-editable theme copy is under
Appearance → Customize and translated in Languages → Strings.

## Translations
- Theme strings use the `rashid` text domain. `bin/make-pot.py` extracts them into `languages/rashid.pot`;
  each language has a `languages/<locale>.po` (source) and a compiled `.mo` (built by `bin/compile-mo.py`).
- Arabic (`ar`) is complete. To add a language, copy `rashid.pot` to `<locale>.po`, translate, then compile.
- After changing any user-facing string: run `bin/make-pot.py`, merge the new strings into each `.po`, run `bin/compile-mo.py`.

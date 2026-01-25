# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Static website generator for Northwest United Methodist Church. PHP script assembles HTML fragments into complete pages with Bootstrap 5.

## Build Command

```bash
php generate.php
```

Generates static HTML pages from fragments in `/working/htmls/` to `/docs/`. Run this after any fragment changes.

## Architecture

**Generator flow:** `header.fragment` + `content.fragment` + `footer.fragment` → `docs/*/index.html`

**Key directories:**
- `/working/htmls/*.fragment` - Content fragments (edit these)
- `/working/header.fragment` - Shared header with navbar
- `/working/footer.fragment` - Shared footer with contact info
- `/docs/` - Generated output (never edit directly)
- `/docs/css/`, `/docs/js/`, `/docs/images/`, `/docs/files/` - Frozen assets (preserved during regeneration)

**Path convention:** Dots in fragment names become directory levels:
- `about.fragment` → `/docs/about/index.html`
- `community.food-pantry.fragment` → `/docs/community/food-pantry/index.html`

Asset paths are automatically adjusted for nested pages by `generate.php`.

## Content Patterns

Use Bootstrap 5 classes. The README.md documents reusable content blocks:
- Lead paragraph with key points (`p.lead`)
- Alternating split blocks (`row`, `col-lg-6`)
- Callout boxes (`p-4 my-4 rounded border bg-light`)
- Pull quotes (`figure`, `fs-4`)
- Steps/process cards (`list-group` or card rows)
- Stats rows (`display-6` numbers)
- FAQ accordions (`accordion`)

Wrap blocks in `<section class="my-5">` for consistent spacing.

## Important Notes

- Header opens `<main class="py-5">` and `.container`; footer closes them
- Fragments contain only the inner content between header and footer
- CSS custom properties defined in `/docs/css/custom.css`: `--brand-accent`, `--surface`, `--ink`

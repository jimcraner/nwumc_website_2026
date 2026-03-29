# Northwest United Methodist Church — Static Site Generator

Static website for [Northwest United Methodist Church](https://nwunitedmethodist.org), Peoria, IL. A PHP script assembles HTML fragment files into complete pages and publishes them to GitHub Pages.

## Quick Start

```bash
# Edit a fragment
nano working/htmls/about.fragment

# Regenerate the site
php generate.php

# Deploy
git add -A
git commit -m "Update about page"
git push
```

GitHub Pages automatically deploys from the `docs/` folder on `main`. Changes appear within 1–2 minutes.

---

## Architecture

```
working/
├── header.fragment          ← shared navbar + opens <main>
├── footer.fragment          ← shared footer + closes </main>
└── htmls/
    ├── index.fragment       → docs/index.html
    ├── about.fragment       → docs/about/index.html
    ├── contact.fragment     → docs/contact/index.html
    ├── giving.fragment      → docs/giving/index.html
    ├── ministries.fragment  → docs/ministries/index.html
    └── news.fragment        → docs/news/index.html
```

`generate.php` combines:

```
header.fragment  +  <content>.fragment  +  footer.fragment  →  docs/*/index.html
```

### How generate.php works

1. **Validates** that `docs/`, `working/header.fragment`, and `working/footer.fragment` exist.
2. **Clears** `docs/` of all previously generated HTML, while preserving the frozen asset directories (`css/`, `js/`, `images/`, `files/`) and the `CNAME` file.
3. **Ensures** frozen directories exist (creates them if missing).
4. **Iterates** over every `.fragment` file in `working/htmls/`:
   - Derives the output path from the filename (see [Path Convention](#path-convention)).
   - Calculates the directory depth of the output page.
   - Calls `adjustAssetPaths()` on the header and footer to prefix asset URLs with the correct number of `../` hops.
   - Writes `header + content + footer` to the output `index.html`.

### Path convention

Dots in fragment filenames become directory separators in the output path:

| Fragment filename              | Output path                            | Depth |
|-------------------------------|----------------------------------------|-------|
| `index.fragment`              | `docs/index.html`                      | 0     |
| `about.fragment`              | `docs/about/index.html`                | 1     |
| `community.food-pantry.fragment` | `docs/community/food-pantry/index.html` | 2  |

### Asset path adjustment

Because the shared header and footer reference assets with paths like `css/bootstrap.min.css`, a page at `docs/about/index.html` would fail to load them with bare relative paths. `generate.php` uses a regex callback to prepend the correct number of `../` hops automatically:

| Page depth | Asset path in generated HTML        |
|-----------|--------------------------------------|
| 0 (root)  | `css/bootstrap.min.css`              |
| 1         | `../css/bootstrap.min.css`           |
| 2         | `../../css/bootstrap.min.css`        |

This is handled entirely by the `adjustAssetPaths(string $html, string $prefix, array $assetDirs)` function in `generate.php`.

### Frozen asset directories

These directories inside `docs/` are **never deleted** during regeneration:

| Directory   | Contents                                      |
|------------|-----------------------------------------------|
| `css/`     | Bootstrap 5 + `custom.css`                   |
| `js/`      | Bootstrap 5 bundle (includes Popper.js)       |
| `images/`  | Church photos                                 |
| `files/`   | Monthly newsletter PDFs                       |
| `CNAME`    | Custom domain record (`nwunitedmethodist.org`) |

To add an image or PDF, drop it into the appropriate directory and commit it — it will survive future regenerations.

---

## Editing Content

### Editing an existing page

1. Open the corresponding fragment in `working/htmls/`.
2. Edit the HTML using Bootstrap 5 classes.
3. Run `php generate.php` and inspect the output in `docs/`.
4. Commit and push.

**Never edit files directly in `docs/`** — they are overwritten every time the generator runs.

### Adding a new page

Create a new `.fragment` file in `working/htmls/` following the naming convention, then regenerate. For example, to add a page at `/sermons/`:

```bash
touch working/htmls/sermons.fragment
# ... edit the file ...
php generate.php
```

To add it to the navbar, also edit `working/header.fragment`.

### Adding a newsletter PDF

1. Copy the PDF into `docs/files/`.
2. Add a link to it in `working/htmls/news.fragment`.
3. Regenerate and deploy.

---

## Fragment structure

### Header (`working/header.fragment`)

Opens the full HTML document through `<main class="py-5 flex-grow-1"><div class="container">`. Includes:

- HTML5 doctype, viewport meta, page title
- Bootstrap 5 CSS and `custom.css`
- Top navigation bar (brand + page links)

Fragments must **not** include `<html>`, `<head>`, or `<body>` — the header provides all of that.

### Footer (`working/footer.fragment`)

Closes `</div></main>` and then adds:

- Three-column footer with address, worship time, and navigation links
- Bootstrap JS bundle `<script>` tag
- Closing `</body></html>`

### Content fragments (`working/htmls/`)

Contain only the inner body content — the HTML that sits between the opening `<div class="container">` and the closing `</div></main>`. No `<html>`, `<head>`, `<body>`, or container div needed.

---

## Styling

All layout uses **Bootstrap 5**. CSS custom properties are defined in `docs/css/custom.css`:

```css
--brand-accent: #1f6feb;   /* primary blue */
--surface:      #ffffff;
--ink:          #1b1f24;
```

### Reusable content blocks

Wrap related blocks in `<section class="my-5">` for consistent vertical spacing.

**Lead paragraph**
```html
<p class="lead">Opening statement here.</p>
```

**Split block (text + image, alternating)**
```html
<div class="row align-items-center g-4">
  <div class="col-lg-6"> ... text ... </div>
  <div class="col-lg-6"> <img src="..." class="img-fluid rounded"> </div>
</div>
```

**Callout box**
```html
<p class="p-4 my-4 rounded border bg-light">Highlighted information.</p>
```

**Pull quote**
```html
<figure class="my-5">
  <blockquote class="fs-4 fst-italic">"Quote text."</blockquote>
  <figcaption>— Attribution</figcaption>
</figure>
```

**FAQ accordion**
```html
<div class="accordion" id="faq">
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button"
              data-bs-toggle="collapse" data-bs-target="#q1">Question</button>
    </h2>
    <div id="q1" class="accordion-collapse collapse" data-bs-parent="#faq">
      <div class="accordion-body">Answer</div>
    </div>
  </div>
</div>
```

---

## The CNAME File

`docs/CNAME` contains `nwunitedmethodist.org` and tells GitHub Pages to serve the site at the custom domain. The generator preserves it automatically.

If it ever gets accidentally deleted:

```bash
echo "nwunitedmethodist.org" > docs/CNAME
git add docs/CNAME
git commit -m "Restore CNAME"
git push
```

---

## Requirements

- PHP 8.x (uses `declare(strict_types=1)` and named-argument-style type hints)
- Git
- A GitHub repository with GitHub Pages configured to serve from `docs/` on `main`

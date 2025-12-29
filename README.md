# Super Simple Website Generator

## Constraints

- output plain html pages from header + content + footer
- use locally-hosted Bootstrap CSS and JS
- simple, responsive layout

## Project Structure

```
/README.md (this file)
/working   (used for work in progress)
  /contents
    {bunch of markdown files}
  /header.fragment
  /footer.fragment
  /lipsum.fragment
  /raw.md //concatenated raw notes, just for human use
/public   (output, copied in whole to web server root)
  /css
  	bootstrap.min.css
  	custom.css
  /js
	  bootstrap.bundle.min.js
  /images				
    (various images used by pages)						
  /files
    (various downloadable files linked from pages)															
  {generated html files}
```
## Content Site Map

Work in progress; subject to change.

## Design Guides

We have the basic page structure in place with header and footer fragments. The header includes navigation links, and the footer includes contact information and additional links.

Header ends by opening a main->div.container and footer starts by closing those tags.

We have content guideline recommendations here to break up the monotony of internal pages:

Got it — you want **repeatable “content blocks”** you can drop *inside*:

```html
<main class="container">
  <h1 class="title">…</h1>
  <!-- blocks go here -->
</main>
```

…so internal pages feel designed without turning into a complicated component library.

Here are **simple, elegant, Bootstrap-5-native patterns** (all stack nicely on mobile). Think of them as “section macros” your generator can render from frontmatter or markdown shortcodes.

---

## Visual aid: a “block menu” you can mix-and-match

```text
Within <main.container> under H1:

[ Lead + Key Points ]
  short intro + 3 bullets

[ Split Block ]
  text | image (stacks on mobile)

[ Callout Band ]
  lightly tinted box with icon + note

[ Pull Quote ]
  big quote, small attribution

[ Steps ]
  1 2 3 cards or list-group

[ Stats Row ]
  3 numbers with labels

[ Media + Caption ]
  image full width + caption

[ Related Links ]
  small card list of next actions
```

---

## 1) Lead paragraph + “Key points” (instant structure)

**Use when:** every page needs a quick “what this is” + scannability
**Why it breaks monotony:** creates a strong top rhythm before longer text.

**Bootstrap shape**

* Lead text: `p.lead`
* Bullets: `row` of 2–3 short items (or `list-group`)

---

## 2) Alternating split blocks (text ↔ image)

**Use when:** you have 2–4 subsections that each deserve a visual
**Why it breaks monotony:** repeating pattern, but alternating sides feels designed.

**Bootstrap shape**

* Each block: `row align-items-center g-4 my-5`
* Text column: `col-lg-6`
* Image column: `col-lg-6` with `img-fluid rounded`

**Generator trick:** add a `flip: true/false` so every other block swaps columns.

---

## 3) Small “Callout” box (note, warning, tip, definition)

**Use when:** you want to highlight context without shouting
**Why it breaks monotony:** a soft background + border changes texture.

**Bootstrap shape**

* `div.p-4 my-4 rounded border bg-light`
* Optional icon column: `d-flex gap-3 align-items-start`

(You can map variants: `tip`, `note`, `warning` → different border classes.)

---

## 4) Pull-quote + aside

**Use when:** an internal page has a key line worth emphasizing
**Why it breaks monotony:** editorial feel with almost no layout complexity.

**Bootstrap shape**

* `figure` with larger text (`fs-4`) + subtle `text-secondary`
* `figcaption` for attribution

---

## 5) “Steps” or “Process” (3–5 items)

**Use when:** you describe how something works
**Why it breaks monotony:** turns paragraphs into digestible chunks.

**Bootstrap shape options**

* Simple: `ol.list-group list-group-numbered`
* Card-y: `row row-cols-1 row-cols-md-3 g-4` with mini cards

---

## 6) Stats row (numbers as visual anchors)

**Use when:** you have any measurable claims (even “10+ years”, “24/7”, “3 regions”)
**Why it breaks monotony:** bold typographic contrast.

**Bootstrap shape**

* `row text-center my-5`
* each stat: `col` with big number (`display-6`) + label (`text-secondary`)

---

## 7) Inline “media with caption” (full-width image, not a hero)

**Use when:** you want an image break *mid-article*
**Why it breaks monotony:** provides a pause without hijacking the page.

**Bootstrap shape**

* `figure.my-5`
* image `img-fluid rounded`
* caption `figcaption.text-secondary small mt-2`

---

## 8) Mini “Related” / next actions strip

**Use when:** internal pages should point somewhere else (docs, contact, pricing)
**Why it breaks monotony:** creates an ending beat besides “the text stops”.

**Bootstrap shape**

* `div.border rounded p-4 my-5`
* 2–3 links as small cards or list items

---

## 9) FAQ accordion (even on non-FAQ pages)

**Use when:** you’re answering common objections
**Why it breaks monotony:** interactive element that still prints clean.

**Bootstrap shape**

* `accordion` with 3–6 items (keep it short)

---

## 10) Comparison table (lightweight)

**Use when:** you’re contrasting options, requirements, tiers
**Why it breaks monotony:** introduces a different reading mode.

**Bootstrap shape**

* `table table-sm` inside `div.table-responsive`
* Keep columns <= 3 for mobile sanity

---

# A dead-simple “recipe” for internal pages

If you want an easy default that looks good almost everywhere:

1. **Lead + key points**
2. **Split block** (image / diagram)
3. **Callout**
4. **Steps or FAQ**
5. **Related links**

That’s enough to make pages feel composed, not “wall of markdown.”

---

## If you want one reusable spacing rule

Wrap each block in a `<section class="my-5">…</section>` and you instantly get consistent rhythm.

---





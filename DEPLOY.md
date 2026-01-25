# Deployment

## Quick Deploy

```bash
php generate.php
git add -A
git commit -m "Update site"
git push
```

GitHub Pages automatically deploys from the `docs/` folder on the `main` branch. Changes typically appear within 1-2 minutes.

## What generate.php Does

1. Clears `docs/` (except `css/`, `js/`, `images/`, `files/`, and `CNAME`)
2. Combines header + content fragments + footer into complete HTML pages
3. Outputs to `docs/`

## The CNAME File

`docs/CNAME` tells GitHub Pages to serve the site at `nwunitedmethodist.org`.

**If you delete this file, the custom domain breaks.**

The generator is configured to preserve it, but if it ever gets deleted:

```bash
echo "nwunitedmethodist.org" > docs/CNAME
git add docs/CNAME
git commit -m "Restore CNAME"
git push
```

## Editing Content

1. Edit fragments in `/working/htmls/`
2. Run `php generate.php`
3. Commit and push

Never edit files in `docs/` directly - they get overwritten.

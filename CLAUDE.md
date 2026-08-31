# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

Beautiful Taxonomy Filters (BTF) — a WordPress plugin distributed on wordpress.org. It adds a term-filter form to custom post type archives and rewrites the resulting URLs into `/posttype/taxonomy/term/` instead of query strings.

Built on the WordPress Plugin Boilerplate (tommcfarlin). There is **no build system, no dependency manager, and no test suite** — no `composer.json`, `package.json`, or linter ruleset in the repo. Development means symlinking/copying the repo into a WordPress install's `wp-content/plugins/` and exercising it in the browser. The plugin source lives at the repository **root** — that is what ships to users.

## Releasing (GitHub Actions → wordpress.org SVN)

Deployment is automated; **never commit to Subversion by hand.** Two workflows in `.github/workflows/`, both using the 10up actions and both authenticating with the `SVN_USERNAME` / `SVN_PASSWORD` repo secrets against `SLUG: beautiful-taxonomy-filters`:

- **`deploy.yml`** — triggers on a *published GitHub release*. Runs `10up/action-wordpress-plugin-deploy`, which pushes to SVN `trunk`, creates the matching SVN tag, and (via `generate-zip: true`) attaches the built zip back onto the GitHub release.
- **`assets.yml`** — triggers on pushes to `master` that touch `README.txt`, `subversion/**`, or the workflow itself. Runs `10up/action-wordpress-plugin-asset-update` to sync the readme and plugin-page assets **without cutting a release**. Use this path for changelog typos or a new banner.

To cut a release:

1. Bump the version in **all three** places — they must agree, and the wordpress.org version comes from the release tag name, so the tag must match them too:
   - `beautiful-taxonomy-filters.php` — the `Version:` header
   - `includes/class-beautiful-taxonomy-filters.php` — `$this->version`
   - `README.txt` — `Stable tag:`, plus a new `== Changelog ==` entry
2. Merge to `master`.
3. Publish a GitHub release whose tag is exactly the version number (e.g. `2.5.0`).

`ASSETS_DIR: subversion` — the `subversion/` directory holds only the wordpress.org plugin-page assets (banners, icons, screenshots) and is deployed separately from the code. `.distignore` controls what is stripped from the deployed plugin and zip (`.git`, `.github`, `README.md`, `LICENSE`, `subversion/`, `.DS_Store`, `*~`); anything added to the repo that shouldn't ship to users belongs there.

`README.txt` is the canonical wordpress.org readme (FAQ, changelog, hook docs); `README.md` is GitHub-facing and excluded from the deploy. Keep the feature/language lists in sync when they change.

Branching is git-flow style: work lands on `develop`, releases merge to `master`.

## Other conventions

- Minified assets (`public/css/*.min.css`, `public/js/beautiful-taxonomy-filters-public.min.js`) are **committed and hand-maintained** — no minify task exists. Note the enqueues are asymmetric: `enqueue_styles()` loads `.min.css`, `enqueue_scripts()` loads the **unminified** JS. Edit the unminified source and regenerate the `.min` CSS counterpart manually.
- Code targets WordPress Coding Standards and carries inline `phpcs:ignore` annotations, but no `phpcs.xml` is committed — run phpcs with the `WordPress` standard yourself if you need it. Older files (`widgets/`) predate the current style (no spacing inside parens, `extract()`).
- Translations live in `languages/` as `.po`/`.mo` pairs against `beautiful-taxonomy-filters.pot`. Text domain: `beautiful-taxonomy-filters`.

## Architecture

Boilerplate wiring: `beautiful-taxonomy-filters.php` → `Beautiful_Taxonomy_Filters` (`includes/class-beautiful-taxonomy-filters.php`) requires every class and registers **all** hooks through `Beautiful_Taxonomy_Filters_Loader`. When adding a hook, register it in `define_admin_hooks()` / `define_public_hooks()` rather than calling `add_action` from inside a class.

### The filtering round-trip (there is no AJAX filtering)

1. `public/partials/beautiful-taxonomy-filters-public-display.php` renders a plain `POST` form. Each taxonomy dropdown is `wp_dropdown_categories()` using `Walker_Slug_Value_Category_Dropdown` (`public/class-beautiful-taxonomy-filters-walker.php`). Option values are **term IDs**, and the select `name` is `select-{taxonomy}` — not the taxonomy slug, because WP would then resolve the query itself before the redirect.
2. `Beautiful_Taxonomy_Filters_Public::catch_filter_values()` runs on `template_redirect`, verifies the `btf_do_filtering_nonce`, converts each posted term ID to its slug, and assembles `/{post_type_archive}/{tax_rewrite_slug}/{term_slug}/…`, re-appending any pre-existing `$_GET` params, then `wp_redirect()`s.
3. The pretty URL is served by rewrite rules built in `Beautiful_Taxonomy_Filters_Rewrite_Rules::generate_rewrite_rules()` and injected into `$wp_rewrite->rules` from `Beautiful_Taxonomy_Filters_Admin::add_rewrite_rules()` on `generate_rewrite_rules`. Since 2.4.0 the rules are a **single regex with optional segments in registration order** (`(?:/slug/([^/]+))?` per taxonomy) plus a paged variant. Consequence: taxonomy segments must appear in the order the taxonomies are returned.

**Steps 2 and 3 must agree exactly** — the URL the redirect produces has to match the rule, or filtering 404s. Since 2.5.0 both derive their segments from the same two helpers in `includes/api.php`: `btf_get_post_type_archive_slug()` and `btf_get_taxonomy_rewrite_slug()` (which strips a leading `{post_type_slug}/` so a taxonomy nested under the CPT archive doesn't put that slug in the URL twice, and exposes the `beautiful_filters_taxonomy_rewrite_slug` filter). Never resolve a slug inline in one of the two places. `generate_rewrite_rules()` also emits the pre-2.5.0 "legacy" rule shape alongside the current one whenever they differ, so URLs generated by older versions keep resolving; when the segment logic changes again, that back-compat pair has to be kept in mind.

### Taxonomy/post-type resolution

`includes/api.php` holds the shared resolution helpers used everywhere: `btf_get_current_posttype()`, `btf_get_current_taxonomies()`, `is_btf_filtered()`. They apply the `beautiful_filters_post_types` / `beautiful_filters_taxonomies` filters and always strip the built-in taxonomies (`category`, `post_tag`, `post_format`) and Polylang's internal ones. Prefer these over reimplementing the logic. (Their docblock claims they are pluggable, but they are **not** wrapped in `function_exists()`.)

`Beautiful_Taxonomy_Filters_Public::get_current_posttype( $rewrite )` is the older static variant still used by the walker and widgets; `$rewrite = true` returns the post type's rewrite slug rather than its name — the two are frequently different and mixing them up is a common source of bugs.

Both `beautiful_filters()` and `beautiful_filters_info()` take an `$args` array whose `echo` key (default `true`) switches between printing and returning the module via output buffering — the shortcodes rely on `echo => false` to render in place. Because the `beautiful_filters_post_types` filter is user-supplied, these methods guard with `is_array()` before `in_array()`; keep that guard when touching them (PHP 8 fatals otherwise).

**Known duplication:** `widgets/beautiful-taxonomy-filters-widget.php` re-implements the taxonomy exclusion logic and the whole form markup inline instead of calling `btf_get_current_taxonomies()` and the public partial. A behavior change to the filter module usually has to be made in both places.

### Settings

Registered in `Beautiful_Taxonomy_Filters_Admin::settings_api_init()` via the Settings API, shown under Settings → "Taxonomy Filters" (`options-general.php?page=taxonomy-filters`) with tabs `basic|advanced|help|about` driven by `admin/partials/admin-display.php`. Every field's render callback is a one-line `require` of a partial in `admin/partials/basic/` or `admin/partials/advanced/`.

Storage is mid-migration: older settings are individual options named `beautiful_taxonomy_filters_*` (e.g. `_post_types`, `_styles`, `_disable_select2`), while newer ones go into the single serialized array option `beautiful_taxonomy_filters_settings` (currently `conditional_dropdowns`). **New settings belong in that array.**

`beautiful_taxonomy_filters_version` stores the installed version; `check_update_version()` runs on `admin_init` and is where upgrade routines go (version-compare branches that show an admin notice via `add_admin_notice()`, or flush rewrite rules as the 2.4.0 branch does).

### Conditional dropdowns (beta)

Opt-in via the advanced tab. `public/js/beautiful-taxonomy-filters-public.js` posts to the `update_filters_callback` AJAX action on every select change; `Beautiful_Taxonomy_Filters_Public::update_filters_callback()` verifies a nonce and assembles a `$wpdb` query joining posts/term_relationships/term_taxonomy to find terms that still yield results. The query is only partly parameterizable: term IDs go through `absint()` and `%d` placeholders, and taxonomy names are whitelisted with `taxonomy_exists()` because they are interpolated as **table aliases** that `prepare()` cannot handle — preserve both guards when editing, and note the standing TODO that child terms aren't accounted for.

The select2 `compat/` modules were dropped when select2 was updated, so the `beautiful_filters_disable_fuzzy` filter is no longer wired up in the JS, and `min_search` is still localized but overridden by a hardcoded `minimumResultsForSearch: 1`.

## Extension points (public API — treat as stable)

- Theme integration: `do_action( 'show_beautiful_filters', $post_type )` and `do_action( 'show_beautiful_filters_info' )`; shortcodes `[show_beautiful_filters]` / `[show_beautiful_filters_info]`; deprecated template tags of the same names in `public/beautiful-taxonomy-filters-functions.php` (kept for back-compat since 1.2.6); or the "automagic" option that injects the modules on `loop_start`.
- Filters are named `beautiful_filters_*`, actions `beautiful_actions_*`. Every option read is passed through a matching filter (usually with `$current_post_type` as a second arg) — follow that convention when adding options, and document new hooks in `README.txt`.

## Compatibility constraints to preserve

- Must degrade without JavaScript: select2 is an enhancement layer only, and the form works as a plain POST when select2 is disabled in settings.
- Polylang gets explicit handling (language taxonomies excluded, per-language rewrite rules in `add_rewrite_rules()`, select2 i18n file loading); WPML is only partially supported via `ICL_LANGUAGE_CODE`.
- Built-in `post` is deliberately unsupported (its permalink structure defeats the rewrite approach) — see the FAQ in `README.txt`.
- `Requires at least: 4.3.0`; avoid modern PHP syntax the existing baseline doesn't already use.

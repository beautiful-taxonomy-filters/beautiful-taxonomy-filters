[![Try it in WordPress Playground](https://img.shields.io/badge/Try%20it-WordPress%20Playground-3858E9?logo=wordpress&logoColor=white)](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/beautiful-taxonomy-filters/beautiful-taxonomy-filters/master/subversion/blueprints/blueprint.json)

Want to see it work before installing it? The badge above boots a throwaway WordPress in your browser with a recipe post type, three taxonomies and sixteen recipes already set up, so you can filter straight away. Nothing is installed on your machine and nothing is saved.

One caveat worth knowing before you try it: the preview runs inside WordPress Playground, and the address bar drawn above it is a mockup that doesn't follow where you are inside the site. Filtering really does rewrite the URL to `/recipes/cuisine/thai/diet/vegan/` rather than `/recipes/?cuisine=thai&diet=vegan` — you just can't watch it happen there. That part needs a real install to see.

The Beautiful Taxonomy Filters plugin is an easy and good-looking way to provide your visitors with filtering for your post types. With this you get a complete solution for adding filtering based on custom taxonomy terms/categories/tags. It will also automatically add rewrite rules for pretty looking filter URLs. It’s completely automatic, works without javascript and is based on the [WordPress Plugin boilerplate](https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate) for a *standardized, organized and object-oriented* codebase. It uses [select2](http://ivaynberg.github.io/select2/) for pretty looking and user friendly dropdowns but will fall back to ordinary ones if javascript is not supported.
**No more horrible looking URLs or hacky Javascript solutions**

#[Check out the wiki for more information](https://github.com/jonathan-dejong/beautiful-taxonomy-filters/wiki)

##features
* Activate filtering on any registered public custom post type.
* Exclude taxonomies you just don’t want the visitors to filter on.
* Beautifies the resulting URLs. You won’t see any /posttype/?taxonomy1=term. Instead you’ll see /posttype/taxonomy/term.
* The pretty URLs are much more SEO friendly so you'll give a boost to those filtered pages. Just remember to use canonicals where it's appropriate.
* BETA: Conditional dropdowns. Make sure your visitors never end up with empty filtered results. AJAX reloads the values in each dropdown based on previously selected values.
* Polylang compatible.
* Multisite compatible. No network settings at the moment.
* Comes with a complete functional filter module for you to put in your theme.
* Three alternatives for putting the filter modules in your theme:
  * Widgets (Also lets you "hard set" a post type for use anywhere)
  * do_action hooks (for granular control)
  * Automagic setting which will magically place the modules in your archive from thin air. Wizards at work…
* Choose from different styles for the component, or disable styling and do it yourself in style.css! Just want to tweak a style? Add your custom CSS directly on the settings page.
* Many more settings for fine-tuning the filter modules behavior:
  * A ”Clear all” link for the filter component.
  * Choose between placeholders or "show all" in the dropdowns.
  * Hide empty terms in the dropdowns.
  * Show a post count next to the term name
  * Disable select2
  * Show term description
  * Disable headings you don't want
  * More to come!
* Ability to show your visitors information about their current active filtering and control the look of this.
* Allows for custom GET parameters to be included. Extend the filter your way with maybe a custom search-parameter or whatever you like.
* Many [filters and actions](https://wordpress.org/plugins/beautiful-taxonomy-filters/other_notes/) for modifying the plugins behavior. For you control freaks out there…

### Languages
* English
* Swedish
* Spanish (Thanks to Juan Javier Moreno Restituto)
* Dutch (Thanks to Piet Bos)
* German (Thanks to [Matthias Bonnes](http://macbo.de/))
* French (Thanks to [Brice Capobianco](https://profiles.wordpress.org/brikou))
* Simplified Chinese (Thanks to [Amos Lee](http://www.wpzhiku.com/))
* Portuguese (Thanks to [Luis Martins](http://www.wearemultiweb.com/))
* Portuguese Brasil (Thanks to Bruno Sousa)
* Catalan (Thanks to [Maiol Xercavins](https://wordpress.org/support/profile/diavolo669))
* Swiss (Thanks to [Raphael Hüni](http://werbelinie.ch/))
* Bulgarian (Thanks to [Georgi Marokov](https://github.com/Georgi-Marokov))
* Romanian (Thanks to [Roberto Tamas](www.novace.ro))

## Development & releases

This plugin is developed here on GitHub and deployed to the [WordPress.org plugin repository](https://wordpress.org/plugins/beautiful-taxonomy-filters/) automatically via GitHub Actions using the [10up WordPress plugin deploy action](https://github.com/10up/action-wordpress-plugin-deploy). There is no need to commit to Subversion by hand.

### Repository layout
- The plugin source lives at the repository **root** — this is what ships to users.
- `subversion/` holds the WordPress.org plugin page assets (banner, icon, screenshots).
- `.distignore` lists files excluded from what gets deployed to WordPress.org (e.g. `README.md`, `.github/`, `subversion/`).

### Cutting a release
1. Bump the version in **all three** places and add a changelog entry:
   - `beautiful-taxonomy-filters.php` — the `Version:` header.
   - `README.txt` — the `Stable tag:` line, plus a new entry under `== Changelog ==`.
   - `includes/class-beautiful-taxonomy-filters.php` — the `$this->version` value.
2. Merge the change into `master`.
3. Publish a **GitHub release** whose tag name is exactly the version number (e.g. `2.5.0`).

Publishing the release triggers [`.github/workflows/deploy.yml`](.github/workflows/deploy.yml), which pushes the code to WordPress.org SVN `trunk` and creates the matching SVN tag. The version delivered to users comes from the release/tag name, so it must match the headers above.

### Updating the plugin page without a release
Pushing changes to `README.txt` or `subversion/**` on `master` triggers [`.github/workflows/assets.yml`](.github/workflows/assets.yml), which syncs the readme and plugin-page assets to WordPress.org without publishing a new version.

### Required repository secrets
Both workflows need these secrets (Settings → Secrets and variables → Actions):
- `SVN_USERNAME` — your WordPress.org username.
- `SVN_PASSWORD` — your WordPress.org password.
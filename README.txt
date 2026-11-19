=== Beautiful taxonomy filters ===
Contributors: Jonathandejong
Donate link: http://fancy.to/k9qxt
Tags: Taxonomy, filter, pretty permalinks, terms, widget
Requires at least: 4.3.0
Tested up to: 7.1
Stable tag: 2.6.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Supercharge your custom post type archives by letting visitors filter posts by their terms/categories. This plugin handles the whole thing for you!

== Description ==

The Beautiful Taxonomy Filters plugin is an easy and good-looking way to provide your visitors with filtering for your post types. With this you get a complete solution for adding filtering based on custom taxonomy terms/categories/tags. It will also automatically add rewrite rules for pretty looking filter URLs. It’s completely automatic, works without javascript and is based on the [WordPress Plugin boilerplate](https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate) for a *standardized, organized and object-oriented* codebase. It uses [select2](http://ivaynberg.github.io/select2/) for pretty looking and user friendly dropdowns but will fall back to ordinary ones if javascript is not supported.
**No more horrible looking URLs or hacky Javascript solutions**

**Try it before installing it.** Use the Live Preview button on this page for a temporary WordPress in your browser, already set up with a recipe archive and three taxonomies to filter on. Nothing is installed and nothing is saved. The one thing the preview cannot show you is the rewritten URL itself - see the FAQ for why.

= Features =
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
* Many [filters and actions](https://beautiful-taxonomy-filters.github.io/beautiful-taxonomy-filters/hooks/) for modifying the plugins behavior. For you control freaks out there…


= Languages =
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

____
Do you want to translate this plugin to another language? I recommend using POEdit (http://poedit.net/) or if you prefer to do it straight from the WordPress admin interface (https://wordpress.org/plugins/loco-translate/). When you’re done, send us the file(s) to me@jonte.dev and we’ll add it to the official plugin!

= Other =
* Based on [WordPress Plugin Boilerplate](https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate)
* Uses [Select2](http://ivaynberg.github.io/select2/) to enhance dropdowns

= Featured on =
* [WP Tavern](http://www.wptavern.com/beautiful-taxonomy-filters-for-wordpress-custom-post-types)
* [RiverTheme](http://www.rivertheme.com/top-22-free-wordpress-plugins-of-december-2014/)
* [The WhiP (WPMU DEV)](http://premium.wpmudev.org/blog/this-week-in-wordpress-5/)
* [TotalPhotoshop](http://www.total-photoshop.com/)


= Developer documentation =
Every filter and action, with examples, is documented at [beautiful-taxonomy-filters.github.io/beautiful-taxonomy-filters](https://beautiful-taxonomy-filters.github.io/beautiful-taxonomy-filters/hooks/).

== Installation ==

1. Upload `beautiful-taxonomy-filters` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Follow the instructions found in Settings > Taxonomy filters to get you started!
4. For more customization have a look at the [filters and actions](https://beautiful-taxonomy-filters.github.io/beautiful-taxonomy-filters/hooks/)


== Frequently Asked Questions ==

= Why doesn't the URL change in the live preview? =

It does change - you just can't see it. The live preview runs a whole WordPress inside your browser using WordPress Playground, and the address bar drawn above it is a mockup that doesn't follow where you are inside the site. So when you pick a couple of filters, the preview shows you the filtered results, but not the pretty URL that produced them.

Behind that mockup the URL really has become something like `/recipes/cuisine/thai/diet/vegan/` rather than `/recipes/?cuisine=thai&diet=vegan`. Since those clean, linkable, bookmarkable URLs are the main thing this plugin does, it's worth installing it on a real site to see that part properly.

= Can I show the filter module on a static page / in my header / in my footer? =

Yes. Either use the widget and set a specific post type in it's settings or add a parameter of your custom post type slug to the `show_beautiful_filters` action. This "hardcodes" the filter module to that post type and lets you use it pretty much anywhere in your theme. Hardcore right..
`
<?php do_action( 'show_beautiful_filters', 'posttypeslug' ); ?>
`

= Is there a way to change the order of the taxonomies? =

Well of course! You can either change the order in which you register your taxonomies OR you can use the filter
`
function moveElement( &$array, $a, $b ) {
    $out = array_splice($array, $a, 1);
    array_splice($array, $b, 0, $out);
}

function custom_tax_ordering( $taxonomies, $current_post_type ) {
	moveElement( $taxonomies, 2, 0 );
	return $taxonomies;
}
add_filter( 'beautiful_filters_taxonomy_order', 'custom_tax_ordering' );
`

= Does this support multiple selecting multiple terms from the same taxonomy? =

No. In a future release we will look into if it’s possible to support this AND having beautiful permalinks. If that doesn’t work we will likely add an option where you can opt out of beautiful permalinks and enjoy the power of multiple terms filtering instead.

= My taxonomy isn’t showing in the filter / the filters are too small =

A Taxonomy will not appear in the filter until at least one post has been connected to one of the terms.
Just start tagging up your posts and you’ll see it shows up! Also, make sure that your custom post type has an archive (in the arguments for the custom post type) since this plugin uses the builtin WordPress functionality for archives.

= Why aren't the builtin post types supported? =
**Posts** are not supported because we haven't been able to create proper rewrite rules for the multiple filtering to work. Posts are handled differently by WordPress than other custom post types (you have probably noticed that there's no /posts/ in the permalink for example). Due to this the same rewrite rules that works for custom post types doesn't work for posts. If you're just looking to filter your posts by their categories with a dropdown you can use this function [wp_dropdown_categories](http://codex.wordpress.org/Function_Reference/wp_dropdown_categories). It's good practice to use a custom post type when you're not going to use it as news/blog -posts so perhaps you should create a Custom post type instead and make use of this beautiful plugin!

= The filter isn't working with my taxonomies using a rewrite slug =
**Since v 2.2 this has been fixed. Make sure you keep BTF updated**
In order for the rewrite rules to work with a taxonomy that has a rewrite slug you also have to add the same slug to the `query_var` parameter of register_taxonomy. It wont have any visible impact for you but it's what's needed for the filtered urls to work!

= My taxonomy rewrite slug is nested under my post type slug =

If you register a taxonomy with a rewrite slug like `horses/locations` on a post type that's archived at `horses`, Beautiful Taxonomy Filters will only use the `locations` part when building the filtered URL, since the `horses` part is already in the post type archive URL. Before 2.5.0 the slug ended up in the URL twice (`/horses/horses/locations/spain/`). Those old URLs still resolve, but new ones won't have the duplicate. If you want the old behaviour back, return the untouched slug from the `beautiful_filters_taxonomy_rewrite_slug` filter.

= Is it compatible with Polylang/WPML? =
It is 100% compatible with Polylang. WPML is a bit wonky but might work depending on your setup. In order for this to work properly you should set the post types and all connected taxonomies to be translatable. The filtered urls will still work even if you don't set the post type to be translatable but when switching language Polylang still think it should add the new language to the URL which means it'll throw a 404 error. This is to be expected and NOT due to this plugin. If you experience 404 errors make sure you flush your rewrite rules by going to settings > permalinks in the admin dashboard.

= Is it compatible with XXXXXX? =
You will be able to use this plugin with any **public registered custom post type** regardless if it's been created by yourself or a plugin. **However** the displaying of the CPT must be via it's archive template. That means that a plugin that uses shortcodes to display the entire post listing on a static page will not work out of the box. It will also not work out of the box with plugins that in some way alter the permalink to the CPT archive [WPMU Devs Events+ for example](https://premium.wpmudev.org/project/events-plus/).

= But I'm using Events+ and I really want this! =
[See here for more info](http://premium.wpmudev.org/forums/topic/i-would-change-the-sidebar-on-the-events-page-i-created)

= I really love this plugin and I want to donate a little something-something =
Why thank you! We don't have proper donate link but if you want to you can send us a giftcard on [fancy](https://fancy.com). We will use it to buy cool stuff for the office! Make it out to jontedejong@gmail.com


== Screenshots ==

1. Basic options.
2. Advanced options.
3. The filter module using the light material look in WordPress twentyfifteen theme.
4. The filter module with a dropdown open and using descriptions for terms.
5. The filter widget in WordPress twentyfifteen theme.
6. The filter widget settings.
7. Example of a beautified permalink structure (4 different taxonomies/terms).


== Changelog ==
= 2.6.2 =
* BUGFIX: Conditional dropdowns treated a dropdown left on its "all" option as an active filter when the form had more than one taxonomy. The dropdown you were picking from then had its own options updated too, instead of staying fully selectable.
* BUGFIX: Conditional dropdowns no longer drop the term descriptions from the options they update.

= 2.6.1 =
* SECURITY: Fixed a stored cross-site scripting vulnerability (CVE-2026-122298) in the conditional dropdowns AJAX endpoint. A user able to create terms could give one a name that the endpoint returned as live HTML. Term names are now returned HTML escaped and decoded as plain text in the browser, and the response is sent as `application/json` with HTML characters escaped. Thanks to Wordfence for the responsible disclosure. Please update.
* SECURITY: Term names, slugs and descriptions in the filter dropdowns are now escaped on output, and select2 no longer parses the selected term's text as HTML when term descriptions are shown.
* SECURITY: The conditional dropdowns endpoint now only answers when conditional dropdowns are enabled, for post types the filter is enabled on, and only about the taxonomies the filter module itself shows. Before, a visitor could ask it for the terms of any taxonomy, including non-public ones.
* SECURITY: All settings are now sanitized when saved, and output from filters such as `beautiful_filters_apply_button`, `beautiful_filters_loader` and `beautiful_filters_term_name` is passed through `wp_kses_post()`. Admin strings are escaped too.
* BUGFIX: Conditional dropdowns no longer return nothing for taxonomies with a hyphen in their name. If you modify the query with `beautiful_filters_related_terms_sql`, note that the joined tables are now aliased `btf_tr0`, `btf_tr1` and so on.
* BUGFIX: Filtering with a term that doesn't exist (anymore) no longer produces a broken URL and PHP warnings. Custom GET parameters carried over to the filtered URL are now properly encoded.
* BUGFIX: The conditional dropdowns endpoint no longer calls `map_deep()`, which doesn't exist before WordPress 4.4, the plugin's minimum supported version being 4.3.
* IMPROVEMENT: Every PHP file now refuses to run when requested directly instead of through WordPress.
* IMPROVEMENT: Rewrite rules are flushed when you save the post types or taxonomies settings, instead of every time the settings page is viewed.
* IMPROVEMENT: The filter widget now skips non-public taxonomies, like the filter module already did.
* DOCS: The filter and action reference has moved to [the developer documentation](https://beautiful-taxonomy-filters.github.io/beautiful-taxonomy-filters/hooks/). It was too long for the wordpress.org plugin page, which cut it off.

= 2.6.0 =
* BUGFIX: A post type whose name contains a hyphen, like wp-book, is now resolved correctly from its archive template. Previously only the part before the first hyphen was used, which meant the filter module silently didn't appear and PHP threw a notice.
* BUGFIX: The filter module now resolves the post type archive slug the same way the rewrite rules and the filtered URL do. This fixes a PHP warning on post types registered with `'rewrite' => false` and a mismatched URL on post types whose `has_archive` differs from their rewrite slug.
* BUGFIX: A taxonomy registered without an explicit all_items label no longer shows its own name as the "all" value. WordPress silently falls all_items back to the taxonomy name, so the filter info module printed things like "Meal Types: Meal Types" and the dropdowns had an "all" option reading just "Meal Types". You'll now get "All Meal Types" unless you've set the label yourself, in which case nothing changes.
* BUGFIX: The select2 placeholder attribute is now properly escaped. A placeholder containing a quote, whether it came from a taxonomy label or from your own `beautiful_filters_dropdown_placeholder` callback, could break the markup of the dropdown.
* IMPROVEMENT: The redirect to the filtered URL now goes through `wp_safe_redirect()`. The URL the plugin builds always points at your own site, so nothing changes for anyone, but if you use the `beautiful_filters_new_url` filter to send visitors to another domain you'll need to add that host to WordPress' `allowed_redirect_hosts` filter. Polylang already registers its language domains there, so a domain per language setup is unaffected.
* BUGFIX: The conditional dropdowns AJAX endpoint no longer builds a malformed query when none of the post type's taxonomies have any terms, and no longer emits PHP warnings when called without parameters.
* NEW FILTER: `beautiful_filters_post_type_archive_slug` lets you change the post type archive segment of a filtered URL, the counterpart to `beautiful_filters_taxonomy_rewrite_slug`. Thanks to AsfalothDE who first asked for this back in 2017.
* NEW FILTER: `beautiful_filters_taxonomy_all_items_label` lets you set the "all terms" string yourself, and is the way to get the old behaviour back if you preferred it. See the [developer documentation](https://beautiful-taxonomy-filters.github.io/beautiful-taxonomy-filters/hooks/).
* NEW FILTERS: `beautiful_filters_pre_related_terms` and `beautiful_filters_related_terms_sql` let you replace or modify the conditional dropdowns database query, so you can point it at your own index on a large site.

= 2.5.0 =
* BUGFIX: The post type slug is no longer added twice to the filtered URL when a taxonomy is registered with a rewrite slug nested under the post type archive, like the rewrite slug `horses/locations` on a post type archived at `horses`. You'll get /horses/locations/spain/ instead of /horses/horses/locations/spain/ from now on. The old URLs are still registered as rewrite rules so anything you've already linked to keeps working.
* NOTE: If you've been working around this with your own code you can remove it now. A `beautiful_filters_new_url` filter that strips the repeated slug does no harm but isn't needed anymore. Anything doing a plain str_replace on the post type slug will break the URL instead of fixing it, so do get rid of that one.
* BUGFIX: A taxonomy registered without a rewrite slug, but with a query_var different from the taxonomy name, produced a URL that didn't match the rewrite rules and gave a 404. Both are built from the same value now.
* BUGFIX: Polylangs language prefixed rewrite rules haven't actually been generated since 2.4.0, so filtered URLs like /en/horses/locations/spain/ gave a 404. They're back.
* NEW FILTER: `beautiful_filters_taxonomy_rewrite_slug` lets you decide what URL segment a taxonomy gets. See the [developer documentation](https://beautiful-taxonomy-filters.github.io/beautiful-taxonomy-filters/hooks/).

= 2.4.9 =
* BUGFIX: The `[show_beautiful_filters]` and `[show_beautiful_filters_info]` shortcodes now render at the position of the shortcode instead of above the content. If you previously relied on the old placement you may need to move the shortcode or adjust your styling.
* IMPROVEMENT: Hardened the filter modules against a rare PHP 8 fatal error when a custom `beautiful_filters_post_types` filter returns a non-array value.
* IMPROVEMENT: The conditional dropdowns AJAX endpoint now returns a proper JSON error response on a failed security check.

= 2.4.8 =
* IMPROVEMENT: Tested up to WordPress 7.1.
* BUGFIX: Fixed a PHP 8+ fatal error that could occur on archive pages when an array-style query parameter was present in the URL. Also hardened a couple of related PHP 8 notices.

= 2.4.7 =
* BUGFIX: Sorted a security issue. Please make sure you update to this version or later.

= 2.4.6 =
* BUGFIX: Fixed issue where the allowed post types option value `beautiful_taxonomy_filters_post_types` could be something other than an array and caused fatal errors.

= 2.4.5 =
* BUGFIX: Fixes some issues with Select2. without search it will fail to stay open for no apparent reason so search is always enabled.
* BUGFIX: Fixed so that select2 honours changed quantities from the select options.
* IMPROVEMENT: Select2 now does not destroy and recreate the dropdowns all the time, instead we force it to recognise a change event.
* IMPROVEMENT: Latest select2 library in use.

= 2.4.4 =
* BUGFIX: Sorted the recent security issue. Please make sure you update to this version or later.

= 2.4.3 =
* BUGFIX: Fixed if statement causing PHP warning.
* IMPROVEMENT: Conditional dropdowns now attempts to *not* disable any value in the first dropdown being filtered, making it a bit more intuitive.

= 2.4.2 =
* BUGFIX: Missing slash in pagination rules. Thanks @fabianlindfors

= 2.4.1 =
* BUGFIX: Did not check if new rewrite rules was empty before attempting to merge them.

= 2.4.0 =
* _This version should fix issues caused by last update_
* IMPROVEMENT: Complete rewrite of the rewrite rules (huehuehue). _note:_ BTF no longer allows for any order of taxonomies in URL. Only the order in which they are registered.
* BUGFIX: Fixed some warning when no taxonomies we're registered on startup.

= 2.3.5 =
* FEATURE: Added shortcode for filter module. Use `[show_beautiful_filters post_type="yourcptslug"]` where you want!
* BUGFIX: Fixed issue where API function is_btf_filtered() returned false if query_var is different than registered taxonomy slug.
* BUGFIX: Some bugfixes for non existing contents etc. Just be happy with the shortcodes and carry on with your day!

= 2.3.4 =
* BUGFIX: HTML characters now properly decode when using conditional dropdowns.
* BUGFIX: Fixed Polylang urls incorrect when using homepage slug in URL.
* BUGFIX: Countable warning in post count for terms.
* BUGFIX: Fixed issue where has_archive was not honored.

= 2.3.3 =
* BUGFIX: Made a booboo with the previous versions new improved dropdown query. Please update right away and don't try to find me.

= 2.3.2 =
* IMPROVEMENT: Vastly improved the conditional dropdowns query. It should now work even with pretty large sets of posts and terms.
* IMPROVEMENT: Norwegian translation added. Thank you Ørjan Hoyd H Vøllestad.
* BUGFIX: Widget now uses correct label for dropdown placeholders/labels.
* BUGFIX: Added initial slash to pagination in urls. Thanks @frantorres.
* BUGFIX: A pesky little php warning.
* BUGFIX: Some other stuff I can barely remember..


= 2.3.1 =
* BUGFIX: in_array warning on new installs. Move along, nothing to see here.

= 2.3.0 =
* IMPROVEMENT: New API functions are now available. They can be found in /includes/api.php. Most usable is probably `is_btf_filtered()` which will return true or false if the current page is filtered by BTF. Not currently in use everywhere in the code tho so don't peak..
* IMPROVEMENT: "Clear all" only appear if there are actually something to clear.. Courtesy of the new api.
* IMPROVEMENT: Body classes are now added if on a BTF enabled archive and if there is currently a filter active. `btf-archive` and `btf-filtered` are added to the body class. Use these however you want!
* BUGFIX: Don't mess with the "all option" when conditional dropdowns are active without select2.



= 2.2.1 =
* FEATURE: Disable fuzzy search in select2. It's as easy as adding this filter:
`
function disable_fuzzy_search( $boolean ) {
	return true;

}
add_filter('beautiful_filters_disable_fuzzy', 'disable_fuzzy_search', 10, 1);
`
(Thanks to [babouz44](https://wordpress.org/support/users/babouz44/) for the help).

* BUGFIX: Was a little quick on the RTL. **now** it will work as it should :)

= 2.2.0 =
* BUGFIX: You can now have a different taxonomy rewrite slug than it's query_var and it'll work just fine anyway. Adds better compatibility with plugins that registers their own taxonomies. Big thanks to [Kristoffer Lorentsen](https://github.com/krilor). Have a cookie 🍪
* BUGFIX: Found a bug when conditional dropdowns was used without select2. Don't ask.
* IMPROVEMENT: Select2 will now automatically detect RTL languages. You can also set this manually using the filter:
`
function modify_rtl( $rtl ) {
	return true;
}
add_filter( 'beautiful_filters_rtl', 'modify_rtl' );
`

* IMPROVEMENT: Select2 will now automatically detect current language (if using Polylang or WPML) and apply the correct translation file. You can also set this manually yourself using the new filter:
`
function modify_current_language( $language ) {
	return 'sv';
}
add_filter( 'beautiful_filters_language', 'modify_current_language' );
`

* IMPROVEMENT: Some overall improvement to the select2 JS script. Just a bit of refactoring.

= 2.1.1 =
* IMPROVEMENT: Added Portuguese Brasil translation (Thanks to Bruno Sousa).
* IMPROVEMENT: Added Romanian translation (Thanks to [Roberto Tamas](www.novace.ro)).

= 2.1.0 =
* IMPROVEMENT: The conditional dropdowns now also apply when loading a filter result page. Please post all issues to the support forums.
* IMPROVEMENT: Updated POT file.
* IMPROVEMENT: Updated Swedish translation.
* IMPROVEMENT: Added Bulgarian translation (Thanks to [Georgi Marokov](https://github.com/Georgi-Marokov)).

= 2.0.0 =
* FEATURE: It's finally here! AJAX-powered conditional dropdowns. Select a term in one taxonomy and see the selectable terms change in all other taxonomies. No more "no posts found" results for the visitors. __This is a BETA feature which you have to enable in the advanced settings. If you find issues please create a topic in the forums.__

A loader will appear and the dropdowns will be disabled while the AJAX works its magic if it takes longer than 800ms. any new AJAX triggered before the previous has finished will also abort the previous one.

You can replace the default loader .gif (WordPress spinner) using the new filter:
`
function my_custom_loader( $loader, $taxonomy, $posttype ){

	return $loader; // $loader is an img tag

}
add_filter('beautiful_filters_loader', 'my_custom_loader', 10, 3);
`

* FEATURE: A new style option "Simple" which just arranges everything without adding colors, drop shadows etc. also tweaked other styles for new select2 classes.
* IMPROVEMENT: Select2: Now a wrapping span element is always added to the results of the dropdown which carries over all classnames from the original option element. Use this to style hierarchical taxonomies. `.select2-results__option .level-1{ padding-left: 1em; }`.
* IMPROVEMENT: Added Portuguese (Thanks to [Luis Martins](http://www.wearemultiweb.com/)).
* IMPROVEMENT: Added Catalan (Thanks to [Maiol Xercavins](https://wordpress.org/support/profile/diavolo669)).
* IMPROVEMENT: Added Swiss (Thanks to [Raphael Hüni](http://werbelinie.ch/)).
* IMPROVEMENT: Fixed some untranslatable strings.
* IMPROVEMENT: Updated POT file and Swedish translation.
* IMPROVEMENT: Greatly improved the information on the help and about tabs. Now there's links to the forum, FAQ, hooks and github repo. All to make it easier for you to nag at me!
* IMPROVEMENT: Updated screenshots for wordpress.org
* IMPROVEMENT: Added a very basic stylesheet always included (already minimized to 486b) with BTF.

= 1.3.0 =
Just a minor update right now.. bigger things to come. carry on!

* IMPROVEMENT: Updated select2 lib to v 4.0.3. Hopefully fixing some issues with later versions of Safari on IOS

= 1.2.9 =
* BUGFIX: Fixed undefined index for widget walker.
* BUGFIX: Fixed incorrect post count in each term when filter module is not on a CPT archive.
* IMPROVEMENT: Updated german translations. Thanks to [Nils Schönwald](https://wordpress.org/support/profile/schoenwaldnils).
* IMPROVEMENT/BUGFIX: Updated select2 library to 4.0.1 (latest stable). This seems to fix issue of `.change` event not happening on original select element.
* IMPROVEMENT: Fixed some commenting inconsistencies. Nothing to see here folks.


= 1.2.8 =
IMPORTANT: In this update we've done a big overhaul of the settings page. This was important to be able to keep improving BTF settings and features. Unfortunately this means that any of your advanced settings will have to be reset. After updating please take a look at the "Advanced options" tab to make sure everything is set as you want.

* FEATURE: Added option under Advanced tab to show term description in the dropdowns. If you're also using select2 the description will wrap in a span which you can style however you like! This feature is also available for widgets and of course you can modify it by post type using the new filter "beautiful_filters_show_description" which you can read more about in Notes on wordpress.org
* IMPROVEMENT/BUGFIX: Makes sure that when the option to show number of posts in term it will only show the number of posts for the current post type (if multiple post types shares the same taxonomies). Pretty neat!
* IMPROVEMENT: Complete overhaul of the settings page. Now with tabs and more logical separation of settings. It will allow me to add more settings to the advanced tab without overwhelming the user.
* IMPROVEMENT: Added notification and checks of versions to make setting changes easier in the future. Will be able to automatically convert settings so you'll no longer lose them. Sorry about that btw..
* IMPROVEMENT: Added a notification on activation to let new users get started easily.
* IMPROVEMENT: Added a link to the post type archive next to each post type and a list of connected post types next to each taxonomy in Basic options settings tab.
* IMPROVEMENT: Some basic housecleaning because we always need more lemon pledge.
* BUGFIX: Automagic setting no longer affects RSS feeds.
* BUGFIX: Fixed minor html validation errors. Thanks to kiwiot for noticing.
* BUGFIX: Fixed issue with hidden select overflowing and causing horizontal scroll. Thanks to OrsomeWells for noticing.

= 1.2.7 =
* IMPROVEMENT: Added the same filter for modifying the "Apply filter" button text for the widget as the other implementations. If you're already using the filter you should see the change to the widget without any further actions.
* IMPROVEMENT: Added filter for changing the "Clear all" button text. Use `beautiful_filters_clear_button`. Takes the string and requires a return of a string.

Now go punch a shark!

= 1.2.6 =
* FEATURE: It's now easier than ever to add the modules to your themes. Instead of using `<?php if(function_exists('show_beautiful_filters')){ show_beautiful_filters(); } ?>` you can now just do `<?php do_action('show_beautiful_filters'); ?>` and of course `<?php do_action('show_beautiful_filters_info'); ?>` for the info module. This means less code, cleaner look and you wont see a white screen of death if you've failed to do a function_exists call and disabled the plugin. **NOTE:** The old way will still work so don't worry.. you don't have to do anything if you don't want to… [More info](http://nacin.com/2010/05/18/rethinking-template-tags-in-plugins/).
* IMPROVEMENT: Some performance improvements and code cleanup to the front end part of the plugin. You probably wont notice any difference but I'll feel good about myself. Late spring cleaning is better than none!
* IMPROVEMENT: More validation and sanitation on the form elements and the functions which handle the filter module. Safety first!

= 1.2.5 =
* IMPROVEMENT: Added filter for the term names in the dropdowns. Add your own indentation indicators or just mess about with the term names. Go bananas!
* IMPROVEMENT: Since security is fashionable we've added Nonce security to the form. Try to hack us now!

= 1.2.4 =
* Tested on WordPress 4.2
* IMPROVEMENT: Added Simplified Chinese translation. Thanks to [Amos Lee](http://www.wpzhiku.com/).
* IMPROVEMENT: Added the ability to sort the taxonomies by filter. No need to re-register them in the "right" order. Thanks to [mranner](https://wordpress.org/support/profile/mranner).
* IMPROVEMENT: Updated the Select2 library (RC2). Fixes usability on devices amongst other things.
* IMPROVEMENT: Added a setting to select2 which only applies the search-field in the dropdown if there's more than 8 results. This can be modified with a new filter which you can read about in the [developer documentation](https://beautiful-taxonomy-filters.github.io/beautiful-taxonomy-filters/hooks/).
* IMPROVEMENT: Added localization for all select2 parameters and created new filters for modifying those.

A new resource for information about how to use BTF and it's filters will soon emerge from the mist…

= 1.2.3 =
* IMPROVEMENT: Added some basic media query styling to the style themes to avoid extremely small dropdowns on those modern electric things people carry around (smartphones).
* FEATURE: German translation added. Thanks to [Matthias Bonnes](http://macbo.de/).
* FIX: Fixed issue with " and ' difference in wp_dropdown_categories walker. I'll try to do some more testing before pushing out new features in the future… Thanks to [Folbert](https://github.com/folbert) for noticing.

= 1.2.2 =
* IMPROVEMENT: Added the terms slug as class to the option element. Allows for custom styling per term option. You can for example use it to colorcode the dropdowns terms.
* IMPROVEMENT: The dropdowns and filter infos now use the registered labels of the taxonomies for "all <taxonomy>" etc. instead of a translatable slug. If you are using polylang or WPML and had translated the "all" string for each language you should instead translate the taxonomy labels.
* IMPROVEMENT: Greatly improved the rewrite rules. They will ONLY be created for the taxonomies of the activated posttypes without any of the built-in taxonomies or polylangs (if they exist). So in short, we've reduced the rewrite rules by quite a bit.
* FIX: Fixed an issue where using the automagic feature would result in a php warning.

**Note: in order for the filtering to work with a rewrite slug for your taxonomies you need to set query_var to the same value as your rewrite slug.**
For example: you have a taxonomy registered with the slug "product_color" but you want the url slug to be "color". Add "color" to both the `query_var` value and `rewrite['slug']` value.


= 1.2.1 =
* IMPROVEMENT: Added multiple new actions for even better control of the filter module and give you the ability to modify the template_redirect filter. Check "other notes" for more.
* IMPROVEMENT: Added a filter to be able to manipulate the new URL a visitor is sent to when filtering
* IMPROVEMENT: Improved the way the filterinfo module determines current taxonomies.
* FIX: Fixed an issue where current taxonomies didn't get displayed properly in the filterinfo module.

= 1.2 =
* FEATURE: the `show_beautiful_filters()` function can now take a parameter of a custom post type name. Doing so enables you to show the filter module anywhere in your theme for a specific post type. Much like the widget except you can place the function pretty much anywhere without having to use a widget. Pretty sweet.
* FEATURE: You're now able to completely disable the select2 library and use good old regular selects instead. Use your own select improving library or whatever… my feelings aren't hurt. Just remember that the regular selects don't support placeholders so it will fall back to the "all option".
* FEATURE: Beautiful Taxonomy Filters is now compatible with Polylang. This is still kind of beta so there might be some bugs to work out over time. I have not been able to try every possible setting so feedback on this is appreciated! See FAQ for more info
* IMPROVEMENT: Updated swedish translations. If you're a well educated multilingual person with a kind heart I'd love translations for other languages as well! Klingon might be a bit excessive tho.
* IMPROVEMENT: Made the menu item name translatable.
* IMPROVEMENT: Minifed JS and CSS for minimal file sizes to load.
* FIX: php warning for the automagic feature in settings page.
* FIX: The result of filter count is now correct and applied to the filterinfo widget as well.
* FIX: Sometimes when on a different post type the filter widget didn't use the proper posttype.
* FIX: The "Clear all" link should now always point to the correct URL.


= 1.1.4.2 =
* FIX: Hotfix #3. Added fix for widgets regarding core taxonomies.

= 1.1.4.1 =
* FIX: Hotfix #2.. Some files got lost in version 1.1.4 and we had to help them find their way back.

= 1.1.4 =
* FIX: This update is a hotfix for an issue where WordPress builtin categories and tags connected to a CPT appear in the filter module. Since they cannot be supported at this time they should not appear at all. This update fixes that. Thanks to BlantantWeb for the notice.

= 1.1.3 =
* FEATURE: The filterinfo module now has the ability to show how many posts a filter has resulted in. There is also new filters for hooking into this.
* FEATURE: New actions have been added to the filterinfo module that allows for custom markup inside the module.
* FEATURE: There is now a filter for modifying the placeholder of each dropdown.
* FEATURE: There is now a filter for modifying the filter buttons text "Apply filter".
* IMPROVEMENT: The plugins scripts will now load in footer instead of head. This also fixes some rare bugs where dependencies with jQuery did not work.
* IMPROVEMENT: Update to swedish translation.

= 1.1.1 =
* FEATURE: You can now automagically insert the two modules into your archive pages! No need for modification of your theme. This feature is sort of experimental and there's a few things to note compared to the manual methods:
  * The modules wont appear if your users select a filtering and there's no posts.
  * You can't control the placement of the filter. You can decide to place the filter info module above or below the filter module but that's it. For more control use one of the manual methods (function calls or widgets).
  * The modules wont output twice. So that means you'll have to remove the manual functions if you're using them. This also means that you can use the automagic way and still manually place the functions on specific posttype archives if you like. Great stuff I know…
* FEATURE: You can now choose to display a placeholder text and a "clear" button on the dropdowns instead of the regular "All <taxonomy>" option. Of course this comes with a filter to let you control this feature per posttype archive. Have placeholders on one archive and an empty option on another.. no problem!
* FIX: The filter module will now work correctly even when you have a different rewrite slug for your CPT.
* FIX: Minor bug fixes resulting in PHP notice logs.
* IMPROVEMENT: Minor code performance improvements.
* IMPROVEMENT: Update to Swedish and French translations. Thanks to [Brice Capobianco](https://profiles.wordpress.org/brikou) for the french translation.
* IMPROVEMENT: Updated select2 to 3.5.2

= 1.1.0 =
* FEATURE: Brand new beautiful widget. You can now add the filter module directly to your sidebar areas via a custom widget.
  * Ability to override the main settings for granular control over each widget.
  * Select a specific posttype and the filter will work from anywhere (redirecting to the proper filtered archive url).
* FEATURE: But wait.. there's more! You get another beautiful widget for displaying the active filter info. Oh and the widget wont even appear where it's not supposed to. So no need to micromanage it's visibility!
* FEATURE: New option to show or hide empty terms in dropdowns
* FEATURE: New option to show post count next to terms in dropdowns
* FEATURE: Dutch translation. Thanks to [Piet Bos](http://senlinonline.com/)
* FEATURE: French translation. Thanks to [Brice Capobianco](https://profiles.wordpress.org/brikou)
* FEATURE: Added filter to set the option to show/hide empty terms
* FEATURE: Added filters to set order and orderby in the dropdown arguments (if you for some reason want to display the terms z-a… for example)
* FEATURE: Added filter to change the "Active filters" heading
* STYLE: Added styling for displaying hierarchical terms in dropdowns (down to 2 levels)
* STYLE: Some minor touch ups on both styles
* FIX: Added taxonomy specific ids to each dropdown wrapper to allow for more in-depth custom styling per dropdown.
* FIX: Added current post type to the filters beautiful_filters_clear_all and beautiful_filters_hide_empty to allow for posttype specific settings.
* FIX: Changed behaviour of the current filter info module to always be visible and show "all <taxonomy>" if no term is active.
* IMPROVEMENT: Abstracted some functionality for cleaner leaner meaner code


= 1.0.2 =
* FIX: Bug found in displaying the filter info
* FIX: Bug found in displaying the filter module

= 1.0.1 =
* FIX: PHP Notice on some occasions using the filter info function
* FEATURE: Spanish translation. Thanks to Juan Javier Moreno Restituto

= 1.0 =
* Initial public version

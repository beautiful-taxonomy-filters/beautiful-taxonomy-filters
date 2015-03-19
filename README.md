=== Beautiful taxonomy filters ===
Contributors: Jonathandejong, tigerton
Donate link: http://fancy.to/k9qxt
Tags: Taxonomy, taxonomies, filter, filtering, pretty permalinks, terms, term, widget, pretty permalinks, rewrite, custom posttype, cpt, beautiful, select2, dropdowns, material design, GET, multisite compatible, polylang compatible, select filter, SEO friendly
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: 1.2.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Supercharge your custom post type archives by letting visitors filter posts by their terms/categories. This plugin handles the whole thing for you!

== Description ==

The Beautiful Taxonomy Filters plugin is an easy and good-looking way to provide your visitors with filtering for your post types. With this you get a complete solution for adding filtering based on taxonomy terms/categories/tags. It will also automatically add rewrite rules for pretty looking filter URLs. It’s completely automatic, works without javascript and is based on the [WordPress Plugin boilerplate](https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate) for a *standardized, organized and object-oriented* codebase. It uses [select2](http://ivaynberg.github.io/select2/) for pretty looking and user-friendly dropdowns but will fall back to ordinary ones if javascript is not supported.
**No more horrible looking URLs or hacky Javascript solutions**

= Features = 
* Activate filtering on any registered public custom post type.
* Exclude taxonomies you just don’t want the visitors to filter on.
* Beautifies the resulting URLs. You won’t see any /posttype/?taxonomy1=term. Instead you’ll see /posttype/taxonomy/term.
* The pretty URLs are much more SEO friendly so you'll give a boost to those filtered pages. Just remember to use canonicals where it's appropriate.
* Polylang compatible (beta).
* Multisite compatible. No network settings at the moment.
* Comes with a complete functional filter module for you to put in your theme. 
* Three alternatives for putting the filter modules in your theme:
  * Widgets (Also lets you "hard set" a post type for use anywhere)
  * Functions (for granular control)
  * Automagic setting which will magically place the modules in your archive from thin air. Wizards at work…
* Choose from different styles for the component, or disable styling and do it yourself in style.css! Just want to tweak a style? Add your custom CSS directly on the settings page.
* Many more settings for fine-tuning the filter modules behaviour:
  * A ”Clear all” link for the filter component.
  * Choose between placeholders or "show all" in the dropdowns.
  * Hide empty terms in the dropdowns.
  * Show a post count next to the term name
  * More to come!
* Ability to show your visitors information about their current active filtering and control the look of this.
* Allows for custom GET parameters to be included. Extend the filter your way with maybe a custom search-parameter or whatever you like. 
* Many [filters and actions](https://wordpress.org/plugins/beautiful-taxonomy-filters/other_notes/) for modifying the plugins behaviour. For you control freaks out there…


= Languages =
* English
* Swedish
* Spanish (Thanks to Juan Javier Moreno Restituto)
* Dutch (Thanks to Piet Bos)
* French (Thanks to [Brice Capobianco](https://profiles.wordpress.org/brikou))
____
Do you want to translate this plugin to another language? I recommend using POEdit (http://poedit.net/) or if you prefer to do it straight from the WordPress admin interface (https://wordpress.org/plugins/loco-translate/). When you’re done, send us the file(s) to jonathan@tigerton.se and we’ll add it to the official plugin!

= Other =
* Based on [WordPress Plugin Boilerplate](https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate)
* Uses [Select2](http://ivaynberg.github.io/select2/) to enhance dropdowns 

= Featured on =
* [WP Tavern](http://www.wptavern.com/beautiful-taxonomy-filters-for-wordpress-custom-post-types)
* [RiverTheme](http://www.rivertheme.com/top-22-free-wordpress-plugins-of-december-2014/)
* [The WhiP (WPMU DEV)](http://premium.wpmudev.org/blog/this-week-in-wordpress-5/)
* [TotalPhotoshop](http://www.total-photoshop.com/)


== Installation ==

1. Upload `beautiful-taxonomy-filters` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Follow the instructions found in Settings > Taxonomy filters to get you started!
4. For more customization have a look at the [filters and actions](https://wordpress.org/plugins/beautiful-taxonomy-filters/other_notes/)


== Frequently Asked Questions ==

= Can I show the filter module on a static page / in my header / in my footer? =

As of version 1.2 the function for showing the filter module `show_beautiful_filters()` takes an optional parameter of a custom post type name. This "hardcodes" the filter module to that post type and lets you use it pretty much anywhere in your theme. Hardcore right.. 
`
<?php echo show_beautiful_filters('myposttype'); ?>
`

= Is there a way to change the order of the taxonomies? =

Using this plugin, no. But the order is the same as the order in which you created the taxonomies. So if you’re for instance using the Custom Post Type UI plugin you can change the order by clicking edit on the taxonomy you want last and saving it without doing any changes. If you’ve created your taxonomies by hand you can just change the order of the register_taxonomy functions.

= Does this support multiple selecting multiple terms from the same taxonomy? =

No. In a future release we will look into if it’s possible to support this AND having beautiful permalinks. If that doesn’t work we will likely add an option where you can opt out of beautiful permalinks and enjoy the power of multiple terms filtering instead. 

= My taxonomy isn’t showing in the filter / the filters are too small =

A Taxonomy will not appear in the filter until at least one post has been connected to one of the terms.
Just start tagging up your posts and you’ll see it shows up! Also, make sure that your custom post type has an archive (in the arguments for the custom post type) since this plugin uses the builtin WordPress functionality for archives.

= Why aren't the builtin post types supported? =
**Posts** are not supported because we haven't been able to create proper rewrite rules for the multiple filtering to work. Posts are handled differently by WordPress than other custom post types (you have probably noticed that there's no /posts/ in the permalink for example). Due to this the same rewrite rules that works for custom post types doesn't work for posts. If you're just looking to filter your posts by their categories with a dropdown you can use this function [wp_dropdown_categories](http://codex.wordpress.org/Function_Reference/wp_dropdown_categories). It's good practice to use a custom post type when you're not going to use it as news/blog -posts so perhaps you should create a Custom post type instead and make use of this beautiful plugin!

= The filter isn't working with my taxonomies using a rewrite slug = 
In order for the rewrite rules to work with a taxonomy that has a rewrite slug you also have to add the same slug to the `query_var` parameter of register_taxonomy. It wont have any visible impact for you but it's what's needed for the filtered urls to work! 

= Is it compatible with Polylang/WPML?
It is currently only compatible with Polylang but support for WPML is coming. We've not been able to test every single setting in polylang so bear with us if there are a few bugs to work out (feedback is much appreciated). In order for this to work properly you should set the post types and all connected taxonomies to be translatable. The filtered urls will still work even if you don't set the posttype to be translatable but when switching language Polylang still think it should add the new language to the URL which means it'll throw a 404 error. This is to be expected and NOT due to this plugin. If you experience 404 errors make sure you flush your rewrite rules by going to settings > permalinks in the admin dashboard. 

Note that it will still work on a site using WPML, it just doesn't have the support for translated taxonomies etc. 

= Is it compatible with XXXXXX? =
You will be able to use this plugin with any **public registered custom post type** regardless if it's been created by yourself or a plugin. **However** the displaying of the CPT must be via it's archive template. That means that a plugin that uses shortcodes to display the entire post listing on a static page will not work out of the box. It will also not work out of the box with plugins that in some way alter the permalink to the CPT archive [WPMU Devs Events+ for example](https://premium.wpmudev.org/project/events-plus/).

= But I'm using Events+ and I really want this! =
You can make Events+ compatible with BTF by adding this snippet to your themes functions.php file. Note that you'll have to change the "events" string if you've set a different one in the Events+ settings.

`
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

add_action( 'template_redirect', 'template_redirect_cb' );
function template_redirect_cb() {
	$url = curPageURL();
	if ( strpos( $url,'eab_events_category' ) !== false ) {
		$u = str_replace( "eab_events_category/", "", $url );
		$u = str_replace( "events/", "blog/events/", $u );
		wp_redirect( $u );
	}
}
`
[See here for more info](http://premium.wpmudev.org/forums/topic/i-would-change-the-sidebar-on-the-events-page-i-created)

= I really love this plugin and I want to donate a little something-something = 
Why thank you! We don't have proper donate link but if you want to you can send us a giftcard on [fancy](https://fancy.com). We will use it to buy cool stuff for the office! 


== Screenshots ==

1. Basic setup settings for the plugin.
2. Filter module settings.
3. Style settings for the modules.
4. The filter info widget.
5. The filter widget.
6. The filter module with the "light material" style.
7. The filter module in work with select2 for user friendly dropdowns.
8. Example of a beautified permalink structure.
9. The widgets on the twentyfourteen theme.


== Changelog ==

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


== API ==

= **Filters** =

These are the filters available to modify the behavior of the plugin. These all take at least 1 parameter which you must return
____

= beautiful_filters_dropdown_categories =

$args is an array of the arguments put into the wp_dropdown_categories function.
$taxonomy is the current taxonomy. 

`
function modify_categories_dropdown( $args, $taxonomy ) {

    return $args;
}
add_filter( 'beautiful_filters_dropdown_categories', 'modify_categories_dropdown’, 10, 2 );
`

= beautiful_filters_post_types =

$post_types is an array. Modifies the selected post types before being used.

`
function modify_post_types( $post_types ) {

    return $post_types;
}
add_filter( 'beautiful_filters_post_types', 'modify_post_types', 10, 1 );
`

= beautiful_filters_taxonomies =

$taxonomies is an array. Modifies the excluded taxonomies before being used.

`
function modify_categories_dropdown( $taxonomies ) {

    return $taxonomies;
}
add_filter( 'beautiful_filters_taxonomies', 'modify_categories_dropdown', 10, 1 );
`

= beautiful_filters_dropdown_placeholder =

$placeholder is the string used for the placeholder. 
$taxonomy is the current taxonomy.
In order to change the placeholders you must use this filter rather than the *modify_categories_dropdown* argument "show_option_all". 

`
function modify_categories_dropdown( $placeholder, $taxonomy ) {

    return 'New placeholder';
}
add_filter( 'beautiful_filters_dropdown_placeholder', 'modify_dropdown_placeholder', 10, 2 );
`

= beautiful_filters_clear_all =

$bool is a boolean which decides if the ”Clear all” link should be used or not. $current_post_type is the current post type being filtered

`
function modify_clear_all( $bool, $current_post_type ) {
	
	//Only add the clear all link to a specific posttype
	if($current_post_type == 'movies'){
		$bool = true;
	}
    return $bool;
}
add_filter( 'beautiful_filters_clear_all', 'modify_clear_all', 10, 2 );
`

= beautiful_filters_hide_empty =

$bool is a boolean which decides if empty terms should be displayed or not. $current_post_type is the current post type being filtered

`
function modify_hide_empty( $bool, $current_post_type ) {

    return $bool;
}
add_filter( 'beautiful_filters_show_empty', 'modify_hide_empty', 10, 2 );
`

= beautiful_filters_show_count =

$bool is a boolean which decides if post count should be displayed or not. $current_post_type is the current post type being filtered

`
function modify_show_count( $bool, $current_post_type ) {

    return $bool;
}
add_filter( 'beautiful_filters_show_empty', 'modify_show_count', 10, 2 );
`

= beautiful_filters_dropdown_order =

$order is a string which defaults to ASC, other possible value is DESC. $taxonomy is the current taxonomy slug

`
function modify_dropdown_order( $order, $taxonomy) {

    return $order;
}
add_filter( 'beautiful_filters_dropdown_order', 'modify_dropdown_order', 10, 2 );
`

= beautiful_filters_dropdown_orderby =

$order is a string which defaults to NAME, other possible value is ID or SLUG. $taxonomy is the current taxonomy slug

`
function modify_dropdown_orderby( $orderby, $taxonomy) {

    return $orderby;
}
add_filter( 'beautiful_filters_dropdown_orderby', 'modify_dropdown_orderby', 10, 2 );
`

= beautiful_filters_dropdown_behaviour =

$behaviour is a string that should be either show_all_option or show_placeholder_option. $current_post_type is the current posttype name.
Use this to modify the dropdown behaviour per posttype or just manually from functions.php

`
function modify_dropdown_behaviour( $behaviour, $current_post_type) {

    return $orderby;
}
add_filter( 'beautiful_filters_dropdown_behaviour', 'modify_dropdown_behaviour', 10, 2 );
`

= beautiful_filters_taxonomy_label =

$label is the name of the taxonomy used as label to the dropdown.

`
function modify_labels($label){
	
	return $label;
}

add_filter('beautiful_filters_taxonomy_label', 'modify_labels', 10, 1);
`

= beautiful_filters_apply_button =

$string is the default string of the apply filters button. 

`
function modify_filter_button($string){
	
	return 'Hej världen';
}

add_filter('beautiful_filters_apply_button', 'modify_filter_button', 10, 1);
`

= beautiful_filters_active_taxonomy =

$label is the taxonomy string for the active filter info
$taxonomy is the current taxonomy name

`
function modify_active_taxonomy($label, $taxonomy){
	
	return $label;
}

add_filter('beautiful_filters_active_taxonomy', 'modify_active_taxonomy', 10, 2);
`

= beautiful_filters_active_terms =

$terms is the terms string for the active filter info
$taxonomy is the current taxonomy name

`
function modify_active_terms($terms, $taxonomy){
	
	return $terms;
}

add_filter('beautiful_filters_active_terms', 'modify_active_terms', 10, 2);
`

= beautiful_filters_disable_heading =

$bool is a boolean of either true (hide filterinfo heading) or false (show filterinfo heading)

`
function toggle_filterinfo_heading($bool){
	
	return true;
	
}
add_filter('beautiful_filters_disable_heading', 'toggle_filterinfo_heading');
`

= beautiful_filters_info_heading =

$filter_heading is the default heading string

`
function modify_filter_heading($filter_heading){
	
	$filter_heading = 'Hej världen';
	return $filter_heading;
	
}
add_filter('beautiful_filters_info_heading', 'modify_filter_heading');
`

= beautiful_filters_disable_postcount =

$bool is a boolean of either true (hide filterinfo postcount) or false (show filterinfo postcount)

`
function toggle_filterinfo_postcount($bool){
	
	return true;
	
}
add_filter('beautiful_filters_disable_postcount', 'toggle_filterinfo_postcount');
`


= beautiful_filters_info_postcount =

$postcount_paragraph is the default postcount string. You MUST add %d somewhere in the new string in order for the resulting number to appear.

`
function modify_filterinfo_postcount($postcount_paragraph){
	
	return 'Hej världen ';
	
}
add_filter('beautiful_filters_info_postcount', 'modify_filterinfo_postcount');
`

= beautiful_filters_new_url =

$posttype is the current posttype. Use this filter to manipulate the URL string of the filtered archive page that the visitor will be directed to.

`
function modify_new_url($url){
	
	return $url . '?filtered=yes';
	
}
add_filter('beautiful_filters_new_url', 'modify_new_url');
`


= **Actions** =
These are the actions you may use to extend the filter component.

= beautiful_actions_before_form =

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.

`
function add_markup_before_form($current_post_type){
		
	echo 'Hej världen';
}

add_action('beautiful_actions_before_form', 'add_markup_before_form' );
`

= beautiful_actions_after_form =

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.

`
function add_markup_after_form($current_post_type){
	
	echo 'Hej världen';
}

add_action('beautiful_actions_after_form', 'add_markup_after_form' );
`

= beautiful_actions_beginning_form =

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.
This action is very usable if you for some reason need to add inputs to be send with the form

`
function add_markup_beginning_form($current_post_type){
	
	echo 'Hej världen';
}

add_action('beautiful_actions_beginning_form', 'add_markup_beginning_form' );
`

= beautiful_actions_ending_form =

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.
This action is very usable if you for some reason need to add inputs to be send with the form.

`
function add_markup_ending_form($current_post_type){
	
	echo 'Hej världen';
}

add_action('beautiful_actions_ending_form', 'add_markup_ending_form' );
`

= beautiful_actions_beginning_form_inner =

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.
This action can be used to add inputs etc to the beginning of the inner div of the filter module.

`
function add_markup_beginning_form_inner($current_post_type){
	
	echo 'Hej världen';
}

add_action('beautiful_actions_beginning_form_inner', 'add_markup_beginning_form_inner' );
`

= beautiful_actions_ending_form_inner =

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.
This action can be used to add inputs etc to the end of the inner div of the filter module.

`
function add_markup_ending_form_inner($current_post_type){
	
	echo 'Hej världen';
}

add_action('beautiful_actions_ending_form_inner', 'add_markup_ending_form_inner' );
`

= beautiful_actions_before_redirection =

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.
This action can be used to add your own stuff or manipulate something before the page is redirected to the new filtered page but after the page has reloaded.

`
function custom_stuff_before_redirection($current_post_type){
	
	echo 'Hej världen';
}

add_action('beautiful_actions_before_redirection', 'custom_stuff_before_redirection' );
`


= beautiful_actions_beginning_filterinfo =

$current_post_type is the post type which the filterinfo component are currently using. Use this variable as a conditional if needed.
This action is very usable if you for some reason need to add markup at the beginning of the filterinfo module

`
function add_markup_beginning_filterinfo($current_post_type){
	
	echo 'Hej världen';
}

add_action('beautiful_actions_beginning_filterinfo', 'add_markup_beginning_filterinfo' );
`

= beautiful_actions_ending_filterinfo =

$current_post_type is the post type which the filterinfo component are currently using. Use this variable as a conditional if needed.
This action is very usable if you for some reason need to add markup at the end of the filterinfo module

`
function add_markup_ending_filterinfo($current_post_type){
	
	echo 'Hej världen';
}

add_action('beautiful_actions_ending_filterinfo', 'add_markup_ending_filterinfo' );
`


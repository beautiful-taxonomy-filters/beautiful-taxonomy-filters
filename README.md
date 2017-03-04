The Beautiful Taxonomy Filters plugin is an easy and good-looking way to provide your visitors with filtering for your post types. With this you get a complete solution for adding filtering based on custom taxonomy terms/categories/tags. It will also automatically add rewrite rules for pretty looking filter URLs. It’s completely automatic, works without javascript and is based on the [WordPress Plugin boilerplate](https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate) for a *standardized, organized and object-oriented* codebase. It uses [select2](http://ivaynberg.github.io/select2/) for pretty looking and user friendly dropdowns but will fall back to ordinary ones if javascript is not supported.
**No more horrible looking URLs or hacky Javascript solutions**

[Features](#features)
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

## Frequently Asked Questions

### Can I show the filter module on a static page / in my header / in my footer?

Yes. Either use the widget and set a specific post type in it's settings or add a parameter of your custom post type slug to the `show_beautiful_filters` action. This "hardcodes" the filter module to that post type and lets you use it pretty much anywhere in your theme. Hardcore right..
```
<?php do_action( 'show_beautiful_filters', 'posttypeslug' ); ?>
```

###Is there a way to change the order of the taxonomies?

Well of course! You can either change the order in which you register your taxonomies OR you can use the filter
```
function moveElement( &$array, $a, $b ) {
    $out = array_splice($array, $a, 1);
    array_splice($array, $b, 0, $out);
}

function custom_tax_ordering( $taxonomies, $current_post_type ) {
	moveElement( $taxonomies, 2, 0 );
	return $taxonomies;
}
add_filter( 'beautiful_filters_taxonomy_order', 'custom_tax_ordering' );
```

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

= Is it compatible with Polylang/WPML? =
It is 100% compatible with Polylang. WPML is a bit wonky but might work depending on your setup. In order for this to work properly you should set the post types and all connected taxonomies to be translatable. The filtered urls will still work even if you don't set the post type to be translatable but when switching language Polylang still think it should add the new language to the URL which means it'll throw a 404 error. This is to be expected and NOT due to this plugin. If you experience 404 errors make sure you flush your rewrite rules by going to settings > permalinks in the admin dashboard.

= Is it compatible with XXXXXX? =
You will be able to use this plugin with any **public registered custom post type** regardless if it's been created by yourself or a plugin. **However** the displaying of the CPT must be via it's archive template. That means that a plugin that uses shortcodes to display the entire post listing on a static page will not work out of the box. It will also not work out of the box with plugins that in some way alter the permalink to the CPT archive [WPMU Devs Events+ for example](https://premium.wpmudev.org/project/events-plus/).

= But I'm using Events+ and I really want this! =
[See here for more info](http://premium.wpmudev.org/forums/topic/i-would-change-the-sidebar-on-the-events-page-i-created)

= I really love this plugin and I want to donate a little something-something =
Why thank you! We don't have proper donate link but if you want to you can send us a giftcard on [fancy](https://fancy.com). We will use it to buy cool stuff for the office! Make it out to jontedejong@gmail.com


# API

##Filters

These are the filters available to modify the behavior of the plugin. These all take at least 1 parameter which you must return

### beautiful_filters_dropdown_categories

$args is an array of the arguments put into the wp_dropdown_categories function.
$taxonomy is the current taxonomy.

```
function modify_categories_dropdown( $args, $taxonomy ) {

    return $args;
}
add_filter( 'beautiful_filters_dropdown_categories', 'modify_categories_dropdown’, 10, 2 );
```

### beautiful_filters_post_types

$post_types is an array. Modifies the selected post types before being used.

```
function modify_post_types( $post_types ) {

    return $post_types;
}
add_filter( 'beautiful_filters_post_types', 'modify_post_types', 10, 1 );
```

### beautiful_filters_taxonomies

$taxonomies is an array. Modifies the excluded taxonomies before being used.

```
function modify_categories_dropdown( $taxonomies ) {

    return $taxonomies;
}
add_filter( 'beautiful_filters_taxonomies', 'modify_categories_dropdown', 10, 1 );
```

### beautiful_filters_taxonomy_order

$taxonomies is an array of the taxonomies slugs. $current_post_type is the post type we're using the filter on. This must return the $taxonomies array.

```
function moveElement(&$array, $a, $b) {
    $out = array_splice($array, $a, 1);
    array_splice($array, $b, 0, $out);
}

function custom_tax_ordering($taxonomies, $current_post_type){
	moveElement($taxonomies, 2, 0);
	return $taxonomies;
}
add_filter('beautiful_filters_taxonomy_order', 'custom_tax_ordering');
```

### beautiful_filters_dropdown_placeholder

$placeholder is the string used for the placeholder.
$taxonomy is the current taxonomy.
In order to change the placeholders you must use this filter rather than the *modify_categories_dropdown* argument "show_option_all".

```
function modify_categories_dropdown( $placeholder, $taxonomy ) {

    return 'New placeholder';
}
add_filter( 'beautiful_filters_dropdown_placeholder', 'modify_dropdown_placeholder', 10, 2 );
```

### beautiful_filters_language
Changes the language code for the current page load.
```
function modify_current_language( $language ) {
	return 'sv';
}
add_filter( 'beautiful_filters_language', 'modify_current_language' );
```

### beautiful_filters_rtl
Changes wether the page is RTL or not.
```
function modify_current_language( $rtl ) {
	return true;
}
add_filter( 'beautiful_filters_rtl', 'modify_rtl' );
```

### beautiful_filters_disable_fuzzy
Disables select2 fuzzy search. particularly useful for terms that are all numbers.

```
function disable_fuzzy_search( $boolean ) {
	return true;

}
add_filter('beautiful_filters_disable_fuzzy', 'disable_fuzzy_search', 10, 1);
```

###beautiful_filters_clear_all

$bool is a boolean which decides if the ”Clear all” link should be used or not. $current_post_type is the current post type being filtered

```
function modify_clear_all( $bool, $current_post_type ) {

	//Only add the clear all link to a specific posttype
	if($current_post_type == 'movies'){
		$bool = true;
	}
    return $bool;
}
add_filter( 'beautiful_filters_clear_all', 'modify_clear_all', 10, 2 );
```

### beautiful_filters_hide_empty

$bool is a boolean which decides if empty terms should be displayed or not. $current_post_type is the current post type being filtered

```
function modify_hide_empty( $bool, $current_post_type ) {

    return $bool;
}
add_filter( 'beautiful_filters_show_empty', 'modify_hide_empty', 10, 2 );
```

### beautiful_filters_show_count

$bool is a boolean which decides if post count should be displayed or not. $current_post_type is the current post type being filtered

```
function modify_show_count( $bool, $current_post_type ) {

    return $bool;
}
add_filter( 'beautiful_filters_show_empty', 'modify_show_count', 10, 2 );
```

### beautiful_filters_show_description

$bool is a boolean which decides if term description should be displayed or not. $current_post_type is the current post type being filtered

```
function modify_show_description( $bool, $current_post_type ) {

    return $bool;
}
add_filter( 'beautiful_filters_show_description', 'modify_show_description', 10, 2 );
```


### beautiful_filters_dropdown_order

$order is a string which defaults to ASC, other possible value is DESC. $taxonomy is the current taxonomy slug

```
function modify_dropdown_order( $order, $taxonomy) {

    return $order;
}
add_filter( 'beautiful_filters_dropdown_order', 'modify_dropdown_order', 10, 2 );
```

### beautiful_filters_dropdown_orderby

$order is a string which defaults to NAME, other possible value is ID or SLUG. $taxonomy is the current taxonomy slug

```
function modify_dropdown_orderby( $orderby, $taxonomy) {

    return $orderby;
}
add_filter( 'beautiful_filters_dropdown_orderby', 'modify_dropdown_orderby', 10, 2 );
```

### beautiful_filters_dropdown_behaviour

$behaviour is a string that should be either show_all_option or show_placeholder_option. $current_post_type is the current posttype name.
Use this to modify the dropdown behaviour per posttype or just manually from functions.php

```
function modify_dropdown_behaviour( $behaviour, $current_post_type) {

    return $orderby;
}
add_filter( 'beautiful_filters_dropdown_behaviour', 'modify_dropdown_behaviour', 10, 2 );
```

### beautiful_filters_dropdown_behaviour

$term_name is a string that have to be returned. $category is the term object. $depth is the level of depth for the current term starting at 0 (no parent).
Use this to alter the output of the term name inside the dropdowns.

```
//Add visual information when a terms are children/grandchildren etc.
add_filter('beautiful_filters_term_name', 'custom_term_name', 10, 3);
function custom_term_name($term_name, $category, $depth){

	//We have indentation
	if($depth !== 0){
		$indent = '';
		//Add one – for each step down the hierarchy, like WP does in admin.
		for($i = 0; $i < $depth; $i++){
			$indent .= '–';
		}
		return $indent . ' ' . $term_name;
	}
	return $term_name;

}
```

### beautiful_filters_taxonomy_label

$label is the name of the taxonomy used as label to the dropdown.

```
function modify_labels($label){

	return $label;
}

add_filter('beautiful_filters_taxonomy_label', 'modify_labels', 10, 1);
```

### beautiful_filters_apply_button

$string is the default string of the apply filters button.

```
function modify_filter_button($string){

	return 'Hej världen';
}

add_filter('beautiful_filters_apply_button', 'modify_filter_button', 10, 1);
```

### beautiful_filters_clear_button

$string is the default string of the apply filters button.

```
function modify_clear_button($string){

	return 'Hej världen';
}
add_filter('beautiful_filters_clear_button', 'modify_clear_button', 10, 1);
```

### beautiful_filters_loader
```
function my_custom_loader( $loader, $taxonomy, $posttype ){

	return $loader; // $loader is an img tag

}
add_filter('beautiful_filters_loader', 'my_custom_loader', 10, 3);
```

### beautiful_filters_active_terms

$terms is the terms string for the active filter info
$taxonomy is the current taxonomy name

```
function modify_active_taxonomy($terms, $taxonomy){

	return $terms;
}

add_filter('beautiful_filters_active_terms', 'modify_active_taxonomy', 10, 2);
```

### beautiful_filters_disable_heading

$bool is a boolean of either true (hide filterinfo heading) or false (show filterinfo heading)

```
function toggle_filterinfo_heading($bool){

	return true;

}
add_filter('beautiful_filters_disable_heading', 'toggle_filterinfo_heading');
```

### beautiful_filters_info_heading

$filter_heading is the default heading string
```
function modify_filter_heading($filter_heading){

	$filter_heading = 'Hej världen';
	return $filter_heading;

}
add_filter('beautiful_filters_info_heading', 'modify_filter_heading');
```

### beautiful_filters_disable_postcount

$bool is a boolean of either true (hide filterinfo postcount) or false (show filterinfo postcount)

```
function toggle_filterinfo_postcount($bool){

	return true;

}
add_filter('beautiful_filters_disable_postcount', 'toggle_filterinfo_postcount');
```


### beautiful_filters_info_postcount

$postcount_paragraph is the default postcount string. You MUST add %d somewhere in the new string in order for the resulting number to appear.

```
function modify_filterinfo_postcount($postcount_paragraph){

	return 'Hej världen ';

}
add_filter('beautiful_filters_info_postcount', 'modify_filterinfo_postcount');
```

### beautiful_filters_new_url

Use this filter to manipulate the URL string of the filtered archive page that the visitor will be directed to.

```
function modify_new_url($url){

	return $url . '?filtered=yes';

}
add_filter('beautiful_filters_new_url', 'modify_new_url');
```

### beautiful_filters_selec2_minsearch

$min_search is an integer. This defines the minimum amount of terms before the search field is shown in the dropdown.

```
function change_minsearch_value($min_search){

	//always show search
	return 1;

}
add_filter('beautiful_filters_selec2_minsearch', 'change_minsearch_value');
```

### beautiful_filters_selec2_allowclear

$bool is a boolean value of either true of false. Setting this to false disables the ability to remove the selection with the x-icon.

```
function change_allowclear_value($bool){

	//Disables the allow clear.
	return false;

}
add_filter('beautiful_filters_selec2_allowclear', 'change_allowclear_value');
```


## Actions
These are the actions you may use to extend the filter component.

### beautiful_actions_before_form

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.

```
function add_markup_before_form($current_post_type){

	echo 'Hej världen';
}

add_action('beautiful_actions_before_form', 'add_markup_before_form' );
```

### beautiful_actions_after_form

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.

```
function add_markup_after_form($current_post_type){

	echo 'Hej världen';
}

add_action('beautiful_actions_after_form', 'add_markup_after_form' );
```

### beautiful_actions_beginning_form

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.
This action is very usable if you for some reason need to add inputs to be send with the form

```
function add_markup_beginning_form($current_post_type){

	echo 'Hej världen';
}

add_action('beautiful_actions_beginning_form', 'add_markup_beginning_form' );
```

### beautiful_actions_ending_form

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.
This action is very usable if you for some reason need to add inputs to be send with the form.

```
function add_markup_ending_form($current_post_type){

	echo 'Hej världen';
}

add_action('beautiful_actions_ending_form', 'add_markup_ending_form' );
```

### beautiful_actions_beginning_form_inner

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.
This action can be used to add inputs etc to the beginning of the inner div of the filter module.

```
function add_markup_beginning_form_inner($current_post_type){

	echo 'Hej världen';
}

add_action('beautiful_actions_beginning_form_inner', 'add_markup_beginning_form_inner' );
```

### beautiful_actions_ending_form_inner

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.
This action can be used to add inputs etc to the end of the inner div of the filter module.

```
function add_markup_ending_form_inner($current_post_type){

	echo 'Hej världen';
}

add_action('beautiful_actions_ending_form_inner', 'add_markup_ending_form_inner' );
```

### beautiful_actions_before_redirection

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.
This action can be used to add your own stuff or manipulate something before the page is redirected to the new filtered page but after the page has reloaded.

```
function custom_stuff_before_redirection($current_post_type){

	echo 'Hej världen';
}

add_action('beautiful_actions_before_redirection', 'custom_stuff_before_redirection' );
```


### beautiful_actions_beginning_filterinfo

$current_post_type is the post type which the filterinfo component are currently using. Use this variable as a conditional if needed.
This action is very usable if you for some reason need to add markup at the beginning of the filterinfo module

```
function add_markup_beginning_filterinfo($current_post_type){

	echo 'Hej världen';
}

add_action('beautiful_actions_beginning_filterinfo', 'add_markup_beginning_filterinfo' );
```

### beautiful_actions_ending_filterinfo

$current_post_type is the post type which the filterinfo component are currently using. Use this variable as a conditional if needed.
This action is very usable if you for some reason need to add markup at the end of the filterinfo module

```
function add_markup_ending_filterinfo($current_post_type){

	echo 'Hej världen';
}

add_action('beautiful_actions_ending_filterinfo', 'add_markup_ending_filterinfo' );
```

=== Beautiful taxonomy filters ===
Contributors: Jonathandejong, tigerton
Donate link: http://example.com/
Tags: Taxonomy, filter, permalinks, terms
Requires at least: 3.0.1
Tested up to: 4.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Supercharge your custom post type archives by letting visitors filter the posts by their terms/categories. This plugin handles the whole thing for you.

== Description ==

The Beautiful Taxonomy Filters plugin is a easy and good-looking way to provide your visitors with filtering for your post types. With this you get a complete solution for adding filtering based on taxonomy terms/categories/tags. It will also automatically add rewrite rules for pretty looking filter URLs. It’s completely automatic, works without javascript and is based on the [WordPress Plugin boilerplate](https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate) for a *standardized, organized and object-oriented* codebase. It uses [select2](http://ivaynberg.github.io/select2/) for pretty looking and user-friendly dropdowns but will fall back to ordinary ones if javascript is not supported.
**No more horrible looking URLs or hacky Javascript solutions**

= Features = 
* Activate filtering on any registered public post type
* Exclude taxonomies you just don’t want the visitors to filter on
* Beautifies the resulting URLs. You won’t see any /posttype/?taxonomy1=term. Instead you’ll see /posttype/taxonomy/term
* Comes with a complete functional filter component for you to put in your theme. 
* Choose from different styles for the component, or disable styling and do it yourself in style.css! Just want to tweak a style? Add your custom CSS directly on the settings page
* Want a ”Clear all” link for the filter component? Just tick a box in the settings page!
* Multiple filters for modifying the plugins behavior. For those controlfreaks out there


= Languages =
* English
* Swedish
____
Do you want to translate this plugin to another language? I recommend using POEdit (http://poedit.net/) or if you prefer to do it straight from the WordPress admin interface (https://wordpress.org/plugins/loco-translate/). When you’re done, send us the file(s) to jonathan@tigerton.se and we’ll add it to the official plugin!

= Other =
* Based on [WordPress Plugin Boilerplate](https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate)
* Uses [Select2](http://ivaynberg.github.io/select2/) to enhance dropdowns 


== Installation ==

1. Upload `beautiful-taxonomy-filters` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Follow the instructions found in Settings > Taxonomy filters to get you started!


== Frequently Asked Questions ==

= Is there a way to change the order of the taxonomies? =

Using this plugin, no. But the order is the same as the order in which you created the taxonomies. So if you’re for instance using the Custom Post Type UI plugin you can change the order by clicking edit on the taxonomy you want last and saving it without doing any changes. If you’ve created your taxonomies by hand you can just change the order of the register_taxonomy functions.

= Does this support multiple selecting multiple terms from the same taxonomy? =

No. In a future release we will look into if it’s possible to support this AND having beautiful permalinks. If that doesn’t work we will likely add an option where you can opt out of beautiful permalinks and enjoy the power of multiple terms filtering instead. 

= My taxonomy isn’t showing in the filter / the filters are too small =

A Taxonomy will not appear in the filter until at least one post has been connected to one of the terms.
Just start tagging up your posts and you’ll see it shows up! Also, make sure that your custom post type has an archive (in the arguments for the custom post type) since this plugin uses the builtin WordPress functionality for archives.


== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot


== Changelog ==

= 1.0 =
* Initial public version


== API ==

= **Filters** =

These are the filters available to modify the behavior of the plugin. These all take at least 1 parameter which you must return
____

= beautiful_filters_dropdown_categories =

$args is an array of the arguments put into the wp_dropdown_categories function.
$taxonomy is the current taxonomy. 

```
function modify_categories_dropdown( $args, $taxonomy ) {

    return $args;
}
add_filter( 'beautiful_filters_dropdown_categories', 'modify_categories_dropdown’, 10, 2 );
```

= beautiful_filters_post_types =

$post_types is an array. Modifies the selected post types before being used.

```
function modify_post_types( $post_types ) {

    return $post_types;
}
add_filter( 'beautiful_filters_post_types', 'modify_post_types', 10, 1 );
```

= beautiful_filters_taxonomies =

$taxonomies is an array. Modifies the excluded taxonomies before being used.

```
function modify_categories_dropdown( $taxonomies ) {

    return $taxonomies;
}
add_filter( 'beautiful_filters_taxonomies', 'modify_categories_dropdown', 10, 1 );
```

= beautiful_filters_clear_all =

$bool is a boolean which decides if the ”Clear all” link should be used or not. 

```
function modify_clear_all( $bool ) {

    return $bool;
}
add_filter( 'beautiful_filters_clear_all', 'modify_clear_all', 10, 1 );
```

= beautiful_filters_taxonomy_label =

$label is the name of the taxonomy used as label to the dropdown.

```
function modify_labels($label){
	
	return $label;
}

add_filter('beautiful_filters_taxonomy_label', 'modify_labels', 10, 1);
```

= beautiful_filters_active_taxonomy =

$label is the taxonomy string for the active filter info
$taxonomy is the current taxonomy name

```
function modify_active_taxonomy($label, $taxonomy){
	
	return $label;
}

add_filter('beautiful_filters_taxonomy_label', 'modify_active_taxonomy', 10, 2);
```

= beautiful_filters_active_terms =

$terms is the terms string for the active filter info
$taxonomy is the current taxonomy name

```
function modify_active_taxonomy($terms, $taxonomy){
	
	return $terms;
}

add_filter('beautiful_filters_active_terms', 'modify_active_terms', 10, 2);
```

= **Actions** =
These are the actions you may use to extend the filter component.

= beautiful_actions_before_form =

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.

```
function add_markup_before_form($current_post_type){
		
	echo 'Hej världen';
}

add_action('beautiful_actions_before_form', 'add_markup_before_form' );
```

= beautiful_actions_after_form =

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.

```
function add_markup_after_form($current_post_type){
	
	echo 'Hej världen';
}

add_action('beautiful_actions_after_form', 'add_markup_after_form' );
```

= beautiful_actions_beginning_form =

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.
This action is very usable if you for some reason need to add inputs to be send with the form

```
function add_markup_beginning_form($current_post_type){
	
	echo 'Hej världen';
}

add_action('beautiful_actions_beginning_form', 'add_markup_beginning_form' );
```

= beautiful_actions_ending_form =

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.
This action is very usable if you for some reason need to add inputs to be send with the form.

```
function add_markup_ending_form($current_post_type){
	
	echo 'Hej världen';
}

add_action('beautiful_actions_ending_form', 'add_markup_ending_form' );
```

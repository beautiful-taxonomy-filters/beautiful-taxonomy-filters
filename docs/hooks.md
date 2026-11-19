---
title: Hooks reference
permalink: /hooks/
---

# Hooks reference

Every filter and action Beautiful Taxonomy Filters offers for changing its behaviour from your theme or plugin.

- [Filters](#filters)
- [Actions](#actions)


## Filters

These are the filters available to modify the behavior of the plugin. These all take at least 1 parameter which you must return
---

### beautiful_filters_dropdown_categories

$args is an array of the arguments put into the wp_dropdown_categories function.
$taxonomy is the current taxonomy.

```php
function modify_categories_dropdown( $args, $taxonomy ) {

    return $args;
}
add_filter( 'beautiful_filters_dropdown_categories', 'modify_categories_dropdown', 10, 2 );
```

### beautiful_filters_post_types

$post_types is an array. Modifies the selected post types before being used.

```php
function modify_post_types( $post_types ) {

    return $post_types;
}
add_filter( 'beautiful_filters_post_types', 'modify_post_types', 10, 1 );
```

### beautiful_filters_taxonomies

$taxonomies is an array. Modifies the excluded taxonomies before being used.

```php
function modify_categories_dropdown( $taxonomies ) {

    return $taxonomies;
}
add_filter( 'beautiful_filters_taxonomies', 'modify_categories_dropdown', 10, 1 );
```

### beautiful_filters_post_type_archive_slug

$post_type_slug is the url segment used for the post type archive, both when building the filtered url and when creating the rewrite rules.
$post_type is the name of the post type.

The counterpart to *beautiful_filters_taxonomy_rewrite_slug*. The value you return is used both when the filtered url is built and when the rewrite rules are created, so it has to be the same in both places or the url won't resolve.
Watch out for one thing in particular: the rewrite rules are generated once and then cached in the rewrite_rules option, while the filtered url is built on every request. If you return a value that depends on request state, the current language being the obvious example, the cached rules were built under whatever state happened to exist when they were last flushed and the two won't match. Use Polylang if you need the url to vary by language, the plugin generates language prefixed rewrite rules for it.

```php
function modify_post_type_archive_slug( $post_type_slug, $post_type ) {

    return $post_type_slug;
}
add_filter( 'beautiful_filters_post_type_archive_slug', 'modify_post_type_archive_slug', 10, 2 );
```

### beautiful_filters_taxonomy_rewrite_slug

$rewrite_slug is the url segment used for a taxonomy, both when building the filtered url and when creating the rewrite rules.
$taxonomy is the name of the taxonomy.
$post_type_slug is the archive slug of the post type being filtered.

The value you return is used both when the filtered url is built and when the rewrite rules are created, so it has to be the same in both places or the url won't resolve. It's here for those of you who want full control over the url structure.

```php
function modify_taxonomy_rewrite_slug( $rewrite_slug, $taxonomy, $post_type_slug ) {

    return $rewrite_slug;
}
add_filter( 'beautiful_filters_taxonomy_rewrite_slug', 'modify_taxonomy_rewrite_slug', 10, 3 );
```

### beautiful_filters_taxonomy_all_items_label

$label is the string used as the "all terms" value, both for the "all" option in the dropdowns, the select2 placeholder and the value shown in the filter info module.
$taxonomy is the name of the taxonomy.

WordPress falls an unset all_items label back to the taxonomy name, so we build "All %s" from the taxonomy name whenever we spot that fallback. An all_items label you've actually registered is passed through untouched. Use this filter if you want a different string, or the plain taxonomy name we used before 2.6.0.

```php
function modify_taxonomy_all_items_label( $label, $taxonomy ) {

    return $label;
}
add_filter( 'beautiful_filters_taxonomy_all_items_label', 'modify_taxonomy_all_items_label', 10, 2 );
```

### beautiful_filters_pre_related_terms

$related_terms is null. Return anything else and the conditional dropdowns database query is skipped entirely and your value is used instead.
$post_type is the name of the post type being filtered.
$selects is an array of the currently selected taxonomies and terms. Each item has the keys taxonomy and term (a comma separated list of term ids). Selects for taxonomies the filter module doesn't show are already removed.
$taxonomies is an array of the taxonomy names of the post type being filtered, limited to the ones the filter module shows.

The endpoint only answers for post types the filter is enabled on, and only while conditional dropdowns are enabled. Terms you return for a taxonomy that isn't in $taxonomies are dropped, and the response is rebuilt from the five properties below, so anything else on your objects isn't sent to the browser. term_name is HTML escaped on the way out.

Use this one to point the conditional dropdowns at something other than the database, an external index for example. What you return has to be shaped exactly like the result of the query it replaces: an array of objects, each one with the properties term_count (the number of matching posts), term_id, term_name, term_slug and taxonomy. Return an empty array for "no matches". Returning null leaves everything as it is.

```php
function replace_related_terms( $related_terms, $post_type, $selects, $taxonomies ) {

    $term = new stdClass();
    $term->term_count = 3;
    $term->term_id    = 12;
    $term->term_name  = 'Spain';
    $term->term_slug  = 'spain';
    $term->taxonomy   = 'locations';

    return array( $term );
}
add_filter( 'beautiful_filters_pre_related_terms', 'replace_related_terms', 10, 4 );
```

### beautiful_filters_related_terms_sql

$sql is the finished SQL query used by the conditional dropdowns.
$post_type is the name of the post type being filtered.
$selects is an array of the currently selected taxonomies and terms, already checked like for *beautiful_filters_pre_related_terms*.
$taxonomies is an array of the taxonomy names of the post type being filtered, limited to the ones the filter module shows.

Since 2.6.1 the term_relationships table is joined once per selected taxonomy under the aliases btf_tr0, btf_tr1 and so on, in the order of $selects. Before that the alias was {taxonomy}_term_relationship, so a callback that edits the query by alias name needs updating.

A word of warning: the query you get has already been through $wpdb->prepare(). Anything you splice into it is entirely your own responsibility, and any % characters in the string you return will not be processed again. If you only need to swap the query out for something else, use *beautiful_filters_pre_related_terms* instead.

```php
function modify_related_terms_sql( $sql, $post_type, $selects, $taxonomies ) {

    return $sql;
}
add_filter( 'beautiful_filters_related_terms_sql', 'modify_related_terms_sql', 10, 4 );
```

### beautiful_filters_taxonomy_order

$taxonomies is an array of the taxonomies slugs. $current_post_type is the post type we're using the filter on. This must return the $taxonomies array.

```php
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

```php
function modify_dropdown_placeholder( $placeholder, $taxonomy ) {
    return 'New placeholder';
}
add_filter( 'beautiful_filters_dropdown_placeholder', 'modify_dropdown_placeholder', 10, 2 );
```

### beautiful_filters_language
Changes the language code for the current page load.
```php
function modify_current_language( $language ) {
	return 'sv';
}
add_filter( 'beautiful_filters_language', 'modify_current_language' );
```

### beautiful_filters_rtl
Changes wether the page is RTL or not.
```php
function modify_current_language( $rtl ) {
	return true;
}
add_filter( 'beautiful_filters_rtl', 'modify_rtl' );
```

### beautiful_filters_disable_fuzzy
Disables select2 fuzzy search. particularly useful for terms that are all numbers.

```php
function disable_fuzzy_search( $boolean ) {
	return true;

}
add_filter('beautiful_filters_disable_fuzzy', 'disable_fuzzy_search', 10, 1);
```

### beautiful_filters_clear_all

$bool is a boolean which decides if the ”Clear all” link should be used or not. $current_post_type is the current post type being filtered

```php
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

```php
function modify_hide_empty( $bool, $current_post_type ) {

    return $bool;
}
add_filter( 'beautiful_filters_show_empty', 'modify_hide_empty', 10, 2 );
```

### beautiful_filters_show_count

$bool is a boolean which decides if post count should be displayed or not. $current_post_type is the current post type being filtered

```php
function modify_show_count( $bool, $current_post_type ) {

    return $bool;
}
add_filter( 'beautiful_filters_show_empty', 'modify_show_count', 10, 2 );
```

### beautiful_filters_show_description

$bool is a boolean which decides if term description should be displayed or not. $current_post_type is the current post type being filtered

```php
function modify_show_description( $bool, $current_post_type ) {

    return $bool;
}
add_filter( 'beautiful_filters_show_description', 'modify_show_description', 10, 2 );
```


### beautiful_filters_dropdown_order

$order is a string which defaults to ASC, other possible value is DESC. $taxonomy is the current taxonomy slug

```php
function modify_dropdown_order( $order, $taxonomy) {

    return $order;
}
add_filter( 'beautiful_filters_dropdown_order', 'modify_dropdown_order', 10, 2 );
```

### beautiful_filters_dropdown_orderby

$order is a string which defaults to NAME, other possible value is ID or SLUG. $taxonomy is the current taxonomy slug

```php
function modify_dropdown_orderby( $orderby, $taxonomy) {

    return $orderby;
}
add_filter( 'beautiful_filters_dropdown_orderby', 'modify_dropdown_orderby', 10, 2 );
```

### beautiful_filters_dropdown_behaviour

$behaviour is a string that should be either show_all_option or show_placeholder_option. $current_post_type is the current posttype name.
Use this to modify the dropdown behaviour per posttype or just manually from functions.php

```php
function modify_dropdown_behaviour( $behaviour, $current_post_type) {

    return $orderby;
}
add_filter( 'beautiful_filters_dropdown_behaviour', 'modify_dropdown_behaviour', 10, 2 );
```

### beautiful_filters_term_name

$term_name is a string that have to be returned. $category is the term object. $depth is the level of depth for the current term starting at 0 (no parent).
Use this to alter the output of the term name inside the dropdowns. $term_name arrives HTML escaped, and what you return is passed through wp_kses_post().

```php
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

```php
function modify_labels($label){

	return $label;
}

add_filter('beautiful_filters_taxonomy_label', 'modify_labels', 10, 1);
```

### beautiful_filters_apply_button

$string is the default string of the apply filters button. The result is passed through wp_kses_post(), so markup allowed in post content, an icon in a span for example, is kept.

```php
function modify_filter_button($string){

	return 'Hej världen';
}

add_filter('beautiful_filters_apply_button', 'modify_filter_button', 10, 1);
```

### beautiful_filters_clear_button

$string is the default string of the apply filters button.

```php
function modify_clear_button($string){

	return 'Hej världen';
}

add_filter('beautiful_filters_clear_button', 'modify_clear_button', 10, 1);
```

### beautiful_filters_loader

The markup shown next to a dropdown while the conditional dropdowns are loading. The result is passed through wp_kses_post().
```php
function my_custom_loader( $loader, $taxonomy, $posttype ){

	return $loader; // $loader is an img tag

}
add_filter('beautiful_filters_loader', 'my_custom_loader', 10, 3);
```

### beautiful_filters_active_terms

$terms is the terms string for the active filter info
$taxonomy is the current taxonomy name

```php
function modify_active_taxonomy($terms, $taxonomy){

	return $terms;
}

add_filter('beautiful_filters_active_terms', 'modify_active_taxonomy', 10, 2);
```

### beautiful_filters_disable_heading

$bool is a boolean of either true (hide filterinfo heading) or false (show filterinfo heading)

```php
function toggle_filterinfo_heading($bool){

	return true;

}
add_filter('beautiful_filters_disable_heading', 'toggle_filterinfo_heading');
```

### beautiful_filters_info_heading

$filter_heading is the default heading string

```php
function modify_filter_heading($filter_heading){

	$filter_heading = 'Hej världen';
	return $filter_heading;

}
add_filter('beautiful_filters_info_heading', 'modify_filter_heading');
```

### beautiful_filters_disable_postcount

$bool is a boolean of either true (hide filterinfo postcount) or false (show filterinfo postcount)

```php
function toggle_filterinfo_postcount($bool){

	return true;

}
add_filter('beautiful_filters_disable_postcount', 'toggle_filterinfo_postcount');
```


### beautiful_filters_info_postcount

$postcount_paragraph is the default postcount string. You MUST add %d somewhere in the new string in order for the resulting number to appear.

```php
function modify_filterinfo_postcount($postcount_paragraph){

	return 'Hej världen ';

}
add_filter('beautiful_filters_info_postcount', 'modify_filterinfo_postcount');
```

### beautiful_filters_new_url

Use this filter to manipulate the URL string of the filtered archive page that the visitor will be directed to.

Since 2.6.0 the redirect goes through `wp_safe_redirect()`, so a URL you return that points at another domain won't be followed and the visitor lands on the unfiltered archive instead. If you need to send people to another domain, add its host to WordPress' own `allowed_redirect_hosts` filter. Polylang registers its language domains there already, so a domain per language setup keeps working without you doing anything.

```php
function modify_new_url($url){

	return $url . '?filtered=yes';

}
add_filter('beautiful_filters_new_url', 'modify_new_url');
```

### beautiful_filters_selec2_minsearch

$min_search is an integer.

```php
function change_minsearch_value($min_search){

	//always show search
	return 1;

}
add_filter('beautiful_filters_selec2_minsearch', 'change_minsearch_value');
```

### beautiful_filters_selec2_allowclear

$bool is a boolean value of either true of false. Setting this to false disables the ability to remove the selection with the x-icon.

```php
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

```php
function add_markup_before_form($current_post_type){

	echo 'Hej världen';
}

add_action('beautiful_actions_before_form', 'add_markup_before_form' );
```

### beautiful_actions_after_form

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.

```php
function add_markup_after_form($current_post_type){

	echo 'Hej världen';
}

add_action('beautiful_actions_after_form', 'add_markup_after_form' );
```

### beautiful_actions_beginning_form

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.
This action is very usable if you for some reason need to add inputs to be send with the form

```php
function add_markup_beginning_form($current_post_type){

	echo 'Hej världen';
}

add_action('beautiful_actions_beginning_form', 'add_markup_beginning_form' );
```

### beautiful_actions_ending_form

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.
This action is very usable if you for some reason need to add inputs to be send with the form.

```php
function add_markup_ending_form($current_post_type){

	echo 'Hej världen';
}

add_action('beautiful_actions_ending_form', 'add_markup_ending_form' );
```

### beautiful_actions_beginning_form_inner

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.
This action can be used to add inputs etc to the beginning of the inner div of the filter module.

```php
function add_markup_beginning_form_inner($current_post_type){

	echo 'Hej världen';
}

add_action('beautiful_actions_beginning_form_inner', 'add_markup_beginning_form_inner' );
```

### beautiful_actions_ending_form_inner

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.
This action can be used to add inputs etc to the end of the inner div of the filter module.

```php
function add_markup_ending_form_inner($current_post_type){

	echo 'Hej världen';
}

add_action('beautiful_actions_ending_form_inner', 'add_markup_ending_form_inner' );
```

### beautiful_actions_before_redirection

$current_post_type is the post type which the filter component are currently using. Use this variable as a conditional if needed.
This action can be used to add your own stuff or manipulate something before the page is redirected to the new filtered page but after the page has reloaded.

```php
function custom_stuff_before_redirection($current_post_type){

	echo 'Hej världen';
}

add_action('beautiful_actions_before_redirection', 'custom_stuff_before_redirection' );
```


### beautiful_actions_beginning_filterinfo

$current_post_type is the post type which the filterinfo component are currently using. Use this variable as a conditional if needed.
This action is very usable if you for some reason need to add markup at the beginning of the filterinfo module

```php
function add_markup_beginning_filterinfo($current_post_type){

	echo 'Hej världen';
}

add_action('beautiful_actions_beginning_filterinfo', 'add_markup_beginning_filterinfo' );
```

### beautiful_actions_ending_filterinfo

$current_post_type is the post type which the filterinfo component are currently using. Use this variable as a conditional if needed.
This action is very usable if you for some reason need to add markup at the end of the filterinfo module

```php
function add_markup_ending_filterinfo($current_post_type){

	echo 'Hej världen';
}

add_action('beautiful_actions_ending_filterinfo', 'add_markup_ending_filterinfo' );
```

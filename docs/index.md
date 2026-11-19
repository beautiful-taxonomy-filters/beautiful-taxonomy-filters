---
title: Developer documentation
---

# Beautiful Taxonomy Filters

Developer documentation for [Beautiful Taxonomy Filters](https://wordpress.org/plugins/beautiful-taxonomy-filters/), the WordPress plugin that adds term filtering to custom post type archives and turns the result into pretty URLs like `/recipes/cuisine/thai/diet/vegan/`.

## Showing the modules

Pick whichever fits your theme:

- **Automagic**: Settings → Taxonomy Filters → Basic options inserts the modules into your post type archives for you.
- **Widgets**: *Beautiful Filter* and *Beautiful Active Filter info*. The filter widget can also be locked to a specific post type so it works anywhere.
- **Shortcodes**: `[show_beautiful_filters]` and `[show_beautiful_filters_info]`. Pass `post_type="your_post_type"` to the first to show it outside the archive.
- **Actions** in your templates:

```php
<?php do_action( 'show_beautiful_filters_info' ); ?>
<?php do_action( 'show_beautiful_filters', 'your_post_type' ); // The post type is optional on its archive. ?>
```

## Scripts and styles

The plugin's scripts, select2 included, are only loaded on pages where a filter module is rendered through one of the methods above, and they load deferred. If you build your own form markup instead, enqueue them yourself:

```php
wp_enqueue_script( 'beautiful-taxonomy-filters' );
```

The stylesheets load on every page. A stylesheet enqueued while the page renders is printed at the bottom of it, which would make the filter flash unstyled.

## Hooks

Almost every setting and every bit of output can be changed with a filter, and there are actions for adding your own markup around the modules.

**[Hooks reference →](hooks/)**

## Help and contributing

- Questions: the [support forum on wordpress.org](https://wordpress.org/support/plugin/beautiful-taxonomy-filters/).
- Bugs and pull requests: [GitHub](https://github.com/beautiful-taxonomy-filters/beautiful-taxonomy-filters).
- These docs live in the [`docs/` folder](https://github.com/beautiful-taxonomy-filters/beautiful-taxonomy-filters/tree/master/docs) of the repository, so a pull request that adds a hook should document it there too.

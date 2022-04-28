# Custom Post Type Menu Highlighter

This plugin allows you to mark selected WordPress menu items as the parents of custom post types or taxonomy archives. It does this by adding the `current-menu-ancestor` class to those menu items when viewing that custom post type/taxonomy archive. Your CSS will need to be configured to style menu items with the `current-menu-ancestor` class appropriately.

## How to use

Install the plugin using Whippet (recommended), or copy it directly into your `wp-content/plugins/` directory for non-Whippet projects.

To mark a menu item as the parent for a custom post type, add `post-type-[post-type-name]` to the "CSS Classes" box for the item in the menu editor.

To mark a menu item as the parent for a taxonomy, add `tax-type-[taxonomy-name]` to the "CSS Classes".

## Development

Install the dependencies:

```
composer install
```

Run the tests:

```
vendor/bin/kahlan spec
```

Run the linters:

```
vendor/bin/psalm
vendor/bin/php-cs-fixer
```

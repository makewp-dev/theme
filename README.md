# MakeWP\Theme

> Make WordPress themes, better.

## Opinionated development

### File structure

```
- blocks/      - blocks can be auto-registered
- functions/   - code-split functions.php
- parts/
- patterns/
- src/
  - scripts/    - JS files; built into ../scripts
  - styles/     - Sass files; built into ../styles
- scripts/      - JS files, built or no
- styles/       - CSS files, built or no
- templates/
- composer.json
- functions.php
- package.json
- style.css     - only [main stylesheet header fields]((https://developer.wordpress.org/themes/core-concepts/main-stylesheet/))
- theme.json
```

You can put your theme's CSS in `/styles` or, if you prefer Sass, put those files in `/src/styles` and they will be built into `/styles`. You can also do both of these, but be aware of file name conflicts. A Sass build step will overwrite existing files.

As with styles, the same pattern is available with `/scripts` and `/src/scripts`.

## Load features

Declare the features you want. Only those files are required and run.

```php
// functions.php
<?php
require_once 'vendor/autoload.php';

\MakeWP\Theme\load( [
  'build_theme'       => true,
  'require_functions' => true,
  'register_blocks'   => true,
  'enqueue_assets'    => true,
  'public_site'       => true,
] );
```

Omit a key (or set it to `false`) to skip that feature.

## Features

### Auto-include all files in `/functions`

**Problem:** `functions.php` becomes long in a mature site.
**Solution:** Code-split into `/functions` and read file system to include all in `functions.php`.

```php
// functions.php
<?php
require_once 'vendor/autoload.php';

\MakeWP\Theme\load( [
  'require_functions' => true,
] );
```

```php
// functions/test.php
<?php
echo 'functions/test.php';
```

You should now see "functions/test.php" in your browser.

### Auto-register blocks in `/blocks`

**Problem:** Must manually register blocks.
**Solution:** Read file system and auto-register blocks.

```php
// functions.php
<?php
require_once 'vendor/autoload.php';

\MakeWP\Theme\load( [
  'register_blocks' => true,
] );
```

```json
// blocks/carousel/block.json
{
  "name": "carousel"
}
```

### Enqueue assets

Enqueues all files in `scripts/` and `styles/` in both public site and admin block editor.

```php
// functions.php
<?php
require_once 'vendor/autoload.php';

\MakeWP\Theme\load( [
  'enqueue_assets' => true,
] );
```

### Various modifications to public site

- Don't convert text emoticons to emojis
- Change excerpt read more link

```php
<?php
require_once 'vendor/autoload.php';

\MakeWP\Theme\load( [
  'public_site' => true,
] );
```

### Build `theme.json` from `js` files (`build_theme`)

Registers the WP-CLI `wp makewp build` command when WP-CLI is present.

```php
<?php
require_once 'vendor/autoload.php';

\MakeWP\Theme\load( [
  'build_theme' => true,
] );
```

## Bugs

- Error when saving posts: “Updating failed. The response is not a valid JSON response.”

## To do

- `BuildCommand.php` - Add filter to change directory from `/config` default?
- `enqueue_style.php` - Enqueue all styles that exist in `/styles` according to template hierarchy naming. For example, `/styles/taxonomy.css` would be enqueued under the same conditions that `/taxonomy.php` would render.
- `enqueue_style.php` - Add filter to change directory from `/styles` default?

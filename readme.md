# MakeWP\Theme

> Make WordPress themes, better.

## Usage

### `functions()`

The goal is to code-split `functions.php`. Typically, `functions.php` contains anything and everything. But we want to be tidy. And we don't need a `functions.php` with lines and lines of `require`s when we can access the file system.

```php
// {your-theme}/functions.php
<?php
require_once 'vendor/autoload.php';
\MakeWP\Theme\functions();
```

```php
// {your-theme}/functions/test.php
<?php
echo 'functions/test.php';
```

You should now see "functions/test.php" in your browser.

#### Ok but wait but we shouldn't be accessing the file system with every page lo-

I mean, but like, cache, right?

### `blocks()`

Same as `functions()`, but `register_block_type()`'s all directories inside.

```php
// {your-theme}/functions.php
<?php
require_once 'vendor/autoload.php';
\MakeWP\Theme\blocks();
```

```json
// {your-theme}/blocks/carousel/block.json
{
  "name": "carousel"
}
```

### `style()`

`style.css` is required for all WordPress themes, but isn't automatically enqueued as a stylesheet. Let's do that for us.

```php
// {your-theme}/functions.php
<?php
require_once 'vendor/autoload.php';
\MakeWP\Theme\style();
```

### `all()`

Want to use all of the above? Don't want to call them all separately? I gotchu.

```php
// {your-theme}/functions.php
<?php
require_once 'vendor/autoload.php';
\MakeWP\Theme\all();
```

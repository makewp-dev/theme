# MakeWP\Theme

> Make WordPress themes, better.

## Features

- Auto-include code-split functions (`require_functions.php`)
- Auto-register blocks (`register_blocks.php`)
- Build `theme.json` from `js` files (`build_theme.php`)

### Upcoming

- `BuildCommand.php` - Add filter to change directory from `/config` default?
- `enqueue_style.php` - Enqueue all styles that exist in `/styles` according to template hierarchy naming. For example, `/styles/taxonomy.css` would be enqueued under the same conditions that `/taxonomy.php` would render.
- `enqueue_style.php` - Add filter to change directory from `/styles` default?

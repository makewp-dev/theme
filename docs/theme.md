
## File structure

```
- blocks/
- functions/
- parts/
- patterns/
- src/
  - scripts/
  - styles/
- scripts/
- styles/
- templates/
- composer.json
- functions.php
- package.json
- style.css
- theme.json
```

`/style.css` contains *only* the "header fields" for a [WordPress theme "main stylesheet"](https://developer.wordpress.org/themes/core-concepts/main-stylesheet/).

You can put your theme's CSS in `/styles` or, if you prefer Sass, put those files in `/src/styles` and they will be built into `/styles`. You can also do both of these, but be aware of file name conflicts. A Sass build step will overwrite existing files.

As with styles, the same pattern is available with `/scripts` and `/src/scripts`.

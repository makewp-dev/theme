<?php
namespace MakeWP\Theme;

/**
 * Assets applied to both public site and admin block editor
 * 
 * @see https://developer.wordpress.org/themes/core-concepts/including-assets/
 * @see https://developer.wordpress.org/block-editor/how-to-guides/enqueueing-assets-in-the-editor/#editor-content-scripts-and-styles
 */
function enqueue_assets()
{
  add_action( 'enqueue_block_assets', function(){
    /**
     * Resolves current issue where, in admin, everything here is run twice:
     * once inside Block Editor iframe, and once outside. We only want inside.
     * I don't know if this will work with non-iframed editor, before WordPress 6.3.
     * 
     * @see https://github.com/WordPress/gutenberg/issues/53590#issuecomment-2754258168
     * @see https://developer.wordpress.org/block-editor/how-to-guides/enqueueing-assets-in-the-editor/#backward-compatibility-and-known-issues
     */
    if ( doing_action( 'admin_enqueue_scripts' ) ) return;

    /**
     * Enqueue assets. Opinionatedly.
     * If debug, cache bust. If not, cache per theme version.
     */
    $assets_version = WP_DEBUG ? time() : wp_get_theme()->get( 'Version' );

    wp_enqueue_style(
      get_template(),
      get_parent_theme_file_uri( 'styles/index.css' ),
      [],
      $assets_version
    );

    wp_enqueue_script(
      get_template(),
      get_parent_theme_file_uri( 'scripts/index.js' ),
      [],
      $assets_version,
      true
    );
  } );
}

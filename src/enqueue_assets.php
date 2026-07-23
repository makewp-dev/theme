<?php
namespace MakeWP\Theme;

/**
 * Enqueue every CSS file in {theme}/styles and every JS file in {theme}/scripts.
 * Applied to both public site and admin block editor.
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
     * If `WP_DEBUG`, cache bust. If not, cache per theme version.
     */
    $assets_version = WP_DEBUG ? time() : wp_get_theme()->get( 'Version' );
    $theme = get_template();

    /**
     * Enqueue all files in `styles/`.
     */
    $styles_dir = get_theme_file_path( '/styles' );
    if ( is_dir( $styles_dir ) )
    {
      foreach ( scandir( $styles_dir ) as $filename )
      {
        $file_path = $styles_dir . '/' . $filename;
        if (
          is_file( $file_path )
          && substr( $file_path, -4 ) === '.css'
        ) {
          wp_enqueue_style(
            $theme . '-' . pathinfo( $filename, PATHINFO_FILENAME ),
            get_theme_file_uri( '/styles/' . $filename ),
            [],
            $assets_version
          );
        }
      }
    }

    /**
     * Enqueue all files in `scripts/`.
     */
    $scripts_dir = get_theme_file_path( '/scripts' );
    if ( is_dir( $scripts_dir ) )
    {
      foreach ( scandir( $scripts_dir ) as $filename )
      {
        $file_path = $scripts_dir . '/' . $filename;
        if ( is_file( $file_path ) && substr( $file_path, -3 ) === '.js' )
        {
          wp_enqueue_script(
            $theme . '-' . pathinfo( $filename, PATHINFO_FILENAME ),
            get_theme_file_uri( '/scripts/' . $filename ),
            [],
            $assets_version,
            true
          );
        }
      }
    }
  } );
}

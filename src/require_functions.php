<?php
namespace MakeWP\Theme;

/**
 * `require_once()` every PHP file in `functions/`
 *
 * @param array $args Reserved for future options.
 */
function require_functions( array $args = [] )
{
  $dir_path = get_theme_file_path( '/functions' );

  // Silently fail if directory doesn't exist
  if ( ! is_dir( $dir_path ) ) return;

  // Or if it does, require each file
  foreach ( scandir( $dir_path ) as $filename )
  {
    $file_path = $dir_path . '/' . $filename;
    if (
      is_file( $file_path )
      && substr( $file_path, -4 ) === '.php'
    ) {
      require_once $file_path;
    }
  }
}

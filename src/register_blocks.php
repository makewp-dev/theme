<?php
namespace MakeWP\Theme;

/**
 * register_block_type() every directory in {theme}/blocks
 * 
 * (For now, don't have a better place for this note..)
 * Get icons for block.json from https://wordpress.github.io/gutenberg/?path=/story/icons-icon--library
 * Change from camelCase to kebab-case.
 * 
 * TO DO:
 * - Allow passing different folder name or folder path altogether.
 */
function register_blocks()
{
  add_action( 'init', function() {
    $dir_path = get_theme_file_path( '/blocks' );
    // Silently fail if directory doesn't exist
    if ( ! is_dir( $dir_path ) ) return;
    // Or if it does..
    foreach( scandir( $dir_path ) as $filename )
    {
      $file_path = $dir_path . '/' . $filename;
      if(
        is_dir( $file_path )
      ){
        register_block_type( $file_path );
      }
    }
  } );
} 
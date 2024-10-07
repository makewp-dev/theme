<?php
namespace MakeWP\Theme;

/**
 * require() every PHP file in {theme}/functions
 */
function require_functions_in_folder()
{
  $dir_path = get_theme_file_path( '/functions' );
  foreach( scandir( $dir_path ) as $filename )
  {
    $file_path = $dir_path . '/' . $filename;
    if(
      is_file( $file_path )
      && substr( $file_path, -4 ) === '.php'
    ){
      require $file_path;
    }
  }
}

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
function register_blocks_in_folder()
{
  add_action( 'init', function() {
    $dir_path = get_theme_file_path( '/blocks' );
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

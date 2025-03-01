<?php
namespace MakeWP\Theme;

function all()
{
  functions();
  blocks();
  style();
}

/**
 * wp_enqueue_style() style.css
 * 
 * Dont't silently fail bc style.css is required for WordPress theme
 */
function style()
{
  add_action( 'wp_enqueue_scripts', function(){
    wp_enqueue_style( get_template(), get_stylesheet_uri() );
  } );
}

/**
 * require_once() every PHP file in {theme}/functions
 */
function functions()
{
  $dir_path = get_theme_file_path( '/functions' );
  // Silently fail if directory doesn't exist
  if ( ! is_dir( $dir_path ) ) return;
  // Or if it does..
  foreach( scandir( $dir_path ) as $filename )
  {
    $file_path = $dir_path . '/' . $filename;
    if(
      is_file( $file_path )
      && substr( $file_path, -4 ) === '.php'
    ){
      require_once $file_path;
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
function blocks()
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

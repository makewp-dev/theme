<?php
namespace MakeWP\Theme;

/**
 * 
 */
function all()
{
  build();
  functions();
  blocks();
  style();
}

/**
 * 
 */
function build()
{
  if (!defined('WP_CLI')) return;

  \WP_CLI::add_command('theme build', function ($args, $assoc_args) {
    $watch = isset($assoc_args['watch']);
    
    function build_theme_json() {
      echo "Generating theme.json...\n";
      
      function soft_require(string $file) {
        if (!file_exists($file)) return [];
        
        $output = shell_exec("node -e \"import config from '$file'; console.log(JSON.stringify(config));\"");
        return json_decode($output, true) ?? [];
      }
      
      $basePath = get_template_directory() . '/config/';
      $settings = soft_require($basePath . 'theme.settings.js');
      $styles = soft_require($basePath . 'theme.styles.js');
      $templateParts = soft_require($basePath . 'theme.templateParts.js');
      $customTemplates = soft_require($basePath . 'theme.customTemplates.js');
      
      $theme = [
        '$schema' => 'https://schemas.wp.org/trunk/theme.json',
        'version' => 3,
        'settings' => $settings,
        'styles' => $styles,
        'templateParts' => $templateParts,
        'customTemplates' => $customTemplates,
      ];
      
      $themeJson = json_encode($theme, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
      
      if (file_put_contents(get_template_directory() . '/theme.json', $themeJson) === false) {
        \WP_CLI::error("Error writing theme.json");
        return false;
      } else {
        \WP_CLI::success("theme.json generated successfully.");
        return true;
      }
    }
    
    // Initial build
    build_theme_json();
    
    if ($watch) {
      echo "\nWatching for changes in config files...\n";
      echo "Press Ctrl+C to stop watching.\n\n";
      
      $configPath = get_template_directory() . '/config/';
      $watchedFiles = [
        $configPath . 'theme.settings.js',
        $configPath . 'theme.styles.js', 
        $configPath . 'theme.templateParts.js',
        $configPath . 'theme.customTemplates.js'
      ];
      
      $lastModified = [];
      
      // Initialize last modified times
      foreach ($watchedFiles as $file) {
        $lastModified[$file] = file_exists($file) ? filemtime($file) : 0;
      }
      
      while (true) {
        $changed = false;
        
        foreach ($watchedFiles as $file) {
          if (file_exists($file)) {
            $currentModified = filemtime($file);
            if ($currentModified > $lastModified[$file]) {
              $changed = true;
              $lastModified[$file] = $currentModified;
              echo "Change detected in " . basename($file) . "\n";
            }
          }
        }
        
        if ($changed) {
          build_theme_json();
        }
        
        // Sleep for 1 second before checking again
        sleep(1);
      }
    }
  });
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

/**
 * wp_enqueue_style() style.css
 * WordPress doesn't do this by default.
 * 
 * Doesn't silently fail bc style.css is a required file for every WordPress theme
 */
function style()
{
  add_action( 'wp_enqueue_scripts', function(){
    wp_enqueue_style( get_template(), get_stylesheet_uri() );
  } );
}

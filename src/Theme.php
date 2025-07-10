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
 * Register WP-CLI commands for theme building
 */
function build()
{
  if (!defined('WP_CLI')) return;

  add_action('cli_init', function() {
    \WP_CLI::add_command('makewp build', 'MakeWP\Theme\BuildCommand');
  });
}

/**
 * WP-CLI command class for building theme.json
 */
class BuildCommand
{
  /**
   * Build theme.json from config files
   *
   * ## OPTIONS
   *
   * [--watch]
   * : Watch for changes in config files and rebuild automatically
   *
   * ## EXAMPLES
   *
   *     wp makewp build
   *     wp makewp build --watch
   *
   * @param array $args
   * @param array $assoc_args
   */
  public function __invoke($args, $assoc_args)
  {
    $watch = isset($assoc_args['watch']);
    
    if ($watch) {
      $this->watch_and_build();
    } else {
      $this->build_theme_json();
    }
  }
  
  /**
   * Build theme.json from config files
   */
  private function build_theme_json()
  {
    \WP_CLI::log('Generating theme.json...');
    
    try {
      $basePath = get_template_directory() . '/config/';
      $settings = $this->soft_require($basePath . 'theme.settings.js');
      $styles = $this->soft_require($basePath . 'theme.styles.js');
      $templateParts = $this->soft_require($basePath . 'theme.templateParts.js');
      $customTemplates = $this->soft_require($basePath . 'theme.customTemplates.js');
      
      $theme = [
        '$schema' => 'https://schemas.wp.org/trunk/theme.json',
        'version' => 3,
        'settings' => $settings,
        'styles' => $styles,
        'templateParts' => $templateParts,
        'customTemplates' => $customTemplates,
      ];
      
      $themeJson = json_encode($theme, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
      
      if ($themeJson === false) {
        \WP_CLI::error('Failed to encode theme.json');
        return false;
      }
      
      $themeJsonPath = get_template_directory() . '/theme.json';
      
      if (file_put_contents($themeJsonPath, $themeJson) === false) {
        \WP_CLI::error('Failed to write theme.json');
        return false;
      }
      
      \WP_CLI::success('theme.json generated successfully.');
      return true;
      
    } catch (\Exception $e) {
      \WP_CLI::error('Error building theme.json: ' . $e->getMessage());
      return false;
    }
  }
  
  /**
   * Safely require a JavaScript config file and return its contents
   */
  private function soft_require(string $file): array
  {
    if (!file_exists($file)) {
      return [];
    }
    
    try {
      $output = shell_exec("node -e \"import config from '$file'; console.log(JSON.stringify(config));\"");
      
      if ($output === null) {
        \WP_CLI::warning("Failed to execute Node.js for file: " . basename($file));
        return [];
      }
      
      $decoded = json_decode($output, true);
      
      if (json_last_error() !== JSON_ERROR_NONE) {
        \WP_CLI::warning("Failed to parse JSON from " . basename($file) . ": " . json_last_error_msg());
        return [];
      }
      
      return $decoded ?? [];
      
    } catch (\Exception $e) {
      \WP_CLI::warning("Error processing " . basename($file) . ": " . $e->getMessage());
      return [];
    }
  }
  
  /**
   * Watch for changes in config files and rebuild automatically
   */
  private function watch_and_build()
  {
    \WP_CLI::log('Watching for changes in config files...');
    \WP_CLI::log('Press Ctrl+C to stop watching.');
    \WP_CLI::log('');
    
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
    
    // Initial build
    $this->build_theme_json();
    
    while (true) {
      $changed = false;
      
      foreach ($watchedFiles as $file) {
        if (file_exists($file)) {
          $currentModified = filemtime($file);
          if ($currentModified > $lastModified[$file]) {
            $changed = true;
            $lastModified[$file] = $currentModified;
            \WP_CLI::log('Change detected in ' . basename($file));
          }
        }
      }
      
      if ($changed) {
        $this->build_theme_json();
      }
      
      // Sleep for 1 second before checking again
      sleep(1);
    }
  }
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

<?php
namespace MakeWP\Theme;

/**
 * Register WP-CLI commands for theme building.
 *
 * @param array $args Reserved for future options.
 */
function build_theme( array $args = [] )
{
  if ( ! defined( 'WP_CLI' ) ) return;

  add_action( 'cli_init', function () {
    \WP_CLI::add_command( 'makewp build', 'MakeWP\Theme\BuildCommand' );
  } );
}

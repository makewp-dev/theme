<?php
namespace MakeWP\Theme;

/**
 * Register WP-CLI commands for theme building
 */
function build_theme()
{
  if (!defined('WP_CLI')) return;

  add_action('cli_init', function() {
    \WP_CLI::add_command('makewp build', 'MakeWP\Theme\BuildCommand');
  });
}

<?php
namespace MakeWP\Theme;

require_once __DIR__ . '/build_theme.php';
require_once __DIR__ . '/require_functions.php';
require_once __DIR__ . '/register_blocks.php';
require_once __DIR__ . '/enqueue_assets.php';
require_once __DIR__ . '/public_site.php';

function all()
{
  build_theme();
  require_functions();
  register_blocks();
  enqueue_assets();
  public_site();
}

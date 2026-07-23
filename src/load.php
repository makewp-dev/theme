<?php
namespace MakeWP\Theme;

/**
 * Declare and activate features from filesystem.
 *
 * Pass `true` for defaults, an args array for options, or `false`/empty to skip.
 *
 * @param array<string, bool|array> $features Feature name => true|false|args.
 * @throws \InvalidArgumentException Unknown feature name, or invalid value.
 */
function load( array $features )
{
  foreach ( $features as $name => $config )
  {
    /**
     * If falsey, skip
     */
    if ( empty( $config ) ) continue;

    /**
     * Must be true or an args array
     */
    if (
      $config !== true
      && ! is_array( $config )
    ) {
      throw new \InvalidArgumentException(
        "Feature `$name` must be true, false, or an args array."
      );
    }

    /**
     * If have file, require file
     */
    $file = __DIR__ . '/' . $name . '.php';
    $function = __NAMESPACE__ . '\\' . $name;
    if ( $name === 'load' || ! is_file( $file ) )
    {
      throw new \InvalidArgumentException(
        "Unknown MakeWP\\Theme feature, `$name`."
      );
    }
    require_once $file;

    /**
     * If have function, call function
     */
    if ( ! function_exists( $function ) )
    {
      throw new \InvalidArgumentException(
        "MakeWP\\Theme feature `$name` is malformed."
      );
    }
    $function( $config === true ? [] : $config );
  }
}

<?php
namespace MakeWP\Theme;

/**
 * Public site functionality.
 *
 * @param array $args Reserved for future options.
 */
function public_site( array $args = [] )
{
  /**
   * Don't convert text emoticons to emojis
   *
   * @see https://developer.wordpress.org/reference/functions/smilies_init/
   */
  add_filter( 'smilies', fn() => [] );

  /**
   * Change excerpt read more link
   *
   * @see https://developer.wordpress.org/reference/hooks/excerpt_more/
   */
  add_filter( 'excerpt_more', function()
  {
    return sprintf(
      '&hellip; <a href="%s">%s</a>',
      get_permalink(),
      __( 'Keep Reading', wp_get_theme()->get( 'TextDomain' ) )
    );
  } );
}

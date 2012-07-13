<?php
/*
Plugin Name: WP Post Columns
Plugin URI: http://www.samburdge.co.uk/plugins/wp-post-columns-plugin-2
Description: Allows you to easily create columns within your posts for a magazine / newspaper style layout. Idea for extra columns credited to RickHap http://www.flyhypersonic.com/wordpress
Version: 2.2
Author: Sam Burdge
Author URI: http://www.samburdge.co.uk
*/
/*

USAGE
follow this formatting in a wordpress post or page:

[column width="40%" padding="5%"]
column 1 content
[/column]
[column width="25%" padding="5%"]
column 2 content
[/column]
[column width="25%" padding="0"]
column 3 content
[/column][end_columns]

you can add as many columns as you like, but the accumulated width must not exceed the overall width available.

dont forget to use the [end_columns] shortcode after all your column formatting is complete!

*/
/* 

UPDATES

24/4/2008
added extra preg_replace code to remove implicit <p> tags inserted by wordpress

27/4/2008
added priority of 13 to add_filter to make compatible with 2.5.1 - preg_replace is called after shortcodes have been processed (priority 11)

19/1/2009
added shortcodes support so that the column widths can be configured within each post.
added the potential to allow as many columns as required, so long as the overall width does not exceed the width of the available space.

9/2/2009
added do_shortcode() function to allow nesting of other shortcodes within the columns

20/4/2009
removed original plugin functions (they were causing bugs with some themes), to be replaced with new shortcode method

*/

function column_shortcode( $atts, $content = null ) {
   extract( shortcode_atts( array(
      'width' => '45%',
      'padding' => '0',
      'num' => '1',
      // ...etc
      ), $atts ) );

   return '<div style="width:'.$width.'; float: left; padding-right: '.$padding.'; display: inline;" class="post_column_'.$num.'"><p>' . do_shortcode($content) . '</div>';
}

function end_column_shortcode(){
return '<div style="clear: both;"></div>';
}

add_shortcode('column', 'column_shortcode');
add_shortcode('end_columns', 'end_column_shortcode');


?>
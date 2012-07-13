<?php
/**
 * The Search Form
 *
 * Optional file that allows displaying a custom search form
 * when the get_search_form() template tag is used.
 *
 * @package WordPress
 * @subpackage Twenty Ten
 * @since 3.0.0
 */
?>

    <form id="searchform" name="searchform" method="get" action="<?php echo home_url(); ?>">
		<div>
			<label for="s" style="float:left; margin-right:5px; padding:5px 5px; color:#575757;">search</label>
			<?php 
			if(isset($_GET['s']))
			{
				echo '<input type="text" id="s" name="s" value="'.$_GET["s"].'"/>';
			}
			else
			{
				echo '<input type="text" id="s" name="s" value="who/what/where?"/>';
			}
			?>
			<input type="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search'); ?>" />
		</div>
    </form>
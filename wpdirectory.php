<?php
/*
Plugin Name: wpDirectory
Plugin URI: http://arielbustillos.com/wpdirectory
Description: A clean structured list of categories, which can be easily customized with CSS. Suitable for articles, links, bussines directory, collections and anything in your mind that needs to be organized.
Version: 1.0
Author: obus3000
Author URI: http://arielbustillos.com/
*/

function wpdirectory_version() {
return '1.0';
}

function wpdirectory_textdomain() {
	if (!function_exists('wp_print_styles')) {
		load_plugin_textdomain('wpDirectory', 'wp-content/plugins/wpdirectory');
	} else {
		load_plugin_textdomain('wpDirectory', false, 'wpdirectory');
	}
}
add_action('init', 'wpdirectory_textdomain');

function wpdirectory_plugin_description($string) {
	if (trim($string) == 'A clean structured list of categories, which can be easily customized with CSS. Suitable for articles, links, bussines directory and anything in your mind that needs to be organized.')
	$string = __('A clean structured list of categories, which can be easily customized with CSS. Suitable for articles, links, bussines directory and anything in your mind that needs to be organized. You can see a demo at <a href="http://arielbustillos.com/">arielbustillos.com</a>.', 'wpDirectory');
	return $string;
}
add_filter('pre_kses', 'wpdirectory_plugin_description');

function wpdirectory_options() {

	$options = $newoptions = get_option('wpdirectory');

	//if update
	if (isset($_POST['wpdirectory_update']))	{

		$newoptions['exclude_cats']          =       stripslashes($_POST['exclude_cats']);
		$newoptions['show_parent_count']     = (int) stripslashes($_POST['show_parent_count']);
		$newoptions['show_child_count']      = (int) stripslashes($_POST['show_child_count']);
		$newoptions['hide_empty']            = (int) stripslashes($_POST['hide_empty']);
		$newoptions['desc_for_parent_title'] = (int) stripslashes($_POST['desc_for_parent_title']);
		$newoptions['desc_for_child_title']  = (int) stripslashes($_POST['desc_for_child_title']);
		$newoptions['child_hierarchical']    = (int) stripslashes($_POST['child_hierarchical']);
		$newoptions['column_count']          =       stripslashes($_POST['column_count']);
		$newoptions['sort_by']               = (int) stripslashes($_POST['sort_by']);
		$newoptions['sort_direction']        = (int) stripslashes($_POST['sort_direction']);
		$newoptions['no_child_alert']        = (int) stripslashes($_POST['no_child_alert']);
		$newoptions['show_child']            = (int) stripslashes($_POST['show_child']);
		$newoptions['maximum_child']         =       stripslashes($_POST['maximum_child']);
		$newoptions['forgot_the_cat']        = (int) stripslashes($_POST['forgot_the_cat']);
		$newoptions['sel_only_one_cat']      = (int) stripslashes($_POST['sel_only_one_cat']);
		$newoptions['sel_only_child_cat']    = (int) stripslashes($_POST['sel_only_child_cat']);
		$newoptions['kinderloss']            = (int) stripslashes($_POST['kinderloss']);
		$newoptions['hide_blocks']           = (int) stripslashes($_POST['hide_blocks']);
		$newoptions['using_wp_version']      = (int) stripslashes($_POST['using_wp_version']);
		$newoptions['cat_block_height']      =       stripslashes($_POST['cat_block_height']);
		$newoptions['hide_tags_field']       = (int) stripslashes($_POST['hide_tags_field']);
		$newoptions['publish_terms']         = (int) stripslashes($_POST['publish_terms']);
		$newoptions['publish_terms_text']    =       stripslashes($_POST['publish_terms_text']);
		$newoptions['show_wpdirectory_item_code']     = (int) stripslashes($_POST['show_wpdirectory_item_code']);

    $error=false;

		//check errors
		if(!preg_match("/^(\d+,)*\d+$/", $newoptions['exclude_cats'])){
		  $error .='<li>' . __('IDs of categories is incorrect.', 'wpDirectory') . '</li>';
	  	$error1 = ' style="border: 1px solid #F00; background: #FFF5F4;"';
	  }
	  
		if (!is_numeric($newoptions['column_count'])) {
		  $error .='<li>' . __('You can only specify a digit in the "The number of columns for categories list".', 'wpDirectory') . '</li>';
	  	$error2 = ' style="border: 1px solid #F00; background: #FFF5F4;"';
	  }
		elseif ($newoptions['column_count'] == 0) {
		  $error .='<li>' . __('The number of columns should not be zero.', 'wpDirectory') . '</li>';
	  	$error2 = ' style="border: 1px solid #F00; background: #FFF5F4;"';
	  }
	  
		if (!is_numeric($newoptions['maximum_child'])) {
		  $error .='<li>' . __('You can only specify a digit in the "The number of child categories to show".', 'wpDirectory') . '</li>';
	  	$error3 = ' style="border: 1px solid #F00; background: #FFF5F4;"';
	  }
	  
		if (!is_numeric($newoptions['cat_block_height'])) {
		  $error .='<li>' . __('You can only specify a digit in the "The height of the categories block".', 'wpDirectory') . '</li>';
	  	$error4 = ' style="border: 1px solid #F00; background: #FFF5F4;"';
	  }
		elseif ($newoptions['cat_block_height'] < 100) {
		  $error .='<li>' . __('The height of the categories block can\'t be less than 100 pixels.', 'wpDirectory') . '</li>';
	  	$error4 = ' style="border: 1px solid #F00; background: #FFF5F4;"';
	  }
	  
		//if something went wrong we write an error message
	  if ($error) {
?>
    	<div class="error">
      	<p><strong><?php _e('Some Errors Occurred:', 'wpDirectory'); ?></strong></p>
      	<ul><?php echo $error;?></ul>
      </div>
<?php
	  } else {

			// если опции разные, то обновляем их
			if ( $options != $newoptions ) {
				$options = $newoptions;
				update_option('wpdirectory', $options);
?>
				<div class="updated"><p><?php _e('Options saved!', 'wpDirectory'); ?></p></div>
<?php
			}
		}
	}
	//if reset
	elseif (isset($_POST['wpdirectory_reset'])) 	{

		// просто добавляем дефолт
		$options = array(
			'exclude_cats' => 0,
			'show_parent_count' => 1,
			'show_child_count' => 1,
			'hide_empty' => 0,
			'desc_for_parent_title' => 1,
			'desc_for_child_title' => 1,
			'child_hierarchical' => 1,
			'column_count' => 3,
			'sort_by' => 0,
			'sort_direction' => 0,
			'no_child_alert' => 1,
			'show_child' => 1,
			'maximum_child' => 0,
			'forgot_the_cat' => 1,
			'sel_only_one_cat' => 1,
			'sel_only_child_cat' => 0,
			'kinderloss' => 1,
			'hide_blocks' => 1,
			'using_wp_version' => 1,
			'cat_block_height' => 200,
			'hide_tags_field' => 0,
			'publish_terms' => 0,
			'publish_terms_text' => '',
			'show_wpdirectory_item_code' => 0,
		);

		update_option('wpdirectory', $options);
?>

		<div class="updated"><p><?php _e('Default values restored.', 'wpDirectory'); ?></p></div>

<?php
	}

	// attribute_escape - удаляет все плохое при выводе в форме
	$exclude_cats          = attribute_escape($newoptions['exclude_cats']);
	$show_parent_count     = attribute_escape($newoptions['show_parent_count']);
	$show_child_count      = attribute_escape($newoptions['show_child_count']);
	$hide_empty            = attribute_escape($newoptions['hide_empty']);
	$desc_for_parent_title = attribute_escape($newoptions['desc_for_parent_title']);
	$desc_for_child_title  = attribute_escape($newoptions['desc_for_child_title']);
	$child_hierarchical    = attribute_escape($newoptions['child_hierarchical']);
	$column_count          = attribute_escape($newoptions['column_count']);
	$sort_by               = attribute_escape($newoptions['sort_by']);
	$sort_direction        = attribute_escape($newoptions['sort_direction']);
	$no_child_alert        = attribute_escape($newoptions['no_child_alert']);
	$show_child            = attribute_escape($newoptions['show_child']);
	$maximum_child         = attribute_escape($newoptions['maximum_child']);
	$forgot_the_cat        = attribute_escape($newoptions['forgot_the_cat']);
	$sel_only_one_cat      = attribute_escape($newoptions['sel_only_one_cat']);
	$sel_only_child_cat    = attribute_escape($newoptions['sel_only_child_cat']);
	$kinderloss            = attribute_escape($newoptions['kinderloss']);
	$hide_blocks           = attribute_escape($newoptions['hide_blocks']);
	$using_wp_version      = attribute_escape($newoptions['using_wp_version']);
	$cat_block_height      = attribute_escape($newoptions['cat_block_height']);
	$hide_tags_field       = attribute_escape($newoptions['hide_tags_field']);
	$publish_terms         = attribute_escape($newoptions['publish_terms']);
	$publish_terms_text    = attribute_escape($newoptions['publish_terms_text']);
	$show_wpdirectory_item_code     = attribute_escape($newoptions['show_wpdirectory_item_code']);

if (!isset($_POST['wpdirectory_update']))	{
	// проверяем каждую опцию
	// если опция есть, то присваиваем её переменной, если нет (:), то дефолт
	$exclude_cats          = isset($options['exclude_cats'])          ?       $options['exclude_cats']          : 0;
	$show_parent_count     = isset($options['show_parent_count'])     ? (int) $options['show_parent_count']     : 1;
	$show_child_count      = isset($options['show_child_count'])      ? (int) $options['show_child_count']      : 1;
	$hide_empty            = isset($options['hide_empty'])            ? (int) $options['hide_empty']            : 0;
	$desc_for_parent_title = isset($options['desc_for_parent_title']) ? (int) $options['desc_for_parent_title'] : 1;
	$desc_for_child_title  = isset($options['desc_for_child_title'])  ? (int) $options['desc_for_child_title']  : 1;
	$child_hierarchical    = isset($options['child_hierarchical'])    ? (int) $options['child_hierarchical']    : 1;
	$column_count          = isset($options['column_count'])          ?       $options['column_count']          : 3;
	$sort_by               = isset($options['sort_by'])               ? (int) $options['sort_by']               : 0;
	$sort_direction        = isset($options['sort_direction'])        ? (int) $options['sort_direction']        : 0;
	$no_child_alert        = isset($options['no_child_alert'])        ? (int) $options['no_child_alert']        : 1;
	$show_child            = isset($options['show_child'])            ? (int) $options['show_child']            : 1;
	$maximum_child         = isset($options['maximum_child'])         ?       $options['maximum_child']         : 0;
	$forgot_the_cat        = isset($options['forgot_the_cat'])        ? (int) $options['forgot_the_cat']        : 1;
	$sel_only_one_cat      = isset($options['sel_only_one_cat'])      ? (int) $options['sel_only_one_cat']      : 1;
	$sel_only_child_cat    = isset($options['sel_only_child_cat'])    ? (int) $options['sel_only_child_cat']    : 0;
	$kinderloss            = isset($options['kinderloss'])            ? (int) $options['kinderloss']            : 1;
	$hide_blocks           = isset($options['hide_blocks'])           ? (int) $options['hide_blocks']           : 1;
	$using_wp_version      = isset($options['using_wp_version'])      ? (int) $options['using_wp_version']      : 1;
	$cat_block_height      = isset($options['cat_block_height'])      ?       $options['cat_block_height']      : 200;
	$hide_tags_field       = isset($options['hide_tags_field'])       ? (int) $options['hide_tags_field']       : 0;
	$publish_terms         = isset($options['publish_terms'])         ? (int) $options['publish_terms']         : 0;
	$publish_terms_text    = isset($options['publish_terms_text'])    ?       $options['publish_terms_text']    : '';
	$show_wpdirectory_item_code     = isset($options['show_wpdirectory_item_code'])     ? (int) $options['show_wpdirectory_item_code']     : 0;
}

?>

<div class="wrap">

	<h2><?php _e('wpDirectory Options', 'wpDirectory'); ?></h2>

	<form method="post">

		<div id="poststuff" class="ui-sortable">

			<div class="postbox">

		    <h3><?php _e('Categories List Options', 'wpDirectory'); ?></h3>

				<div class="inside">

					<table class="form-table">

					 	<tr valign="top">
							<td scope="row" colspan="3"><strong style="color: #F60"><?php _e('This options afect the display (on the site) of the list of categories.', 'wpDirectory'); ?></strong></td>
			      </tr>

					 	<tr valign="top">
							<td scope="row" style="width: 360px"><label for="exclude_cats"><?php _e('Comma separated IDs of categories, which should be excluded:', 'wpDirectory'); ?></label></td>
							<td width="130">
								<input<?php echo $error1; ?> name="exclude_cats" type="text" id="exclude_cats" value="<?php echo $exclude_cats; ?>" size="15" />
							</td>
							<td><?php _e('For example , "<tt>1,3,7</tt>" (without quotes). 0 - all categories will be displayed.', 'wpDirectory'); ?></td>
			      </tr>

					 	<tr valign="top">
							<td scope="row"><label for="show_parent_count"><?php _e('Show the number of items in parent categories?', 'wpDirectory'); ?></label></td>
							<td>
								<select name="show_parent_count" size="1">
									<option value="1"<?php selected('1', $show_parent_count); ?>><?php _e('Yes', 'wpDirectory'); ?></option>
									<option value="0"<?php selected('0', $show_parent_count); ?>><?php _e('No', 'wpDirectory'); ?></option>
								</select>
							</td>
							<td></td>
			      </tr>

						<tr valign="top">
							<td scope="row"><label for="show_child_count"><?php _e('Show the number of items in child categories?', 'wpDirectory'); ?></label></td>
							<td>
								<select name="show_child_count" size="1">
									<option value="1"<?php selected('1', $show_child_count); ?>><?php _e('Yes', 'wpDirectory'); ?></option>
									<option value="0"<?php selected('0', $show_child_count); ?>><?php _e('No', 'wpDirectory'); ?></option>
								</select>
							</td>
							<td></td>
			      </tr>

						<tr valign="top">
							<td scope="row"><label for="hide_empty"><?php _e('Hide empty categories?', 'wpDirectory'); ?></label></td>
							<td>
								<select name="hide_empty" size="1">
									<option value="1"<?php selected('1', $hide_empty); ?>><?php _e('Yes', 'wpDirectory'); ?></option>
									<option value="0"<?php selected('0', $hide_empty); ?>><?php _e('No', 'wpDirectory'); ?></option>
								</select>
							</td>
							<td></td>
			      </tr>

						<tr valign="top">
							<td scope="row"><label for="desc_for_parent_title"><?php _e('Show description in the title of parent categories?', 'wpDirectory'); ?></label></td>
							<td>
								<select name="desc_for_parent_title" size="1">
									<option value="1"<?php selected('1', $desc_for_parent_title); ?>><?php _e('Yes', 'wpDirectory'); ?></option>
									<option value="0"<?php selected('0', $desc_for_parent_title); ?>><?php _e('No', 'wpDirectory'); ?></option>
								</select>
							</td>
							<td></td>
			      </tr>

						<tr valign="top">
							<td scope="row"><label for="desc_for_child_title"><?php _e('Show description in the title of child categories?', 'wpDirectory'); ?></label></td>
							<td>
								<select name="desc_for_child_title" size="1">
									<option value="1"<?php selected('1', $desc_for_child_title); ?>><?php _e('Yes', 'wpDirectory'); ?></option>
									<option value="0"<?php selected('0', $desc_for_child_title); ?>><?php _e('No', 'wpDirectory'); ?></option>
								</select>
							</td>
							<td></td>
			      </tr>

						<tr valign="top">
							<td scope="row"><label for="child_hierarchical"><?php _e('Use hierarchy for child categories?', 'wpDirectory'); ?></label></td>
							<td>
								<select name="child_hierarchical" size="1">
									<option value="1"<?php selected('1', $child_hierarchical); ?>><?php _e('Yes', 'wpDirectory'); ?></option>
									<option value="0"<?php selected('0', $child_hierarchical); ?>><?php _e('No', 'wpDirectory'); ?></option>
								</select>
							</td>
							<td></td>
			      </tr>

					 	<tr valign="top">
							<td scope="row"><label for="column_count"><?php _e('The number of columns for categories list:', 'wpDirectory'); ?></label></td>
							<td>
								<input<?php echo $error2; ?> name="column_count" type="text" id="column_count" value="<?php echo $column_count; ?>" size="4" maxlength="2" />
							</td>
							<td></td>
			      </tr>

						<tr valign="top">
							<td scope="row"><label for="sort_by"><?php _e('Sort categories list:', 'wpDirectory'); ?></label></td>
							<td>
								<select name="sort_by" size="1">
									<option value="0"<?php selected('0', $sort_by); ?>><?php _e('By name', 'wpDirectory'); ?></option>
									<option value="1"<?php selected('1', $sort_by); ?>><?php _e('By your choice', 'wpDirectory'); ?></option>
								</select>
							</td>
							<td><?php _e('For sorting by your choice you need to install <a href="http://wordpress.org/extend/plugins/my-category-order/">My Category Order</a> plugin.', 'wpDirectory'); ?></td>
			      </tr>

						<tr valign="top">
							<td scope="row"><label for="sort_direction"><?php _e('Sort direction of parent categories:', 'wpDirectory'); ?></label></td>
							<td>
								<select name="sort_direction" size="1">
									<option value="1"<?php selected('1', $sort_direction); ?>><?php _e('From top to down', 'wpDirectory'); ?></option>
									<option value="0"<?php selected('0', $sort_direction); ?>><?php _e('From left to right', 'wpDirectory'); ?></option>
								</select>
							</td>
							<td><?php _e('At sorting "From left to right" the list is built more rationally.', 'wpDirectory'); ?></td>
			      </tr>

						<tr valign="top">
							<td scope="row"><label for="no_child_alert"><?php _e('Show the "No categories", if category don\'t contain subcategories?', 'wpDirectory'); ?></label></td>
							<td>
								<select name="no_child_alert" size="1">
									<option value="1"<?php selected('1', $no_child_alert); ?>><?php _e('Yes', 'wpDirectory'); ?></option>
									<option value="0"<?php selected('0', $no_child_alert); ?>><?php _e('No', 'wpDirectory'); ?></option>
								</select>
							</td>
							<td></td>
			      </tr>

						<tr valign="top">
							<td scope="row"><label for="show_child"><?php _e('Show the child categories?', 'wpDirectory'); ?></label></td>
							<td>
								<select name="show_child" size="1">
									<option value="1"<?php selected('1', $show_child); ?>><?php _e('Yes', 'wpDirectory'); ?></option>
									<option value="0"<?php selected('0', $show_child); ?>><?php _e('No', 'wpDirectory'); ?></option>
								</select>
							</td>
							<td></td>
			      </tr>

						<tr valign="top">
							<td scope="row"><label for="maximum_child"><?php _e('The number of child categories to show:', 'wpDirectory'); ?></label></td>
							<td>
								<input<?php echo $error3; ?> name="maximum_child" type="text" id="maximum_child" value="<?php echo $maximum_child; ?>" size="4" maxlength="2" />
							</td>
							<td><?php _e('0 - all child categories will be displayed. If the number other than zero, level 3 child categories not shown.', 'wpDirectory'); ?></td>
			      </tr>

			    </table>

				</div><!-- .inside -->

			</div><!-- .postbox -->

			<div class="postbox">

		    <h3><?php _e('Admin Interface Options', 'wpDirectory'); ?></h3>

				<div class="inside">

					<table class="form-table">

					 	<tr valign="top">
							<td scope="row" colspan="3"><strong style="color: #F60"><?php _e('This options is only for the "Write Post" page.', 'wpDirectory'); ?></strong></td>
			      </tr>

						<tr valign="top">
							<td scope="row" style="width: 360px"><label for="forgot_the_cat"><?php _e('Show warning to author, if not selected any category?', 'wpDirectory'); ?></label></td>
							<td width="130">
								<select name="forgot_the_cat" size="1">
									<option value="1"<?php selected('1', $forgot_the_cat); ?>><?php _e('Yes', 'wpDirectory'); ?></option>
									<option value="0"<?php selected('0', $forgot_the_cat); ?>><?php _e('No', 'wpDirectory'); ?></option>
								</select>
							</td>
							<td></td>
			      </tr>

						<tr valign="top">
							<td scope="row"><label for="sel_only_one_cat"><?php _e('Show warning to author, if selected more than one category?', 'wpDirectory'); ?></label></td>
							<td>
								<select name="sel_only_one_cat" size="1">
									<option value="1"<?php selected('1', $sel_only_one_cat); ?>><?php _e('Yes', 'wpDirectory'); ?></option>
									<option value="0"<?php selected('0', $sel_only_one_cat); ?>><?php _e('No', 'wpDirectory'); ?></option>
								</select>
							</td>
							<td><?php _e('Recommended to publish an item in only one category for the prevention of duplicate content. This option would avoid the publication in more than one category.', 'wpDirectory'); ?></td>
			      </tr>

						<tr valign="top">
							<td scope="row"><label for="sel_only_child_cat"><?php _e('Forbid publication in parent categories?', 'wpDirectory'); ?></label></td>
							<td>
								<select name="sel_only_child_cat" size="1">
									<option value="1"<?php selected('1', $sel_only_child_cat); ?>><?php _e('Yes', 'wpDirectory'); ?></option>
									<option value="0"<?php selected('0', $sel_only_child_cat); ?>><?php _e('No', 'wpDirectory'); ?></option>
								</select>
							</td>
							<td><?php _e('Publishing of item will be possible only in child categories. Meanwhile, all child categories are hidden by default, but they opens when clicking on the parent category.', 'wpDirectory'); ?></td>
			      </tr>

						<tr valign="top">
							<td scope="row"><label><?php _e('Hide unnecessary blocks from the "Write Post" page, if this is not the site administrator?', 'wpDirectory'); ?></label></td>
							<td>
			<script type="text/javascript"><!--
			function CheckHideBlocks() {
				if (document.getElementById('hide_blocks').value=='0') {
					document.getElementById('hide_tags_field_label').style.display = 'none';
					document.getElementById('hide_tags_field_block').style.display = 'none';
					document.getElementById('using_wp_version_label').style.display = 'none';
					document.getElementById('using_wp_version_block').style.display = 'none';
					document.getElementById('using_wp_version_descr').style.display = 'none';
					jQuery('.hide').addClass('hide_this');
				} else {
					document.getElementById('hide_tags_field_label').style.display = 'block';
					document.getElementById('hide_tags_field_block').style.display = 'block';
					document.getElementById('using_wp_version_label').style.display = 'block';
					document.getElementById('using_wp_version_block').style.display = 'block';
					document.getElementById('using_wp_version_descr').style.display = 'block';
					jQuery('.hide').removeClass('hide_this');
				}
			}
			//--></script>
			<style>
			.hide_this {padding: 0 !important;	margin: 0 !important; border: 0 !important;}
			</style>
								<select onchange="CheckHideBlocks()" name="hide_blocks" id="hide_blocks" size="1">
									<option value="1"<?php selected('1', $hide_blocks); ?>><?php _e('Yes', 'wpDirectory'); ?></option>
									<option value="0"<?php selected('0', $hide_blocks); ?>><?php _e('No', 'wpDirectory'); ?></option>
								</select>
							</td>
							<td><?php _e('Practice has shown, that authors fill completely not necessary fields, for example, "Post Password". Therefore this option will help the administrator to avoid superfluous work at items moderation. On page there will be only really necessary elements.', 'wpDirectory'); ?></td>
			      </tr>

						<tr valign="top">
							<td class="hide" scope="row"><label id="hide_tags_field_label"><?php _e('Show the tags field?', 'wpDirectory'); ?></label></td>
							<td class="hide"><div id="hide_tags_field_block">
								<select name="hide_tags_field" id="hide_tags_field" size="1">
									<option value="1"<?php selected('1', $hide_tags_field); ?>><?php _e('Yes', 'wpDirectory'); ?></option>
									<option value="0"<?php selected('0', $hide_tags_field); ?>><?php _e('No', 'wpDirectory'); ?></option>
								</select></div>
							</td>
							<td class="hide"></td>
			      </tr>

						<tr valign="top">
							<td class="hide" scope="row"><label id="using_wp_version_label"><?php _e('Which version of WordPress you are using?', 'wpDirectory'); ?></label></td>
							<td class="hide"><div id="using_wp_version_block">
								<select name="using_wp_version" id="using_wp_version" size="1">
									<option value="1"<?php selected('1', $using_wp_version); ?>><?php _e('2.3.x or lower', 'wpDirectory'); ?></option>
									<option value="0"<?php selected('0', $using_wp_version); ?>><?php _e('2.5 or higher', 'wpDirectory'); ?></option>
								</select></div>
							</td>
							<td class="hide"><div id="using_wp_version_descr"><?php _e('It is necessary for correct work of two previous options.', 'wpDirectory'); ?></div></td>
			      </tr>

						<tr valign="top">
							<td scope="row"><label for="cat_block_height"><?php _e('The height of the categories block, in pixels:', 'wpDirectory'); ?></label></td>
							<td>
								<input<?php echo $error4; ?> name="cat_block_height" type="text" id="cat_block_height" value="<?php echo $cat_block_height; ?>" size="4" maxlength="4" /> px
							</td>
							<td></td>
			      </tr>

						<tr valign="top">
							<td scope="row"><label><?php _e('Use "Terms of article publication" on "Write/Edit Post" page?', 'wpDirectory'); ?></label></td>
							<td>
			<script type="text/javascript"><!--
			function CheckPublishTerms() {
			if (document.getElementById('publish_terms').value=='0') {
				document.getElementById('publish_terms_text_block').style.display = 'none';
				document.getElementById('publish_terms_text_label').style.display = 'none';
			} else {
				document.getElementById('publish_terms_text_block').style.display = 'block';
				document.getElementById('publish_terms_text_label').style.display = 'block';
			}
			}
			//--></script>
								<select onchange="CheckPublishTerms()" name="publish_terms" id="publish_terms" size="1">
									<option value="1"<?php selected('1', $publish_terms); ?>><?php _e('Yes', 'wpDirectory'); ?></option>
									<option value="0"<?php selected('0', $publish_terms); ?>><?php _e('No', 'wpDirectory'); ?></option>
								</select>
							</td>
							<td></td>
			      </tr>

						<tr valign="top">
							<td scope="row"><label<?php if($publish_terms == 0) echo ' style="display: none"'; ?> for="publish_terms_text" id="publish_terms_text_label"><?php _e('Text of "Terms of article publication:"', 'wpDirectory'); ?></label></td>
							<td colspan="2">
							  <table width="100%"<?php if($publish_terms == 0) echo ' style="display: none"'; ?> style="border-collapse: collapse;" id="publish_terms_text_block">
			      			<tr valign="top">
			      				<td style="border:none; padding: 0 10px 0 0"><textarea style="font: 11px/13px Arial, Tahoma, Arial; width: 400px; height: 200px;" name="publish_terms_text" id="publish_terms_text"><?=$publish_terms_text?></textarea></td>
			      				<td style="border:none; padding: 0"><?php _e('The terms appear before the article edit form. You can use html tags for text formatting, for example, <tt>&lt;p&gt;, &lt;ul&gt;, &lt;strong&gt;, &lt;a&gt;</tt>.', 'wpDirectory'); ?></td>
			      			</tr>
			      		</table>
							</td>
			      </tr>

			    </table>

				</div><!-- .inside -->

			</div><!-- .postbox -->

			<div class="postbox">

		    <h3><?php _e('Other Options', 'wpDirectory'); ?></h3>

				<div class="inside">

					<table class="form-table">

						<tr valign="top">
							<td scope="row" style="width: 360px"><label><?php _e('Exclude the child categories items from the parent categories pages?', 'wpDirectory'); ?></label></td>
							<td width="130">
								<select name="kinderloss" size="1">
									<option value="1"<?php selected('1', $kinderloss); ?>><?php _e('Yes', 'wpDirectory'); ?></option>
									<option value="0"<?php selected('0', $kinderloss); ?>><?php _e('No', 'wpDirectory'); ?></option>
								</select>
							</td>
							<td></td>
			      </tr>

						<tr valign="top">
							<td scope="row" style="width: 360px"><label><?php _e('Show Item source code?', 'wpDirectory'); ?></label></td>
							<td>
								<select name="show_wpdirectory_item_code" size="1">
									<option value="1"<?php selected('1', $show_wpdirectory_item_code); ?>><?php _e('Yes', 'wpDirectory'); ?></option>
									<option value="0"<?php selected('0', $show_wpdirectory_item_code); ?>><?php _e('No', 'wpDirectory'); ?></option>
								</select>
							</td>
							<td><?php _e('Appears on article page.', 'wpDirectory'); ?></td>
			      </tr>

			    </table>

				</div><!-- .inside -->

			</div><!-- .postbox -->

			<p><input type="submit" name="wpdirectory_update" class="button-primary" value="<?php _e('Update Options', 'wpDirectory') ?>" style="font-weight:bold;" /><br><br></p>
	    <p><input type="submit" name="wpdirectory_reset" class="button-primary" value=" <?php _e('Reset Defaults', 'wpDirectory') ?> " /><br><br></p>

			<div class="postbox">

		    <h3><?php _e('Copyright', 'wpDirectory'); ?></h3>

				<div class="inside">

					<p>&copy; 2008-<?php echo date('Y'); ?> <a href="http://arielbustillos.com">Ariel Bustillos</a> | <a href="http://arielbustillos.com/wpdirectory">wpDirectory</a> | <?php _e('version', 'wpDirectory') ?> <?php echo wpdirectory_version() ?></p>

				</div><!-- .inside -->

			</div><!-- .postbox -->

		</div><!-- #poststuff -->

	</form>

</div><!-- .wrap -->

<?php

}
add_action('admin_menu', 'wpdirectory_options_page');


$options = get_option('wpdirectory');


if ($options['hide_blocks'] == 1) {

	if ($options['using_wp_version'] == 1) {
		// <= wp 2.3.3
		function hide_blocks() {

		  global $options;
		  $hide = '';

			if ( current_user_can('level_7') ) {
				$hide = '
<style type="text/css">
#categorychecklist {height: '.$options['cat_block_height'].'px !important}
</style>';
			} else {
				$hide = '
<style type="text/css">
#update-nag {display: none !important}
#advancedstuff_tag {display: none !important}
#commentstatusdiv {display: none !important}
#passworddiv {display: none !important}
#slugdiv {display: none !important}
#poststatusdiv {display: none !important}
#posttimestampdiv {display: none !important}
.submit input {visibility: hidden}
.submit input#save {visibility: visible}
.submit input#publish {visibility: visible}
';
				if ($options['hide_tags_field'] == 0) {
					$hide .= '
#tagdiv {display: none !important}';
				}
				$hide .= '
#seodiv {display: none !important}
#advancedstuff {display: none !important}
#categorychecklist {height: '.$options['cat_block_height'].'px !important}
#devnews {display: none !important}
#planetnews {display: none !important}
#zeitgeist {display: none !important}
#footer {display: none !important}
</style>';
			}
			echo $hide;
		}
		add_action('admin_head', 'hide_blocks');

	} else {
		// wp 2.5+
		function hide_blocks() {

		  global $options;
		  $hide = '';

			if ( current_user_can('level_7') ) {
				$hide = '
<style type="text/css">
div.ui-tabs-panel {height: '.$options['cat_block_height'].'px !important}
</style>';
			} else {
				$hide = '
<style type="text/css">
#update-nag {display: none !important}
.inside p {display: none !important}
.inside p#jaxtag {display: block !important}
.side-info {display: none !important}
#media-buttons {display: none !important}
';
				if ($options['hide_tags_field'] == 0) {
					$hide .= '
#tagsdiv {display: none !important}
#old-tagsdiv {display: none !important}';
				}
				$hide .= '
#postexcerpt {display: none !important}
#trackbacksdiv {display: none !important}
#postcustom {display: none !important}
#commentstatusdiv {display: none !important}
#passworddiv {display: none !important}
#category-adder {display: none !important}
#seodiv {display: none !important}
div.ui-tabs-panel {height: '.$options['cat_block_height'].'px !important}
#category-tabs li.hide-if-no-js {display: none !important}
#rightnow {visibility: hidden !important}
#rightnow .reallynow {visibility: visible !important}
#footer {display: none !important}
</style>';
			}
			echo $hide;
		}
		add_action('admin_head', 'hide_blocks');
	}
} else {

	if ($options['using_wp_version'] == 1) {
		// <= wp 2.3.3
		function hide_blocks() {

		  global $options;
		  $hide = '
<style type="text/css">
#categorychecklist {height: '.$options['cat_block_height'].'px !important}
</style>';
			echo $hide;
		}
		add_action('admin_head', 'hide_blocks');

	} else {

		// wp 2.5+
		function hide_blocks() {

		  global $options;
		  $hide = '
<style type="text/css">
div.ui-tabs-panel {height: '.$options['cat_block_height'].'px !important}
</style>';

			echo $hide;
		}
		add_action('admin_head', 'hide_blocks');
	}
}



function clear_fields() {

	global $options;

	if ($options['hide_blocks'] == 1) {

		if ( !current_user_can('level_7') ) {

			if (isset($_POST['excerpt'])) $_POST['excerpt'] = '';
			if (isset($_POST['trackback_url'])) $_POST['trackback_url'] = '';
			if (isset($_POST['post_password'])) $_POST['post_password'] = '';
			if (isset($_POST['post_name'])) $_POST['post_name'] = '';
			if (isset($_POST['save']) || isset($_POST['publish'])) $_POST['post_status'] = 'pending';

			if ($options['hide_tags_field'] == 0) {
				if (isset($_POST['tags_input'])) $_POST['tags_input'] = '';
				if (isset($_POST['old_tags_input'])) $_POST['old_tags_input'] = '';
			}

		}

	}
}
add_action('init', 'clear_fields');



if ($options['forgot_the_cat'] == 1) {

	//thanks to "Forgot the Category" plugin - http://dancoulter.com/forgot-the-category/
	class DC_ForgotTheCategory {
	  function AddToEditPage() {
		?>
	    <script type="text/javascript"><!--
	      jQuery("form#post").submit(function(){
	        var ln = jQuery("ul#categorychecklist input:checkbox:checked").length;
	        if ( ln < 1 ) {
	          alert("<?php _e('Oops! You forgot to select a category.', 'wpDirectory'); ?>");
	          return false;
	        } else {
	          return true;
	        }
	      });
	    //--></script>
		<?php
	  }
	}
	add_action("edit_form_advanced", array("DC_ForgotTheCategory", "AddToEditPage"));
}



if ($options['sel_only_one_cat'] == 1) {

	class SelectOnlyOneCategory {
	  function AddToEditPage() {
		?>
	    <script type="text/javascript"><!--
	      jQuery("form#post").submit(function(){
	        var ln = jQuery("ul#categorychecklist input:checkbox:checked").length;
	        if ( ln > 1 ) {
	          alert("<?php _e('Attention! You can select only ONE category.', 'wpDirectory'); ?>");
	          return false;
	        } else {
	          return true;
	        }
	      });
	      jQuery('#categorychecklist label').click(function(){
	        var ln = jQuery("ul#categorychecklist input:checkbox:checked").length;
	        if ( ln > 1 ) {
	          alert("<?php _e('Attention! You can select only ONE category.', 'wpDirectory'); ?>");
	          return false;
	        } else {
	          return true;
	        }
	      });
	    //--></script>
		<?php
	  }
	}
	add_action("edit_form_advanced", array("SelectOnlyOneCategory", "AddToEditPage"));
}



if ($options['sel_only_child_cat'] == 1) {

	function wpdirectory_SelectOnlyChildCategory() {
?>
    <script type="text/javascript"><!--
			var $j = jQuery.noConflict();
			$j('#categorychecklist li:has(ul.children) > label').css({borderBottom: '1px dashed #666'});
			$j('#categorychecklist .children').hide();
			$j('#categorychecklist li:has(ul.children) > label').toggle(
				function() {
					$j(this).parent('li').find('ul.children').slideDown();
					return false;
				},
				function() {
					$j(this).parent('li').find('ul.children').slideUp();
					return false;
				}
			);
    //--></script>
<?php
	}
	add_action('edit_form_advanced', 'wpdirectory_SelectOnlyChildCategory');
}



if ($options['kinderloss'] == 1) {

	//thanks to "Kinderlose" plugin - http://guff.szub.net/kinderlose
	function kinderloss_where($where) {
		if ( is_category() ) {
			global $wp_query;
			$where = preg_replace('/.term_id IN \(\'(.*)\'\)/', '.term_id IN (\'' . $wp_query->query_vars['cat'] . '\') AND post_type = \'post\' AND post_status = \'publish\'', $where);
		}

		return $where;
	}

	add_filter('posts_where', 'kinderloss_where');
}



//thanks to "Manage Your Posts Only" plugin - http://code.mincus.com/41/manage-your-posts-only-in-wordpress/
function mypo_parse_query_useronly( $wp_query ) {
    if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/edit.php' ) !== false ) {
        if ( !current_user_can( 'level_7' ) ) {
            global $current_user;
            $wp_query->set( 'author', $current_user->id );
        }
    }
}
add_filter('parse_query', 'mypo_parse_query_useronly' );



if ($options['publish_terms'] == 1) {

	global $pagenow;
	$wp_pages = array('post.php', 'post-new.php', );

	if ( in_array($pagenow, $wp_pages) ) {
		function PublishTerms() {

		  $options = get_option('wpdirectory');

			$publishTerms = "<div class=\"wrap\"><h2>" . __('Terms of article publication', 'wpDirectory'). "</h2>";
			$publishTerms .= $options['publish_terms_text'];
			$publishTerms .="</div>";

			if (!current_user_can('level_7')) {
				echo $publishTerms;
			}
		}
		add_action('admin_notices', 'PublishTerms');
	}
}



function restrict_dashboard_screens() {
	if ( ((strpos($_SERVER['SCRIPT_NAME'], 'wp-admin/edit-comments.php')) or (strpos($_SERVER['SCRIPT_NAME'], 'wp-admin/comment.php')))) {
		if (!current_user_can('level_7')) {
			echo "<div class='wrap'><h2>" . __('Restricted area', 'wpDirectory'). "</h2><a href='javascript:history.go(-1)'>&laquo; " . __('go back', 'wpDirectory'). "</a></div>";
			die();
		}
	}
}
add_action('init', 'restrict_dashboard_screens');



if ($options['show_wpdirectory_item_code'] == 1) {

	function get_wpdirectory_item_code($text) {
		$rn = "\r\n\r\n";
		$get_wpdirectory_item_code = '
<script type="text/javascript">
var $j = jQuery.noConflict();
$j(function() {
	$j("#get-article-code").css({opacity: 0}).hide();
	$j("#get-article-source").toggle(
		function() { $j("#get-article-code").animate({opacity: 1}, 300).show();	return false;	},
		function() { $j("#get-article-code").animate({opacity: 0}).hide();	return false;	}
	);
	$j("#html-version").text($j("#html-version").text() + $j("#artdir-post").html() + "<p>Source: <a href=\"'.get_permalink().'\">'.get_permalink().'</a></p>");
	$j("#text-version").text($j("#text-version").text() + "\r\n\r\n" + $j("#artdir-post").text() + "\r\n" + "Source: '.get_permalink().'");
	$j("#get-article-code textarea, #get-article-code input").click(function() { $j(this).select() });
})
</script>
<p><a href="#" id="get-article-source" style="text-decoration:none;border-bottom:1px dashed;font-weight:bold">'.__('Article Source', 'wpDirectory').'</a></p>
<div id="get-article-code" style="display:none">
	<p>'.__('HTML Version', 'wpDirectory').':</p>
	<textarea id="html-version" rows="15" cols="50" style="width:99.5%;font:11px Arial,Tahoma"><h1>'.get_the_title().'</h1></textarea>
	<p>'.__('Text Version', 'wpDirectory').':</p>
	<textarea id="text-version" rows="15" cols="50" style="width:99.5%;font:11px Arial,Tahoma">'.get_the_title().'</textarea>
	<p>'.__('Article Url', 'wpDirectory').':</p>
	<input type="text" value="'.get_permalink().'" style="width: 99.5%" />
</div>
		';
		if (is_single()) {
			return '<div id="artdir-post">'.$text.'</div>'.$get_wpdirectory_item_code;
		} else {
			return $text;
		}
	}
	add_filter('the_content', 'get_wpdirectory_item_code');

//Enqueue jQuery
	function wpdirectory_jquery() {
		if (is_single()) { wp_enqueue_script('jquery'); }
	}
	add_action('wp_head', 'wpdirectory_jquery', 1);

}

function wpdirectory() {

	// получаем все опции
	$options = get_option('wp_directory');

	// проверяем каждую опцию
	// если опция есть, то присваиваем её переменной, если нет (:), то дефолт
	$exclude_cats          = isset($options['exclude_cats'])          ?       $options['exclude_cats']          : 0;
	$show_parent_count     = isset($options['show_parent_count'])     ? (int) $options['show_parent_count']     : 1;
	$show_child_count      = isset($options['show_child_count'])      ? (int) $options['show_child_count']      : 1;
	$hide_empty            = isset($options['hide_empty'])            ? (int) $options['hide_empty']            : 0;
	$desc_for_parent_title = isset($options['desc_for_parent_title']) ? (int) $options['desc_for_parent_title'] : 1;
	$desc_for_child_title  = isset($options['desc_for_child_title'])  ? (int) $options['desc_for_child_title']  : 1;
	$child_hierarchical    = isset($options['child_hierarchical'])    ? (int) $options['child_hierarchical']    : 1;
	$column_count          = isset($options['column_count'])          ?       $options['column_count']          : 3;
	$sort_by               = isset($options['sort_by'])               ? (int) $options['sort_by']               : 0;
	$sort_direction        = isset($options['sort_direction'])        ? (int) $options['sort_direction']        : 0;
	$no_child_alert        = isset($options['no_child_alert'])        ? (int) $options['no_child_alert']        : 1;
	$show_child            = isset($options['show_child'])            ? (int) $options['show_child']            : 1;
	$maximum_child         = isset($options['maximum_child'])         ?       $options['maximum_child']         : 0;
	$forgot_the_cat        = isset($options['forgot_the_cat'])        ? (int) $options['forgot_the_cat']        : 1;
	$sel_only_one_cat      = isset($options['sel_only_one_cat'])      ? (int) $options['sel_only_one_cat']      : 1;
	$sel_only_child_cat    = isset($options['sel_only_child_cat'])    ? (int) $options['sel_only_child_cat']    : 0;
	$kinderloss            = isset($options['kinderloss'])            ? (int) $options['kinderloss']            : 1;
	$hide_blocks           = isset($options['hide_blocks'])           ? (int) $options['hide_blocks']           : 1;
	$using_wp_version      = isset($options['using_wp_version'])      ? (int) $options['using_wp_version']      : 1;
	$cat_block_height      = isset($options['cat_block_height'])      ?       $options['cat_block_height']      : 200;
	$hide_tags_field       = isset($options['hide_tags_field'])       ? (int) $options['hide_tags_field']       : 0;
	$publish_terms         = isset($options['publish_terms'])         ? (int) $options['publish_terms']         : 0;
	$publish_terms_text    = isset($options['publish_terms_text'])    ?       $options['publish_terms_text']    : '';
	$show_wpdirectory_item_code     = isset($options['show_wpdirectory_item_code'])     ? (int) $options['show_wpdirectory_item_code']     : 0;

	$exclude_cat = array($exclude_cats);


	global $wpdb;
	$cal_tree = array();
	if (!$column_count) $column_count = 1;


	if (!function_exists('wpdirectory_wp_version')) {
		function wpdirectory_wp_version() {
			$version = file_get_contents(ABSPATH."wp-includes/version.php");
			preg_match("/'(.*)'/is", $version, $out);
			preg_match("/\d\.\d/i", $out[1], $match);
			return $match[0];
		}
	}


	global $rssfeeds;
  $feed = '';
	if ($rssfeeds) {
		$feed = 'RSS';
		$show_parent_count = 0;
		$show_child_count = 0;
	}


	if ($sort_by == 0) $order_by = $orderby = 'name';
	elseif ($sort_by == 1) { $order_by = 'term_order'; $orderby = 'term_group'; }


	$parent_cats = $wpdb->get_results("SELECT *
	FROM " . $wpdb->term_taxonomy . " term_taxonomy
	LEFT JOIN " . $wpdb->terms . " terms
	ON terms.term_id = term_taxonomy.term_id
	WHERE term_taxonomy.taxonomy = 'category' AND term_taxonomy.parent = 0 " .
	( count($exclude_cat) ? ' AND terms.term_id NOT IN (' . implode(',', $exclude_cat) . ') ' : '' )
	. " ORDER BY terms." . $order_by);

	foreach ($parent_cats as $parent) {

		$summ = "SELECT SUM(count) FROM " . $wpdb->term_taxonomy . " WHERE taxonomy = 'category' AND parent = " . $parent->term_id;

		$child_summ = mysql_result(mysql_query($summ),0); //считаем кол-во статей в подрубрике 1-го уровня

		$catid = $wpdb->get_var("SELECT term_ID FROM " . $wpdb->term_taxonomy . " WHERE taxonomy = 'category' AND parent = " . $parent->term_id); //определяем ID подрубрики 1-го уровня

		$sub_child_summ = (int)$catid ? $wpdb->get_var("SELECT SUM(count) FROM " . $wpdb->term_taxonomy . " WHERE taxonomy = 'category' AND parent = " . $catid) : 0; //считаем кол-во статей в подрубрике 2-го уровня

		$cat_name = get_the_category_by_ID($parent->term_id);

 		$descr = sprintf(__("View all posts filed under %s"), $cat_name);

		if ($desc_for_parent_title == 1) {
			if (empty($parent->description)) {
				$descr = $descr;
			} else {
				$descr = $parent->description;
			}
		}

		$child_summ += $parent->count;  //прибавляем к сумме родительской рубрики сумму в подрубрике 1-го уровня
		$child_summ += $sub_child_summ; //прибавляем к сумме родительской рубрики сумму в подрубрике 2-го уровня

		if ($show_parent_count == 1) {
			$parent_count = ' (' . $child_summ . ')';
		} else {
			$parent_count = '';
		}

		$cal_tree[] = array(
			'cat' => array(
			'href'  => get_category_link($parent->term_id),
			'title' => $descr,
			'name'  => $cat_name,
			'count' => $parent_count
		),
		'cats'=> wp_list_categories( ( count($exclude_cat) ? 'exclude=' . implode(',', $exclude_cat) : '' ) . '&orderby=' . $orderby . '&show_count=' . $show_child_count . '&hide_empty=' . $hide_empty . '&use_desc_for_title=' . $desc_for_child_title . '&child_of=' . $parent->term_id . '&title_li=&hierarchical=' . $child_hierarchical . '&echo=0&feed=' . $feed)
		);

	}


	$_tree = array();
	$count = count($cal_tree);
	if ($sort_direction) {
		$line_count = ceil( $count / $column_count );
		$limit      = $count - $line_count * $column_count % $count;
		for ($i = 0; $i < $count; $i++) {
			$index = floor($i / $line_count) + ($limit && $i > $limit ? 1 : 0);
			if (!isset($_tree[$index])) { $_tree[$index] = array(); }
			$_tree[$index][] = &$cal_tree[$i];
		}
	}
	else {
		for ($i = 0; $i < $count; $i++) {
			$index = $i % $column_count;
			if (!isset($_tree[$index])) { $_tree[$index] = array(); }
			$_tree[$index][] = &$cal_tree[$i];
		}
	}


	if (count($_tree)) {

		echo '
<div id="categories">';

		for ($j = 0, $count = count($_tree); $j < $count; $j++) {

			// вывод столбца
			$write = '
		<ul class="column">';

			// вывод рубрик для столбца
			for ($i = 0, $icount = count($_tree[$j]); $i < $icount; $i++) {

				$catcount = $i + 11;
				if ($j == 1) $catcount = $i + 21;
				if ($j == 2) $catcount = $i + 31;
				if ($j == 3) $catcount = $i + 41;
				if ($j == 4) $catcount = $i + 51;

				if ($rssfeeds) {

					$write .= '

			<li id="cat-'. $catcount .'"><div><a href="' . wp_specialchars($_tree[$j][$i]['cat']['href']) . '" title="' . wp_specialchars($_tree[$j][$i]['cat']['title']) . '">' . wp_specialchars($_tree[$j][$i]['cat']['name']) . '</a> (<a href="' . wp_specialchars($_tree[$j][$i]['cat']['href']) . '/feed/" title="' . wp_specialchars($_tree[$j][$i]['cat']['title']) . '">RSS</a>)</div>';

				} else {

					$write .= '

			<li id="cat-'. $catcount .'"><div><a href="' . wp_specialchars($_tree[$j][$i]['cat']['href']) . '" title="' . wp_specialchars($_tree[$j][$i]['cat']['title']) . '">' . wp_specialchars($_tree[$j][$i]['cat']['name']) . '</a>' . $_tree[$j][$i]['cat']['count'] . '</div>';

				}

				// see wp-includes/category-template.php::276
				// $output .= '<li>' . __("No categories") . '</li>';
				$nocats = '<li>' . __("No categories") . '</li>';

				if ($no_child_alert == 1) $nocats = '';

				if ($_tree[$j][$i]['cats'] != $nocats && $show_child == 1) {

				$write .= '
			<ul class="sub-categories">';
					if ($maximum_child) {
						for ($s = 0, $strlen = strlen($_tree[$j][$i]['cats']), $counter = $maximum_child+1, $slevel = 0; $s < $strlen; $s++) {
							if (!$slevel && substr($_tree[$j][$i]['cats'], $s, 3) == '<li' && !(--$counter)) break;
							else if (substr($_tree[$j][$i]['cats'], $s, 3) == '<ul') $slevel++;
							else if ($slevel && substr($_tree[$j][$i]['cats'], $s-4, 4) == '/ul>') $slevel--;
							else if (!$slevel) $write .= substr($_tree[$j][$i]['cats'], $s, 1);
						}
						$licount = substr_count($_tree[$j][$i]['cats'], '<li');
						if ( ($licount > $maximum_child) && ($_tree[$j][$i]['cats'] != '<li>' . __("No categories") . '</li>') ) {
							$write .= '<li>...</li>';
						}
					}
					else $write .= $_tree[$j][$i]['cats'];

					$write .= '
			</ul>';

				}
				$write .= '
		</li>';

			}

			// печать одного столбца
			echo $write . '
	</ul>';

		}

echo '
</div>';

	}

}


function wpdirectory_options_page() {
  if (function_exists('add_options_page')) {
  	add_options_page('wpDirectory', 'wpDirectory', 8, __FILE__, 'wpdirectory_options');
	}
}

function addCss()
{
	echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/wpdirectory/categories.css" />' . "\n";
}
add_action('wp_head','addCss');
?>
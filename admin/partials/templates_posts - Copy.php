<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://ljapps.com
 * @since      1.0.0
 *
 * @package    WP_FB_Reviews
 * @subpackage WP_FB_Reviews/admin/partials
 */
 
     // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
	$dbmsg = "";
	$html="";
	$currenttemplate= new stdClass();
	$currenttemplate->id="";
	$currenttemplate->title ="";
	$currenttemplate->template_type ="";
	$currenttemplate->style ="";
	$currenttemplate->created_time_stamp ="";
	$currenttemplate->display_num ="";
	$currenttemplate->display_num_rows ="";
	$currenttemplate->display_order ="";
	$currenttemplate->hide_no_text ="";
	$currenttemplate->template_css ="";
	$currenttemplate->min_rating ="";
	$currenttemplate->min_words ="";
	$currenttemplate->max_words ="";
	$currenttemplate->rtype ="";
	$currenttemplate->rpage ="";
	$currenttemplate->showreviewsbyid ="";
	$currenttemplate->createslider ="";
	$currenttemplate->numslides ="";
	$currenttemplate->sliderautoplay ="";
	$currenttemplate->sliderdirection ="";
	$currenttemplate->sliderarrows ="";
	$currenttemplate->sliderdots ="";
	$currenttemplate->sliderdelay ="";
	$currenttemplate->sliderheight ="";
	
	//echo $this->_token;
	//if token = wp-fb-reviews then using free version
	
	//db function variables
	global $wpdb;
	$table_name = $wpdb->prefix . 'wpfb_post_templates';
	
	//form deleting and updating here---------------------------
	if(isset($_GET['taction'])){
		$tid = htmlentities($_GET['tid']);
		//for deleting
		if($_GET['taction'] == "del" && $_GET['tid'] > 0){
			//security
			check_admin_referer( 'tdel_');
			//delete
			$wpdb->delete( $table_name, array( 'id' => $tid ), array( '%d' ) );
		}
		//for updating
		if($_GET['taction'] == "edit" && $_GET['tid'] > 0){
			//security
			check_admin_referer( 'tedit_');
			//get form array
			$currenttemplate = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$tid );
		}
		
	}
	//------------------------------------------

	//form posting here--------------------------------
	//check to see if form has been posted.
	//if template id present then update database if not then insert as new.

	if (isset($_POST['wpfbr_submittemplatebtn'])){
		//verify nonce wp_nonce_field( 'wpfbr_save_template');
		check_admin_referer( 'wpfbr_save_template');

		//get form submission values and then save or update
		$t_id = htmlentities($_POST['edittid']);
		$title = htmlentities($_POST['wpfbr_template_title']);
		$template_type = htmlentities($_POST['wpfbr_template_type']);
		$style = htmlentities($_POST['wpfbr_template_style']);
		$display_num = htmlentities($_POST['wpfbr_t_display_num']);
		$display_num_rows = htmlentities($_POST['wpfbr_t_display_num_rows']);
		$display_order = htmlentities($_POST['wpfbr_t_display_order']);
		$hide_no_text = htmlentities($_POST['wpfbr_t_hidenotext']);
		$template_css = htmlentities($_POST['wpfbr_template_css']);
		
		$createslider = htmlentities($_POST['wpfbr_t_createslider']);
		$numslides = htmlentities($_POST['wpfbr_t_numslides']);
		
		//pro settings
		$min_rating = htmlentities($_POST['wpfbr_t_min_rating']);
		$min_words = htmlentities($_POST['wpfbr_t_min_words']);
		$max_words = htmlentities($_POST['wpfbr_t_max_words']);
		
		$rtype = htmlentities($_POST['wpfbr_t_rtype']);
		$rpage = htmlentities($_POST['wpfbr_t_rpage']);
		$showreviewsbyid = htmlentities($_POST['wpfbr_t_showreviewsbyid']);
		
		$sliderautoplay = htmlentities($_POST['wpfbr_sliderautoplay']);
		$sliderdirection = htmlentities($_POST['wpfbr_sliderdirection']);
		$sliderarrows = htmlentities($_POST['wpfbr_sliderarrows']);
		$sliderdots = htmlentities($_POST['wpfbr_sliderdots']);
		$sliderdelay = htmlentities($_POST['wpfbr_t_sliderdelay']);
		$sliderheight = htmlentities($_POST['wpfbr_sliderheight']);
		
		//santize
		$title = sanitize_text_field( $title );
		$template_type = sanitize_text_field( $template_type );
		$display_order = sanitize_text_field( $display_order );
		$template_css = sanitize_text_field( $template_css );
		$display_order = sanitize_text_field( $display_order );
		$rtype = sanitize_text_field( $rtype );
		$rpage = sanitize_text_field( $rpage );
		$showreviewsbyid = sanitize_text_field( $showreviewsbyid );
		
		
		
		//only save if using pro version
		if($this->_token=="wp-fb-reviews"){
			$min_rating = "";
			$min_words = "";
			$max_words = "";			
			$rtype = "";
			$rpage = "";
			$showreviewsbyid="";
			$sliderautoplay = "";
			$sliderdirection = "";
			$sliderarrows = "";
			$sliderdots = "";
			$sliderdelay = "";
			$sliderheight = "";
		}
		$timenow = time();
		
		//+++++++++need to sql escape using prepare+++++++++++++++++++
		//+++++++++++++++++++++++++++++++++++++++++++++++++++++
		//insert or update
			$data = array( 
				'title' => "$title",
				'template_type' => "$template_type",
				'style' => "$style",
				'created_time_stamp' => "$timenow",
				'display_num' => "$display_num",
				'display_num_rows' => "$display_num_rows",
				'display_order' => "$display_order", 
				'hide_no_text' => "$hide_no_text",
				'template_css' => "$template_css", 
				'min_rating' => "$min_rating", 
				'min_words' => "$min_words",
				'max_words' => "$max_words",
				'rtype' => "$rtype", 
				'rpage' => "$rpage",
				'createslider' => "$createslider",
				'numslides' => "$numslides",
				'sliderautoplay' => "$sliderautoplay",
				'sliderdirection' => "$sliderdirection",
				'sliderarrows' => "$sliderarrows",
				'sliderdots' => "$sliderdots",
				'sliderdelay' => "$sliderdelay",
				'sliderheight' => "$sliderheight",
				'showreviewsbyid' => "$showreviewsbyid"
				);
			$format = array( 
					'%s',
					'%s',
					'%d',
					'%d',
					'%d',
					'%d',
					'%s',
					'%s',
					'%s',
					'%d',
					'%d',
					'%d',
					'%s',
					'%s',
					'%s',
					'%d',
					'%s',
					'%s',
					'%s',
					'%s',
					'%d',
					'%s',
					'%s'
				); 

		if($t_id==""){
			//insert
			$wpdb->insert( $table_name, $data, $format );
				//exit( var_dump( $wpdb->last_error ) );
				//Print last SQL query string
				//$wpdb->last_query;
				// Print last SQL query result
				//$wpdb->last_result;
				// Print last SQL query Error
				//$wpdb->last_error;
		} else {
			//update
			$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $t_id ), $format, array( '%d' ));
			if($updatetempquery>0){
				$dbmsg = '<div id="setting-error-wpfbr_message" class="updated settings-error notice is-dismissible">'.__('<p><strong>Template Updated!</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>', 'wp-fb-reviews').'</div>';
			}
		}
		
	}

	//Get list of all current forms--------------------------
	$currentforms = $wpdb->get_results("SELECT id, title, template_type, created_time_stamp, style FROM $table_name");
	
	//-------------------------------------------------------
	
	
	
	//check to see if reviews are in database
	//total number of rows
	$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
	$reviewtotalcount = $wpdb->get_var( 'SELECT COUNT(*) FROM '.$reviews_table_name );
	if($reviewtotalcount<1){
		$dbmsg = $dbmsg . '<div id="setting-error-wpfbr_message" class="updated settings-error notice is-dismissible">'.__('<p><strong>No reviews found. Please visit the <a href="?page=wp_fb-settings">Get FB Reviews</a> page to retrieve reviews from Facebook, or manually add one on the <a href="?page=wp_fb-reviews">Review List</a> page. </strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>', 'wp-fb-reviews').'</div>';
	}
	
	
?>

<div class="wrap" id="wp_fb-settings">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>
<?php 
include("tabmenu.php");
?>
<div class="wpfbr_margin10">
	<a id="wpfbr_helpicon_posts" class="wpfbr_btnicononly button dashicons-before dashicons-editor-help"></a>
	<a id="wpfbr_addnewtemplate" class="button dashicons-before dashicons-plus-alt"><?php _e('Add New Reviews Template', 'wp-fb-reviews'); ?></a>
</div>

<?php
//display message
echo $dbmsg;
		$html .= '
		<table class="wp-list-table widefat striped posts">
			<thead>
				<tr>
					<th scope="col" width="30px" class="manage-column">'.__('ID', 'wp-fb-reviews').'</th>
					<th scope="col" class="manage-column">'.__('Title', 'wp-fb-reviews').'</th>
					<th scope="col" width="100px" class="manage-column">'.__('Type', 'wp-fb-reviews').'</th>
					<th scope="col" width="170px" class="manage-column">'.__('Date Created', 'wp-fb-reviews').'</th>
					<th scope="col" width="300px" class="manage-column">'.__('Action', 'wp-fb-reviews').'</th>
				</tr>
				</thead>
			<tbody id="review_list">';
	foreach ( $currentforms as $currentform ) 
	{
	//remove query args we just used
	$urltrimmed = remove_query_arg( array('taction', 'id') );
		$tempeditbtn =  add_query_arg(  array(
			'taction' => 'edit',
			'tid' => "$currentform->id",
			),$urltrimmed);
			
		$url_tempeditbtn = wp_nonce_url( $tempeditbtn, 'tedit_');
			
		$tempdelbtn = add_query_arg(  array(
			'taction' => 'del',
			'tid' => "$currentform->id",
			),$urltrimmed) ;
			
		$url_tempdelbtn = wp_nonce_url( $tempdelbtn, 'tdel_');
			
		$html .= '<tr id="'.$currentform->id.'">
				<th scope="col" class="wpfbr_upgrade_needed manage-column">'.$currentform->id.'</th>
				<th scope="col" class="wpfbr_upgrade_needed manage-column"><b>'.$currentform->title.'</b></th>
				<th scope="col" class="wpfbr_upgrade_needed manage-column"><b>'.$currentform->template_type.'</b></th>
				<th scope="col" class="wpfbr_upgrade_needed manage-column">'.date("F j, Y",$currentform->created_time_stamp) .'</th>
				<th scope="col" class="manage-column" templateid="'.$currentform->id.'" templatetype="'.$currentform->template_type.'"><a href="'.$url_tempeditbtn.'" class="button button-primary dashicons-before dashicons-admin-generic">'.__('Edit', 'wp-fb-reviews').'</a>, <a href="'.$url_tempdelbtn.'" class="button button-secondary dashicons-before dashicons-trash">'.__('Delete', 'wp-fb-reviews').'</a>, <a class="wpfbr_displayshortcode button button-secondary dashicons-before dashicons-visibility">'.__('Shortcode', 'wp-fb-reviews').'</a></th>
			</tr>';
	}	
		$html .= '</tbody></table>';
			
 echo $html;			
?>
<div class="wpfbr_margin10" id="wpfbr_new_template">
<form name="newtemplateform" id="newtemplateform" action="?page=wp_fb-templates_posts" method="post" onsubmit="return validateForm()">
	<table class="wpfbr_margin10 form-table ">
		<tbody>
			<tr class="wpfbr_row">
				<th scope="row">
					<?php _e('Template Title:', 'wp-fb-reviews'); ?>
				</th>
				<td>
					<input id="wpfbr_template_title" data-custom="custom" type="text" name="wpfbr_template_title" placeholder="" value="<?php echo $currenttemplate->title; ?>" required>
					<p class="description">
					<?php _e('Enter a title or name for this template.', 'wp-fb-reviews'); ?>		</p>
				</td>
			</tr>
			<tr class="wpfbr_row">
				<th scope="row">
					<?php _e('Choose Template Type:', 'wp-fb-reviews'); ?>
				</th>
				<td><div id="divtemplatestyles">

					<input type="radio" name="wpfbr_template_type" id="wpfbr_template_type1-radio" value="post" checked="checked">
					<label for="wpfbr_template_type1-radio"><?php _e('Post or Page', 'wp-fb-reviews'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

					<input type="radio" name="wpfbr_template_type" id="wpfbr_template_type2-radio" value="widget" <?php if($currenttemplate->template_type== "widget"){echo 'checked="checked"';}?>>
					<label for="wpfbr_template_type2-radio"><?php _e('Widget Area', 'wp-fb-reviews'); ?></label>
					</div>
					<p class="description">
					<?php _e('Are you going to use this on a Page/Post or in a Widget area like your sidebar?', 'wp-fb-reviews'); ?></p>
				</td>
			</tr>
			<tr class="wpfbr_row">
				<th scope="row">
					<?php _e('Select Template Style:', 'wp-fb-reviews'); ?>
				</th>
				<td><div class="divtemplatestyles">
					<div class="divtemplatestyle">
					<input type="radio" name="wpfbr_template_style" id="wpfbr_template_style1-radio" value="1" checked="checked">
					<label for="wpfbr_template_style1-radio">Style 1</label><br>
					<img src="<?php echo plugin_dir_url( __FILE__ ) . 'template1_thumb.png'; ?>" alt="" class="wpfbr_template_thumb">
					</div></div>
					<p class="description">
					<?php _e('More styles available in Pro Version of plugin.', 'wp-fb-reviews'); ?></p>
				</td>
			</tr>
			<tr class="wpfbr_row">
				<th scope="row">
					<?php _e('Number of Reviews:', 'wp-fb-reviews'); ?>
				</th>
				<td><div class="divtemplatestyles">
					<label for="wpfbr_t_display_num"><?php _e('How many per a row?', 'wp-fb-reviews'); ?></label>
					<select name="wpfbr_t_display_num" id="wpfbr_t_display_num">
					  <option value="1" <?php if($currenttemplate->display_num==1){echo "selected";} ?>>1</option>
					  <option value="2" <?php if($currenttemplate->display_num==2){echo "selected";} ?>>2</option>
					  <option value="3" <?php if($currenttemplate->display_num==3 || $currenttemplate->display_num==""){echo "selected";} ?>>3</option>
					  <option value="4" <?php if($currenttemplate->display_num==4){echo "selected";} ?>>4</option>
					</select>
					
					<label for="wpfbr_t_display_num_rows"><?php _e('How many total rows?', 'wp-fb-reviews'); ?></label>
					<input id="wpfbr_t_display_num_rows" type="number" name="wpfbr_t_display_num_rows" placeholder="" value="<?php if($currenttemplate->display_num_rows>0){echo $currenttemplate->display_num_rows;} else {echo "1";}?>">
					
					</div>
					<p class="description">
					<?php _e('How many reviews to display on the page at a time. Widget style templates can only display 1 per row.', 'wp-fb-reviews'); ?></p>
				</td>
			</tr>
			
			<tr class="wpfbr_row">
				<th scope="row">
					<?php _e('Display Order:', 'wp-fb-reviews'); ?>
				</th>
				<td>
					<select name="wpfbr_t_display_order" id="wpfbr_t_display_order">
						<option value="random" <?php if($currenttemplate->display_order=="random"){echo "selected";} ?>><?php _e('Random', 'wp-fb-reviews'); ?></option>
						<option value="newest" <?php if($currenttemplate->display_order=="newest"){echo "selected";} ?>><?php _e('Newest', 'wp-fb-reviews'); ?></option>
					</select>
					<p class="description">
					<?php _e('The order in which the reviews are displayed.', 'wp-fb-reviews'); ?></p>
				</td>
			</tr>
			<tr class="wpfbr_row">
				<th scope="row">
					<?php _e('Hide Reviews Without Text:', 'wp-fb-reviews'); ?>
				</th>
				<td>
					<select name="wpfbr_t_hidenotext" id="wpfbr_t_hidenotext">
						<option value="yes" <?php if($currenttemplate->hide_no_text=="yes"){echo "selected";} ?>><?php _e('Yes', 'wp-fb-reviews'); ?></option>
						<option value="no" <?php if($currenttemplate->hide_no_text=="no"){echo "selected";} ?>><?php _e('No', 'wp-fb-reviews'); ?></option>
					</select>
					<p class="description">
					<?php _e('Set to Yes and only display reviews that have text included.', 'wp-fb-reviews'); ?></p>
				</td>
			</tr>
			<tr class="wpfbr_row">
				<th scope="row">
					<?php _e('Custom CSS:', 'wp-fb-reviews'); ?>
				</th>
				<td>
					<textarea name="wpfbr_template_css" id="wpfbr_template_css" cols="50" rows="4"><?php echo $currenttemplate->template_css; ?></textarea>
					<p class="description">
					<?php _e('Enter custom CSS code to change the look of the template when being displayed.</br>ex: .wpfbr_t1_outer_div {
						background: #e4e4e4;
					}', 'wp-fb-reviews'); ?></p>
				</td>
			</tr>
			<tr class="wpfbr_row">
				<th scope="row">
					<?php _e('Create Slider:', 'wp-fb-reviews'); ?>
				</th>
				<td>
					<div class="divtemplatestyles">
						<label for="wpfbr_t_createslider"><?php _e('Display reviews in slider?', 'wp-fb-reviews'); ?></label>
						<select name="wpfbr_t_createslider" id="wpfbr_t_createslider">
							<option value="no" <?php if($currenttemplate->createslider=="no"){echo "selected";} ?>><?php _e('No', 'wp-fb-reviews'); ?></option>
							<option value="yes" <?php if($currenttemplate->createslider=="yes"){echo "selected";} ?>><?php _e('Yes', 'wp-fb-reviews'); ?></option>
						</select>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label for="wpfbr_t_display_num_rows"><?php _e('How many total slides?', 'wp-fb-reviews'); ?></label>
						<select name="wpfbr_t_numslides" id="wpfbr_t_numslides">
							<option value="2" <?php if($currenttemplate->numslides=="2"){echo "selected";} ?>>2</option>
							<option value="3" <?php if($currenttemplate->numslides=="3"){echo "selected";} ?>>3</option>
							<option value="4" <?php if($currenttemplate->numslides=="4"){echo "selected";} ?>>4</option>
							<option value="5" <?php if($currenttemplate->numslides=="5"){echo "selected";} ?>>5</option>
							<option value="6" <?php if($currenttemplate->numslides=="6"){echo "selected";} ?>>6</option>
							<option value="7" <?php if($currenttemplate->numslides=="7"){echo "selected";} ?>>7</option>
							<option value="8" <?php if($currenttemplate->numslides=="8"){echo "selected";} ?>>8</option>
							<option value="9" <?php if($currenttemplate->numslides=="9"){echo "selected";} ?>>9</option>
							<option value="10" <?php if($currenttemplate->numslides=="10"){echo "selected";} ?>>10</option>
						</select>
					
					</div>
					<p class="description">
					<?php _e('Allows you to create a slide show with your reviews.', 'wp-fb-reviews'); ?></p>
				</td>
			</tr>
<?php
if($this->_token=="wp-fb-reviews"){
?>
			<tr>
				<td class="notice updated " colspan="2" style="border-left: 4px solid #d6d6d6;">
					<p><strong><?php _e('Upgrade to the Pro Version of this plugin to access more super cool settings! <a href="'.plugin_dir_url( __FILE__ ) . 'pro_settings.png" target="_blank">(Screenshot)</a> Get the Pro Version <a href="?page=wp_fb-get_pro">here</a>!', 'wp-fb-reviews'); ?></strong></p>
				</td>
			</tr>
<?php
}
?>	
			<tr class="wpfbr_row">
				<th scope="row">
					<?php _e('Slider Settings:', 'wp-fb-reviews'); ?>
				</th>
				<td>
					<div class="w3_wprs-row slidersettingsdivs">
						  <div class="w3_wprs-col s4 slidersettingsdivtoprow"><?php _e('Autoplay Slides:', 'wp-fb-reviews'); ?></div>
						  <div class="w3_wprs-col s8">
							<input type="radio" name="wpfbr_sliderautoplay" id="wpfbr_sliderautoplay1-radio" value="no" checked="checked">
							<label for="wpfbr_sliderautoplay1-radio"><?php _e('No', 'wp-fb-reviews'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" name="wpfbr_sliderautoplay" id="wpfbr_sliderautoplay2-radio" value="yes" <?php if($currenttemplate->sliderautoplay== "yes"){echo 'checked="checked"';}?>>
							<label for="wpfbr_sliderautoplay2-radio"><?php _e('Yes', 'wp-fb-reviews'); ?></label>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<label for="wpfbr_t_createslider"><?php _e('Time delay between slides:', 'wp-fb-reviews'); ?></label>
							<select name="wpfbr_t_sliderdelay" id="wpfbr_t_sliderdelay">
								<option value="3" <?php if($currenttemplate->sliderdelay=="3"){echo "selected";} ?>><?php _e('3 sec', 'wp-fb-reviews'); ?></option>
								<option value="5" <?php if($currenttemplate->sliderdelay=="5"){echo "selected";} ?>><?php _e('5 sec', 'wp-fb-reviews'); ?></option>
								<option value="7" <?php if($currenttemplate->sliderdelay=="7"){echo "selected";} ?>><?php _e('7 sec', 'wp-fb-reviews'); ?></option>
								<option value="9" <?php if($currenttemplate->sliderdelay=="9"){echo "selected";} ?>><?php _e('9 sec', 'wp-fb-reviews'); ?></option>
								<option value="11" <?php if($currenttemplate->sliderdelay=="11"){echo "selected";} ?>><?php _e('11 sec', 'wp-fb-reviews'); ?></option>
								<option value="13" <?php if($currenttemplate->sliderdelay=="13"){echo "selected";} ?>><?php _e('13 sec', 'wp-fb-reviews'); ?></option>
								<option value="15" <?php if($currenttemplate->sliderdelay=="15"){echo "selected";} ?>><?php _e('15 sec', 'wp-fb-reviews'); ?></option>
							</select>
						</div>
						<div class="w3_wprs-col s4 slidersettingsdiv"><?php _e('Slide Animation:', 'wp-fb-reviews'); ?></div>
						<div class="w3_wprs-col s8 slidersettingsdiv">
							<input type="radio" name="wpfbr_sliderdirection" id="wpfbr_sliderdirection1-radio" value="horizontal" checked="checked">
							<label for="wpfbr_sliderdirection1-radio"><?php _e('Horizontal', 'wp-fb-reviews'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" name="wpfbr_sliderdirection" id="wpfbr_sliderdirection2-radio" value="vertical" <?php if($currenttemplate->sliderdirection== "vertical"){echo 'checked="checked"';}?>>
							<label for="wpfbr_sliderdirection2-radio"><?php _e('Vertical', 'wp-fb-reviews'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" name="wpfbr_sliderdirection" id="wpfbr_sliderdirection3-radio" value="fade" <?php if($currenttemplate->sliderdirection== "fade"){echo 'checked="checked"';}?>>
							<label for="wpfbr_sliderdirection3-radio"><?php _e('Fade', 'wp-fb-reviews'); ?></label>
						</div>
						<div class="w3_wprs-col s4 slidersettingsdiv"><?php _e('Show Navigation Arrows:', 'wp-fb-reviews'); ?></div>
						<div class="w3_wprs-col s8 slidersettingsdiv">
							<input type="radio" name="wpfbr_sliderarrows" id="wpfbr_sliderarrows1-radio" value="yes" checked="checked">
							<label for="wpfbr_sliderarrows1-radio"><?php _e('Yes', 'wp-fb-reviews'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" name="wpfbr_sliderarrows" id="wpfbr_sliderarrows2-radio" value="no" <?php if($currenttemplate->sliderarrows== "no"){echo 'checked="checked"';}?>>
							<label for="wpfbr_sliderarrows2-radio"><?php _e('No', 'wp-fb-reviews'); ?></label>
						</div>
						<div class="w3_wprs-col s4 slidersettingsdiv"><?php _e('Show Navigation Dots:', 'wp-fb-reviews'); ?></div>
						<div class="w3_wprs-col s8 slidersettingsdiv">
							<input type="radio" name="wpfbr_sliderdots" id="wpfbr_sliderdots1-radio" value="yes" checked="checked">
							<label for="wpfbr_sliderdots1-radio"><?php _e('Yes', 'wp-fb-reviews'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" name="wpfbr_sliderdots" id="wpfbr_sliderdots2-radio" value="no" <?php if($currenttemplate->sliderdots== "no"){echo 'checked="checked"';}?>>
							<label for="wpfbr_sliderdots2-radio"><?php _e('No', 'wp-fb-reviews'); ?></label>
						</div>
						<div class="w3_wprs-col s4 slidersettingsdiv"><?php _e('Change Height For Each Slide:', 'wp-fb-reviews'); ?></div>
						<div class="w3_wprs-col s8 slidersettingsdiv">
							<input type="radio" name="wpfbr_sliderheight" id="wpfbr_sliderheight2-radio" value="no" checked="checked">
							<label for="wpfbr_sliderheight2-radio"><?php _e('No', 'wp-fb-reviews'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" name="wpfbr_sliderheight" id="wpfbr_sliderheight1-radio" value="yes" <?php if($currenttemplate->sliderheight== "yes"){echo 'checked="checked"';}?>>
							<label for="wpfbr_sliderheight1-radio"><?php _e('Yes', 'wp-fb-reviews'); ?></label>
						</div>
					</div>
				</td>
			</tr>		
			<tr class="wpfbr_row">
				<th scope="row">
					<?php _e('Minimum Rating:', 'wp-fb-reviews'); ?>
				</th>
				<td>
					<select name="wpfbr_t_min_rating" id="wpfbr_t_min_rating">
					  <option value="1" <?php if($currenttemplate->min_rating==1){echo "selected";} ?>><?php _e('Show All', 'wp-fb-reviews'); ?></option>
					  <option value="2" <?php if($currenttemplate->min_rating==2){echo "selected";} ?>><?php _e('2 & Higher', 'wp-fb-reviews'); ?></option>
					  <option value="3" <?php if($currenttemplate->min_rating==3){echo "selected";} ?>><?php _e('3 & Higher', 'wp-fb-reviews'); ?></option>
					  <option value="4" <?php if($currenttemplate->min_rating==4){echo "selected";} ?>><?php _e('4 & Higher', 'wp-fb-reviews'); ?></option>
					  <option value="5" <?php if($currenttemplate->min_rating==5){echo "selected";} ?>><?php _e('Only 5 Star', 'wp-fb-reviews'); ?></option>
					</select>
					<p class="description">
					<?php _e('Show only reviews with at least this value rating. Allows you to hide low reviews.', 'wp-fb-reviews'); ?></p>
				</td>
			</tr>
			<tr class="wpfbr_row">
				<th scope="row">
					<?php _e('Filter By Word Count:', 'wp-fb-reviews'); ?>
				</th>
				<td><?php _e('Only show reviews between', 'wp-fb-reviews'); ?> <input id="wpfbr_t_min_words" type="number" name="wpfbr_t_min_words" placeholder="" value="<?php echo $currenttemplate->min_words; ?>" style="width: 3em"> <?php _e('minimum and', 'wp-fb-reviews'); ?> <input id="wpfbr_t_max_words" type="number" name="wpfbr_t_max_words" placeholder="" value="<?php echo $currenttemplate->max_words; ?>" style="width: 3em"> <?php _e('maximum words.', 'wp-fb-reviews'); ?>
					<p class="description">
					<?php _e('Leave blank to show all reviews. Allows you to filter out the reviews based on word count.', 'wp-fb-reviews'); ?></p>
				</td>
			</tr>
			<tr class="wpfbr_row">
				<th scope="row">
					<?php _e('Display Review Types:', 'wp-fb-reviews'); ?>
				</th>
				<td>
					<select name="wpfbr_t_rtype" id="wpfbr_t_rtype">
					  <option value="all" <?php if($currenttemplate->rtype=="all"){echo "selected";} ?>><?php _e('All Types', 'wp-fb-reviews'); ?></option>
					  <option value="fb" <?php if($currenttemplate->rtype=="fb"){echo "selected";} ?>><?php _e('Facebook', 'wp-fb-reviews'); ?></option>
					  <option value="manual" <?php if($currenttemplate->rtype=="manual"){echo "selected";} ?>><?php _e('Manually Input', 'wp-fb-reviews'); ?></option>
					</select>
					<p class="description">
					<?php _e('Show only reviews of this type.', 'wp-fb-reviews'); ?></p>
				</td>
			</tr>
			<tr class="wpfbr_row">
				<th scope="row">
					<?php _e('Display From Page:', 'wp-fb-reviews'); ?>
				</th>
				<td>
					<input id="wpfbr_t_rpage" type="text" name="wpfbr_t_rpage" placeholder="" value="<?php echo $currenttemplate->rpage; ?>">
					<p class="description">
					<?php _e('Enter Facebook Page IDs separated by commas for the reviews you want to show. Leave blank to show all reviews in your database. You can find Page IDs on the "Get FB Reviews" tab.', 'wp-fb-reviews'); ?></p>
				</td>
			</tr>
			<tr class="wpfbr_row">
				<th scope="row">
					<?php _e('Select Reviews To Show:', 'wp-fb-reviews'); ?>
				</th>
				<td>
					<input id="wpfbr_t_showreviewsbyid" type="hidden" name="wpfbr_t_showreviewsbyid" placeholder="" value="<?php echo $currenttemplate->showreviewsbyid; ?>"> <a id="wpfbr_btn_pickreviews" class="button dashicons-before dashicons-yes"><?php _e('Select Reviews', 'wp-fb-reviews'); ?></a>
					<p class="description">
					<?php _e('Allows you to individually pick which reviews to display in this template.', 'wp-fb-reviews'); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<?php 
	//security nonce
	wp_nonce_field( 'wpfbr_save_template');
	?>
	<input type="hidden" name="edittid" id="edittid"  value="<?php echo $currenttemplate->id; ?>">
	<input type="submit" name="wpfbr_submittemplatebtn" id="wpfbr_submittemplatebtn" class="button button-primary" value="<?php _e('Save Template', 'wp-fb-reviews'); ?>">
	<a id="wpfbr_addnewtemplate_cancel" class="button button-secondary"><?php _e('Cancel', 'wp-fb-reviews'); ?></a>
	</form>
</div>

	<div id="popup_review_list" class="popup-wrapper wpfbr_hide">
	  <div class="popup-content">
		<div class="popup-title">
		  <button type="button" class="popup-close">&times;</button>
		  <h3 id="popup_titletext"></h3>
		</div>
		<div class="popup-body">
		  <div id="popup_bobytext1"></div>
		  <div id="popup_bobytext2"></div>
		</div>
	  </div>
	</div>
</div>
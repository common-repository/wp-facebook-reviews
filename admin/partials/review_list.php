<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_FB_Reviews
 * @subpackage WP_FB_Reviews/admin/partials
 */
 
     // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
	$html="";
//db function variables
global $wpdb;
$table_name = $wpdb->prefix . 'wpfb_reviews';
$rowsperpage = 20;
?>
<div class="wrap" id="wp_fb-settings">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>
<?php 
include("tabmenu.php");
?>
<div class="wpfbr_margin10">
	<a id="wpfbr_helpicon" class="wpfbr_btnicononly button dashicons-before dashicons-editor-help"></a>
	<a id="wpfbr_removeallbtn" class="button dashicons-before dashicons-no"><?php _e('Remove All Reviews', 'wp-fb-reviews'); ?></a>
<p>
	<?php 
_e('Hide certain reviews, manually add reviews, save a CSV file of your reviews to your computer, and more features available in the <a href="?page=wp_fb-get_pro">Pro Version</a> of this plugin!', 'wp-fb-reviews'); 
?>
</p>
</div>
<?php 

	//remove all, first make sure they want to remove all
	if(isset($_GET['opt']) && $_GET['opt']=="delall"){
		$delete = $wpdb->query("TRUNCATE TABLE `".$table_name."`");
	}
	
	//pagenumber
	if(isset($_GET['pnum'])){
	$temppagenum = $_GET['pnum'];
	} else {
	$temppagenum ="";
	}
	if ( $temppagenum=="") {
		$pagenum = 1;
	} else if(is_numeric($temppagenum)){
		$pagenum = intval($temppagenum);
	}
	
	if(!isset($_GET['sortdir'])){
		$_GET['sortdir'] = "";
	}
	if ( $_GET['sortdir']=="" || $_GET['sortdir']=="DESC") {
		$sortdirection = "&sortdir=ASC";
	} else {
		$sortdirection = "&sortdir=DESC";
	}
	$currenturl = remove_query_arg( 'sortdir' );
	
	//make sure sortby is valid
	if(!isset($_GET['sortby'])){
		$_GET['sortby'] = "";
	}
	$allowed_keys = ['created_time_stamp', 'reviewer_name', 'rating', 'review_length', 'pagename', 'type' ];
	$checkorderby = sanitize_key($_GET['sortby']);
	
		if(in_array($checkorderby, $allowed_keys, true) && $_GET['sortby']!=""){
			$sorttable = $_GET['sortby']. " ";
		} else {
			$sorttable = "created_time_stamp ";
		}
		if($_GET['sortdir']=="ASC" || $_GET['sortdir']=="DESC"){
			$sortdir = $_GET['sortdir'];
		} else {
			$sortdir = "DESC";
		}
		unset($sorticoncolor);
		for ($x = 0; $x <= 10; $x++) {
			$sorticoncolor[$x]="";
		} 
		if($sorttable=="hide "){
			$sorticoncolor[0]="text_green";
		} else if($sorttable=="reviewer_name "){
			$sorticoncolor[1]="text_green";
		} else if($sorttable=="rating "){
			$sorticoncolor[2]="text_green";
		} else if($sorttable=="created_time_stamp "){
			$sorticoncolor[3]="text_green";
		} else if($sorttable=="review_length "){
			$sorticoncolor[4]="text_green";
		} else if($sorttable=="pagename "){
			$sorticoncolor[5]="text_green";
		} else if($sorttable=="type "){
			$sorticoncolor[6]="text_green";	
		}
		
		$html .= '
		<table class="wp-list-table widefat striped posts">
			<thead>
				<tr>
					<th scope="col" width="70px" class="manage-column"><a href="'.esc_url( add_query_arg( 'sortby', 'hide',$currenturl ) ).$sortdirection.'"><i class="dashicons dashicons-sort '.$sorticoncolor[0].'" aria-hidden="true"></i> '.__('Hide', 'wp-fb-reviews').'</a></th>
					<th scope="col" width="50px" class="manage-column">'.__('Pic', 'wp-fb-reviews').'</th>
					<th scope="col" style="min-width:70px" class="manage-column"><a href="'.esc_url( add_query_arg( 'sortby', 'reviewer_name',$currenturl ) ).$sortdirection.'"><i class="dashicons dashicons-sort '.$sorticoncolor[1].'" aria-hidden="true"></i> '.__('Name', 'wp-fb-reviews').'</a></th>
					<th scope="col" width="70px" class="manage-column"><a href="'.esc_url( add_query_arg( 'sortby', 'rating',$currenturl ) ).$sortdirection.'"><i class="dashicons dashicons-sort '.$sorticoncolor[2].'" aria-hidden="true"></i> '.__('Rating', 'wp-fb-reviews').'</a></th>
					<th scope="col" class="manage-column">'.__('Review Text', 'wp-fb-reviews').'</th>
					<th scope="col" width="100px" class="manage-column"><a href="'.esc_url( add_query_arg( 'sortby', 'created_time_stamp',$currenturl ) ).$sortdirection.'"><i class="dashicons dashicons-sort '.$sorticoncolor[3].'" aria-hidden="true"></i> '.__('Date', 'wp-fb-reviews').'</a></th>
					<th scope="col" width="70px" class="manage-column"><a href="'.esc_url( add_query_arg( 'sortby', 'review_length',$currenturl ) ).$sortdirection.'"><i class="dashicons dashicons-sort '.$sorticoncolor[4].'" aria-hidden="true"></i> '.__('Length', 'wp-fb-reviews').'</a></th>
					<th scope="col" width="100px" class="manage-column"><a href="'.esc_url( add_query_arg( 'sortby', 'pagename',$currenturl ) ).$sortdirection.'"><i class="dashicons dashicons-sort '.$sorticoncolor[5].'" aria-hidden="true"></i> '.__('Page Name', 'wp-fb-reviews').'</a></th>
					<th scope="col" width="100px" class="manage-column"><a href="'.esc_url( add_query_arg( 'sortby', 'type',$currenturl ) ).$sortdirection.'"><i class="dashicons dashicons-sort '.$sorticoncolor[6].'" aria-hidden="true"></i> '.__('Type', 'wp-fb-reviews').'</a></th>
				</tr>
				</thead>
			<tbody id="review_list">';
		//get reviews from db
		$lowlimit = ($pagenum - 1) * $rowsperpage;
		$tablelimit = $lowlimit.",".$rowsperpage;
		$reviewsrows = $wpdb->get_results(
			$wpdb->prepare("SELECT * FROM ".$table_name."
			WHERE id>%d
			ORDER BY ".$sorttable." ".$sortdir." 
			LIMIT ".$tablelimit." ", "0")
		);
		//total number of rows
		$reviewtotalcount = $wpdb->get_var( 'SELECT COUNT(*) FROM '.$table_name );
		//total pages
		$totalpages = ceil($reviewtotalcount/$rowsperpage);
		
		if($reviewtotalcount>0){
			foreach ( $reviewsrows as $reviewsrow ) 
			{
				if($reviewsrow->hide!="yes"){
					$hideicon = '<i class="dashicons dashicons-visibility text_green" aria-hidden="true"></i>';
				} else {
					$hideicon = '<i class="dashicons dashicons-hidden" aria-hidden="true"></i>';
				}
				$hideicon ='';
				//user image
				$userpic = '<img style="-webkit-user-select: none" src="http://graph.facebook.com/'.$reviewsrow->reviewer_id.'/picture?type=square">';
				//user profile link
				if($reviewsrow->type=="Facebook"){
					$profilelink = "http://facebook.com/".$reviewsrow->reviewer_id;
				}
				if($profilelink){
					$userpic = '<a href="'.$profilelink.'" target=_blank>'.$userpic.'</a>';
				}
	
				$html .= '<tr id="'.$reviewsrow->id.'">
						<th scope="col" class="wpfbr_upgrade_needed manage-column">'.$hideicon.'</th>
						<th scope="col" class="manage-column">'.$userpic.'</th>
						<th scope="col" class="manage-column">'.$reviewsrow->reviewer_name.'</th>
						<th scope="col" class="manage-column">'.$reviewsrow->rating.'</th>
						<th scope="col" class="manage-column">'.$reviewsrow->review_text.'</th>
						<th scope="col" class="manage-column">'.$reviewsrow->created_time.'</th>
						<th scope="col" class="manage-column">'.$reviewsrow->review_length.'</th>
						<th scope="col" class="manage-column">'.$reviewsrow->pagename.'</th>
						<th scope="col" class="manage-column">'.$reviewsrow->type.'</th>
					</tr>';
			}
		} else {
				$html .= '<tr>
						<th colspan="9" scope="col" class="manage-column">'.__('No reviews found. Please visit the <a href="?page=wp_fb-settings">Get FB Reviews</a> page to retrieve reviews from Facebook, or manually add one.', 'wp-fb-reviews').'</th>
					</tr>';
		}					
				
				
		$html .= '</tbody>
		</table>';
		
		//pagination bar
		$html .= '<div id="wpfb_review_list_pagination_bar">';
		$currenturl = remove_query_arg( 'pnum' );
		for ($x = 1; $x <= $totalpages; $x++) {
			if($x==$pagenum){$blue_grey = "blue_grey";} else {$blue_grey ="";}
			$html .= '<a href="'.esc_url( add_query_arg( 'pnum', $x,$currenturl ) ).'" class="button '.$blue_grey.'">'.$x.'</a>';
		} 
		
		$html .= '</div>';
				
		$html .= '</div>';		
 
 echo $html;
?>
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
	


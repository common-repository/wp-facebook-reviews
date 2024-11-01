<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_FB_Reviews
 * @subpackage WP_FB_Reviews/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_FB_Reviews
 * @subpackage WP_FB_Reviews/admin
 * @author     Your Name <email@example.com>
 */
class WP_FB_Reviews_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugintoken    The ID of this plugin.
	 */
	private $plugintoken;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugintoken       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugintoken, $version ) {

		$this->_token = $plugintoken;
		//$this->version = $version;
		//for testing==============
		$this->version = time();
		//===================

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_FB_Reviews_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_FB_Reviews_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		//only load for this plugin
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_fb-settings" || $_GET['page']=="wp_fb-reviews" || $_GET['page']=="wp_fb-templates_posts" || $_GET['page']=="wp_fb-get_pro"){
			wp_enqueue_style( $this->_token, plugin_dir_url( __FILE__ ) . 'css/wprev_admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->_token."_wprev_w3", plugin_dir_url( __FILE__ ) . 'css/wprev_w3.css', array(), $this->version, 'all' );
			
			}
			
			//load template styles for wp_pro-templates_posts page
			if($_GET['page']=="wp_fb-templates_posts"){
				//enque template styles for preview
				wp_enqueue_style( $this->_token."_style1", plugin_dir_url(dirname(__FILE__)) . 'public/css/wprev-public_template1.css', array(), $this->version, 'all' );

			}
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_FB_Reviews_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_FB_Reviews_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		

		//scripts for all pages in this plugin
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_fb-settings" || $_GET['page']=="wp_fb-reviews" || $_GET['page']=="wp_fb-templates_posts" || $_GET['page']=="wp_fb-get_pro"){
				//pop-up script
				wp_register_script( 'simple-popup-js',  plugin_dir_url( __FILE__ ) . 'js/wprev_simple-popup.min.js' , '', $this->version, false );
				wp_enqueue_script( 'simple-popup-js' );
			}
		}
		
		//scripts for get fb reviews page
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_fb-settings"){
				//admin js
				wp_enqueue_script( $this->_token, plugin_dir_url( __FILE__ ) . 'js/wprev_admin.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				wp_localize_script($this->_token, 'adminjs_script_vars', 
					array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring')
					)
				);
			}
		}
		
		
		//scripts for review list page
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_fb-reviews"){
				//admin js
				wp_enqueue_script('review_list_page-js', plugin_dir_url( __FILE__ ) . 'js/review_list_page.js', array( 'jquery' ), $this->version, false );
			}
			
			//scripts for templates posts page
			if($_GET['page']=="wp_fb-templates_posts"){
				//admin js
				wp_enqueue_script('templates_posts_page-js', plugin_dir_url( __FILE__ ) . 'js/templates_posts_page.js', array( 'jquery' ), $this->version, false );
				wp_localize_script('templates_posts_page-js', 'adminjs_script_vars', 
					array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
					'pluginsUrl' => wpfbrev_plugin_url
					)
				);
				//add color picker here
				wp_enqueue_style( 'wp-color-picker' );
				//enque alpha color add-on wprevpro-wp-color-picker-alpha.js
				wp_enqueue_script( 'wp-color-picker-alpha', plugin_dir_url( __FILE__ ) . 'js/wprevpro-wp-color-picker-alpha.js', array( 'wp-color-picker' ), '1.2.2', false );
			}
		}
		
	}
	
	public function add_menu_pages() {

		/**
		 * adds the menu pages to wordpress
		 */

		$page_title = 'WP FB Reviews : Get Facebook Reviews';
		$menu_title = 'WP FB Reviews';
		$capability = 'manage_options';
		$menu_slug = 'wp_fb-settings';
		
		add_menu_page($page_title, $menu_title, $capability, $menu_slug, array($this,'wp_fb_settings'),'dashicons-star-half');
		
		// We add this submenu page with the same slug as the parent to ensure we don't get duplicates
		$sub_menu_title = 'Get FB Reviews';
		add_submenu_page($menu_slug, $page_title, $sub_menu_title, $capability, $menu_slug, array($this,'wp_fb_settings'));
		
		// Now add the submenu page for the actual reviews list
		$submenu_page_title = 'WP FB Reviews : Reviews List';
		$submenu_title = 'Reviews List';
		$submenu_slug = 'wp_fb-reviews';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, array($this,'wp_fb_reviews'));
		
		// Now add the submenu page for the reviews templates
		$submenu_page_title = 'WP FB Reviews : Templates';
		$submenu_title = 'Templates';
		$submenu_slug = 'wp_fb-templates_posts';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, array($this,'wp_fb_templates_posts'));
		
		// Now add the submenu page for the reviews templates
		$submenu_page_title = 'WP FB Reviews : Upgrade';
		$submenu_title = 'Get Pro';
		$submenu_slug = 'wp_fb-get_pro';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, array($this,'wp_fb_getpro'));
	}
	
	public function wp_fb_settings() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/settings.php';
	}

	public function wp_fb_reviews() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/review_list.php';
	}
	
	public function wp_fb_templates_posts() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/templates_posts.php';
	}
	public function wp_fb_getpro() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/get_pro.php';
	}
	
	/**
	 * custom option and settings on settings page
	 */
	public function wpfbr_settings_init()
	{
		// register a new setting for "wp_fb-settings" page
		register_setting('wp_fb-settings', 'wpfbr_options');
	 
		// register a new section in the "wp_fb-settings" page
		add_settings_section(
			'wpfbr_section_developers',
			'',
			array($this,'wpfbr_section_developers_cb'),
			'wp_fb-settings'
		);
	 
		//register fb app id input field
		add_settings_field(
			'fb_app_ID', // as of WP 4.6 this value is used only internally
			'Facebook App ID',
			array($this,'wpfbr_field_fb_app_id_cb'),
			'wp_fb-settings',
			'wpfbr_section_developers',
			[
				'label_for'         => 'fb_app_ID',
				'class'             => 'wpfbr_row',
				'wpfbr_custom_data' => 'custom',
			]
		);
		//register get access token btn field
		add_settings_field(
			'fb_user_token_btn', // as of WP 4.6 this value is used only internally
			'Get Access Token',
			array($this,'wpfbr_gettoken_cb'),
			'wp_fb-settings',
			'wpfbr_section_developers',
			[
				'label_for'         => 'fb_user_token_btn',
				'class'             => 'wpfbr_row'
			]
		);
		//register fb Access Token input field
		add_settings_field(
			'fb_user_token_field_display', // as of WP 4.6 this value is used only internally
			'FB Access Token',
			array($this,'wpfbr_field_fb_access_cb'),
			'wp_fb-settings',
			'wpfbr_section_developers',
			[
				'label_for'         => 'fb_user_token_field_display',
				'class'             => 'wpfbr_row hide'
			]
		);
		
	}
	/**
	 * custom option and settings:
	 * callback functions
	 */
	 
	//==== developers section cb ====
	// section callbacks can accept an $args parameter, which is an array.
	// $args have the following keys defined: title, id, callback.
	// the values are defined at the add_settings_section() function.
	public function wpfbr_section_developers_cb($args)
	{
		//echos out at top of section
	}
	
	//==== field cb =====
	// field callbacks can accept an $args parameter, which is an array.
	// $args is defined at the add_settings_field() function.
	// wordpress has magic interaction with the following keys: label_for, class.
	// the "label_for" key value is used for the "for" attribute of the <label>.
	// the "class" key value is used for the "class" attribute of the <tr> containing the field.
	// you can add custom key value pairs to be used inside your callbacks.
	public function wpfbr_field_fb_app_id_cb($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wpfbr_options');

		// output the field
		?>
		<input id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['wpfbr_custom_data']); ?>" type="text" name="wpfbr_options[<?= esc_attr($args['label_for']); ?>]" placeholder="" value="<?php echo $options[$args['label_for']]; ?>">
		
		<p class="description">
			<?= esc_html__('Enter the Facebook App ID of your newly created app and click Save Settings. Look for the Facebook window that pops up, make sure it is not being blocked.', 'wp_fb-settings'); ?>
		</p>
		<?php
	}
	public function wpfbr_gettoken_cb($args)
	{
		?>
		<button id="<?= esc_attr($args['label_for']); ?>" type="button" class="btn_green">Get Authorization &amp; Pages</button>
		<p class="description">
			<?= esc_html__('Click to allow your newly created Facebook app to pull reviews from your Facebook Pages.', 'wp_fb-settings'); ?>
		</p>
		<?php
	}
	public function wpfbr_field_fb_access_cb($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wpfbr_options');

		// output the field
		?>
		<input id="<?= esc_attr($args['label_for']); ?>" type="text" name="wpfbr_options[<?= esc_attr($args['label_for']); ?>]" placeholder="" value="<?php echo $options[$args['label_for']]; ?>">
		
		<p class="description">
			<?= esc_html__('This gives the plugin authorization to pull your reviews from a Facebook page.', 'wp_fb-settings'); ?>
		</p>
		<?php
	}
	/**
	 * Store reviews in table, called from javascript file admin.js
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wpfb_process_ajax(){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
		
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		
		$postreviewarray = $_POST['postreviewarray'];
		
		//var_dump($postreviewarray);

		//loop through each one and insert in to db
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		
		$stats = array();
		
		foreach($postreviewarray as $item) { //foreach element in $arr
			$pageid = $item['pageid'];
			$pagename = $item['pagename'];
			$created_time = $item['created_time'];
			$created_time_stamp = strtotime($created_time);
			$reviewer_name = $item['reviewer_name'];
			$reviewer_id = $item['reviewer_id'];
			$rating = $item['rating'];
			$review_text = $item['review_text'];
			$review_length = str_word_count($review_text);
			$rtype = $item['type'];
			
			//check to see if row is in db already
			$checkrow = $wpdb->get_row( "SELECT id FROM ".$table_name." WHERE created_time = '$created_time'" );
			if ( null === $checkrow ) {
				$stats[] =array( 
						'pageid' => $pageid, 
						'pagename' => $pagename, 
						'created_time' => $created_time,
						'created_time_stamp' => strtotime($created_time),
						'reviewer_name' => $reviewer_name,
						'reviewer_id' => $reviewer_id,
						'rating' => $rating,
						'review_text' => $review_text,
						'hide' => '',
						'review_length' => $review_length,
						'type' => $rtype
					);
			}
		}
		$i = 0;
		$insertnum = 0;
		foreach ( $stats as $stat ){
			$insertnum = $wpdb->insert( $table_name, $stat );
			$i=$i + 1;
		}
	
		$insertid = $wpdb->insert_id;

		//header('Content-Type: application/json');
		echo $insertnum."-".$insertid."-".$i;

		die();
	}

	/**
	 * adds drop down menu of templates on post edit screen
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */	
	//add_action('media_buttons','add_sc_select',11);
	public function add_sc_select(){
		//get id's and names of templates that are post type 
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_post_templates';
		$currentforms = $wpdb->get_results("SELECT id, title, template_type FROM $table_name WHERE template_type = 'post'");
		if(count($currentforms)>0){
		echo '&nbsp;<select id="wprs_sc_select"><option value="select">Review Template</option>';
		foreach ( $currentforms as $currentform ){
			$shortcodes_list .= '<option value="[wprev_usetemplate tid=\''.$currentform->id.'\']">'.$currentform->title.'</option>';
		}
		 echo $shortcodes_list;
		 echo '</select>';
		}
	}
	//add_action('admin_head', 'button_js');
	public function button_js() {
			echo '<script type="text/javascript">
			jQuery(document).ready(function(){
			   jQuery("#wprs_sc_select").change(function() {
							if(jQuery("#wprs_sc_select :selected").val()!="select"){
							  send_to_editor(jQuery("#wprs_sc_select :selected").val());
							}
							  return false;
					});
			});
			</script>';
	}
	
	
	

}

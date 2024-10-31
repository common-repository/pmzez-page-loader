<?php
/**
 * @package pmzez-page-loader
 * @version 1.2
 */
/*
Plugin Name: PmZez-Page-Loader
Plugin URI: http://www.pmzez.com/plugins/pmzez-page-loader
Description: Slim progress bars for Ajax'y applications. Inspired by Google, YouTube, and Medium. Go to ***"Settings >> PmZez Page Loader"*** -on the bottom and you can change loader color.
Version: 1.2
Author: Rashedul Islam Sagor
Author URI: http://www.pmzez.com
*/

/*Plugin Path*/
define('PMZEZ_PAGE_LAZY_LOADER_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );

/* Adding Latest jQuery from Wordpress */
function pmzez_lazy_loader_latest_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'pmzez_lazy_loader_latest_jquery');

/* Adding plugin main-css active file -> below jQuery */
function pmzez_lazy_loader_main_css_install() {
	wp_enqueue_style('pmzez-page-lazy-loader-css-active', PMZEZ_PAGE_LAZY_LOADER_PATH.'css/nprogress.css');
}
add_action('init', 'pmzez_lazy_loader_main_css_install');

/* Adding plugin main jquery file -> below jQuery */
function pmzez_lazy_loader_main_js_install() {
	wp_enqueue_script('pmzez-page-lazy-loader-main-js-active', PMZEZ_PAGE_LAZY_LOADER_PATH.'js/nprogress.js', array('jquery'));
}
add_action('init', 'pmzez_lazy_loader_main_js_install');

/* Adding plugin javascript active file -> below jQuery */
function pmzez_lazy_loader_active_js_install() {
	wp_enqueue_script('pmzez-page-lazy-loader-js-active', PMZEZ_PAGE_LAZY_LOADER_PATH.'js/active.js', array('jquery'),1.0,true);
}
add_action('wp_footer', 'pmzez_lazy_loader_active_js_install');

//Option Panel Start
add_action('admin_menu', 'pmzez_lazy_loader_option_menu');
function pmzez_lazy_loader_option_menu(){
	add_options_page('Pmzez Color Loader Settings Option', 'PmZez Page Loader', 'manage_options', 'pmzez-page-loader.php', 'pmzez_page_loader_options_framwork');
}

//Default Value
$pmzez_lazy_loader_options = array(
	'all_color' => '#29d'
);


//Add Color Picker
add_action( 'admin_enqueue_scripts', 'wptuts_add_color_picker' );
function wptuts_add_color_picker( $hook ) {
 
    if( is_admin() ) { 
     
        // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' ); 
         
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( '/js/color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 
    }
}


if ( is_admin() ) :

	function pmzez_lazy_loader_register_settings(){
		register_setting('pmzez_p_options', 'pmzez_lazy_loader_options', 'pmzez_lazy_loader_validate_options');
	}
	add_action('admin_init', 'pmzez_lazy_loader_register_settings');

	function pmzez_page_loader_options_framwork(){
		global $pmzez_lazy_loader_options;
		if ( !isset($_REQUEST['updated']) )
			$_REQUEST['updated'] = false;?>
		
		<div class="warp">
			<h2>Pmzez Color Loader Settings Option</h2>
			<?php if (false !== $_REQUEST['updated']) : ?>
				<div class="updated fade"><p><strong><?php _e('Options Saved'); ?></strong></p></div>
			<?php endif; ?>
			
			<form method="POST" action="options.php">
			
				<?php $settings = get_option('pmzez_lazy_loader_options', $pmzez_lazy_loader_options); ?>
				<?php settings_fields('pmzez_p_options'); ?>
				
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for="all_color">Color Change Option</label></th>
						<td>
							<input id="all_color" type="text" name="pmzez_lazy_loader_options[all_color]" value="<?php echo stripslashes($settings['all_color']); ?>" class="color-field" />
							<p class="des">Insert color CODE(#cc0000) or color NAME(red)</p>
						</td>
					</tr>
				</table>
				<p class="submit"><input type="submit" class="button-primary" value="Save" /></p>
				
			</form>
		</div>
	<?php }
    function pmzez_lazy_loader_validate_options($input){
	global $pmzez_lazy_loader_options;
	$settings = get_option('pmzez_lazy_loader_options', $pmzez_lazy_loader_options);
	$input['all_color'] = wp_filter_post_kses($input['all_color']);	
	return $input;
}
endif;
function pmzez_lazy_loader_color_over_ride(){
	global $pmzez_lazy_loader_options; $pmzez_color_settings = get_option('pmzez_lazy_loader_options', $pmzez_lazy_loader_options);?>
<style type="text/css">
	#nprogress .bar {
	  background: <?php echo $pmzez_color_settings['all_color'];?>!important;
	}
	#nprogress .peg {
		box-shadow: 0 0 10px <?php echo $pmzez_color_settings['all_color'];?>, 0 0 5px <?php echo $pmzez_color_settings['all_color']; ?>!important;
	}
	#nprogress .spinner-icon {
		border-top-color: <?php echo $pmzez_color_settings['all_color'];?>!important;
		border-left-color: <?php echo $pmzez_color_settings['all_color'];?>!important;	
	}
	</style>
<?php }
add_action('wp_footer', 'pmzez_lazy_loader_color_over_ride'); ?>
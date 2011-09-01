<?php
/*
Plugin Name: Custom Post Type View Generator
Plugin URI:
Description:
Version: 0.1.3
Author: Marco Constâncio
Author URI: http://www.betasix.net
*/

if (!defined('WP_PLUGIN_DIR'))
	define('WP_PLUGIN_DIR','/');

if (!defined('CPVG_PLUGIN_NAME'))
    define('CPVG_PLUGIN_NAME', 'custom-post-view-generator');

if (!defined('CPVG_PLUGIN_DIR'))
    define('CPVG_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . CPVG_PLUGIN_NAME);

if (!defined('CPVG_PLUGIN_URL'))
    define('CPVG_PLUGIN_URL', WP_PLUGIN_URL . '/' . CPVG_PLUGIN_NAME . '/');

if (!defined('CPVG_TEMPLATE_DIR'))
    define('CPVG_TEMPLATE_DIR', CPVG_PLUGIN_DIR. '/templates');

if (!defined('CPVG_TEMPLATE_URL'))
    define('CPVG_TEMPLATE_URL', WP_PLUGIN_URL . '/' . CPVG_PLUGIN_NAME . '/templates');

if (!defined('CPVG_FIELDTYPES_DIR'))
    define('CPVG_FIELDTYPES_DIR', CPVG_PLUGIN_DIR. '/fieldtypes');

if (!defined('CPVG_PLUGINSCODE_DIR'))
    define('CPVG_PLUGINSCODE_DIR', CPVG_PLUGIN_DIR. '/pluginscode');

$types_options = cpvg_load_fieldtypes();

if (is_admin()){
	add_action('admin_menu', 'cpvg_menu_pages');

	wp_register_style( 'cpvg_style', CPVG_PLUGIN_URL . '/cpvg_style.css' );
	wp_enqueue_style('cpvg_style');

	wp_register_script( 'cpvg_functions', CPVG_PLUGIN_URL . '/cpvg_functions.js', false, null);
	wp_register_script( 'cpvg_flowplayer', CPVG_PLUGIN_URL . '/libs/flowplayer/flowplayer-3.2.6.min.js', false, null);
	wp_enqueue_script(array('jquery-ui-draggable','jquery-ui-droppable', 'jquery-ui-sortable','cpvg_functions','cpvg_flowplayer'));

	if (version_compare(PHP_VERSION, '5.3.0', '<')) {
		$encoded_type_options = json_encode($types_options);
	}else{
		$encoded_type_options = json_encode($types_options,JSON_HEX_TAG);
	}

	wp_localize_script( 'cpvg_functions', 'server_data', array('type_options'=>$encoded_type_options,'wpurl'=>get_bloginfo('wpurl')));

	add_action('wp_ajax_generate_preview', 'cpvg_generate_preview');
	add_action('wp_ajax_save_layout', 'cpvg_save_layout');
	add_action('wp_ajax_delete_layout', 'cpvg_delete_layout');
	add_action('wp_ajax_get_post_type_data', 'cpvg_get_post_type_data');
}else{
	wp_register_script( 'cpvg_flowplayer', CPVG_PLUGIN_URL . '/libs/flowplayer/flowplayer-3.2.6.min.js', false, null);
	wp_enqueue_script(array('cpvg_flowplayer'));

	add_filter('the_content', 'cpvg_process_page',-999);
}

function cpvg_menu_pages() {
    add_menu_page('CPT View Generator','CPT View Generator', 'manage_options','cpvg_topmenu','cpvg_settings');
    add_submenu_page('cpvg_topmenu','Post Views','Post Views','manage_options','cpvg_topmenu','cpvg_settings');
    add_submenu_page('cpvg_topmenu','Help','Help','manage_options','cpvg_help1','cpvg_help');
}

function cpvg_settings() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

	$exclude_list = array('content-type'=>'content-type');
	$post_types=array_diff_assoc(get_post_types(array('_builtin'=>false),'names'),$exclude_list);

	?>
	<div id='cpvg-wrap' class='wrap'>
		<div id='icon-edit-pages' class='icon32'><br></div><h2>Post Views</h2>
		<?php
		if(empty($post_types)){
			echo "No custom post types detected on this installation.";
		}else{
		?>
		<div id='cpvg-posttype-options'>
			<div>
				<label for='cpvg-posttype-select'>Post type:</label>
				<select name='cpvg-posttype-select' id='cpvg-posttype-select'>
					<option selected='selected' value=''></option>
					<?php
						foreach ($post_types  as $post_type ) {
							echo "<option value='".$post_type."' size='50'>".ucwords($post_type)."</option>\n";
						}
					?>
				</select>
			</div>
			<div>
				<label for='cpvg-template-select'>Template:</label>
				<select name='cpvg-template-select' id='cpvg-template-select'>
					<?php
						foreach(cpvg_load_templates("php") as $file=>$title){
							echo "<option value='".$file."' size='50'>".ucwords($title)."</option>\n";
						}
					?>
				</select>
			</div>
			<div id='action-buttons'>
				<input class='button-secondary action' type='button' id='cpvg-preview' value='Generate Preview'>
				<input class='button-secondary action' id='cpvg-save-layout' type='button' value='Save layout'>
				<input class='button-secondary action' id='cpvg-delete-layout' type='button' value='Delete Selected layout'>
				<span id='action-message'></span>

			</div>
		</div>

		<div id='cpvg-posttype-view'>
			Drag the desired fields here:
			<ol id='cpvg-fieldlist'></ol>
		</div>

		<div id='cpvg-posttype-fields'>
			Available fields:
			<div id='cpvg-posttype-fields-content'>
				<?php
					foreach ($post_types  as $post_type ) {
						echo "<div id='".$post_type."-fieldgroup' class='cpvg-posttype-fieldgroup' style='display:none;'>\n";
							$custom_fields_data = cpvg_get_customfields($post_type);
							if(!empty($custom_fields_data)){
								foreach($custom_fields_data  as $field_id=>$field_name){
									if(!empty($field_name)){
										echo "<div id='$field_id' class='cpvg-field-draggable cpvg-post-type-field'>$field_name</div>\n";
									}
								}
							}

							if(post_type_supports($post_type,'editor')){
								echo "<div class='cpvg-field-draggable cpvg-post-type-editor'>Editor content</div>\n";
							}
						echo "</div>\n";
					}
				?>
				<div class='clear'></div>
			</div>
		</div>

		<div id='cpvg-posttype-preview'>Preview<div id='cpvg-posttype-preview-content'></div></div>
		<div class='clear'></div>

		<?php } ?>
	</div>
	<?php
}

/**************************** LOAD NECESSARY CLASSES ************************************************/
function cpvg_load_fieldtypes(){
	$types_options = array();
	$find_strings = array();
	$replace_strings = array();
	$files = array();
	if ($handle = opendir(CPVG_FIELDTYPES_DIR)) {
		while (false !== ($file = readdir($handle))) {
			if(end(explode(".", $file)) == "php") {
				$files[] = preg_replace("/\\.[^.\\s]{3,4}$/", "", str_replace($find_strings,$replace_strings,$file));
			}
		}
		closedir($handle);
	}

	asort($files);

	foreach($files as $fieldtype_name){
		include_once CPVG_FIELDTYPES_DIR."/".$fieldtype_name.".php";
		$fieldtype_object = new $fieldtype_name();
		$types_options = array_merge($types_options,$fieldtype_object->adminProperties());
	}

	return $types_options;
}

function cpvg_load_pluginscode(){
	$pluginfiles_data = array();
	$find_strings = array();
	$replace_strings = array();
	$files = array();

	if ($handle = opendir(CPVG_PLUGINSCODE_DIR)) {
		while (false !== ($file = readdir($handle))) {
			if(end(explode(".", $file)) == "php") {
				$files[] = preg_replace("/\\.[^.\\s]{3,4}$/", "", str_replace($find_strings,$replace_strings,$file));
			}
		}
		closedir($handle);
	}

	return $files;
}

function cpvg_load_templates($file_type="php"){
	$template_files = array();
	$find_strings = array("-");
	$replace_strings = array(" ");

	if ($handle = opendir(CPVG_TEMPLATE_DIR)) {
		while (false !== ($file = readdir($handle))) {
			if(end(explode(".", $file)) == $file_type) {
				$template_files[preg_replace("/\\.[^.\\s]{3,4}$/", "", $file)] = preg_replace("/\\.[^.\\s]{3,4}$/", "", str_replace($find_strings,$replace_strings,$file));
			}
		}
		closedir($handle);
	}
	asort($template_files);

	return $template_files;
}

/*********************************** DATA GETTERS *************************************************************/


function cpvg_get_customfields($custom_post_type){
	$custom_fields_data = array();
	$pluginfiles = cpvg_load_pluginscode();

	foreach($pluginfiles as $pluginfile_name){
		include_once CPVG_PLUGINSCODE_DIR."/".$pluginfile_name.".php";

		$pluginfile_object = new $pluginfile_name();
		if ($pluginfile_object->isEnabled()) {
			return $pluginfile_object->getCustomfields($custom_post_type);
		}
	}

	return $custom_fields_data;
}

function cpvg_help() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

	$readme = file_get_contents(CPVG_PLUGIN_DIR.'/readme.txt');
	$readme = make_clickable(nl2br(esc_html($readme)));


	$faq_info = "== Frequently Asked Questions ==".cpvg_get_between($readme,'== Frequently Asked Questions ==','== Fields Info ==');
	$usage_info = "== Instructions ==".cpvg_get_between($readme,'= Instructions =','== Screenshots ==');
	$fields_info = "== Fields Info ==".cpvg_get_between($readme,'== Fields Info ==','== Changelog ==');
	$usage_info = str_replace(" in **Other Notes** page","",$readme);

	$readme = $usage_info.$fields_info.$faq_info;
	//Parses Markdown
	$readme = preg_replace('/`(.*?)`/', '<code>\\1</code>', $readme);

	$readme = preg_replace('/[\040]\*\*(.*?)\*\*/', ' <strong>\\1</strong>', $readme);
	$readme = preg_replace('/[\040]\*(.*?)\*/', ' <em>\\1</em>', $readme);

	$readme = preg_replace('/=== (.*?) ===/', '<h2>\\1</h2>', $readme);
	$readme = preg_replace('/== (.*?) ==/', '<h3>\\1</h3>', $readme);
	$readme = preg_replace('/= (.*?) =/', '<h4>\\1</h4>', $readme);

	//Fixes a few formating issues
	$readme = str_replace("<br />\n<br />", "", $readme);
	$readme = str_replace('PLUGINS:', 'PLUGINS:<br>', $readme);
	$readme = str_replace('USAGE:', '<br /><br />USAGE:<br />', $readme);
	$readme = preg_replace('/\* /', '- ', $readme);
	$readme = str_replace("1. ", '- ', $readme);

	echo $readme;
    //Render the HTML for the Help page or include a file that does
}


/*********************************** HTML/DATA PROCESSING ***********************************/
function cpvg_process_page(){
	global $table_prefix,$wpdb, $post;

	$wp_cpvg_table = $table_prefix . "cpvg";

	$custom_post_type_options = $wpdb->get_var("SELECT custom_post_type_options
											    FROM $wp_cpvg_table
											    WHERE custom_post_type_name = '$post->post_type'");

	if(empty($custom_post_type_options)){
		return $post->post_content;
	}else{
		$data = json_decode($custom_post_type_options,true);
		$data['field_data'] = get_post_custom($post->ID);
		$data['post_content'] = $post->post_content;

		$pluginfiles = cpvg_load_pluginscode();

		foreach($pluginfiles as $pluginfile_name){
			include_once CPVG_PLUGINSCODE_DIR."/".$pluginfile_name.".php";

			$pluginfile_object = new $pluginfile_name();
			if ($pluginfile_object->isEnabled()) {
				$data = $pluginfile_object->processPageAdditionalCode($post->post_type,$data);
			}
		}

		return cpvg_generate_html($data);
	}
}

function cpvg_generate_html($data=null){
	if(isset($data['template']) && isset($data['fields'])){
		$template = $data['template'];
		$fields = $data['fields'];
	}else{
		$template = CPVG_TEMPLATE_DIR.'/'.$_POST['template'].".php";
		$fields = $_POST['fields'];
	}

	$html = '';
	$record_data = array();
	if(!empty($fields)){
		foreach($fields as $field_data){
			$field = array();
			$additional_data = array();
			$output_options = array();

			if($field_data['name'] == "Content Editor"){
				$field_content = $data['post_content'];
			}else{
				for($i=1;$i<5;$i++){
					if(isset($field_data["options".$i])){
						$output_options[$i] = $field_data["options".$i];
					}
				}
				if(isset($data['field_data'])){
					$field_content = $data['field_data'][cpvg_sanitize_title_with_underscores($field_data['name'])][0];
				}else{
					$field_content = 'NOT_SET';
				}

				if(isset($field_data['additional_data'])){
					$additional_data = $field_data['additional_data'];
				}
			}

			if($field_data['type'] == "content-editor"){
				if(is_null($field_content)){
					$field['value'] = cpvg_random_text_value();
				}else{
					$field['value'] = $field_content;
				}
			}else{
				if(class_exists($field_data['type'])){
					$fieldtype_object = new $field_data['type'];
					$field['value'] = $fieldtype_object->processValue($field_content,$output_options,$additional_data);
				}else{
					$field['value'] = $field_content;
				}
			}

			$field = array_merge($field,$field_data);
			$record_data[]=$field;
		}
	}

	ob_start();
	if(file_exists($template)){
		require_once $template;
	}else{
		//DISPLAYS DATA EVEN IF NO TEMPLATE WAS SELECTED
		foreach($record_data as $record){
			echo "<b>".$record['name']."</b>: ".$record['value']."<br/>";
		}

	}
	$html = ob_get_contents();
	ob_end_clean();

	return $html;
}

function cpvg_generate_preview(){
	print cpvg_generate_html();
	exit;
}


/***************************************************** ADMIN ACTIONS ******************************************************/
function cpvg_save_layout(){
	if(isset($_POST['template']) && isset($_POST['fields']) && isset($_POST['post-type'])){
		if(!empty($_POST['post-type'])){
			$layout_data = array();
			$layout_data['template_file'] = $_POST['template'];

			if(!empty($_POST['template'])){	$_POST['template'] = CPVG_TEMPLATE_DIR.'/'.$_POST['template'].".php"; }
			$layout_data['template'] = $_POST['template'];

			$layout_data['fields'] = $_POST['fields'];
			check_database();

			global $table_prefix,$wpdb;
			$wp_cpvg_table = $table_prefix . "cpvg";

			$cpvg_id = $wpdb->get_var("SELECT id FROM $wp_cpvg_table WHERE custom_post_type_name = '".$_POST['post-type']."'");

			if($cpvg_id){
				$rows_affected = $wpdb->update($wp_cpvg_table,array('custom_post_type_options' => json_encode($layout_data)),
															 array('id'=>$cpvg_id));
				print "Layout Updated.";
			}else{
				$rows_affected = $wpdb->insert( $wp_cpvg_table, array('custom_post_type_name' => $_POST['post-type'],
																	 'custom_post_type_options' => json_encode($layout_data)));
				print "Layout Saved.";
			}
		}else{
			print "No Post Type Was Selected.";
		}
	}
	exit;
}

function cpvg_delete_layout(){
	if(isset($_POST['post-type'])){
		if(!empty($_POST['post-type'])){
			global $table_prefix,$wpdb;
			$wp_cpvg_table = $table_prefix . "cpvg";
			$result = $wpdb->query("DELETE FROM ".$wp_cpvg_table." WHERE custom_post_type_name = '".$_POST['post-type']."'");
			if($result){
				print "Layout Deleted.";
			}else{
				print "ERROR: Layout no deleted.";
			}
		}else{
			print "No Post Type Was Selected.";
		}
	}
	exit;
}

/****************************************** MISC **************************************************/
function check_database(){
	global $table_prefix,$wpdb;

	$wp_cpvg_table = $table_prefix . "cpvg";

	if($wpdb->get_var("show tables like '$wp_cpvg_table'") != $wp_cpvg_table) {
		$sql0  = "CREATE TABLE `". $wp_cpvg_table . "` ( ";
		$sql0 .= "  `id`       					int(11)      NOT NULL auto_increment,";
		$sql0 .= "  `custom_post_type_name` 	varchar(255) NOT NULL default '', ";
		$sql0 .= "  `custom_post_type_options`  text         NOT NULL default '', ";
		$sql0 .= "  UNIQUE KEY `id` (`id`) ";
		$sql0 .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ; ";

		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		dbDelta($sql0);
	}
}

function cpvg_get_post_type_data(){
	global $table_prefix,$wpdb;

	if(isset($_POST['post-type'])){
		$wp_cpvg_table = $table_prefix."cpvg";
		$custom_post_type_options = $wpdb->get_var("SELECT custom_post_type_options FROM $wp_cpvg_table WHERE custom_post_type_name = '".$_POST['post-type']."'");
		print $custom_post_type_options;
	}
}

#
#  UTIL FUNCTIONS
#
function cpvg_random_text_value(){
	$codelenght = 8;
	while($newcode_length < $codelenght) {
		$x=1; $y=4;
		$part = rand($x,$y);

		if($part==1){$a=48;$b=57;}  // Numbers
		if($part==2){$a=65;$b=90;}  // UpperCase
		if($part==3){$a=97;$b=122;} // LowerCase
		//if($part==4){$a=32;$b=31;} // Space

		$code_part=chr(rand($a,$b));
		$newcode_length = $newcode_length + 1;
		$newcode = $newcode.$code_part;
	}
	return $newcode;
}

function cpvg_object_to_array($object){
	if(!is_object($object) && !is_array($object)){
		return $object;
	}

	if(is_object($object)){
		$object = get_object_vars( $object );
	}

	return array_map('objectToArray',$object);
}

function cpvg_sanitize_title_with_underscores($string){
	return str_replace("-","_",sanitize_title_with_dashes($string));
}

function cpvg_get_between($input, $start, $end){
  $substr = substr($input, strlen($start)+strpos($input, $start), (strlen($input) - strpos($input, $end))*(-1));
  return $substr;
}

?>
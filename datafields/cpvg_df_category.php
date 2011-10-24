<?php
class cpvg_df_category{
	public function adminProperties() {
		/* Available fields:
		'term_id', 'name', 'slug', 'term_group', 'term_taxonomy_id', 'taxonomy', 'description',
		'parent', 'count', 'cat_ID', 'category_count', 'category_description',
		'cat_name', 'category_nicename', 'category_parent'
		*/
		return array('category' => array('cat_ID'=>'ID', 'cat_name'=>'Name' , 'category_nicename'=>'Nicename',
										 'term_id'=>'Term ID' , 'term_group'=>'Term Groups' , 'parent'=>'Parent Id',
										 'count'=>'Post Count' , 'category_description'=>'Description'));
    }

	public function getValue($field_name,$post_data) {
		$result = array();
		$category_data = array();

		//if($post_data->post_type == 'post' || $post_data->post_type == 'page'){
			$category_data = wp_get_post_categories($post_data->ID);
		/*}else{
			$pluginfiles = cpvg_get_pluginscode_files();

			foreach($pluginfiles as $pluginfile_name){
				include_once CPVG_PLUGINSCODE_DIR."/".$pluginfile_name.".php";
				$pluginfile_object = new $pluginfile_name();

				if ($pluginfile_object->isEnabled()) {
					$category_data = $pluginfile_object->getCategories($post_data);
				}
			}
		}*/
		if(is_array($category_data)){
			foreach($category_data as $c){
				$cat = get_category( $c );
				$result[] = $cat->$field_name;
			}
		}

		return serialize($result);
    }
}
?>
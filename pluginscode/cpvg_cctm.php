<?php
class cpvg_cctm{

    public function isEnabled(){
		return in_array("custom-content-type-manager/index.php",get_option("active_plugins"));
    }

	public function getCustomfields($custom_post_type){
		$custom_fields_data = array();
		$custom_post_data=get_post_types(array('_builtin'=>false),'object');

		if(!empty($custom_post_data)){
			$custom_fields = $custom_post_data[$custom_post_type]->custom_fields;
			$custom_fields_data = array();
			if(isset($custom_fields) && is_array($custom_fields)){
				array_walk($custom_fields, create_function('$val, $key, $obj', '$obj[$val["name"]] = $val["label"];'), &$custom_fields_data);
			}
		}
		return $custom_fields_data;
	}

	public function processPageAdditionalCode($singular_type_name, $data){
		return $data;
	}
}
?>
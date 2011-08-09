<?php
class cpvg_text{

    public function adminProperties() {
		return array('cpvg_text' => array('label'=>'Text'));
    }

    public function processValue($value='NOT_SET',$output_options='',$additional_data) {
		if($value=='NOT_SET'){
			return cpvg_random_text_value();
		}else{
			//REQUIRED CODE TO DELIVER NON SANATIZED VALUES SAVED BY THE Content Types plugin by iambriansreed (WHEN USED)
			if(isset($additional_data['content_types_plugin_data'][$value])){
				return $additional_data['content_types_plugin_data'][$value];
			}
			return $value;
		}
	}
}
?>
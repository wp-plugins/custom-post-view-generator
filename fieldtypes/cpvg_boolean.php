<?php
class cpvg_boolean{

    public function adminProperties() {
		$output_options1 = array('true_false'=>'True/False','yes_no'=>'Yes/No');

		return array('cpvg_boolean' => array('label'=>'Boolean',
											 'options' => array($output_options1)));
    }

    public function processValue($value='NOT_SET',$output_options='',$additional_data) {
		if($value=='NOT_SET'){
			$value = rand(0,1);
		}

		switch($output_options[1]){
			case 'yes_no':
				if((bool) $value){
					return "Yes";
				}else{
					return "No";
				}
			default:
				if((bool) $value){
					return "True";
				}else{
					return "False";
				}
		}
	}
}
?>
<style type="text/css">
	.cpvg-table{
		margin-top:10px;
		border: 1px solid #a6a6a6;
		border-collapse: collapse;
	}
	.cpvg-table td {
		border: 1px solid #a6a6a6;
		padding: 2px;
	}
	.cpvg-table th {
		border: 1px solid #E5E5E5;
		background-color: #a6a6a6;
	}
	.cpvg-table td ul {
		/*padding:0px;
		margin:0px 0px 0px 20px;*/
	}
</style>
<?php
	$processed_data = array();
	$output = "";
	foreach($record_data as $record){
		if(!isset($record['label'])){
			//if there is no label then it is a heading or horizontal line or a similar element
			//this finishes the table and prints the element					
			if(!empty($processed_data)){
				$output.="<table class='cpvg-table'>".implode("",$processed_data)."</table>";
				$processed_data = array();
			}
			$output.=$record['value'];
		}else{
			$processed_data[]="<tr><th>".$record['label']."</th><td>".$record['value']."</td>";
		}
	}
	
	if(!empty($processed_data)){
		$output.="<table class='cpvg-table'>".implode("",$processed_data)."</table>";
		$processed_data = array();
	}		
	echo $output;
?>

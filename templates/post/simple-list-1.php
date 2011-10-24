<style type="text/css">
	.cpvg-field-name{ font-weight:bold; }
	.cpvg-field-value{ }
	.cpvg-field-value ul {
		/*padding:0px;
		margin:0px 0px 0px 20px;*/
	}
</style>
<?php
	foreach($record_data as $record){
		echo "<span class='cpvg-field-name'>".$record['label']."</span>: <span class='cpvg-field-value'>".$record['value']."</span><br/>";
	}
?>
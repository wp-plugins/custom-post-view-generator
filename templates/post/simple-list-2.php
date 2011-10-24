<style type="text/css">
	.cpvg-field-name{ font-weight:bold; }
	.cpvg-field-value{ }
	.cpvg-field{ margin-top:10px; margin-bottom:10px; }
	.cpvg-field-value ul {
		/*padding:0px;
		margin:0px 0px 0px 20px;	*/
	}
</style>
<?php
	foreach($record_data as $record){
		echo "<div class='cpvg-field'>";
		echo "<span class='cpvg-field-name'>".$record['label']."</span>: <span class='cpvg-field-value'>".$record['value']."</span><br/>";
		echo "</div>";
	}
?>
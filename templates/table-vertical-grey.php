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
<table class='cpvg-table'>
	<?php
		foreach($record_data as $record){
		cpvg_content_types_plugin_parse	echo "<tr><th>".$record['label']."</th><td>".$record['value']."</td>";
		}
	?>
</table>
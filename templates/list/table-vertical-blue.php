<style type="text/css">
	.cpvg-table{
		margin-top:10px;
		border: 1px solid #8CACBB;
		border-collapse: collapse;
	}

	.cpvg-table td {
		border: 1px solid #8CACBB;
		padding: 2px;
	}
	.cpvg-table th {
		border: 1px solid #8CACBB;
		background-color: #DEE7EC;
	}
	.cpvg-table td ul {
		/*padding:0px;
		margin:0px 0px 0px 20px;*/
	}
	.cpvg-table .cpvg-table-blank{
		border-left: 1px solid #FFFFFF;
		border-right: 1px solid #FFFFFF;
	}

</style>
<table class='cpvg-table'>
	<?php
		$num_records = count($records_data);
		foreach($records_data as $record_index => $record_data){
			foreach($record_data as $record){
				echo "<tr><th>".$record['label']."</th><td>".$record['value']."</td>";
			}
			if(($num_records-1) != $record_index){ //DON'T PRINT THE LAST TABLE LINE
				echo "<tr><td colspan='2' class='cpvg-table-blank'></td></tr>";
			}
		}
	?>
</table>
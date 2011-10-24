<style type="text/css">
	.cpvg-table{
		margin-top:10px;
		border: 1px solid #8CACBB;
		border-collapse: collapse;
	}

	.cpvg-table td {
		border: 1px solid #DEE7EC;
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
</style>
<table class='cpvg-table'>
	<?php
		echo "<tr>";
		foreach($record_data as $record){
			echo "<th>".$record['label']."</th>";
		}
		echo "</tr><tr>";
		foreach($record_data as $record){
			echo "<td>".$record['value']."</td>";
		}
		echo "</tr>";
	?>
</table>
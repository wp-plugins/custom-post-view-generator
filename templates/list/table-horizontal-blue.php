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
		if(isset($records_data[0])){
			echo "<tr>";
			foreach($records_data[0] as $record){
				echo "<th>".$record['label']."</th>";
			}
			echo "</tr>";

			foreach($records_data as $record_index => $record_data){
				echo "<tr>";
				foreach($record_data as $record){
					echo "<td>".$record['value']."</td>";
				}
				echo "</tr>";
			}
		}
	?>
</table>
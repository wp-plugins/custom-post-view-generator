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
</style>
<table class='cpvg-table'>
	<?php
		foreach($record_data as $record){
			echo "<tr><th>".$record['label']."</th><td>".$record['value']."</td>";
		}
	?>
</table>
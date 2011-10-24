<style type="text/css">
.cpvg-record-div{
	padding:5px;
	margin-bottom:5px;
	border: 1px solid #8CACBB;
	background-color: #DEE7EC;

    -webkit-background-size: 100%;
    -o-background-size: 100%;
    -khtml-background-size: 100%;

    -moz-border-radius: 8px;
    -webkit-border-radius: 8px;
}

#content .cpvg-table{
	margin:0px;
	padding:0px;
}

#content .cpvg-table tr th{
	padding: 0px;
}

#content .cpvg-table tr td{
	padding: 0px;
}
</style>
<?php
	foreach($records_data as $record_index => $record_data){
		echo "<div class='cpvg-record-div'><table class='cpvg-table'>\n";
		foreach($record_data as $record){
			echo "<tr><th>".$record['label']."</th><td>".$record['value']."</td></tr>";
		}
		echo "</table></div>\n";
	}
?>

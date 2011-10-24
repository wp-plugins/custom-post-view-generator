<style type="text/css">
.cpvg-record-div{
	padding: 10px;
	border: 1px solid #8CACBB;
	background-color: #DEE7EC;
	margin-bottom:10px;

    -webkit-background-size: 100%;
    -o-background-size: 100%;
    -khtml-background-size: 100%;

    -moz-border-radius: 8px;
    -webkit-border-radius: 8px;
}
</style>
<?php
	foreach($records_data as $record_index => $record_data){
		echo "<div class='cpvg-record-div'>";
		foreach($record_data as $record){
			echo "<b>".$record['label']."</b>: ".$record['value']."<br>";
		}
		echo "</div>";
	}
?>

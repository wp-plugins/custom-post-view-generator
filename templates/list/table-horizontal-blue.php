<style type="text/css">
	/* TEMPLATE CSS */
	.cpvg-table{ margin-top:10px;border: 1px solid #8CACBB;border-collapse: collapse; }
	.cpvg-table td { border: 1px solid #8CACBB;padding: 2px;	}
	.cpvg-table th { border: 1px solid #8CACBB;background-color: #DEE7EC; }
	.cpvg-table td ul { /*padding:0px;margin:0px 0px 0px 20px;*/	}
	.cpvg-table .cpvg-table-blank{ border-left: 1px solid #FFFFFF;border-right: 1px solid #FFFFFF; }
	.cpvg-page{ border: 1px solid #FF0000;margin: 10px; }
	#cpvg-records{ margin-top:18px; }
	
	/* PAGINATION CSS */
	.pager{ font-family: "Bitstream Cyberbit","MS Georgia","Times New Roman",Bodoni,Garamond,"Minion Web","ITC Stone Serif","Helvetica";height: 32px;padding: 0;margin: 0;padding-top: 5px;padding-left: 3px; }
	.pager div.short{ float: right;margin: 0;padding: 0;margin-right: 10px;width: 74px; }
	.pager div.short input{ width: 28px;height: 20px;8CACBBborder: none;float: left; }
	.pager ul{ list-style: none;padding: 0;margin: 0;float: left;margin-right: 4px; }
	.pager ul li{ display: inline;margin-left: 2px; }
	.pager ul li a.normal{ text-decoration: none;display: inline-table;width: 20px;height: 20px;text-align: center; }
	.pager span{ margin-left: 4px;float: left; }
	.pager .btn{ display: block;text-align: center;float: left;padding: 0;margin: 0;margin-left: 4px;cursor: pointer; }
	.pager.themecolor .btn{ height: 24px; }
	.pager ul li a.active{ text-decoration: none;display: inline-table;width: 20px;height: 20px;text-align: center; }

	/* PAGINATION THEME CSS */
	.themecolor{ background-color: #DEE7EC;border: 1px solid #8CACBB; }
	.themecolor.normal{ background-color: #95BACF;color: White;border: solid 1px #8CACBB; }
	.themecolor.active{ background-color: #5B839A;color: #BFBFBF;border: solid 1px #8CACBB; }
	.pager.themecolor .btn{ background-color: #95BACF;color: White;border: solid 1px #8CACBB; }
</style>

<script type='text/javascript'>
jQuery(document).ready(function(){
	<?php if(isset($pagination)){ ?>
		jQuery('#cpvg-paginator').smartpaginator({ totalrecords: <?php echo count($records_data); ?>, 
												    recordsperpage: <?php echo $pagination; ?>,
												    datacontainer: 'cpvg-records',
												    dataelement: 'tr' }); 
	<?php } ?>
});
</script>

<?php
	if(isset($records_data[0])){	
		//PAGINATION DIV
		echo "<div id='cpvg-paginator'></div>";
		
		//RECORDS TABLE HEAD
		echo "<table id='cpvg-records' class='cpvg-table'>\n";
		echo "<tr>\n";
		foreach($records_data[0] as $record){
			echo "<th>".$record['label']."</th>";
		}
		echo "</tr>\n";
		
		//RECORDS TABLE RECORDS
		foreach($records_data as $record_index => $record_data){
			echo "<tr>";
			foreach($record_data as $record_idx => $record_data){
				echo "<td>".$record_data['value']."</td>";
			}
			echo "</tr>";
		}
		echo "</table>";										
	}						
?>

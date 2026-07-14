<?php

include_once '../../_params/_Server.php';

define('OUTFILE', 'backup/db-backup-'.date("YmdHis").'.sql');
define('OUTFILEPLAIN', 'db-backup-'.date("YmdHis").'.sql');



backup_tables(DBHOST,DBUSER,DBPASS,DBASE);

/* backup the db OR just a table */
function backup_tables($host,$user,$pass,$name,$tables = '*')
{
	$mysqli = new mysqli(DBHOST,DBUSER,DBPASS,DBASE);
	//$link = mysqli_connect($host,$user,$pass);
	$mysqli->select_db($name);
	
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = $mysqli->query('SHOW TABLES');
		while($row = $result->fetch_row())
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	
	//cycle through
	foreach($tables as $table)
	{
		$result = $mysqli->query('SELECT * FROM '.$table);
		$num_fields = mysqli_num_fields($result);
		//echo $num_fields;
		
		$return.= 'DROP TABLE '.$table.';';
		$row2 = $mysqli->query('SHOW CREATE TABLE '.$table)->fetch_row();
		$return.= "\n\n".$row2[1].";\n\n";
		
		for ($i = 0; $i < $num_fields; $i++) 
		{
			while($row = $result->fetch_row())
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j < $num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = str_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j < ($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}
	
	
	
	
	//save file
	$handle = fopen(OUTFILE,'w+');
	fwrite($handle,$return);
	fclose($handle);
}


?>




<html>
	<head>
	</head>
	<body>
<div class="lightbox-ajax" style="width:700px !important;">

	<!-- title -->
	<h4>Backup - done</h4>
	
	<!-- body -->
	<div class="lightbox-ajax-body">
        <p>
        	Feel free to download the dumpfile (save target as ...): <a href="<?php echo OUTFILE; ?>"><?php echo OUTFILEPLAIN; ?></a>  
        </p>
	</div>
	<!-- /body -->

</div>
	</body>
</html>

<?	
	require('./inc/class/base.class.php');
	require('./inc/class/util.class.php');
	
	$RoBase = new Base();
	
	$RoBase->set('tabela',"patrocinador");
	$RoBase->set('fields',"id,nome,link");
	$RoBase->set('where',"1=1");				
	$rs = $RoBase->doSelect();	
				
	while($result = mysql_fetch_array($rs)){                                                 		
		echo $result['link']."<br>";
		
	}	
?>

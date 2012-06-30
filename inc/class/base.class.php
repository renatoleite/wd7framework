<?

require(dirname(__FILE__).'/db.class.php');

class Base extends DBWD7{

	private $tabela;
	private $where;
	private $fields;
	private $values;
	private $order;
	private $group;
	private $limit;
	
	function __construct(){			
		require(dirname(__FILE__).'/../../configmysql.php');
		parent::set('host',$host);
		parent::set('user',$user);
		parent::set('pass',$pass);
		parent::set('data',$data);		
		parent::conectar();
		parent::setDB();			
	}
	
	public function query(){
		$qry = mysql_query('SELECT '.$this->getFields().' FROM '.$this->tabela.$this->getWhere().$this->getOrder().$this->getGroupBy().$this->getLimit()) or die (parent::erro(mysql_error()));
		return $qry;	
	}

	public function txtquery(){
		return 'SELECT '.$this->getFields().' FROM '.$this->tabela.$this->getWhere().$this->getOrder().$this->getGroupBy().$this->getLimit();
	}

	public function numrows(){		
		$qry = mysql_query('SELECT '.$this->getFields().' FROM '.$this->tabela.$this->getWhere().$this->getOrder().$this->getGroupBy()) or die (parent::erro(mysql_error()));
		$rows = mysql_num_rows($qry);
		return $rows;	
	}

	public function last($lsField){
		$qry = mysql_query('SELECT '.$lsField.' FROM '.$this->tabela.' ORDER BY '.$lsField.' DESC LIMIT 1') or die (parent::erro(mysql_error()));					
		while($result = mysql_fetch_array($qry)){
			return $result[$lsField];
			break;
		}		
	}

	public function GetPK(){
		$qry = mysql_query('SHOW KEYS FROM '.$this->tabela.' WHERE Key_name = \'PRIMARY\'') or die (parent::erro(mysql_error()));					
		while($result = mysql_fetch_array($qry)){
			return $result['Column_name'];
			break;
		}		
	}
	
	public function insert(){
		$qry = mysql_query('INSERT INTO '.$this->tabela.' ('.$this->fields.') VALUES ('.$this->values.')');
		return ($qry) ? true : false;
	}

	public function delete(){
		$qry = mysql_query('DELETE FROM '.$this->tabela.$this->getWhere());
		return ($qry) ? true : false;			
	}
	
	public function update(){		
		$lsFields = $this->GeraArray(',',$this->fields);
		$lsValues = $this->GeraArray(',',$this->values);							
		
		for ($i = 1; $i <=count($lsFields); $i++){
			$lsVirgula = ($i >= 2) ? ',' : ''; 
			$lsResult  = $lsVirgula.$lsFields[$i].'='.$lsValues[$i];
		}
				
		$qry = mysql_query('UPDATE '.$this->tabela.' SET '.$lsResult.$this->getWhere);
		return ($qry) ? true : false;
	}
    	
	public function set($prop,$value){
		$this->$prop = $value;
	}
	
	public function getFields(){		
		return ($this->fields <> '') ? $this->fields : '*';
	}
	
	public function getWhere(){		
		$lswhere = ($this->where <> '') ? $this->where : '1=1';
		return ' WHERE '.$lswhere;	
	}

	public function getOrder(){		
		$lsorder = ($this->order <> '') ? ' ORDER BY '.$this->order : '';
		return $lsorder;	
	}

	public function getGroupBy(){		
		$lsgroup = ($this->group <> '') ? ' GROUP BY '.$this->group : '';
		return $lsgroup;	
	}

	public function getLimit(){		
		$lslimit = ($this->limit <> '') ? ' LIMIT '.$this->limit : '';
		return $lslimit;	
	}
	
	public function GeraArray($separador,$str){
		return explode($separador,$str);
	}
	
	public function log($log){      	   		
		$arqerro = fopen("./logint.log", "a");
		$escreve = fwrite($arqerro,"[".date("d/m/y H:i")."] - ".$log."\r\n");
		fclose($arqerro);	   	  	  		
	}			
    	
}

?>

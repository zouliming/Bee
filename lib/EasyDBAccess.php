<?php
class EasyDBAccess{
	private $link;
	private $query_count=0;
	private $version;
	
	function EasyDBAccess($db_conf){
		if($db_conf['persistent']) {
			$this->link=@mysql_pconnect($db_conf['host'], $db_conf['usr'], $db_conf['pass']);
		}else{
			$this->link=@mysql_connect($db_conf['host'], $db_conf['usr'], $db_conf['pass'],true);
		}
		
		if(!$this->link) {
			$this->halt('DATABASE CONNECT ERROR');
		}
		
		$this->version= substr(mysql_get_server_info($this->link),0,3);
		
		if($this->version >= '4.1') {
			if($db_conf['encoding']) {
				mysql_query("SET NAMES '".$db_conf['encoding']."'",$this->link);
			}
		}

		if($this->version >= '5.0') {
			mysql_query("SET sql_mode=''",$this->link);
		}

		if($db_conf['database']) {
			mysql_select_db($db_conf['database'],$this->link);
		}
	}
	
	function dbh(){
		return $this->link;
	}
	
	function execute(){
		$sql=$this->sql(func_get_args());
		if(!mysql_query($sql,$this->link)) {
			die('Query failed: ' . mysql_error($this->link)."\nSQL: ".$sql);
		}
		return mysql_affected_rows($this->link);
	}
	
	function exec(){
		$sql=$this->sql(func_get_args());
		if(!mysql_query($sql,$this->link)) {
			die('Query failed: ' . mysql_error($this->link)."\nSQL: ".$sql);
		}
		return true;
	}
	
	
	function query(){
		$sql=$this->sql(func_get_args());
		$result = mysql_query($sql,$this->link);
		if($result==false) {
			die('Query failed: ' . mysql_error($this->link)."\nSQL: ".$sql);
		}
		return $result;
	}
	
	function select(){
		$sql=$this->sql(func_get_args());
		$result = mysql_query($sql,$this->link);
		if($result==false) {
			die('Query failed: ' . mysql_error($this->link)."\nSQL: ".$sql);
		}
		$r=array();
		while ( ( $row = mysql_fetch_array($result, MYSQL_ASSOC) )!=false ) {
			array_push($r,$row);
		}
		mysql_free_result($result);
		return $r;
	}
	
	function select_col(){
		$sql=$this->sql(func_get_args());
		$result = mysql_query($sql,$this->link);
		if($result==false) {
			die('Query failed: ' . mysql_error($this->link));
		}
		$r=array();
		while (($row = mysql_fetch_array($result, MYSQL_NUM))!=false) {
			array_push($r,$row[0]);
		}
		mysql_free_result($result);
		return $r;	
	}
	
	function select_row(){
		$sql=$this->sql(func_get_args());
		$result = mysql_query($sql,$this->link);
		if($result==false) {
			die('Query failed: ' . mysql_error($this->link)."\nSQL: ".$sql);
		}
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		mysql_free_result($result);
		return $row;
	}
	
	function select_one(){
		$sql=$this->sql(func_get_args());
		$result = mysql_query($sql,$this->link);
		if($result==false) {
			die('Query failed: ' . mysql_error($this->link)."\nSQL: ".$sql);
		}
		$row = mysql_fetch_array($result, MYSQL_NUM);
		mysql_free_result($result);
		if($row===false){
			return false;
			#die('BUG select_one:'.$sql);
		}else{
			return $row[0];
		}
	}
	
	#CREATE TABLE RES(ATTRIB VARCHAR(255) NOT NULL,ID INT NOT NULL ,PRIMARY KEY (ATTRIB))
	function id($name,$count=1){
		$affected_rows=$this->execute('UPDATE RES SET ID=LAST_INSERT_ID(ID+?) WHERE ATTRIB=?',$count,$name);
		if($affected_rows==0){
			$this->execute('INSERT INTO RES(ATTRIB,ID) VALUES(?,0)',$name);
			$this->execute('UPDATE RES SET ID=LAST_INSERT_ID(ID+?) WHERE ATTRIB=?',$count,$name);
		}
		return $this->select_one('SELECT LAST_INSERT_ID()');
	}

	function close() {
		return mysql_close($this->link);
	}

	function free($result){
		mysql_free_result($result);
	}

	function sql($args){
		$args[0]=str_replace("%", "%%",$args[0]);
		$args[0]=str_replace("?", "%s",$args[0]);
		for($i=count($args);$i-->1;){
			if($args[$i]===null){
				$args[$i]='null';
			}else{
				$args[$i]="'".mysql_escape_string($args[$i])."'";
			}
		}
		return call_user_func_array('sprintf',$args);
	}

	function halt($msg, $sql=''){
?>
<html>
<head>
<title>APPLICATION ERROR: <?=$msg?></title>
</head>
<body>
<h1>APPLICATION ERROR: <?=$msg?></h1><br/>
<p><?=$sql?></p>
</body>
</html>
<?php
	exit;
	}
}



?>
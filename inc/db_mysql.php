<?php
/*
 *	mysql数据库 DB类
 */
class db {
	var $pre = "";	//数据表名的前缀
	var $debug = true;
	var $connection_id = "";
	var $pconnect = 0;
	var $shutdown_queries = array();
	var $queries = array();
	var $query_id = "";
	var $query_count = 0;
	var $record_row = array();
	var $record_row_count = 0;
	var $failed = 0;
	var $halt = "";
	var $query_log = array();
	function connect($_DB){
		$this->pre=$_DB['prefix'];
		if ($this->pconnect){
			$this->connection_id = mysql_pconnect($_DB['hostname'], $_DB['username'], $_DB['password']);
		}else{
			$this->connection_id = mysql_connect($_DB['hostname'], $_DB['username'], $_DB['password']);
		}
		if ( ! $this->connection_id ){
			$this->halt("Can not connect MySQL Server");
		}
		if ( ! @mysql_select_db($_DB['database'], $this->connection_id) ){
			$this->halt("Can not connect MySQL Database");
		}
		if ($_DB['charset']) {
			@mysql_unbuffered_query("SET NAMES '".$_DB['charset']."'");
		}
		return true;
	}

	function concat(){
		$args=func_get_args();
		return "CONCAT(".implode(",",$args).")";
	}

	//发送SQL 查询，并返回结果集
    function query($query_id, $query_type='mysql_query'){
        $this->query_id = $query_type($query_id, $this->connection_id);
		$this->queries[] = $query_id;
        if (! $this->query_id ) {
            $this->halt("查询失败:\n$query_id");
		}
		$this->query_count++;
		$this->query_log[] = $str;
		//echo($this->query_count."-----".$query_id."\r\n");
        return $this->query_id;
    }
	//发送SQL 查询，并不获取和缓存结果的行
	function query_unbuffered($sql=""){
		return $this->query($sql, 'mysql_unbuffered_query');
	}
	//从结果集中取得一行作为关联数组
    function fetch_array($sql = ""){
    	if ($sql == "") $sql = $this->query_id;
        $this->record_row = @mysql_fetch_array($sql, MYSQL_ASSOC);
        return $this->record_row;
    }
	//取得结果集中行的数目，仅对 SELECT 语句有效
    function num_rows($query_id="") {
		if ($query_id == "") $query_id = $this->query_id;
        return @mysql_num_rows($query_id);
    }
	//返回上一个 MySQL 操作中的错误信息的数字编码
	function get_errno(){
		$this->errno = @mysql_errno($this->connection_id);
		return $this->errno;
	}
	//取得上一步 INSERT 操作产生的 ID
    function insert_id(){
        return @mysql_insert_id($this->connection_id);
    }
	//得到查询次数
    function query_count() {
        return $this->query_count;
    }
	//释放结果内存
    function free_result($query_id=""){
   		if ($query_id == "") $query_id = $this->query_id;
    	@mysql_free_result($query_id);
    }
	//关闭 MySQL 连接
    function close_db(){
    	if ( $this->connection_id ) return @mysql_close( $this->connection_id );
    }
	//错误提示
    function halt($the_error=""){
		$message = $the_error."<br/>\r\n";
		$message.= $this->get_errno() . "<br/>\r\n";
		if ($this->debug==true){
			echo "<html><head><title>MySQL Error</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />";
			echo "<style type=\"text/css\"><!--.error { font: 11px tahoma, verdana, arial, sans-serif, simsun; }--></style></head>\r\n";
			echo "<body>\r\n";
			echo "<blockquote>\r\n";
			echo "<textarea class=\"error\" rows=\"15\" cols=\"100\" wrap=\"on\" >" . htmlspecialchars($message) . "</textarea>\r\n";
			echo "</blockquote>\r\n</body></html>";
			exit;
		}else{
			exit("<html><head><title>6KZZ</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /></head><body>System is busy... <a href=\"http://www.6kzz.com\">www.6kzz.com</a></body></html>");
		}
    }
	function __destruct(){
		$this->shutdown_queries = array();
		$this->close_db();
	}
	function sql_select($tbname, $where="", $limit=0, $fields="*", $orderby="id DESC"){
		$sql = "SELECT ".$fields." FROM ".$tbname." ".($where?" WHERE ".$where:"")." ORDER BY ".$orderby.($limit ? " limit ".$limit:"");
		//echo($sql);
		return $sql;
	}
	function sql_insert($tbname, $row){
		foreach ($row as $key=>$value) {
			$sqlfield .= $key.",";
			$sqlvalue .= "'".$value."',";
		}
		return "INSERT INTO ".$tbname." (".substr($sqlfield, 0, -1).") VALUES (".substr($sqlvalue, 0, -1).")";
	}
	function sql_replace($tbname, $row){
		foreach ($row as $key=>$value) {
			$sqlfield .= $key.",";
			$sqlvalue .= "'".$value."',";
		}
		return "REPLACE INTO ".$tbname." (".substr($sqlfield, 0, -1).") VALUES (".substr($sqlvalue, 0, -1).")";
	}
	function sql_update($tbname, $row, $where){
		foreach ($row as $key=>$value) {
			if(stristr($key,"NOQUOTE_")){
				continue;
			}
			if($row["NOQUOTE_{$key}"]){
				$sqlud .= $key."=".$value.",";
			}else{
				$sqlud .= $key."= '".$value."',";
			}
		}
		return "UPDATE ".$tbname." SET ".substr($sqlud, 0, -1).($where?" WHERE ".$where:"");
	}
	function sql_delete($tbname, $where){
		return "DELETE FROM ".$tbname." WHERE ".$where;
	}

	function row_query($sql){
		$rs	 = $this->query($sql);
		$rs_num = $this->num_rows($rs);
		$rows = array();
		for($i=0; $i<$rs_num; $i++){
			$rows[] = $this->fetch_array($rs);
		}
		$this->free_result($rs);
		$this->record_row_count = $rs_num;
		return $rows;
	}
	function row_query_one($sql){
		//print($sql."<br />");
		$rs	 = $this->query($sql);
		$row = $this->fetch_array($rs);
		$this->free_result($rs);
		return $row;
	}

	function fix_tbname_cname($tbname){
		$tbs=explode(",",$tbname);
		$tbname="";
		for($t=0;$t<count($tbs);$t++){
			$tbname .= "`{$this->pre}{$tbs[$t]}` {$tbs[$t]},";
		}
		$tbname=substr($tbname,0,strlen($tbname)-1);
		return $tbname;
	}

	function fix_tbname($tbname){
		$tbname = "`{$this->pre}{$tbname}`";
		return $tbname;
	}

	//新增加一条记录
	function row_insert($tbname, $row){
		$tbname = $this->fix_tbname($tbname);
		$sql = $this->sql_insert($tbname, $row);
		return $this->query_unbuffered($sql);
	}
	//REPLACE一条记录
	function row_replace($tbname, $row){
		$tbname = $this->fix_tbname($tbname);
		$sql = $this->sql_replace($tbname, $row);
		return $this->query_unbuffered($sql);
	}
	//更新指定记录
	function row_update($tbname, $row, $where=""){
		$tbname = $this->fix_tbname($tbname);
		$sql = $this->sql_update($tbname, $row, $where);
		return $this->query_unbuffered($sql);
	}
	//删除满足条件的记录
	function row_delete($tbname, $where){
		$tbname = $this->fix_tbname($tbname);
		$sql = $this->sql_delete($tbname, $where);
		//echo($sql);
		return $this->query_unbuffered($sql);
	}
	//查询多条记录
	function row_select($tbname, $where="", $limit=0, $fields="*", $orderby="id DESC"){
		$tbname = $this->fix_tbname_cname($tbname);
		$sql = $this->sql_select($tbname, $where, $limit, $fields, $orderby);
		return $this->row_query($sql);
	}
	//查询一条记录
	function row_select_one($tbname, $where="", $fields="*", $orderby="id"){
		$tbname = $this->fix_tbname_cname($tbname);
		$sql = $this->sql_select($tbname, $where, 1, $fields, $orderby);
		//echo($sql);
		return $this->row_query_one($sql);
	}

	//计数统计
	function row_count($tbname, $where=""){
		$tbname = $this->fix_tbname_cname($tbname);
		$sql = "SELECT count(0) as row_sum FROM ".$tbname." ".($where?" WHERE ".$where:"");
		$row = $this->row_query_one($sql);
		return $row['row_sum'];
	}


}
?>
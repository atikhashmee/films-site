<?php 

include("atik_config.php");

/**
* 
*/
class BasicDb
{
	    
	 private $con = null;
	 function __construct()
	{
		$this->con = FilmDbConnection::getInstance();
	}
	public function insert($table_name, $data)
	{
		$val = false;
		 try {
		 	$fields = array_keys($data);
		$sql = "INSERT INTO `".$table_name."`( `".implode("`,`",$fields)."`) value ('".implode("','",$data)."')";
		 $qry = $this->con->prepare($sql);
		 $val =  $qry->execute();
		 } catch (PDOException $e ) {
			echo $sql . "<br>" . $e->getMessage();
		}
		 return $val;
	}

	public function update($table_name, $formdata, $where_clause="")
	{
		$val = false;
			try {

			$wehersql = "";
		//check the WHERE clause when it is not empty
		if (!empty($where_clause)) {
					//if there is no such substring called where then 
			if (substr(strtoupper(trim($where_clause)), 0,5) != "WHERE") {
					//it will add up a where clause itself 
				$wehersql = "WHERE ".$where_clause;
			}else{
				//and if exists then simply added to the $wheresql variable  

				$wehersql = " ".trim($where_clause);
			}
		}
		$updatesal = "UPDATE ".$table_name." SET ";
		$sets = array();
		foreach ($formdata as $coloumn => $value) {
			$sets[] = "`".$coloumn."` = '".$value."'";
		}
		$updatesal .= implode(",", $sets);
		$updatesal .= $wehersql;
		//$obj = self::getConncetion();
		
		$qry = $this->con->prepare($updatesal);
		$val = $qry->execute();
			} catch (PDOException $e ) {
			echo $sql . "<br>" . $e->getMessage();
		}

		
		return $val;
	}
	
	public function selectAll($table_name,$where_clause=""){
		$data;

		 try {
		 	$wheresql="";
		if (!empty($where_clause)) {
			if(substr(strtoupper(trim($where_clause)), 0,5)!="WHERE")
			{
				$wheresql = "WHERE ".$where_clause;
			}else{

				$wheresql = " ".$where_clause;
			}
		}


		$sal; 

		if (!empty($wheresql)) {
			$sal = "SELECT * FROM ".$table_name." ".$wheresql;
			
		}else{
			$sal = "SELECT * FROM ".$table_name;
			
		}
		
		$data = $this->con->prepare($sal);
		$data->execute();
		 } catch (PDOException $e ) {
			echo $sql . "<br>" . $e->getMessage();
		}
		return $data;
	}


	public function joinQuery($sql){
		$qry;
		try {
		$qry = $this->con->prepare($sql);
		$qry->execute();
			
		} catch (PDOException $e ) {
			echo $sql . "<br>" . $e->getMessage();
		}
		
		return $qry;
	}
	public function delete($table,$id){
		$val = false;
		  try {
		  	$qry = $this->con->prepare("DELETE FROM `".$table."` WHERE ".$id."");
		    $val = $qry->execute();
		  } catch (PDOException $e ) {
			echo $sql . "<br>" . $e->getMessage();
		}
			
		return $val;
	}
	
	public function getInsertId($colid) {
		return $this->con->lastInsertId($colid);
	}
}


?>
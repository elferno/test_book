<?php
class Mysqli_class {
	# SYSTEM #
	function __construct()
	{
		$this -> connect();
	}
	
	private function connect ()
	{
		$this -> mysqli =  new mysqli("localhost", "elfern4j_qqq", "Qn5_6k", "elfern4j_qqq");
	}
	# # #


	########## SELECT ##########
	public function select ($selectQ)
	{
		if ($this -> mysqli)
			if($store_result = $this -> mysqli -> query($selectQ, MYSQLI_STORE_RESULT))
				if($result = $store_result -> fetch_assoc())
					$SQL_result = $result;

		return $SQL_result;
	}

	public function select_multiple ($selectQ)
	{
		$sql_DATA = array();
		if ($selectQ && $this -> mysqli)
			if ($this -> mysqli -> multi_query($selectQ))
				do {
					if ($store_result = $this -> mysqli -> store_result()) {
						$table_name =  $store_result -> fetch_fields();
						foreach($table_name as $single_name) {
							$table_name = $single_name -> table;
							break;
						}
												
						$sql_DATA[$table_name] = [];
						while($result = $store_result -> fetch_assoc())
							$sql_DATA[$table_name][] = $result;

						$store_result -> free();
					}
				} while ($this -> mysqli -> next_result());

		return $sql_DATA;
	}
	# # #


	########## QUERY ##########
	public function query ($query)
	{
		if ($this -> mysqli)
			$this -> mysqli -> query($query);

		if ($this -> mysqli -> error)
			exit($this -> mysqli -> error);
	}
	# # #

	
	########## REAL ESCAPE STRING ##########
	public function safeString ($str)
	{
		return $this -> mysqli ? $this -> mysqli -> real_escape_string($str) : $str;
	}
	# # #

	
	########## CLOSE ##########
	public function close()
	{
		if ($this -> mysqli)
			$this -> mysqli -> close();
	}
	# # #	
}

$mySQL = new Mysqli_class();
?>

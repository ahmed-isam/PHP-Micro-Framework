<?php

class DatabaseContext
{
	
	function __construct() {
		$this->database = new PDO(
			'mysql:host=127.0.0.1;dbname=clear_crm;charset=utf8mb4', 'root', 'kokojumbo'
		);
		$this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->database->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	}

	public function getDB()	{
		return $this->database;
	}

	public function getEntity($tableName, $id) {
		return $this->getObject("SELECT * FROM ". $tableName ." WHERE id = ". $id);
	}

	public function getEntities($tableName) {
		return $this->getObjectList("SELECT * FROM ". $tableName);
	}

	public function getObject($queryString) {
		return $this->database->query($queryString)->fetch(PDO::FETCH_OBJ);
	}

	public function getObjectList($queryString) {
		return $this->database->query($queryString)->fetchAll(PDO::FETCH_OBJ);
	}

	public function persistObject($tableName, $object) {
		return $this->persistArray($tableName, (array) $object);
	}

	public function persistArray($tableName, $array) {
		$sql = "INSERT INTO ". $tableName;
		$values = "(";
		$rowsNames = "(";
		foreach ($array as $row => $value) {
			$values .= ":".$row .",";
			$rowsNames .= $row . ",";
			$array[":" . $row] = $value;
			unset($array[$row]);
		}
		$values = rtrim($values, ",") . ")";
		$rowsNames = rtrim($rowsNames, ",") . ")";
		$sql .= $rowsNames ." VALUES" .$values;
		$query = $this->database->prepare($sql);
		$query->execute($array);
		return $db->lastInsertId();
	}

	private $database;
}
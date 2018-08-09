<?php
class ModelCatalogTubs extends Model {


	public function getTubs($data = array(), $table_name) {
		$sql = "SELECT * FROM " . DB_PREFIX . "$table_name";

		if (!empty($data['filter_tube_name'])) {
			$sql .= " WHERE  tube_name LIKE '" . $this->db->escape($data['filter_tube_name']) . "%'";
		}

		$sort_data = array(
			'tube_name',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY tube_name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		echo $sql;
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}

	public function getTub($tubs_id, $table_name) {
		$query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "$table_name WHERE tube_id = '" .(int)$tubs_id . "'");

		return $query->row;
	}

	public function getTotalTubs($table_name) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "$table_name");

		return $query->row['total'];
	}


	public function addTubs($data, $xml, $table_name) {

		
		$indexTable = 0;

		for($i=0; $i <= count($xml->object); $i++) {
			if($xml->object[$i]['table'] == $table_name) {
				$indexTable = $i;
				break;
			}
		}

		$tube_name         = $this->db->escape($data['tube_name']);	

		$sql = "
		INSERT INTO " . DB_PREFIX . "$table_name 
		SET tube_name = '$tube_name'";

		foreach ($xml->object[$indexTable]->field as $field) {
			$name = $field['key'];
			$buffer_value = $data["$name"];
			if($field['dbtype'] == 'int'){
				$sql .= " ,". $name." = '".(int)$buffer_value."'";
			}else{
				$sql .= " ,". $name." = '".$this->db->escape($buffer_value)."'";
			}
		}	
		
		echo $sql;
	
		$this->db->query($sql);

		
		$tubs_id = $this->db->getLastId();
	

		return $tubs_id;
	}
	

	public function editTub($tubs_id, $data, $xml, $table_name) {

		$indexTable = 0;

		for($i=0; $i <= count($xml->object); $i++) {
			if($xml->object[$i]['table'] == $table_name) {
				$indexTable = $i;
				break;
			}
		}

		$tube_name        = $this->db->escape($data['tube_name']);
		

		$sql = "
		UPDATE ". DB_PREFIX . "$table_name 
		SET tube_name = '$tube_name'";

		foreach ($xml->object[$indexTable]->field as $field) {
			$name = $field['key'];
			$buffer_value = $data["$name"];
			if($field['dbtype'] == 'int'){
				$sql .= " ,". $name." = '".(int)$buffer_value."'";
			}else{
				$sql .= " ,". $name." = '".$this->db->escape($buffer_value)."'";
			}
		}	

		$sql .= "WHERE tube_id     = '$tubs_id';";

		$this->db->query($sql);	
	}

	public function deleteTubs($tubs_id, $table_name) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "$table_name WHERE tube_id = '" . (int)$tubs_id . "'");

		
	}	


		
	
}

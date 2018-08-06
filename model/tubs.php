<?php
class ModelCatalogTubs extends Model {


	public function getTubs($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "tubs_1";

		

		$sort_data = array(
			'tube_type',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY tube_type";
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
		
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTub($tubs_id) {
		$query = $this->db->query("SELECT * FROM `oc_tubs_1` WHERE tube_id = '" .(int)$tubs_id . "'");

		return $query->row;
	}

	public function getTotalTubs() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tubs_1");

		return $query->row['total'];
	}


	public function addTubs($data, $xml, $table_name) {

		echo $table_name;
		$indexTable = 0;
		for($i=0; $i <= count($xml->object); $i++) {
			if($xml->object[$i]['table'] == $table_name) {
				$indexTable = $i;
				break;
			}
		}

		echo $indexTable;
		$buffer = 0; // не получается вставить динамически имя переменной как ${$type['key']}, поэтому сделал эту переменную как буфер.
		foreach ($xml->object[$indexTable]->field as $field) {
			$buffer = $field['key'];
			if($field['dbtype'] == 'int'){
				${$buffer} = $data[$buffer];
			}else{
				echo '<pre>';
				print_r($field['key']);
				echo '</pre>';
				${$buffer} = $data[$buffer];
				 	
			}
		}
		
		print_r($xml->object[0]->field[1]['key']);
		
		

		

		$tube_type         = $this->db->escape($data['tube_type']);
		$sort_order		   = $this->db->escape($data['sort_order']);
		$selector_switch_a = $this->db->escape($data['selector_switch_a']);
		$selector_switch_b = $this->db->escape($data['selector_switch_b']);
		$filament		   = $this->db->escape($data['selector_switch_b']);
		$sort_order        = $this->db->escape($data['sort_order']);		
		
		$sql = "
		INSERT INTO " . DB_PREFIX . "tubs_1 
		SET tube_type     = '$tube_type',
		selector_switch_a = '$selector_switch_a',
		selector_switch_b = '$selector_switch_b',
		filament          = '$filament',
		sort_order        = '$sort_order';
		";
	
		$this->db->query($sql);

		
		$tubs_id = $this->db->getLastId();
	

		return $tubs_id;
	}
	

	public function editTub($tubs_id, $data) {

		$tube_type         = $this->db->escape($data['tube_type']);
		$sort_order        = $this->db->escape($data['sort_order']);
		$selector_switch_a = $this->db->escape($data['selector_switch_a']);
		$selector_switch_b = $this->db->escape($data['selector_switch_b']);
		$filament          = $this->db->escape($data['filament']);
		$sort_order        = $this->db->escape($data['sort_order']);

		$sql = "
		UPDATE ". DB_PREFIX ."tubs_1
		SET tube_type     = '$tube_type',
		selector_switch_a = '$selector_switch_a',
		selector_switch_b = '$selector_switch_b',
		filament          = '$filament',
		sort_order        = '$sort_order'
		WHERE tube_id     = '$tubs_id';
		";

		$this->db->query($sql);	
	}

	public function deleteTubs($tubs_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "tubs_1 WHERE tube_id = '" . (int)$tubs_id . "'");

		
	}	


		
	
}

<?php
class ControllerCatalogTubs extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/tubs');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/tubs');

		$this->getList();
	}

	


	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'tube_type';
		}


		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}


		

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['table'])) {
			$url .= '&table=' . $this->request->get['table'];
		} else $url .= '&table=tubs_1';

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/tubs', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('catalog/tubs/add', 'token=' . $this->session->data['token'] . $url, true . $this->request->get['table']);
		$data['delete'] = $this->url->link('catalog/tubs/delete', 'token=' . $this->session->data['token'] . $url, true . $this->request->get['table']);

		$data['tubs'] = array();
		$data['arr_rows'] = array(); // передаем в tpl массив со строками

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		
        $tubs_total = $this->model_catalog_tubs->getTotalTubs($this->request->get['table']);
		$results = $this->model_catalog_tubs->getTubs($filter_data, $this->request->get['table']);

		$xmlArr = $this->xml();
		

		$arr_rows_for_tpl = array(); // будет использовать в tpl как id для $tubs;			
		$arr_column_for_tpl = array(); // название строк таблицы ( Tube type, Selector)
		

		$arr_rows_for_tpl = $this->xml_get_rows($xmlArr, $this->request->get['table']);
		$arr_column_for_tpl = $this->xml_get_column($xmlArr, $this->request->get['table']);
		
		$data['arr_rows'] = $arr_rows_for_tpl;
		$data['arr_column'] = $arr_column_for_tpl;

		foreach ($results as $key => $result) {
			
			$data['tubs'][] = array(	
				'tube_id' => $result['tube_id'],
				'tube_type' => $result['tube_type'],
				'edit'            => $this->url->link('catalog/tubs/edit', 'token=' . $this->session->data['token'] . '&tubs_id=' . $result['tube_id'] . $url, true)
			);
			
				foreach ($arr_rows_for_tpl as $id => $value) {
						$data['tubs'][$key][$arr_rows_for_tpl[$id]] = $result[$arr_rows_for_tpl[$id]];			
				}				
		}

	

		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}



		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['table'])) {
			$url .= '&table=' . $this->request->get['table'];
		}

		$data['sort_tube_type'] = $this->url->link('catalog/tubs', 'token=' . $this->session->data['token'] . '&sort=tube_type' . $url, true);
		



		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $tubs_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/tubs', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($tubs_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($tubs_total - $this->config->get('config_limit_admin'))) ? $tubs_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $tubs_total, ceil($tubs_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');


		$this->response->setOutput($this->load->view('catalog/tubs_list', $data));
	}


	protected function getForm() {


		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['manufacturer_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_amount'] = $this->language->get('text_amount');

		$data['entry_name'] = $this->language->get('entry_name');
		
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');

		$data['help_keyword'] = $this->language->get('help_keyword');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');


		$xmlArr = $this->xml();
		$table_rows = $this->xml_get_rows($xmlArr, $this->request->get['table']);

		$arr_rows_for_tpl = array(); // будет использовать в tpl как id для $tubs;
		$index_table_rows = 0; // для вставки элементов массива  $arr_rows_for_tpl с ключом в виде (1)(2), чтобы через счетчик  получить их в tpl
		foreach ($table_rows as $value) {
			
			$arr_rows_for_tpl[$index_table_rows] = $value;
			$index_table_rows += 1;
		}

		$arr_column_for_tpl = $this->xml_get_column($xmlArr, $this->request->get['table']);
		
		$data['arr_rows'] = $arr_rows_for_tpl;
		$data['arr_column'] = $arr_column_for_tpl;


		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['table'])) {
			$url .= '&table=' . $this->request->get['table'];
		}


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/tubs', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['tubs_id'])) {
			$data['action'] = $this->url->link('catalog/tubs/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/tubs/edit', 'token=' . $this->session->data['token'] . '&tubs_id=' . $this->request->get['tubs_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/tubs', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['tubs_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$tubs_info = $this->model_catalog_tubs->getTub($this->request->get['tubs_id'], $this->request->get['table'] );
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['tube_type'])) {
			$data['tube_type'] = $this->request->post['tube_type'];
		} elseif (!empty($tubs_info)) {
			$data['tube_type'] = $tubs_info['tube_type'];
		} else {
			$data['tube_type'] = '';
		}

		foreach ($arr_rows_for_tpl as $key) {
			
			if (isset($this->request->post["$key"])) {
			$data["$key"] = $this->request->post["$key"];
			} elseif (!empty($tubs_info)) {
				$data["$key"] = $tubs_info["$key"];
			} else {
				$data["$key"] = '';
			}

		}
		
		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/tubs_form', $data));
	}

	public function add() {
		$this->load->language('catalog/tubs');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/tubs');


		

		$xmlArr = $this->xml();
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_catalog_tubs->addTubs($this->request->post, $xmlArr, $this->request->get['table']);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['table'])) {
				$url .= '&table=' . $this->request->get['table'];
			}

			$this->response->redirect($this->url->link('catalog/tubs', 'token=' . $this->session->data['token'] . $url, true ));
		}

		$this->getForm();
	}

	public function xml() {

		$xmlDB = "<database>
		<object table='tubs_1' name='MX_10A'>
		   
		    <field key='selector_switch_a' dbtype='varchar' name='Selector Switch A' />
		    <field key='selector_switch_b' dbtype='varchar' name='Selector Switch B' />
		    <field key='filament' dbtype='int' name='Filament' />
		   
		</object>
		<object table='tubs_2' name='NO_BC6'>
		   
		    <field key='filament_voltage' dbtype='varchar' name='Filament Voltage' />
		    <field key='potentiomenters_l' dbtype='varchar' name='Potentiomenters L' />
		    <field key='potentiomenters_r' dbtype='int'   name='Potentiomenters R' />
		    <field key='rest' dbtype='int'   name='Rest' />
		   
		</object>

		</database>";

		return $xmlArr = new SimpleXMLElement($xmlDB);
		
	}

	public function xml_get_rows($xml,$table_name) {
		$buffer = 0; // не получается вставить динамически имя переменной как ${$type['key']}, поэтому сделал эту переменную как буфер.

		$indexTable = 0;
		for($i=0; $i <= count($xml->object); $i++) {
			if($xml->object[$i]['table'] == $table_name) {
				$indexTable = $i;
				break;
			}
		}

		foreach ($xml->object[$indexTable]->field as $field) {

			$arr_rows[] = (string)$field['key'];
							 		
		}



		return $arr_rows;
	}

	public function xml_get_column($xml,$table_name) {
		$buffer = 0; // не получается вставить динамически имя переменной как ${$type['key']}, поэтому сделал эту переменную как буфер.

		$indexTable = 0;
		for($i=0; $i <= count($xml->object); $i++) {
			if($xml->object[$i]['table'] == $table_name) {
				$indexTable = $i;
				break;
			}
		}

		foreach ($xml->object[$indexTable]->field as $field) {

			$arr_rows[] = (string)$field['name'];
							 		
		}

		return $arr_rows;
	}

	public function edit() {
		$xmlArr = $this->xml();
		$this->load->language('catalog/tubs');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/tubs');

		$xmlArr = $this->xml();

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_catalog_tubs->editTub($this->request->get['tubs_id'], $this->request->post, $xmlArr, $this->request->get['table']);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			


			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['table'])) {
				$url .= '&table=' . $this->request->get['table'];
			}

			$this->response->redirect($this->url->link('catalog/tubs', 'token=' . $this->session->data['token'] . $url, true ));
		}

		$this->getForm();
	}
   
    public function delete() {
		$this->load->language('catalog/tubs');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/tubs');

		if (isset($this->request->post['selected']) ) {
			foreach ($this->request->post['selected'] as $tubs_id) {
				$this->model_catalog_tubs->deleteTubs($tubs_id, $this->request->get['table']);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['table'])) {
				$url .= '&table=' . $this->request->get['table'];
			}

			$this->response->redirect($this->url->link('catalog/tubs', 'token=' . $this->session->data['token'] . $url, true ));
		}

		$this->getList();
	}

}

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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/tubs', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('catalog/tubs/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/tubs/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['tubs'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		
        $tubs_total = $this->model_catalog_tubs->getTotalTubs();
		$results = $this->model_catalog_tubs->getTubs($filter_data);

		foreach ($results as $result) {
			$data['tubs'][] = array(
				'tube_id' => $result['tube_id'],
				'tube_type' => $result['tube_type'],
				'selector_switch_a'            => $result['selector_switch_a'],
				'selector_switch_b'      => $result['selector_switch_b'],
				'filament'  => $result['filament'],
				'sort_order'      => $result['sort_order'],
				'edit'            => $this->url->link('catalog/tubs/edit', 'token=' . $this->session->data['token'] . '&tubs_id=' . $result['tube_id'] . $url, true)
			);
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

		$data['sort_tube_type'] = $this->url->link('catalog/tubs', 'token=' . $this->session->data['token'] . '&sort=tube_type' . $url, true);
		$data['sort_selector_switch_a'] = $this->url->link('catalog/tubs', 'token=' . $this->session->data['token'] . '&sort=selector_switch_a' . $url, true);
		$data['sort_selector_switch_b'] = $this->url->link('catalog/tubs', 'token=' . $this->session->data['token'] . '&sort=selector_switch_b' . $url, true);
		$data['sort_filament'] = $this->url->link('catalog/tubs', 'token=' . $this->session->data['token'] . '&sort=filament' . $url, true);



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
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');

		$data['help_keyword'] = $this->language->get('help_keyword');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

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
			$tubs_info = $this->model_catalog_tubs->getTub($this->request->get['tubs_id']);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['tube_type'])) {
			$data['tube_type'] = $this->request->post['tube_type'];
		} elseif (!empty($tubs_info)) {
			$data['tube_type'] = $tubs_info['tube_type'];
		} else {
			$data['tube_type'] = '';
		}

		if (isset($this->request->post['selector_switch_a'])) {
			$data['selector_switch_a'] = $this->request->post['selector_switch_a'];
		} elseif (!empty($tubs_info)) {
			$data['selector_switch_a'] = $tubs_info['selector_switch_a'];
		} else {
			$data['selector_switch_a'] = '';
		}

		if (isset($this->request->post['selector_switch_b'])) {
			$data['selector_switch_b'] = $this->request->post['selector_switch_b'];
		} elseif (!empty($tubs_info)) {
			$data['selector_switch_b'] = $tubs_info['selector_switch_b'];
		} else {
			$data['selector_switch_b'] = '';
		}

		if (isset($this->request->post['filament'])) {
			$data['filament'] = $this->request->post['filament'];
		} elseif (!empty($tubs_info)) {
			$data['filament'] = $tubs_info['filament'];
		} else {
			$data['filament'] = '';
		}

		

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($tubs_info)) {
			$data['sort_order'] = $tubs_info['sort_order'];
		} else {
			$data['sort_order'] = '';
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

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_catalog_tubs->addTubs($this->request->post);

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

			$this->response->redirect($this->url->link('catalog/tubs', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}


	public function edit() {
		$this->load->language('catalog/tubs');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/tubs');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_catalog_tubs->editTub($this->request->get['tubs_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('catalog/tubs', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}
   
    public function delete() {
		$this->load->language('catalog/tubs');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/tubs');

		if (isset($this->request->post['selected']) ) {
			foreach ($this->request->post['selected'] as $tubs_id) {
				$this->model_catalog_tubs->deleteTubs($tubs_id);
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

			$this->response->redirect($this->url->link('catalog/tubs', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

}

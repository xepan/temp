<?php

namespace xAi;


class View_DynamicDataTable extends \View{
	
	public $analytic = null;
	public $from_date=null;
	public $to_date=null;

	public $information_model = null;
	public $grid = null;
	
	function init(){
		parent::init();

		$title = $this->add('H3')->setHtml($this->analytic['chart_title']. ($this->analytic['chart_sub_title']?'<small>'.$this->analytic['chart_sub_title'].'</small>':''));
		$title->setStyle('text-align','center');
		$this->grid = $grid = $this->add('Grid');

		$this->setStyle('border','1px solid gray');

		if($this->from_date == null)
			$this->from_date = date("Y-m-d", strtotime(date("Y-m-d") . " -30 DAY"));

		if($this->to_date == null)
			$this->to_date = date("Y-m-d");

		$this->to_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($this->to_date)) . " +1 DAY")); 

		// Generate Data First
		$this->information_model = $information_model = $this->add('xAi/Model_Information');
		$session_j = $information_model->join('xai_session','session_id');
		$session_j->addField('created_at');
		$meta_info_j= $information_model->join('xai_meta_information','meta_information_id');

		$information_model->_dsql()->del('fields');

		$q= $information_model->dsql();

		$information_model->_dsql()->field($q->expr('value `'. $this->analytic['grid_group_by_meta_information'].'`'));
		switch ($this->analytic['grid_value']) {
			case 'VALUE':
				$information_model->_dsql()->field('value result');
				break;
			case 'COUNT':
				$information_model->_dsql()->field('COUNT(*) result');
				# code...
				break;
			case 'SUM':
				$information_model->_dsql()->field('SUM(value) result');
				# code...
				break;
			case 'WEIGHTSUM':
				$information_model->_dsql()->field('SUM(weight) result');
				# code...
				break;
			
			default:
				# code...
				break;
		}

		$information_model->_dsql()->where($session_j->table_alias.'.created_at','>=', $this->from_date);
		$information_model->_dsql()->where($session_j->table_alias.'.created_at','<', $this->to_date);
		$information_model->_dsql()->where($meta_info_j->table_alias.'.name', $this->analytic['grid_group_by_meta_information']);
		
		$information_model->_dsql()->group('`'. $this->analytic['grid_group_by_meta_information'].'`');
		$information_model->_dsql()->order('result','desc');
		$information_model->setLimit($this->analytic['limit_top']);

		// $information_model->_dsql()->debug()->render();

		$grid->addColumn($this->analytic['grid_group_by_meta_information']);
		$grid->addColumn('result');

		$grid->setSource($information_model->_dsql());
		$grid->addPaginator(10);

	}
}
<?php

namespace xAi;

class View_Analytical extends \View{

	public $filter_dashboard='push_to_analytic_dashboard';
	public $from_date=null;
	public $to_date = null;

	public $graphs =array();

	function init(){
		parent::init();	

		if($this->from_date == null)
			$this->from_date = date("Y-m-d", strtotime(date("Y-m-d") . " -30 DAY"));

		if($this->to_date == null )
			$this->to_date = date("Y-m-d");

		if($_GET['xai-analytical-from-date']) $this->from_date = $_GET['xai-analytical-from-date'];
		if($_GET['xai-analytical-to-date']) $this->to_date = $_GET['xai-analytical-to-date'];


		$form = $this->add('Form',null,null, array('form_horizontal'));
		$form->addField('DatePicker','from_date')->set($this->from_date);
		$form->addField('DatePicker','to_date')->set($this->to_date);
		$form->addSubmit('Update');

		if($form->isSubmitted()){
			$this->js()->reload(array(
					'xai-analytical-from-date' => $form['from_date']?:0,
					'xai-analytical-to-date' => $form['to_date']?:0
				))->execute();
		}

		$dashboard_analytics = $this->add('xAi/Model_VisualAnalytic');
		$dashboard_analytics->addCondition($this->filter_dashboard,true);

		foreach ($dashboard_analytics as $junk) {
			$analytic_to_draw = $this->add('xAi/Model_VisualAnalytic')->load($junk['id']);
			if($analytic_to_draw['visual_style']=='grid'){
				$this->graphs[] = $this->add('xAi/View_DynamicDataTable',array('analytic'=> $analytic_to_draw, 'from_date'=>$this->from_date,'to_date'=>$this->to_date));
				continue;
			}
			$this->graphs[] = $this->add('xAi/View_DynamicChart',array('analytic'=> $analytic_to_draw, 'from_date'=>$this->from_date,'to_date'=>$this->to_date));
		}


	}
}
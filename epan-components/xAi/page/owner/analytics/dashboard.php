<?php


class page_xAi_page_owner_analytics_dashboard extends page_xAi_page_owner_main {
	
	public $from_date=null;
	public $to_date=null;

	function init(){
		parent::init();

		if($this->from_date == null)
			$this->from_date = date("Y-m-d", strtotime(date("Y-m-d") . " -30 DAY"));

		if($this->to_date == null )
			$this->to_date = date("Y-m-d");

		if($_GET['xai-analytical-from-date']) $this->from_date = $_GET['xai-analytical-from-date'];
		if($_GET['xai-analytical-to-date']) $this->to_date = $_GET['xai-analytical-to-date'];

		$form = $this->app->layout->add('Form',null,null, array('form_horizontal'));
		$form->addField('DatePicker','from_date')->set($this->from_date);
		$form->addField('DatePicker','to_date')->set($this->to_date);
		$form->addSubmit('Update');

		if($form->isSubmitted()){
			$form->js()->reload(array(
					'xai-analytical-from-date' => $form['from_date']?:0,
					'xai-analytical-to-date' => $form['to_date']?:0
				))->execute();
		}
		
		$main_cols = $this->add('Columns');
		$app_dash = $main_cols->addColumn(12);
		// $manager_report_col = $main_cols->addColumn(4);

		$graphs = $this->add('xAi/Model_VisualAnalytic')->addCondition('push_to_analytic_dashboard',true)->setOrder('analytic_dashboard_order')->setLimit(4);
		$i=0;
		foreach ($graphs as $junk) {
			$analytic_to_draw = $this->add('xAi/Model_VisualAnalytic')->load($junk['id']);
			if($i==0) $row = $app_dash->add('Columns');
			if($analytic_to_draw['visual_style']=='grid'){
					$col = $row->addColumn(1 * ($analytic_to_draw['span_on_analytic_dashboard']?:1));
					$dch = $col->app->layout->add('xAi/View_DynamicDataTable',array('analytic'=> $analytic_to_draw,'from_date'=>$this->from_date,'to_date'=>$this->to_date));
			}else{
				$col = $row->addColumn(1 * ($analytic_to_draw['span_on_analytic_dashboard']?:1));
				$dch = $col->app->layout->add('xAi/View_DynamicChart',array('analytic'=> $analytic_to_draw,'from_date'=>$this->from_date,'to_date'=>$this->to_date));
				$dch->chart->options['legend'] = array('layout'=> 'horizontal', 'align'=> 'center', 'verticalAlign'=> 'bottom');
			}

			// $dch->information_model->_dsql()->where('updated_at','>',date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s').' -3 MINUTE')));
			$i++;
		}

	}
}
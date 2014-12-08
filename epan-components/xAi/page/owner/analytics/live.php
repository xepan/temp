<?php


class page_xAi_page_owner_analytics_live extends page_xAi_page_owner_main {
	
	function init(){
		parent::init();
		
		$main_cols = $this->app->layout->add('Columns');
		$app_dash = $main_cols->addColumn(12);
		// $manager_report_col = $main_cols->addColumn(4);

		$graphs = $this->add('xAi/Model_VisualAnalytic')->addCondition('push_to_live_dashboard',true)->setOrder('live_dashboard_order')->setLimit(4);
		$i=0;
		foreach ($graphs as $junk) {
			$analytic_to_draw = $this->add('xAi/Model_VisualAnalytic')->load($junk['id']);
			if($i==0) $row = $app_dash->add('Columns');
			if($analytic_to_draw['visual_style']=='grid'){
					$col = $row->addColumn(1 * ($analytic_to_draw['span_on_live_dashboard']?:1));
					$dch = $col->add('xAi/View_DynamicDataTable',array('analytic'=> $analytic_to_draw));
			}else{
				$col = $row->addColumn(1 * ($analytic_to_draw['span_on_live_dashboard']?:1));
				$dch = $col->add('xAi/View_DynamicChart',array('analytic'=> $analytic_to_draw));
				$dch->chart->options['legend'] = array('layout'=> 'horizontal', 'align'=> 'center', 'verticalAlign'=> 'bottom');
			}

			$dch->information_model->_dsql()->where('updated_at','>',date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s').' -3 MINUTE')));
			$i++;
		}
	}
}
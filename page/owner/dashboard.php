<?php

class page_owner_dashboard extends page_base_owner {
	function init() {
		parent::init();

		$this->app->layout->add( 'H3' )->setHTML('<i class="fa fa-dashboard"></i> '. strtoupper($this->api->current_website['name']) . " Dashboard <small>One shot view for your Website/Application</small>" );

		$main_cols = $this->app->layout->add('Columns');
		$app_dash = $main_cols->addColumn(8);
		$manager_report_col = $main_cols->addColumn(4);

		$xAi_installed = $this->add('Model_InstalledComponents');
		$xAi_installed->addCondition('name','xAtrificial Intelligence');
		$xAi_installed->tryLoadAny();
		if($xAi_installed->loaded()){
			$graphs = $this->add('xAi/Model_VisualAnalytic')->addCondition('push_to_main_dashboard',true)->setOrder('main_dashboard_order')->setLimit(4);
			$i=0;
			foreach ($graphs as $junk) {
				$analytic_to_draw = $this->add('xAi/Model_VisualAnalytic')->load($junk['id']);
				if($i==0) $row = $app_dash->add('Columns');
				if($analytic_to_draw['visual_style']=='grid'){
						$col = $row->addColumn(1 * ($analytic_to_draw['span_on_main_dashboard']?:1));
						$dch = $col->add('xAi/View_DynamicDataTable',array('analytic'=> $analytic_to_draw));
				}else{
					$col = $row->addColumn(1 * ($analytic_to_draw['span_on_main_dashboard']?:1));
					$dch = $col->add('xAi/View_DynamicChart',array('analytic'=> $analytic_to_draw));
					$dch->chart->options['legend'] = array('layout'=> 'horizontal', 'align'=> 'center', 'verticalAlign'=> 'bottom');
				}

				$i++;
			}
			
			$manager_view = $manager_report_col->add('View')->addClass('list-group');
			$manager_view_heading_view =$manager_view->add('View')->setElement('a')->addClass('list-group-item active text-center')->setAttr('href','#');
			$manager_view_heading = $manager_view_heading_view->add('H3')->addClass('list-group-item-heading')->set('xAi : Manager Report');
			$manager_view_heading_text = $manager_view_heading_view->add('View')->addClass('list-group-item-text')->set('Reserved space for full Ai version, Subscribe to get notified');

			$temp_array=array('success','info','warning','danger');
			$mind_array=array('Task Report','Suggession','Thinking');
			for($i=0;$i<=8;$i++){
				$type = $temp_array[array_rand($temp_array)];
				$mind = $mind_array[array_rand($mind_array)];
				$manager_view->add('View')->addClass('list-group-item alert alert-'.$type)->setHTML('<i class="glyphicon glyphicon-'.$type.'-sign"></i> Some Random '. $mind);
			}

		}


	}
}
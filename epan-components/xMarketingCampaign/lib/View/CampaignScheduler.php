<?php

namespace xMarketingCampaign;

class View_CampaignScheduler extends \View{
	function init(){
		parent::init();

		$this->app->layout->add('View_Error')->set('hello');
	}

	function render(){
		$options=array("editable"=> true,'header'=>array('left'=>'prev,next today','center'=> 'title'));
		// defaultDate: '2014-11-12',
		// 	editable: true,
		// 	eventLimit: true

		// header: {
		// 		left: 'prev,next today',
		// 		center: 'title',
		// 		right: 'month,agendaWeek,agendaDay'
		// 	},
		$this->js(true)->_load('full-calendar/lib/moment.min')->_load('full-calendar/fullcalendar.min')->fullCalendar($options);
		parent::render();
	}

	function defaultTemplate(){
		$this->app->pathfinder->base_location->addRelativeLocation(
		    'epan-components/'.__NAMESPACE__, array(
		        'php'=>'lib',
		        'template'=>'templates',
		        'css'=>'templates/css',
		        'js'=>'templates/js',
		    )
		);

		return array('view/calendar');
	}

}
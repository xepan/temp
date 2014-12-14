<?php

namespace xMarketingCampaign;

class View_CampaignScheduler extends \View{
	public $calendar_options = array();
	
	function init(){
		parent::init();
		$this->calendar_options = array("editable"=> true,'header'=>array('left'=>'prev,next today','center'=> 'title','right'=> 'month,agendaWeek,agendaDay'));
		// $this->app->layout->add('View_Error')->set('hello');
		if($what=$_GET[$this->name.'_event_type']){

			$func=$_GET[$this->name.'_event_act'].ucfirst($what);

			$this->$func($_GET[$this->name.'_event_id'], $_GET[$this->name.'_ondate']);
			// $s[] = $this->js()->univ()->successMessage($_GET[$this->name.'_ondate']);
			// $s[] = $this->js()->fullCalendar('removeEvents',array($_GET[$this->name.'_event_jsid']));
			// $s=array();
			// echo implode(";", $s);
			// exit;
		}
	}

	function addEvent($newsletter_id,$on_date){
		return true;
	}

	function setModel($model){
		$events = $this->getEvents($model);
		$this->calendar_options = array_merge($this->calendar_options,array('events'=>$events));
		parent::setModel($model);
	}

	function getEvents($campaign){
		$events = array();
		$news_letters_events = $campaign->ref('xMarketingCampaign/CampaignNewsLetter');
		foreach ($news_letters_events as $junk) {
			$events[] = array('title'=>$news_letters_events['newsletter'],'start'=>$news_letters_events['posting_date'], 'color'=>'#922');
		}

		$social_events = $campaign->ref('xMarketingCampaign/CampaignSocialPost');
		foreach ($social_events as $junk) {
			$events[] = array('title'=>$social_events['socialpost'],'start'=>$social_events['post_on_datetime'],'color'=>'#7a7');
		}

		return $events;
	}

	function render(){
		
		// defaultDate: '2014-11-12',
		// 	editable: true,
		// 	eventLimit: true

		// header: {
		// 		left: 'prev,next today',
		// 		center: 'title',
		// 		right: 'month,agendaWeek,agendaDay'
		// 	},
		$this->js(true)->_load('full-calendar/lib/moment.min')->_load('full-calendar/fullcalendar.min')->_load('campaigncalendar')->univ()->campaigncalendar($this,$this->calendar_options, $this->api->url(null), $this->name, $this->model->id);
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
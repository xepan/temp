<?php

namespace xMarketingCampaign;

class View_CampaignScheduler extends \View{
	public $calendar_options = array();
	
	function init(){
		parent::init();
		$this->calendar_options = array("editable"=> true,'header'=>array('left'=>'prev,next today','center'=> 'title','right'=> 'month,agendaWeek,agendaDay'));
		// $this->app->layout->add('View_Error')->set('hello');
		if($what=$_GET[$this->name.'_event_type']){

			$func=$_GET[$this->name.'_event_act'].$what;

			$this->$func($_GET[$this->name.'_event_id'], $_GET[$this->name.'_ondate']);
			$s=array();
			$s[] = $this->js()->univ()->successMessage("Done");
			// $s[] = $this->js()->fullCalendar('removeEvents',array($_GET[$this->name.'_event_jsid']));
			echo implode(";", $s);
			exit;
		}
	}

	function moveNewsLetter($newsletter_id,$on_date){
		$save = 0;
		$campaign = $this->add('xMarketingCampaign/Model_Campaign')->load($_GET['campaign_id']);	
		$campaign_start_date = strtotime($campaign['starting_date']);
		$campaign_end_date = strtotime($campaign['ending_date']);
		$duration = $this->add('xDate')->diff(date('Y-m-d 00:00:00',strtotime($on_date)),$campaign['starting_date'],'days');
		
		if($campaign['effective_start_date'] == "CampaignDate"){
			if(strtotime($on_date) > $campaign_start_date and $campaign_end_date >= strtotime($on_date)){
				$campaign_newsletter_model = $this->add('xMarketingCampaign/Model_CampaignNewsLetter');
				if(!$campaign_newsletter_model->isExist($newsletter_id,$_GET['campaign_id'],$duration)){
					$save = 1;
				}

			}

		}
			
		if($save){

		}else{
			$s=array();
			$s[]= $this->js()->fullCalendar('removeEvents',array($_GET[$this->name.'_event_jsid']));
			$s[]= $this->js()->univ()->errorMessage('Could Not Saved');
			echo implode(";", $s);
			exit;
		}

	}

	function addNewsLetter($newsletter_id,$on_date){
		$save = 0;
		$campaign = $this->add('xMarketingCampaign/Model_Campaign')->load($_GET['campaign_id']);	
		$campaign_start_date = strtotime($campaign['starting_date']);
		$campaign_end_date = strtotime($campaign['ending_date']);
		$duration = $this->add('xDate')->diff(date('Y-m-d 00:00:00',strtotime($on_date)),$campaign['starting_date'],'days');

		switch ($campaign['effective_start_date']) {
			case 'SubscriptionDate':
				$s=array();
				$s[]= $this->js()->fullCalendar('removeEvents',array($_GET[$this->name.'_event_jsid']));
				$s[]= $this->js()->univ()->errorMessage('Campaign start from Subscription Date ');
				echo implode(";", $s);
				exit;				
				break;

			case 'CampaignDate':
				if(strtotime($on_date) > $campaign_start_date and $campaign_end_date >= strtotime($on_date)){
					$campaign_newsletter_model = $this->add('xMarketingCampaign/Model_CampaignNewsLetter');
					if(!$campaign_newsletter_model->isExist($newsletter_id,$_GET['campaign_id'],$duration))
						$save = 1;						
				}	
				break;		
		}

		if($save){
			$campaign_newsletter_model = $this->add('xMarketingCampaign/Model_CampaignNewsLetter');
			return $campaign_newsletter_model->createNew($newsletter_id,$_GET['campaign_id'],$duration);
		}

		$s=array();
		$s[]= $this->js()->fullCalendar('removeEvents',array($_GET[$this->name.'_event_jsid']));
		$s[]= $this->js()->univ()->errorMessage('Could Not Saved');
		echo implode(";", $s);
		exit;
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
			$events[] = array('title'=>$news_letters_events['newsletter'],'start'=>$news_letters_events['posting_date'], 'color'=>'#922', "_eventtype"=> "NewsLetter", "_nid"=> $news_letters_events['newsletter_id']);
		}

		$social_events = $campaign->ref('xMarketingCampaign/CampaignSocialPost');
		foreach ($social_events as $junk) {
			$events[] = array('title'=>$social_events['socialpost'],'start'=>$social_events['post_on_datetime'],'color'=>'#7a7', "_eventtype"=> "SocialPost");
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
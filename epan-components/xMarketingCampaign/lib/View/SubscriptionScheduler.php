<?php

namespace xMarketingCampaign;


class View_SubscriptionScheduler extends \View{
	
	public $calendar_options=array();

	function init(){
		parent::init();

		if($_GET[$this->name.'_event_act']){
			$this->js()->univ()->successMessage($_GET[$this->name.'_event_act']. ' ' . $_GET[$this->name.'_onday'])->execute();
			exit;
		}
		
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
			$events[] = array('title'=>$news_letters_events['newsletter'],'day'=>$news_letters_events['duration'], 'color'=>'#922', "_eventtype"=> "NewsLetter", "_nid"=> $news_letters_events['newsletter_id']);
		}

		// print_r($events);
		return $events;
	}

	function render(){
		// $this->app->jui->addInclude('subscriptioncalendar');
		$this->calendar_options['url']=$this->api->url(null);
		$this->calendar_options['schedular_name']=$this->name;
		$this->calendar_options['campaign_id']=$this->model->id;

		$this->js(true)->xepan_subscriptioncalander($this->calendar_options);
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

		return array('view/subscriptioncalendar');
	}
}
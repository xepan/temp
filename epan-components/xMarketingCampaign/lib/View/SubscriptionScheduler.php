<?php

namespace xMarketingCampaign;


class View_SubscriptionScheduler extends \View{
	
	function init(){
		parent::init();
		
	}

	function render(){
		$this->app->jui->addInclude('subscriptioncalendar');
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
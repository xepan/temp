<?php

namespace xMarketingCampaign;

// Model Post

// Model Post Activity/Comments

class Controller_SocialPosters_Base_Social extends \AbstractController{

	function login_status(){
		return "Oops";
	}

	function config_page(){
		echo "Oops";
	}

	function get_post_fields_using(){
		return array('title','image','255');
	}

	function post($params){
		
	}

}
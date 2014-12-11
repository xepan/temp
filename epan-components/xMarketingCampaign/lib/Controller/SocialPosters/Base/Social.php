<?php

namespace xMarketingCampaign;

class Model_SocialConfig extends \Model_Table{
	public $table='xMarketingCampaign_SocialConfig';

	function init(){
		parent::init();

		$this->addField('social_app')->mandatory(true)->system(true); // Must Be Set In Extended class

		$this->addField('name');
		$this->addField('appId');
		$this->addField('secret');
		$this->addField('post_in_groups')->type('boolean')->defaultValue(true);
		$this->addField('filter_repeated_posts')->type("boolean")->defaultValue(true);

		$this->hasMany('xMarketingCampaign/SocialUsers','config_id');

		$this->addHook('beforeDelete',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeDelete(){
		$this->ref('xMarketingCampaign/SocialUsers')->deleteAll();
	}

}

class Model_SocialUsers extends \Model_Table{
	public $table='xMarketingCampaign_SocialUsers';

	function init(){
		parent::init();
		$this->hasOne('xMarketingCampaign/SocialConfig','config_id');
		
		$this->addField('name');
		$this->addField('userid');
		$this->addField('userid_returned');
		$this->addField('access_token')->system(false)->type('text');
		$this->addField('access_token_secret')->system(false)->type('text');
		$this->addField('access_token_expiry')->system(false)->type('datetime');
		$this->addField('is_access_token_valid')->type('boolean')->defaultValue(false)->system(true);
		$this->addField('is_active')->type('boolean')->defaultValue(true);

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}

// Model Post

class Model_SocialPosting extends \SQL_Model{
	public $table="xMarketingCampaign_SocialPostings";

	function init(){
		parent::init();

		$this->hasOne('xMarketingCampaign/Model_SocialUsers','user_id');
		$this->hasOne('xMarketingCampaign/SocialPost','post_id');

		$this->addField('postid_returned'); // Rturned by social site 
		$this->addField('posted_on')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));

		$this->addField('likes'); // Change Caption in subsequent extended social controller, if nesecorry
		$this->addField('share'); // Change Caption in subsequent extended social controller, if nesecorry

		$this->hasMany('xMarketingCampaign/Activity','posting_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}


// Model Post Activity/Comments
class Model_Activity extends \SQL_Model{
	public $tabel = "xMarketingCampaign_SocialPostings_Activities";

	function init(){
		parent::init();
		$this->hasOne('xMarketingCampaign/Model_SocialPosting','posting_id');

		$this->addField('activity_type');
		$this->addField('activity_on')->type('datetime'); // NOT DEFAuLT .. MUst get WHEN actual activity happened from social sites

		$this->addField('activity_by');// Get the user from social site who did it.. might be an id of the user on that social site
		$this->addField('name')->caption('Activity');

		$this->add('dynamic_model/Controller_AutoCreator');		
	}

}

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
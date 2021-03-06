<?php

namespace xMarketingCampaign;

// EXTRA  MODELS DEFINED AT THE BOTTOM OF THIS FILES

class Controller_SocialPosters_Facebook extends Controller_SocialPosters_Base_Social {
	public $fb=null;
	public $config=null;

	function init(){
		parent::init();
		require_once('epan-components/xMarketingCampaign/lib/Controller/SocialPosters/Facebook/facebook.php');
	}

	function login_status(){
		$config_model = $this->add('xMarketingCampaign/Model_FacebookConfig');
		$config_model->tryLoad($_GET['for_config_id']);

		if(!$config_model->loaded()){
			$this->add('View_Error')->set('Could not load Config Model');
			return false;
		}

		$config = array(
		      'appId' => $config_model['appId'],
		      'secret' => $config_model['secret'],
		      'fileUpload' => true, // optional
		      'allowSignedRequest' => false, // optional, but should be set to false for non-canvas apps
		  );

		$this->fb = $facebook = new \Facebook($config);

		if(!$this->fb){
			echo "Configuration Problem";
			return false;
		}
		
		$user_id = $this->fb->getUser();
		if(!$user_id){
			$login_url = $this->fb->getLoginUrl(array('scope'=>'publish_actions,status_update,publish_stream,user_groups','redirect_uri'=>'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?page=xMarketingCampaign_page_socialafterloginhandler&xfrom=Facebook&for_config_id='.$config_model->id));
		  	echo '<a class="btn btn-danger btn-xs" href="'.$login_url.'">Login</a>';
		}else{
			if($this->after_login_handler())
				$this->add('View_Info')->set('Access Token Updated');
			else
				$this->add('View_Error')->set('Access Token Not Updated');

			// $this->config['userid_returned'] = $user_id;
			// $this->config->save();
		 //  	return '<a class="btn btn-success btn-xs" href="#" onclick="javascript:'.$this->owner->js()->reload(array('facebook_logout'=>1)).'">Logout</a>';
		}

	}

	function after_login_handler(){
		
		$config_model = $this->add('xMarketingCampaign/Model_FacebookConfig');
		$config_model->tryLoad($_GET['for_config_id']);

		if(!$config_model->loaded()){
			$this->add('View_Error')->set('Could not load Config Model');
			return false;
		}

		$config = array(
		      'appId' => $config_model['appId'],
		      'secret' => $config_model['secret'],
		      'fileUpload' => true, // optional
		      'allowSignedRequest' => false, // optional, but should be set to false for non-canvas apps
		  );

		$this->fb = $facebook = new \Facebook($config);

		if(!$this->fb){
			return "Configuration Problem";
		}

		$user_id = $this->fb->getUser();
		
		if(!$user_id){
			$login_url = $this->fb->getLoginUrl(array('scope'=>'publish_actions,status_update,publish_stream,user_groups','redirect_uri'=>'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?page=xMarketingCampaign_page_socialafterloginhandler&xfrom=Facebook&for_config_id='.$config_model->id));
			echo "<a href='$login_url'>Login URL $login_url</a>";
			return false;

		}


		$this->fb->setExtendedAccessToken();
		$new_token = $this->fb->getAccessToken();

		$fb_user = $this->add('xMarketingCampaign/Model_SocialUsers');
		$fb_user->addCondition('userid_returned',$user_id);
		$fb_user->addCondition('config_id',$config_model->id);
		$fb_user->tryLoadAny();

		$user_profile = $this->fb->api('/me','GET',array('access_token'=>$new_token));
        $fb_user['name']= $user_profile['name'];
        

		$fb_user['access_token'] = $new_token;
		$fb_user['is_access_token_valid']= true;
		$fb_user->save();

		return true;
	}


	function config_page(){
		$c=$this->owner->add('CRUD');
		$c->setModel('xMarketingCampaign/FacebookConfig');

		$users_crud = $c->addRef('xMarketingCampaign/SocialUsers',array('label'=>'Users'));

		if($c->grid and !$users_crud){
			$f = $c->addFrame('Login URL');

			if($f){
				$config_model = $this->add('xMarketingCampaign/Model_FacebookConfig');
				$config_model->load($c->id);
				$config = array(
			      'appId' => $config_model['appId'],
			      'secret' => $config_model['secret'],
			      'fileUpload' => true, // optional
			      'allowSignedRequest' => false, // optional, but should be set to false for non-canvas apps
				);

				$facebook = new \Facebook($config);
				$f->add('View')->setElement('a')->setAttr('href','index.php?page=xMarketingCampaign_page_socialloginmanager&social_login_to=Facebook&for_config_id='.$config_model->id)->setAttr('target','_blank')->set('index.php?page=xMarketingCampaign_page_socialloginmanager&social_login_to=Facebook&for_config_id='.$config_model->id);
			}
			$c->add('Controller_FormBeautifier');
		}

	}

	function postSingle($user_model,$params,$post_in_groups=true, &$groups_posted=array(),$under_campaign_id=0){
		if(! $user_model instanceof xMarketingCampaign\Model_SocialUsers AND !$user_model->loaded()){
			throw $this->exception('User must be a loaded model of Social User Type','Growl');
		}

		$post_content=array();
	  		
  		$api='feed';
  		if($params['post_title']) $post_content['title'] = $params['post_title'];
  		if($params['url']) $post_content['link'] = $params['url'];
  		if($params['image']){
  			
  			if(!$params['url']) $api='photos';

  			$post_content['ImageSource'] = '@'.realpath($params['image']);
  		} 

  		if($params['message_255_chars']) $post_content['message'] = $params['message_255_chars'];
  		$post_content['access_token'] = $user_model['access_token'];


  		$config_model = $user_model->ref('config_id');

  		$config = array(
				      'appId' => $config_model['appId'],
				      'secret' => $config_model['secret'],
				      'fileUpload' => true, // optional
				      'allowSignedRequest' => false, // optional, but should be set to false for non-canvas apps
				  );

  		$this->fb = $facebook = new \Facebook($config);
		$this->fb->setFileUploadSupport(true);

		$ret_obj = $this->fb->api('/'. $user_model['userid_returned'] .'/'.$api, 'POST',
			  								$post_content
		                                 );
		$social_posting_save = $this->add('xMarketingCampaign/Model_SocialPosting');
		$social_posting_save->create($user_model->id, $params->id, $ret_obj['id'], 'Status Update', 0,"", $under_campaign_id);

		if($post_in_groups){
			$groups = $this->fb->api('/'. $user_model['userid_returned'] .'/groups', 'GET',array('access_token'=>$user_model['access_token']));
			print_r($groups);
			foreach ($groups['data'] as $grp) {
				if(!in_array($grp['id'],$groups_posted)  OR !$config_model['filter_repeated_posts']){
			  		try{
			  			$ret_obj = $this->fb->api('/'. $grp['id'] .'/'.$api, 'POST',$post_content);
			  			$social_posting_save->create($user_model->id, $params->id, $ret_obj['id'], 'Group Post', $grp['id'], $grp['name'], $under_campaign_id);
				  		$groups_posted[] = $grp['id'];
			  		}catch(\Exception $e){
			  			continue;
			  		}
	  			}
	  		}
		}

		/*
	single post return obj 
		Array ( [id] => 1518888648358366_1533808290199735 ) 
	groups object
		Array ( [data] => Array ( [0] => Array ( [name] => Xavoc [bookmark_order] => 1 [id] => 274814329235304 ) ) [paging] => Array ( [next] => https://graph.facebook.com/v2.2/1518888648358366/groups?icon_size=16&limit=5000&offset=5000&__after_id=enc_Aez2nUTN78cytXqKvhbxXh4ViCEVLM0tPMjot6flXqvQGqVi7S9NBj5JqD9ckHORo533WmjCyiWZM_erXbC6oPlK ) ) 
		*/

	}

	function postAll($params,$under_campaign_id=0){ // all social post row as hash array or model

	  	try{

	  		$groups_posted=array();
	  		
	  		$config_model = $this->add('xMarketingCampaign/Model_FacebookConfig');
	  		foreach ($config_model as $junk) {
	  			
		  		$users=$config_model->ref('xMarketingCampaign/SocialUsers');
		  		$users->addCondition('is_active',true);

		  		foreach ($users as $junk) {
		  			$this->postSingle($users,$params,$config_model['post_in_groups'], $groups_posted, $under_campaign_id);
		  		}
		  	}

	  	}catch(\Exception $e){

	  		echo "<h2>".$e->getMessage()."</h2>";
	  		// print_r($post_content);
	  	}
	  	
	}

	function get_post_fields_using(){
		return array('title','url','image','255');
	}

	function icon($only_css_class=false){
		if($only_css_class) 
			return "fa fa-facebook";
		return "<i class='fa fa-facebook'></i>";
	}

	function profileURL($user_id_pk, $other_user_id=false){
		if(!$other_user_id){
			$user = $this->add('xMarketingCampaign/Model_SocialUsers')->tryLoad($user_id_pk);
			if(!$user->loaded()) return false;
			$other_user_id = $user['userid_returned'];
			$name=$user['name'];
		}else{
			$id_name_array=explode("_", $other_user_id);
			$other_user_id=$id_name_array[0];
			$name=$id_name_array[1];
		}

		return array('url'=>"https://www.facebook.com/app_scoped_user_id/". $other_user_id ."/",'name'=>$name);
		// return "https://www.facebook.com/profile.php?id=".$user['userid'];
	}

	function postURL($post_id_returned){
		$post = $this->add('xMarketingCampaign/Model_SocialPosting')->tryLoadBy('postid_returned',$post_id_returned);
		if(!$post->loaded()) return false;
		
		$user= $post->ref('user_id');
		if(!$user['userid']) return false;
		
		$post_id_returned = explode("_", $post_id_returned);
		if(count($post_id_returned) !=2) return false;

		$post_id_returned = $post_id_returned[1];

		return "https://www.facebook.com/permalink.php?story_fbid=".$post_id_returned."&id=".$user['userid'];
		throw $this->exception('Define in extnding class');
	}

	function groupURL($group_id){
		throw $this->exception('Define in extnding class');
	}

	function updateActivities($posting_model){
		if(! $posting_model instanceof xMarketingCampaign\Model_SocialPosting and !$posting_model->loaded())
			throw $this->exception('Posting Model must be a loaded instance of Model_SocialPosting','Growl');

		$user_model = $posting_model->ref('user_id');

		$config_model = $user_model->ref('config_id');

  		$config = array(
				      'appId' => $config_model['appId'],
				      'secret' => $config_model['secret'],
				      'fileUpload' => true, // optional
				      'allowSignedRequest' => false, // optional, but should be set to false for non-canvas apps
				  );

  		$post_content['access_token'] = $user_model['access_token'];

  		$this->fb = $facebook = new \Facebook($config);
		$this->fb->setFileUploadSupport(true);

		$post_id_returned = explode("_", $posting_model['postid_returned']);
		if(count($post_id_returned) !=2) return false;

		$post_id_returned = $post_id_returned[1];


  		$post_content['summary'] = 'true';

  		// likes
		$likes = $this->fb->api('/'. $post_id_returned .'/likes', 'GET',
			  								$post_content
		                                 );

		$posting_model->updateLikesCount($likes['summary']['total_count']);

		// shares
		$share = $this->fb->api('/'. $post_id_returned .'/share', 'GET',
			  								$post_content
		                                 );

		$posting_model->updateShareCount($share['summary']['total_count']);

		// comments

		$comments = $this->fb->api('/'. $post_id_returned .'/comments', 'GET',
			  								$post_content
		                                 );

		foreach ($comments['data'] as $comment) {
			$activity = $this->add('xMarketingCampaign/Model_Activity');
			$activity->addCondition('posting_id',$posting_model->id);
			$activity->addCondition('activityid_returned',$comment['id']);
			$activity->tryLoadAny();

			$activity['activity_type']='Comment';
			$activity['activity_on']=$comment['created_time'];
			$activity['activity_by']=$comment['from']['id'].'_'.$comment['from']['name'];
			$activity['name']=$comment['message'];
			if($comment['like_count']) $activity['name'] = $activity['name'] . '<br><i class="fa fa-thumbs-up">'.$comment['like_count'].'</i>';
			$activity['action_allowed']=$comment['can_remove']?'can_remove':'';
			$activity->save();

		}

	}

	function comment($posting_model,$msg){
		if(! $posting_model instanceof xMarketingCampaign\Model_SocialPosting and !$posting_model->loaded())
			throw $this->exception('Posting Model must be a loaded instance of Model_SocialPosting','Growl');

		$user_model = $posting_model->ref('user_id');

		$config_model = $user_model->ref('config_id');

  		$config = array(
				      'appId' => $config_model['appId'],
				      'secret' => $config_model['secret'],
				      'fileUpload' => true, // optional
				      'allowSignedRequest' => false, // optional, but should be set to false for non-canvas apps
				  );

  		$post_content['access_token'] = $user_model['access_token'];

  		$this->fb = $facebook = new \Facebook($config);
		$this->fb->setFileUploadSupport(true);

		$post_id_returned = explode("_", $posting_model['postid_returned']);
		if(count($post_id_returned) !=2) return false;

		$post_id_returned = $post_id_returned[1];


  		$post_content['message'] = $msg;

  		// response
		$response = $this->fb->api('/'. $post_id_returned .'/comments', 'POST',
			  								$post_content
		                                 );
		$this->updateActivities($posting_model);
	}

}


class Model_FacebookConfig extends Model_SocialConfig {
	function init(){
		parent::init();
		$this->getElement('social_app')->defaultValue('Facebook');
		$this->addCondition('social_app','Facebook');

	}
}

class Model_FacebookUsers extends xMarketingCampaign\Model_SocialUsers {}

class Model_FacebookPosting extends xMarketingCampaign\Model_SocialPosting {}

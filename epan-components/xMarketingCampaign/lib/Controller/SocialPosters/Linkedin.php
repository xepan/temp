<?php

namespace xMarketingCampaign;

// EXTRA  MODELS DEFINED AT THE BOTTOM OF THIS FILES

class Controller_SocialPosters_Linkedin extends Controller_SocialPosters_Base_Social{
	public $client=null;
	public $client_config=null;

	function init(){
		parent::init();

		require_once('epan-components/xMarketingCampaign/lib/Controller/SocialPosters/Base/http.php');
		require_once('epan-components/xMarketingCampaign/lib/Controller/SocialPosters/Base/oauth/client/class.php');
		
		
		// if($_GET['facebook_logout']){
		// 	$this->fb->destroySession();
		// }
	}

	function setup_client($client_config_id){
		$this->client_config = $client_config = $this->add('xMarketingCampaign/Model_LinkedinConfig')->load($client_config_id);
		
		$this->client = $client = new \oauth_client_class;
		$client->debug = 1;
		$client->debug_http = 1;
		$client->server = 'LinkedIn';
		$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?page=xMarketingCampaign_page_socialafterloginhandler&xfrom=Linkedin&client_config_id='.$client_config_id;

		$client->client_id = $this->client_config['appId']; $application_line = __LINE__;
		$client->client_secret = $this->client_config['secret'];
		// $client->access_token = $this->client_config['access_token'];

		/*  API permission scopes
		 *  Separate scopes with a space, not with +
		 */
		$client->scope = 'rw_company_admin w_messages r_basicprofile r_contactinfo r_fullprofile r_network r_emailaddress rw_nus rw_groups';
		$client->Initialize();
	}

	function login_status(){
		$this->setup_client($_GET['for_config_id']);

		$client = $this->client;

		$client->ResetAccessToken();

		if(($success = $client->Initialize()))
		{
			if(($success = $client->Process()))
			{
				if(strlen($client->access_token))
				{
					$success = $client->CallAPI(
						'http://api.linkedin.com/v1/people/~', 
						'GET', array(
							'format'=>'json'
						), array('FailOnAccessError'=>true), $user);
				}
			}
			$success = $client->Finalize($success);
		}
		if($client->exit){
			exit;
		}
		
		if(strlen($client->authorization_error))
		{
			$client->error = $client->authorization_error;
			$success = false;
		}

		if($success){
			// echo $this->client->access_token;
			$this->client_config['access_token'] = $this->client->access_token;
			$this->client_config->save();
			return;
		}else{
			echo $client->authorization_error;
		}

		$client->Output();

	}

	function after_login_handler(){
		$this->setup_client($_GET['for_config_id']);
		
		if(!$this->client){
			return "Configuration Problem";
		}

		$client = $this->client;

		if(($success = $client->Initialize()))
		{
			if(($success = $client->Process()))
			{
				if(strlen($client->access_token))
				{
					$success = $client->CallAPI(
						'http://api.linkedin.com/v1/people/~', 
						'GET', array(
							'format'=>'json'
						), array('FailOnAccessError'=>true), $user);
				}
			}
			$success = $client->Finalize($success);
		}
		if($client->exit)
			exit;
		if(strlen($client->authorization_error))
		{
			$client->error = $client->authorization_error;
			$success = false;
		}

		if($success){
			// print_r($this->client);

			$fetched_url=$user->siteStandardProfileRequest->url;

			preg_match_all("/.*\?id=(\d*).*/", $fetched_url,$user_id);
			// echo "dadsa" .$user_id[1][0];
			// echo $this->client->access_token;
			
			$li_user= $this->add('xMarketingCampaign/Model_SocialUsers');
			$li_user->addCondition('userid_returned',$user_id[1][0]);
			$li_user->addCondition('config_id',$this->client_config->id);
			$li_user->tryLoadAny();

			$li_user['name'] = $user->firstName;
			$li_user['access_token'] = $this->client->access_token;
			$li_user['access_token_secret'] = $this->client->access_token_secret;
			$li_user['access_token_expiry'] = $this->client->access_token_expiry;
			$li_user->save();
			return true;
		}
		throw new \Exception("Error Processing Request", 1);
		
		return false;

	}


	function config_page(){
		$c=$this->owner->add('CRUD',array('allow_add'=>false,'allow_del'=>false));
		$c->setModel('xMarketingCampaign/LinkedinConfig');
		
		$users_crud = $c->addRef('xMarketingCampaign/SocialUsers',array('label'=>'Users'));

		if($c->grid and !$users_crud){
			$f=$c->addFrame('Login URL');
			if($f){
				$f->add('View')->setElement('a')->setAttr('href','index.php?page=xMarketingCampaign_page_socialloginmanager&social_login_to=Facebook&for_config_id='.$config_model->id)->setAttr('target','_blank')->set('index.php?page=xMarketingCampaign_page_socialloginmanager&social_login_to=Linkedin&for_config_id='.$config_model->id);
			}
		}

		$c->add('Controller_FormBeautifier');
	}

	function postSingle($user_model,$params,$post_in_groups=true, &$groups_posted=array(),$under_campaign_id=0){
		if(! $user_model instanceof xMarketingCampaign\Model_SocialUsers AND !$user_model->loaded()){
			throw $this->exception('User must be a loaded model of Social User Type','Growl');
		}

		$config_model = $user_model->ref('config_id');

		$this->setup_client($config_model->id);

  		$client = $this->client;
  		$client->access_token = $user_model['access_token'];
		$client->access_token_secret = $user_model['access_token_secret'];

		// echo $client->access_token;
		// exit;

  		$parameters = new \stdClass;

  		$activity_type =false;
		if($params['url'] and $params['image']){
	  		$activity_type = 'shares';
			// Its a share 

			/*
				<?xml version="1.0" encoding="UTF-8"?>
				<share>
				    <comment>83% of employers will use social media to hire: 78% LinkedIn, 55% Facebook, 45% Twitter [SF Biz Times] http://bit.ly/cCpeOD</comment>
				    <content>
				        <title>Survey: Social networks top hiring tool - San Francisco Business Times</title>
				        <submitted-url>http://sanfrancisco.bizjournals.com/sanfrancisco/stories/2010/06/28/daily34.html</submitted-url>
				        <submitted-image-url>http://images.bizjournals.com/travel/cityscapes/thumbs/sm_sanfrancisco.jpg</submitted-image-url>
				    </content>
				    <visibility>
				        <code>anyone</code>
				    </visibility>
				</share>
			*/

			$parameters = new \stdClass;
			$parameters->content = new \stdClass;
			$parameters->visibility = new \stdClass;
			$parameters->visibility->code = 'anyone';
	  		if($params['post_title']) $parameters->content->title = $params['post_title'];
	  		if($params['message_255_chars']) $parameters->comment = $params['message_255_chars'];
	  		
	  		$parameters->content->{'submitted-url'} = $params['url'];
	  		$parameters->content->{'submitted-image-url'} = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/' .$params['image'];



		}

		if(!$params['url'] and !$params['image']){
	  		$activity_type = 'person-activities';
	  		// its network update
			/*	<?xml version='1.0' encoding='UTF-8'?>
				<activity locale="en_US">
			    	<content-type>linkedin-html</content-type>
			    	<body>&amp;lt;a href=&amp;quot;http://www.linkedin.com/profile?viewProfile=&amp;amp;key=3639896&amp;amp;authToken=JdAa&amp;amp;authType=name&amp;amp;trk=api*a119686*s128146*&amp;quot;&amp;gt;Kirsten Jones&amp;lt;/a&amp;gt; is reading about &amp;lt;a href=&amp;quot;http://www.tigers.com&amp;quot;&amp;gt;Tigers&amp;lt;/a&amp;gt;http://www.tigers.com&amp;gt;Tigers&amp;lt;/a&amp;gt;..</body>
				</activity>
			*/
			$parameters = new \stdClass;
					$parameters->{'content-type'} = 'linkedin-html';
			  		// if($params['post_title']) $parameters->content->title = $params['post_title'];
			  		if($params['message_255_chars']) $parameters->body = $params['message_255_chars'];
		}

		if(!$activity_type) return;

		// $success = $client->CallAPI('http://api.linkedin.com/v1/people/~/'.$activity_type.'?format=json','POST', $parameters, array('FailOnAccessError'=>true, 'RequestContentType'=>'application/json'), $new_post);
		// $success = $client->Finalize($success);

		$social_posting_save = $this->add('xMarketingCampaign/Model_SocialPosting');
		// $social_posting_save->create($user_model->id, $params->id, $new_post->updateKey, $activity_type, 0,"", $under_campaign_id);
		

		// Post in all groups

		if($post_in_groups){
			$success = $client->CallAPI(
						'http://api.linkedin.com/v1/people/~/group-memberships',
						'GET', null, array('FailOnAccessError'=>true, 'RequestContentType'=>'application/json'), $groups);

			$groups =simplexml_load_string($groups);
			$groups = json_encode($groups);
			$groups = json_decode($groups,true);
			// echo "<pre>";
			// print_r($groups['group-membership']);
			$parameters->title = $parameters->content->title;
			$parameters->summary = $parameters->comment;

			unset($parameters->visibility);
			unset($parameters->comment);

			if(isset($groups['group-membership'])){
				foreach ($groups['group-membership'] as $grp) {
					// print_r($grp);
					$grp_id= $grp['group']['id'];
					// echo $grp_id ."<br/>";
					if(!in_array($grp_id, $groups_posted) OR !$this->client_config['filter_repeated_posts']){
						try{

							$success = $client->CallAPI(
								'http://api.linkedin.com/v1/groups/'.$grp_id.'/posts?format=json',
								'POST', $parameters, array('FailOnAccessError'=>true, 'RequestContentType'=>'application/json'), $group_post, $headers);
							// echo $headers['location'];
							$success = $client->Finalize($success);
							$groups_posted[] = $grp_id;

							// Get Grup post URL 
							$group_post_id = explode("/",$headers['location']);
							$group_post_id = $group_post_id[count($group_post_id)-1];
							$success = $client->CallAPI(
								'http://api.linkedin.com/v1/posts/'.$group_post_id.':(site-group-post-url)?format=json',
								'GET', $parameters, array('FailOnAccessError'=>true, 'RequestContentType'=>'application/json'), $group_post_url, $headers);

							$social_posting_save->create($user_model->id, $params->id, $group_post_url->siteGroupPostUrl, 'Group Post', $headers['location'], $grp['group']['name'], $under_campaign_id);
						}catch(\Exception $e){
							print_r($headers);
							throw $e;
							continue;
						}
					}
				}
			}

		}

	}

	function postAll($params,$under_campaign_id=0){ // all social post row as hash array or model

  		$groups_posted=array();

  		$config_model = $this->add('xMarketingCampaign/Model_LinkedinConfig');
  		foreach ($config_model as $junk) {	  			
	  		$users=$config_model->ref('xMarketingCampaign/SocialUsers');
	  		$users->addCondition('is_active',true);

	  		foreach ($users as $junk) {
	  			$this->postSingle($users,$params,$config_model['post_in_groups'], $groups_posted, $under_campaign_id);
	  		}
	  	}	  	
	}

	function icon($only_css_class=false){
		if($only_css_class) 
			return "fa fa-linkedin";
		return "<i class='fa fa-linkedin'></i>";
	}

	function profileURL($user_id_pk){
		$user = $this->add('xMarketingCampaign/Model_SocialUsers')->tryLoad($user_id_pk);
		if(!$user->loaded()) return false;

		return "https://www.linkedin.com/profile/view?id=".$user['userid_returned'];
		// return "https://www.facebook.com/profile.php?id=".$user['userid'];
	}

	function postURL($post_id_returned){

		$post = $this->add('xMarketingCampaign/Model_SocialPosting')->tryLoadBy('postid_returned',$post_id_returned);
		if(!$post->loaded()) return false;
				
		$post_id_returned_array = explode("_", $post_id_returned);
		$topic_id = $post_id_returned_array[count($post_id_returned_array)-1];
		if(count($post_id_returned) ==3){
			// UPDATE-384280894-5949163916801175552 Its a share
			return "https://www.linkedin.com/nhome/updates?topic=".$topic_id;
		}

		return $post_id_returned;

	}

	function groupURL($group_id){
		throw $this->exception('Define in extnding class');
	}

	function updateActivities($posting_model){
		throw $this->exception('Define in extnding class');
	}

	function comment($posting_model){
		throw $this->exception('Define in extnding class');
	}

	function get_post_fields_using(){
		return array('title','url','image','255');
	}
}


class Model_LinkedinConfig extends Model_SocialConfig {
	function init(){
		parent::init();
		$this->getElement('social_app')->defaultValue('Linkedin');
		$this->addCondition('social_app','Linkedin');

	}
}

class Model_LinkedinUsers extends xMarketingCampaign\Model_SocialUsers {}

class Model_LinkedinPosting extends xMarketingCampaign\Model_SocialPosting {}

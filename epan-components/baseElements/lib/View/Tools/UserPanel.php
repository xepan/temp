<?php

namespace baseElements;

class View_Tools_UserPanel extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	
	function init(){
		parent::init();
		$user_panel_name="Login ID";
		$user_panel_pass="Password";
		$user_panel_btn_login_name="Login";
		$user_panel_btn_registration_name="registration";
		$user_panel_verify="Verify";
		$user_panel_forgot_pass="Forgot password";
		$user_panel_activation_code="Re send Activation Code";
		$user_panel_btn_Verify_name="Verify";
		$user_panel_login_btn_css = "btn-block btn btn-success";

		if($this->html_attributes['user_panel_name'])
			$user_panel_name=$this->html_attributes['user_panel_name'];

		if($this->html_attributes['user_panel_pass'])
			$user_panel_pass=$this->html_attributes['user_panel_pass'];

		if($this->html_attributes['user_panel_verify_caption'])
			$user_panel_verify=$this->html_attributes['user_panel_verify_caption'];

		if($this->html_attributes['user_panel_btn_login_name'])
			$user_panel_btn_login_name=$this->html_attributes['user_panel_btn_login_name'];

		if($this->html_attributes['user_panel_verify_caption'])
			$user_panel_btn_Verify_name=$this->html_attributes['user_panel_verify_caption'];
		
		if($this->html_attributes['user_panel_forgot_pass'])
			$user_panel_forgot_pass=$this->html_attributes['user_panel_forgot_pass'];

		if($this->html_attributes['user_panel_activation_code'])
			$user_panel_activation_code=$this->html_attributes['user_panel_activation_code'];

		if($this->html_attributes['user_panel_btn_registration_name'])
			$user_panel_btn_registration_name=$this->html_attributes['user_panel_btn_registration_name'];
		
		if($this->html_attributes['user_panel_login_btn_css'])
			$user_panel_login_btn_css = $this->html_attributes['user_panel_login_btn_css'];
		
		
		if(!$this->api->auth->isLoggedIn()){
			$this->api->stickyGET('new_registration');
			$this->api->stickyGET('verify_account');

			if($_GET['new_registration']){
				$r_form = $this->add('Form');
				$r_form->addField('line','first_name')->validateNotNull(true);
				$r_form->addField('line','last_name')->validateNotNull(true);
				$r_form->addField('line','email_id')->validateNotNull()->validateField('filter_var($this->get(), FILTER_VALIDATE_EMAIL)');
				$r_form->addField('password','password')->validateNotNull(true);
				$r_form->addField('password','re_password')->validateNotNull(true);				

				$custome_field = $this->add('Model_UserCustomFields');
				if($custome_field->getCount($this->api->current_website->id) > 0){
					foreach ($custome_field as $junk) {		
						if($junk['type']=='captcha'){
							$captcha_field=$r_form->addField('line','captcha');
							$captcha_field->belowField()->add('H4')->set('Please enter the code shown above');
							$captcha_field->add('x_captcha/Controller_Captcha');
							}
							// elseif($junk['mandatory']){
							// 	$field=$r_form->addField($custome_field['type'],$this->api->normalizeName($custome_field['name']),$custome_field['name'])->validateNotNull(true);
							else{
								$field=$r_form->addField($custome_field['type'],$this->api->normalizeName($custome_field['name']),$custome_field['name']);
							}

						if($custome_field['is_expandable']){		
							$new_arr =explode(',', $custome_field['set_value']);
							$to_put=array();
							foreach ($new_arr as $value) {
								$to_put[$value] = $value;
							}
							$field->setValueList($to_put);
						}

						if($custome_field['change']){
							$to_array  = json_decode($custome_field['change'],true);
							// echo $custome_field['change'];
							// print_r($to_array);
							$normalized_array=array();
							foreach ($to_array as $val => $fields) {
								foreach ($fields as &$fld) {
									$normalized_array[$val][]=$this->api->normalizeName($fld);
								}
							}
							// print_r($normalized_array);

							$field->js(true)->univ()->bindConditionalShow($normalized_array,'div .atk-form-row');
						}

					}

				}
										
				$r_form->addSubmit('submit')->set('Register');

				if($r_form->isSubmitted()){
					if( $r_form['password'] != $r_form['re_password']){
						$r_form->displayError('password','Password not match');
					}
					// throw new \Exception("Error Processing Request", 1);
					
					// check mandatories 
					$form= $r_form;
					$custome_field_mendatory_check = $this->add('Model_UserCustomFields');
					$custome_field_mendatory_check->addCondition('epan_id',$this->api->current_website->id);

					foreach ($custome_field_mendatory_check as $junk) {
						if($junk['mandatory']){
							// check if it is in any condistional show
							$allfields_with_custom = $this->add('Model_UserCustomFields');
							$allfields_with_custom->addCondition('epan_id',$this->api->current_website->id);
							$allfields_with_custom->addCondition(
								$allfields_with_custom->dsql()->orExpr()
									->where('change','<>','')
									->where('change','<>',null)
								);
							$allfields_with_custom->addCondition('change','like','%'.$junk['name'].'%');
							$found_in_condition=false;
							foreach ($allfields_with_custom as $junk_all_field) {
							// if yes
								// check if fields value is the one when it is shown
								$change_array = json_decode($allfields_with_custom['change'],true);
								if(in_array($junk['name'], $change_array[$value])){
										$found_in_condition=true;
								}
								foreach ($change_array as $value => $fields_to_show) {
									if( $form[$this->api->normalizeName($allfields_with_custom['name'])] == $value AND in_array($junk['name'], $change_array[$value]) and !$form[$this->api->normalizeName($junk['name'])]){
									// if empty
										// display error
										$form->displayError($this->api->normalizeName($junk['name']),'Must fill');
									}
								}
								
							}
							// if no
								// if value is empty display error
							if(!$found_in_condition and !$form[$this->api->normalizeName($custome_field_mendatory_check['name'])])
								$form->displayError($this->api->normalizeName($custome_field_mendatory_check['name']),'Must Fill ...');
						}
					}

					// $form->displayError('first_name','hahaha121212');

					$user_model=$this->add('Model_Users');
					$user_model['name'] = $r_form['first_name']." ".$r_form['last_name'];
					$user_model['email'] = $r_form['email_id'];
					$user_model['username'] = $r_form['email_id'];
					$user_model['password'] = $r_form['password'];
					$user_model['created_at'] = date('Y-m-d');
					$user_model['type'] = 50;
					$user_model['activation_code'] = rand(999,10000);
					$user_model['epan_id'] = $this->api->current_website->id;
					
					if($this->api->current_website['user_activation']=='default_activated'){
						$user_model['is_active'] = 1;
						$user_model->save();
						}
					elseif($this->api->current_website['user_activation']=='self_activated'){							
						$user_model->save();
						$user_model->sendVerificationMail($user_model['email'],null,$user_model['activation_code']);
					}else{											
						$user_model->save();						
					}

					$custome_field_model = $this->add('Model_UserCustomFields');
					$custome_field_model->addCondition('epan_id',$this->api->current_website->id);
					
					$allFields = $form->getAllFields();
					
					foreach ($custome_field_model as $junk) {
						$custom_field_value_model = $this->add('Model_UserCustomFieldValue');
						$custom_field_value_model->createNew($user_model['id'],$junk['id'],$allFields[$this->api->normalizeName($junk['name'])]);
					}

					//Todo for executing plugin after user craeted in epan model
					$user_model_value = array($user_model);
					$this->api->exec_plugins('new_user_registered',$user_model_value);
					// end of plugin call				
					$this->js(null,$this->js()->univ()->successMessage('Created Successfully'))->reload()->execute();		
				}

			}elseif($_GET['verify_account']){
				
				$verify_user_model=$this->add('Model_Users');	
							
				$verify_form=$this->add('Form');
				$verify_form->addField('line','email_id');
				$verify_form->addField('line','verification_code');
				$verify_form->addSubmit('Submit');

				if($_GET['activation_code']){
					$verify_form['verification_code']=$_GET['activation_code'];
				}

				if($_GET['activate_email']){
					$verify_form['email_id']=$_GET['activate_email'];
				}

				if($verify_form->isSubmitted()){
					if(!$verify_user_model->verifyAccount($verify_form['email_id'],$verify_form['verification_code'])){
						$verify_form->js(null,$this->js()->univ()->errorMessage('Try Again'))->reload()->execute();		
					}					
					$this->api->stickyForget('verify_account');								
					$verify_form->js(null,$this->js()->univ()->successMessage('Account Verify Successfully'))->reload()->execute();	
				} 
			}
			elseif($_GET['forgot_password_view']){
				$this->api->stickyGET('forgot_password_view');
				$resend_form = $this->add('Form');
				$resend_form->addClass('stacked');
				$email_field = $resend_form->addField('line','email','Registered Email Id')->validateNotNull()->validateField('filter_var($this->get(), FILTER_VALIDATE_EMAIL)');
				$email_field->setAttr('PlaceHolder','Enter your Registerd E-mail Id.');
				$btn = $resend_form->addSubmit('Send Activation Code');	
				
				if($resend_form->isSubmitted()){
					$user = $this->add('Model_Users');
					$user->addCondition('email',$resend_form['email']);
					$user->tryLoadAny();
					if($user->loaded())	{
						$user->sendVerificationMail($resend_form['email'],null,$user['activation_code']);											
						$resend_form->js(null,$this->js()->univ()->successMessage('Activation code Send to Registered Email id'))->reload(array('verify_account'=>1))->execute();							
						// $this->js('click',$this->js()->reload(array('verify_account'=>1)));		
					}
					$resend_form->js(null,$this->js()->univ()->errorMessage('Wrong Email id'))->reload()->execute();							
				}		
			}
			else{
				// create login form
				if($this->html_attributes['login_view']){
					$this->add('View')->set('Login')->setElement('a')->setAttr('href','index.php?subpage='.$this->html_attributes['user_panel_redirect_page']);
				}else{

				$form=$this->add('Form');
				if($this->html_attributes['form_stacked_on'])
					$form->addClass('stacked');
				
				$username_field = $form->addField('line','username',$user_panel_name)->validateNotNull();
				$password_field = $form->addField('password','password',$user_panel_pass)->validateNotNull();

				if($this->html_attributes['user_panel_username_placeholder'])
					$username_field->setAttr('placeHolder',$this->html_attributes['user_panel_username_placeholder']);
				if($this->html_attributes['user_panel_password_placeholder'])
					$password_field->setAttr('placeHolder',$this->html_attributes['user_panel_password_placeholder']);
				
				//add submit form
				// $submit_field = $form->addSubmit($user_panel_btn_login_name);
				$submit_field = $form->addButton($user_panel_btn_login_name);
				$submit_field->addClass($user_panel_login_btn_css);
				$submit_field->js('click',$form->js()->submit());
				$cols = $this->add('Columns');

				if($this->html_attributes['show_register_new_user']){
					$col = $cols->addColumn(4);
					$sign_up_field = $col->add('View')->setHTML($user_panel_btn_registration_name);
					//  = $form->add('Button')->set($user_panel_btn_registration_name);
					// $col->add($sign_up_field);
					$sign_up_field->js('click',$this->js()->reload(array('new_registration'=>1)));
				}
				
				if($this->html_attributes['show_forgot_password']){
					$col = $cols->addColumn(4);
					$forgot_field = $col->add('View')->setHTML($user_panel_forgot_pass);	
					$this->html_attributes['show_forgot_password'] = 0;										
					$forgot_field->js('click',$this->js()->reload(array('forgot_password_view'=>1)));
				}	
				
				// if($this->html_attributes['user_panel_activation_code']){
				// 	$activation_field = $form->add('View')->setHTML($user_panel_activation_code)->setElement('a')->setAttr('href',$this->api->url(null,array('subpage'=>$this->html_attributes['user_panel_activation_code'])));
				// }	
				
				if($this->html_attributes['show_verify_me']){
					$col = $cols->addColumn(4);
					$verify_account=$col->add('View')->setHTML($user_panel_btn_Verify_name);
					$verify_account->js('click',$this->js()->reload(array('verify_account'=>1)));
				}
				// $submit_field->js(true)->appendTo($col);//->add($submit_field);
				// $form->js(true)->find('.atk-buttons')->removeClass('atk-buttons');
				
				if($form->isSubmitted()){
					$user_model = $this->add('Model_Users');
					$user_model->addCondition('username',$form['username']);
					$user_model->addCondition('password',$form['password']);
					$user_model->tryLoadAny();

					if(!$user_model->loaded())
						$form->displayError('username','Wrong Credentials');

					if(!$user_model['is_active'])
						$form->displayError('username','Please Activate Your Account First');

					//save into Cookies
					//end of saving into cookies	

					$this->api->auth->login($user_model);
					// if reload page
						$this->js()->univ()->redirect($this->api->url(null,array('subpage'=>$this->html_attributes['user_panel_after_login_page'])))->execute();
					// else
						$this->js()->reload()->execute();
				}
				}
			}

		}else{
			// create hello user panel
			if($this->html_attributes['user_panel_show_logout_view']){
				$cols=$this->add('Columns');
				$leftcol=$cols->addColumn(10);
				$rightcol=$cols->addColumn(2);
				$leftcol->add('View')->set('Hello'." ".$this->api->auth->model['username']);
				$rightcol->add('View')->set('Logout')->setElement('a')->setAttr('href','index.php?page=logout');
			}

			if($this->html_attributes['user_panel_after_login_page']){
				// if($this->api->auth->model['is_active'])
					// $this->js(true)->univ()->redirect($this->api->url(null,array('subpage'=>$this->html_attributes['user_panel_after_login_page'])));
			}				
		}

	}

	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}
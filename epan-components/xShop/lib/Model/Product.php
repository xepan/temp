<?php

namespace xShop;

class Model_Product extends \Model_Table{
	public $table='xshop_products';
	public $table_alias='Item';

	function init(){
		parent::init();	
		$f = $this->hasOne('xShop/Supplier','supplier_id')->group('a~6');
		$f->icon = "fa fa-user~blue";
		$f = $this->hasOne('xShop/Manufacturer','manufacturer_id')->group('a~6');
		$f->icon = "fa fa-user~blue";
		// $f->group='a/6';
		//TODO for Mutiple Epan website
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);

		$f = $this->addField('name')->mandatory(true)->group('b~6');
		$f->icon = "fa fa-puzzle-piece~red";
		$f = $this->addField('sku')->PlaceHolder('Insert Unique Referance Code')->caption('Code')->hint('Place your unique product code ')->mandatory(true)->group('b~4');
		$f->icon = "fa fa-puzzle-piece~red";
		$f = $this->addField('is_publish')->type('boolean')->defaultValue(true)->group('b~2');
		$f->icon = "fa fa-exclamation~blue";

		$f = $this->addField('short_description')->type('text')->group('d~6');//->display(array('form'=>'RichText'));
		$f->icon = "fa fa-pencil~blue";
		$f = $this->addField('original_price')->mandatory(true)->group('d~3');
		$f->icon = "fa fa-money~blue";
		$f = $this->addField('sale_price')->type('int')->mandatory(true)->group('d~3');
		$f->icon = "fa fa-money~blue";
		$f = $this->addField('rank_weight')->defaultValue(0)->hint('Higher Rank Weight Product Display First')->mandatory(true)->group('d~6~dl');
		$f->icon = "glyphicon glyphicon-sort-by-attributes~blue";
		$f = $this->addField('created_at')->type('date')->defaultValue(date('Y-m-d'))->group('d~3~dl');				
		$f->icon = "fa fa-calendar~blue";
		$f = $this->addField('expiry_date')->type('date')->group('d~3~dl');
		$f->icon = "fa fa-calendar~blue";
		$f = $this->addField('description')->type('text')->display(array('form'=>'RichText'))->group('g~12');
		$f->icon = "fa fa-pencil~blue";
		
		//Item Allow Optins
		$f = $this->addField('allow_attachment')->type('boolean')->group('f~3~<i class=\'fa fa-cog\' > Item Allow Options</i>');
		$f->icon = "fa fa-folder-open~blue";		
		$f = $this->addField('allow_saleable')->type('boolean')->group('f~3');
		$f->icon = "fa fa-shopping-cart~blue";		
		$f = $this->addField('allow_enquiry')->type('boolean')->group('f~3');
		$f->icon = "fa fa-envelope~blue";		

		//Search String
		$this->addField('search_string')->type('text')->system(true);

		//Item Display Options
		$f = $this->addField('show_offer')->type('boolean')->group('i~2~<i class=\'fa fa-cog\' > Item Display Options</i>');
		$f->icon = "glyphicon glyphicon-eye-open~#337ab7";		
		$f = $this->addField('show_detail')->type('boolean')->defaultValue(true)->group('i~2~Item');
		$f->icon = "glyphicon glyphicon-eye-open~#337ab7";		
		$f = $this->addField('show_price')->type('boolean')->group('i~2');
		$f->icon = "glyphicon glyphicon-eye-open~#337ab7";		
		$f = $this->addField('show_manufacturer_detail')->type('boolean')->caption('Manufacturer Detail')->group('i~2~Item Display Options');
		$f->icon = "glyphicon glyphicon-eye-open~#337ab7";		
		$f = $this->addField('show_supplier_detail')->type('boolean')->caption('Supplier Detail')->group('i~2~Item Display Options');
		$f->icon = "glyphicon glyphicon-eye-open~#337ab7";		

		//Marked
		$f = $this->addField('new')->type('boolean')->caption('New')->defaultValue(true)->group('m~3~<i class=\'fa fa-cog\' > Marked Options</i>');
		$f->icon = "glyphicon glyphicon-pushpin~#5cb85c";
		$f = $this->addField('feature')->type('boolean')->caption('Featured')->group('m~3');
		$f->icon = "glyphicon glyphicon-pushpin~#337ab7";
		$f = $this->addField('latest')->type('boolean')->caption('Latest')->group('m~3');
		$f->icon = "glyphicon glyphicon-pushpin~#f0ad4e";
		$f = $this->addField('mostviewed')->type('boolean')->caption('Most Viewed')->group('m~3');
		$f->icon = "glyphicon glyphicon-pushpin~#5bc0de";
		
		//Enquiry Send To		
		$f = $this->addField('enquiry_send_to_self')->caption('Self/ Owner')->type('boolean')->group('e~3~<i class=\'fa fa-cog\' > Enquiry Send To</i>');
		$f->icon = "glyphicon glyphicon-send~#5cb85c";		
		$f = $this->addField('enquiry_send_to_supplier')->caption('Supplier')->type('boolean')->group('e~3');
		$f->icon = "glyphicon glyphicon-send~#5cb85c";		
		$f= $this->addField('enquiry_send_to_manufacturer')->caption('Manufacturer')->type('boolean')->group('e~3');
		$f->icon = "glyphicon glyphicon-send~#5cb85c";		
		$f = $this->addField('product_enquiry_auto_reply')->caption('Item Enquiry Auto Reply')->type('boolean')->group('e~3');
		$f->icon = "glyphicon glyphicon-send~#5cb85c";		

		//Item Comment Options
		$f = $this->addField('allow_comments')->type('boolean')->group('com~4~<i class=\'fa fa-cog\'> Item Comment Options</i>');
		$f->icon = "glyphicon glyphicon-comment~blue";		
		$f = $this->addField('comment_api')->setValueList(
														array('disqus'=>'Disqus')
														)->group('com~8');
		$f->icon = "glyphicon glyphicon-ok~blue";		

		//Item Other Options	
		$f = $this->addField('add_custom_button')->type('boolean')->group('o~3~<i class=\'fa fa-cog\'> Item Other Options</i>');
		$f = $this->addField('meta_title')->group('o~3~bl');
		$f->icon = "glyphicon glyphicon-pencil~blue";		
		$f = $this->addField('custom_button_text')->group('o~4');
		$f->icon = "glyphicon glyphicon-pencil~blue";		
		$f = $this->addField('meta_description')->type('text')->group('o~4~bl');
		$f->icon = "glyphicon glyphicon-pencil~blue";		
		$f = $this->addField('custom_button_url')->placeHolder('subpage name like registration etc.')->group('o~5');
		$f->icon = "glyphicon glyphicon-pencil~blue";		
		$f = $this->addField('tags')->type('text')->PlaceHolder('Comma Separated Value')->group('o~5~bl');
		$f->icon = "glyphicon glyphicon-pencil~blue";	

		$this->hasMany('xShop/CategoryProduct','product_id');
		$this->hasMany('xShop/ProductImages','product_id');
		$this->hasMany('xShop/CustomFields','product_id');
		$this->hasMany('xShop/Attachments','product_id');
		$this->hasMany('xShop/ProductEnquiry','product_id');
		$this->hasMany('xShop/OrderDetails','product_id');
			
		$this->addHook('beforeSave',$this);
		$this->addHook('beforeDelete',$this);
		$this->add('dynamic_model/Controller_AutoCreator');	
	}

	function beforeSave($m){
		// todo checking SKU value must be unique
		$product_old=$this->add('xShop/Model_Product');
		if($this->loaded())
			$product_old->addCondition('id','<>',$this->id);

		$product_old->addCondition('sku',$this['sku']);		
		$product_old->tryLoadAny();
		//TODO Rank Weight Auto Increment 
		if($product_old->loaded())
			throw new \Exception("Product Code is Allready Exist");


		//do inserting search string for full text search
		// $p_model=$this->add('xShop/Model_Product');
		$this['search_string']= implode(" ", $this->getCategory($this['id'])). " ".
								$this["name"]. " ".
								$this['sku']. " ".
								$this['short_description']. " ".
								$this["description"]. " ".
								$this["meta_title"]. " ".
								$this["meta_description"]. " ".
								$this['sale_price']
							;

	}	

	function getCategory($product_id){
		$cat_pro_model=$this->add('xShop/Model_CategoryProduct');
		$cat_pro_model->addCondition('product_id',$product_id);
		$cat_name=array();
		foreach ($cat_pro_model as $j) {
			$cat_name[]=$cat_pro_model->ref('category_id')->get('name');
		}
		return $cat_name;				
	}

	function beforeDelete($m){
		$order_count = $m->ref('xShop/OrderDetails')->count()->getOne();
		$product_enquiry_count = $m->ref('xShop/ProductEnquiry')->count()->getOne();						
		
		if($this->api->auth->model['type'] and($order_count or $product_enquiry_count)){
			$this->api->js(true)->univ()->errorMessage('Cannot Delete,first delete Orders or Enquiry')->execute();	
		}

		$m->ref('xShop/CategoryProduct')->deleteAll();
		$m->ref('xShop/ProductImages')->deleteAll();
		$m->ref('xShop/CustomFields')->deleteAll();
		$m->ref('xShop/Attachments')->deleteAll();	 
		
	}

	function updateSearchString($product_id){
		if($this->loaded()){
			$this['search_string']= implode(" ", $this->getCategory($this['id'])). " ".
								$this["name"]. " ".
								$this['sku']. " ".
								$this['short_description']. " ".
								$this["description"]. " ".
								$this["meta_title"]. " ".
								$this["meta_description"]. " ".
								$this['sale_price']
							;						
			$this->update();
		}
	}

	function sendEnquiryMail($to_mail,$name=null,$contact=null,$email_id=null,$message=null,$form=null,$product_name,$product_code,$reply_email='0'){
						
		$tm=$this->add( 'TMail_Transport_PHPMailer' );
		$msg=$this->add( 'SMLite' );
		if(!$reply_email){
			$msg->loadTemplate( 'mail/xShop_productenquiry' );		
			$msg_body = "<h3>Enquiry Related to Product:".$product_name."<br> Product Code ".$product_code."</h3>";			
			$msg_body .= "<b>Name : </b>".$name."<br>";
			$msg_body .= "<b>Email id :</b>".$email_id."<br>";
			$msg_body .= "<b>Contact No :</b>".$contact."<br>";
			$msg_body .= "<b>Message : </b>".$message."<br>";
			$msg->trySet('epan',$this->api->current_website['name']);
			$msg->setHTML('custome_form',$msg_body);
			$subject ="You Got An  Enquiry !!!";			
		}else{
			$config_model=$this->add('xShop/Model_Configuration');
			$config_model->tryLoadAny();

			$subject =$config_model['subject'];
			$msg->loadTemplate( 'mail/xShop_productenquiryreply' );		
			$msg_body = $config_model['message'];
			// throw new \Exception("Error Processing Request".$msg_body);			
			$msg_body .= '<b>Product_name : </b>'.$product_name."<br>";
			$msg_body .= '<b>Product_code : </b>'.$product_code."<br>";
			$msg->setHTML('enquiry_product_reply_detail',$msg_body);
		}

		$email_body=$msg->render();
		// throw new \Exception($to_mail);
		
		if($to_mail){
				$tm->send( $to_mail, "", $subject, $email_body ,false,null);
				// throw new \Exception("Error Processing Request mail in send", 1);
			}

	}

	function updateContent($id,$content){
		if($this->loaded())
			throw new \Exception("Model_loaded at time of product");
		$this->load($id);
		$this['description']=$content;
		$this->save();
		return 'true';
	}

	function getProduct($id){
		$this->load($id);				
		return $this;
	}

	function getAllProductCount(){
		if($this->loaded())
			throw new \Exception("Product Model Loaded at Count All Product");	
		return $this->count()->getOne();
	}

	function getPublishCount(){
		if($this->loaded())
			throw new \Exception("Product Model Loaded at Count Active Product");	
		return $this->addCondition('is_publish',true)->count();
	}

	function getUnpublishCount(){
		if($this->loaded())
			throw new \Exception("Product Model Loaded at Count Unactive Product");	
		return $this->addCondition('is_publish',false)->count();	
	}
}


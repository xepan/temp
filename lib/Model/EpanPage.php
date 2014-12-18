<?php

class Model_EpanPage extends Model_Table {
	var $table= "epan_page";
	
	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		
		$f=$this->hasOne('EpanPage','parent_page_id')->defaultValue(0);
		$f->group = 'tmp~6';
		$f->icon='fa fa-arrow-up~blue';
		
		$f=$this->hasOne('EpanTemplates','template_id');
		$f->group='tmp~6';
		$f->icon='fa fa-clipboard~blue';

		$f=$this->addField('name')->caption('Url')->group('a~4~<i class="fa fa-link"></i> Page Access Details')->sortable(true); // Menu name for this page default is 'Home'
		$f->icon="fa fa-anchor~red";
		$f=$this->addField('menu_caption')->caption('Menu')->hint('Leave blank if you don\'t want page in menus')->group('a~4')->sortable(true); // Menu name for this page default is 'Home'
		$f->icon = 'fa fa-eye~blue';

		$f=$this->addField('access_level')->setValueList(array(0=>'Public',50=>'Registered User',80=>'Back End User',100=>'Super user'))->defaultValue('public')->mandatory(true)->group('a~4')->sortable(true);
		$f->icon= 'fa fa-unlock-alt~red';
		$this->addField('is_template')->type('boolean')->defaultValue(false);

		$this->addField('title')->type('text')->hint('Title shown at Title Bar for your Page, Imp for SEO')->group('seo~12~<i class="fa fa-globe"></i> SEO Information');
		$this->addField('description')->type('text')->hint('Title shown at Title Bar for your Page, Imp for SEO')->group('seo~12~bl');
		$this->addField('keywords')->type('text')->hint('Title shown at Title Bar for your Page, Imp for SEO')->group('seo~12~bl');

		$this->addField('content')->type('text');
		$this->addField('body_attributes')->type('text');

		$this->addField('created_on')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));
		$this->addField('updated_on')->type('datetime');


		$this->hasMany('EpanPageSnapshots','epan_page_id');
		$this->hasMany('EpanPage','parent_page_id');

		$this->addHook('beforeSave',$this);
		$this->addHook('beforeInsert',$this);
		$this->addHook('afterInsert',$this);
		$this->addHook('beforeDelete',$this);

		$this->setOrder('created_on');
		// $this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){

		if($this->ref('epan_id')->get('name') == 'demo')
			$this->api->js()->univ()->errorMessage('Not Available in demo')->execute();

		if(!$this->loaded() AND $this['name']=='')
			throw $this->exception('URL must be filled','ValidityCheck')->setField('name');

		if($this->loaded() AND $this['name'] == '') $this['name']='home';

		// CHECK URL PATTERN
		if(!preg_match('/^[a-z0-9\-]+$/', $this['name']))
			throw $this->exception('URL can only contain lowercase alphabets, numbers and - ' . $this['name'] ,'ValidityCheck')->setField('name');


		if(strlen($this['name'])>60)
			throw $this->exception('Max URL length: 60 Characters only, while it is '.strlen($this['name']));

		if($this['name'] == 'home' and $this['access_level']!=0)
			throw $this->exception('Home Page must be public','ValidityCheck')->setField('access_level');

		// IF Title, Keywords or Description is not Fed, Take from Epan Details
		if(trim($this['title'])=='') $this['title'] = $this->ref('epan_id')->get('name');
		if(trim($this['keywords'])=='') $this['keywords'] = $this->ref('epan_id')->get('keywords');
		if(trim($this['description'])=='') $this['description'] = $this->ref('epan_id')->get('description');
		
		if(!$this['template_id']) $this['template_id'] = $this->add('Model_EpanTemplates')->addCondition('is_current',true)->loadAny()->get('id');

		$this['updated_on']=date('Y-m-d H:i:s');
	}

	function beforeInsert(){
		// count existing page and modules by some joins till their rate
		// now if fund < (page_count+1 * 300 + sum_of_existing_modules_rate) Show error

	}

	function afterInsert($obj,$new_id){

	}

	function beforeDelete(){
		if($this->ref('epan_id')->get('name') == 'demo')
			$this->api->js()->univ()->errorMessage('Not Available in demo')->execute();
		
		foreach($snapshots = $this->ref('EpanPageSnapshots') as $junk){
			$snapshots->delete();
		}

	}

	function generateURI(){
		if($this->api->getConfig('sef_url')){
			return $this['name'];
		}
		return "index.php?subpage=".$this['name'];
	}
}
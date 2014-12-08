<?php

namespace xAi;

class Model_MetaData extends \Model_Table {
	var $table= "xai_meta_data";
	
	function init(){
		parent::init();

		$this->addField('from')->setValueList(array('SERVER'=>'SERVER','SESSION'=>'SESSION','GET'=>'GET','POST'=>'POST','COOKIE'=>'COOKIE'));
		$this->addField('name')->hint('Key of PHP Global variables like "USER_AGENT" from $_SERVER["USER_AGENT"]'); // GET /POST / SESSION /SERVER etc keys
		$this->addField('last_value')->type('text')->caption('Sample Value');
		$this->addField('description')->type('text');
		$this->addField('action')->setValueList(array(-1 => 'New Data', 0=>'Discard',1=>'FirstTimeOnly',2=>'Record Every Time'))->defaultValue(-1);

		$this->hasMany('xAi/Model_InformationExtractor','meta_data_id');

		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}
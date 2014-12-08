<?php

class Model_MarketPlace extends Model_Table {
	var $table= "epan_components_marketplace";
	public $isInstalling=false;	
	
	function init(){
		parent::init();
		
		$f=$this->addField('namespace')->hint('Unique name, Variable rules applied')->mandatory(true)->group('a~4~<i class="fa fa-info"></i> Component Information');
		$f->icon='fa fa-lock~red';
		$f=$this->addField('type')->enum(array('element','module','application','plugin'))->group('a~4');
		$f->icon='fa fa-question~red';
		$f=$this->addField('name')->mandatory(true)->group('a~4');
		$f->icon='fa fa-wrench~red';
		// $this->addField('is_final')->type('boolean')->defaultValue(false);
		// $this->addField('rate')->type('number');
		$this->addField('allowed_children')->hint('comma separated ids of allowed children, mark final for none, and \'all\' for all');
		$this->addField('specific_to')->hint('comma separated ids of specified parent ids only, leave blank for none, and \'body\' for root only');

		$this->addField('is_system')->type('boolean')->defaultValue(false)->hint('System compoenets are not available to user for installation');
		$this->addField('description')->type('text')->display(array('grid'=>'text'));
		// $this->addField('plugin_hooked')->type('text');
		$this->addField('default_enabled')->type('boolean')->defaultValue(true);
		$this->addField('has_toolbar_tools')->type('boolean')->defaultValue(false)->caption('Tools')->group('b~3~<i class="fa fa-cog"></i> Component Options');
		$this->addField('has_owner_modules')->type('boolean')->defaultValue(false)->caption('Owner Module')->group('b~3');
		$this->addField('has_plugins')->type('boolean')->defaultValue(false)->caption('Plugins')->group('b~3');
		$this->addField('has_live_edit_app_page')->type('boolean')->defaultValue(false)->caption('Has Front App')->group('b~3');

		$this->addField('git_path')->group('c~8~<i class="fa fa-github"></i> GitHub Configuration');
		$this->addField('initialize_and_clone_from_git')->type('boolean')->defaultValue(true)->group('c~4');
		$this->addField('category')->hint('admin,components,website,marketing or new ... If unique a new menu will get created.');

		$this->hasMany('InstalledComponents','component_id');
		$this->hasMany('Tools','component_id');
		$this->hasMany('Plugins','component_id');

		$this->addHook('beforeSave',$this);
		$this->addHook('beforeDelete',$this);

		// $this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		if(!$this['type']) throw $this->exception('Please specify type', 'ValidityCheck')->setField('type');

		$existing_check = $this->add('Model_MarketPlace');
		$existing_check->addCondition('id','<>',$this->id);
		$existing_check->addCondition('namespace',$this['namespace']);
		$existing_check->tryLoadAny();

		if($existing_check->loaded())
			throw $this->exception('Name Space Already Used', 'ValidityCheck')->setField('namespace');

		// TODO :: check namespace on server as well...
		if(file_exists(getcwd().DS.'epan-components'.DS.$this['namespace']) and !$this->isInstalling){
			throw $this->exception('namespace folder is already created', 'ValidityCheck')->setField('namespace');
		}

		if(!$this->isInstalling){ //Added in AddComponentTorepository View
			$create_component_folder = true;
			if($this['initialize_and_clone_from_git'] and $this['git_path']){
				$repo=Git::create($dest=getcwd().DS.'epan-components'.DS.$this['namespace'], $this['git_path']);
				$create_component_folder = false;
			}
			$this->createNewFiles($create_component_folder);
		}

	}

	function createNewFiles($create_component_folder=true){
		$source=getcwd().DS.'epan-addons'.DS.'componentStructure'.DS.'namespace';
		$dest=getcwd().DS.'epan-components'.DS.$this['namespace'];

		$this->api->xcopy($source,$dest,$create_component_folder);

		foreach (
		  $iterator = new RecursiveIteratorIterator(
		  new RecursiveDirectoryIterator($dest, RecursiveDirectoryIterator::SKIP_DOTS),
		  RecursiveIteratorIterator::SELF_FIRST) as $item) {
		  if ($item->isDir()) {
		  	continue;
		  } else {
		  	// open file $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName()
		  	// replace {namespace} with $this['namespace']
		  	// save
		  	if(strpos($item, ".git") !== false /* git bypass */) continue;
		  	
		  	$file =$item;
			$file_contents = file_get_contents($file);
			$fr = fopen($file, "r");
			$new_name = str_replace("namespace", $this['namespace'], $file);
			$fw = fopen($new_name, "w");
			
			$file_contents = str_replace('{namespace}',$this['namespace'],$file_contents);
			$file_contents = str_replace('{git_path}',$this['git_path']?'"'.$this['git_path'].'"':'null',$file_contents);

			fwrite($fw, $file_contents);
			fclose($fr);
			fclose($fw);

			if(strpos($file, 'namespace')!==false){
				unlink($file);
			}
		  }
		}
	}

	function reProcessConfig(){
		
		$config_file_path = 'epan-components'.DS.$this['namespace'].DS.'config.xml';
		
		if(!file_exists($config_file_path))
			throw $this->exception('Config file not found')->addMoreInfo('Component',$this['namespace']);

		$config_file_data = file_get_contents($config_file_path);
		$xml = simplexml_load_string( $config_file_data );
		$json = json_encode( $xml );
		$config_array = json_decode( $json, TRUE );
		// set error if not found -- not proper xml
		if ( $config_array['namespace'] == "" ) {
			throw $this->exception('NameSpace not found in config');
			return;
		}

		// check entry in marketplace if this namespace is already used
		$marketplace = $this->add( 'Model_MarketPlace' );
		$marketplace->tryLoadBy( 'namespace', $config_array['namespace'] );

		if ( ! $marketplace->loaded() ) {
			throw $this->exception('Config Update Failed, No Existing Component is installed')->addMoreInfo('Component',$config_array['namespace']);
		}

		$marketplace->ref('Tools')->deleteAll();
		$marketplace->ref('Plugins')->deleteAll();
		// $marketplace->ref('InstalledComponents')->deleteAll();

		// add entry to marketplace table (Model)

		// throw $this->exception('<pre>'.print_r($config_array,true).'</pre>', 'ValidityCheck')->setField('FieldName');

		// $marketplace=$this->add( 'Model_MarketPlace' );
		$marketplace['name']=$config_array['name'];
		$marketplace['namespace']=$config_array['namespace'];
		$marketplace['type']=$config_array['type'];
		$marketplace['is_system']=$config_array['is_system'];
		$marketplace['description']=$config_array['description'];
		$marketplace['default_enabled']=$config_array['default_enabled'];
		$marketplace['has_toolbar_tools']=$config_array['has_toolbar_tools'];
		$marketplace['has_owner_modules']=$config_array['has_owner_modules'];
		$marketplace['has_plugins']=$config_array['has_plugins'];
		$marketplace['has_live_edit_app_page']=$config_array['has_live_edit_app_page'];
		$marketplace['allowed_children']=$config_array['allowed_children'];
		$marketplace['specific_to']=$config_array['specific_to'];
		$marketplace['initialize_and_clone_from_git']=$config_array['initialize_and_clone_from_git'];
		$marketplace['git_path']=(is_array($config_array['git_path']))?'':$config_array['git_path'];
		$marketplace->isInstalling = true;
		$marketplace->save();


		foreach ($config_array['Tools'] as $tools) {
			if(is_array($tools[0])){
				foreach ($tools as $tool_2) {
					$tool = $this->add('Model_Tools');
					$tool['component_id'] = $marketplace->id;
					$tool['name'] = $tool_2['name'];
					$tool['display_name'] = (is_array($tool_2['display_name']))?'':$tool_2['display_name'];
					$tool['order'] = (is_array($tool_2['order']))?'':$tool_2['order'];
					$tool['name'] = $tool_2['name'];
					$tool['is_serverside'] = $tool_2['is_serverside'];
					$tool['is_resizable'] = $tool_2['is_resizable'];
					$tool['is_sortable'] = $tool_2['is_sortable'];
					$tool->isInstalling = true;
					$tool->save();	
				}
				break;
			}else{
				$tool = $this->add('Model_Tools');
				$tool['component_id'] = $marketplace->id;
				$tool['name'] = $tools['name'];
				$tool['display_name'] = (is_array($tools['display_name']))?'':$tools['display_name'];
				$tool['order'] = (is_array($tools['order']))?'':$tools['order'];
				$tool['is_serverside'] = $tools['is_serverside'];
				$tool['is_resizable'] = $tools['is_resizable'];
				$tool['is_sortable'] = $tools['is_sortable'];
				$tool->isInstalling = true;
				$tool->save();
				
			}
		}


		foreach ($config_array['Plugins'] as $plg) {
			if(is_array($plg[0])){
				foreach ($plg as $plg_2) {
					$plg_m = $this->add('Model_Plugins');
					$plg_m['component_id'] = $marketplace->id;
					$plg_m['name'] = $plg_2['name'];
					$plg_m['event'] = $plg_2['event'];
					$plg_m['params'] = $plg_2['params'];
					$plg_m['is_system'] = $plg_2['is_system'];
					$plg_m->isInstalling = true;
					$plg_m->save();
				}
				break;
			}else{
				$plg_m = $this->add('Model_Plugins');
				$plg_m['component_id'] = $marketplace->id;
				$plg_m['name'] = $plg['name'];
				$plg_m['event'] = $plg['event'];
				$plg_m['params'] = $plg['params'];
				$plg_m['is_system'] = $plg['is_system'];
				$plg_m->isInstalling = true;
				$plg_m->save();
			}
		}
	}

	function beforeDelete(){
		if(!($deleted = $this->api->rrmdir($path = getcwd().DS.'epan-components'.DS.$this['namespace']))){
		}

		$this->ref('Plugins')->deleteAll();
		$this->ref('Tools')->deleteAll();
		$this->ref('InstalledComponents')->deleteAll();
		// throw $this->exception($path . ' deleted = '. $deleted, 'ValidityCheck')->setField('FieldName');
	}

	function createPackage($bypasszip=false){
		// Make Db Backup (xml config file ) file

		$xml = new SimpleXMLElement('<xml/>');

		$xml->addChild('name',$this['name']);
		$xml->addChild('namespace',$this['namespace']);
		$xml->addChild('type',$this['type']);
		$xml->addChild('allowed_children',$this['allowed_children']?:0);
		$xml->addChild('specific_to',$this['specific_to']?:0);
		$xml->addChild('is_system',$this['is_system']);
		$xml->addChild('description',$this['description']?:0);
		$xml->addChild('default_enabled',$this['default_enabled']);
		$xml->addChild('has_toolbar_tools',$this['has_toolbar_tools']);
		$xml->addChild('has_owner_modules',$this['has_owner_modules']);
		$xml->addChild('has_live_edit_app_page',$this['has_live_edit_app_page']);
		$xml->addChild('git_path',$this['git_path']?:0);
		$xml->addChild('initialize_and_clone_from_git',$this['initialize_and_clone_from_git']);

		$tools_node = $xml->addChild('Tools');

		foreach ($tools = $this->ref('Tools') as $tools_array) {
			$tool_node = $tools_node->addChild('Tool');
			$tool_node->addChild('name',$tools['name']);
			$tool_node->addChild('display_name',$tools['display_name']);
			$tool_node->addChild('order',$tools['order']);
			$tool_node->addChild('is_serverside',$tools['is_serverside']);
			$tool_node->addChild('is_resizable',$tools['is_resizable']);
			$tool_node->addChild('is_sortable',$tools['is_sortable']);

		}

		$plugins_node = $xml->addChild('Plugins');

		foreach ($plugins = $this->ref('Plugins') as $plugin_array) {
			$plugin_node = $plugins_node->addChild('Plugin');
			$plugin_node->addChild('name',$plugins['name']);
			$plugin_node->addChild('event',$plugins['event']);
			$plugin_node->addChild('params',$plugins['params']);
			$plugin_node->addChild('is_system',$plugins['is_system']);
		}

		file_put_contents(getcwd().DS.'epan-components'.DS.$this['namespace'].DS.'config.xml', $xml->asXML());
		if(!$bypasszip){
			// Zip file
			$component_zip = new zip;
			if(file_exists(getcwd().DS.'epan-components'.DS.$this['namespace'].DS.$this['namespace'].'.zip')){
				unlink(getcwd().DS.'epan-components'.DS.$this['namespace'].DS.$this['namespace'].'.zip');
			}
			$component_zip->makeZip(getcwd().DS.'epan-components'.DS.$this['namespace'].DS.'/.',getcwd().DS.'epan-components'.DS.$this['namespace'].DS.$this['namespace'].'.zip');
			// Download file
			// delete created zip file
		}
	}

}

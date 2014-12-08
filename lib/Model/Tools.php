<?php

class Model_Tools extends Model_Table {
	var $table= "epan_components_tools";
	public $isInstalling=false;

	function init(){
		parent::init();

		$this->hasOne('MarketPlace','component_id');
		
		$f=$this->addField('name')->group('a~8~<i class="fa fa-wrench"></i> Component Tool')->mandatory(true);
		$f->icon='fa fa-wrench~red';

		$f=$this->addField('display_name')->group('a~8~bl');
		$f->icon='fa fa-eye~red';
		
		$f=$this->addField('order')->type('int')->group('a~8~bl');
		$f->icon = 'fa fa-sort-amount-desc~blue';
		
		$f=$this->addField('is_serverside')->type('boolean')->group('a~4');
		$f->icon='fa fa-exclamation~blue';
		$f=$this->addField('is_sortable')->type('boolean')->group('a~4');
		$f->icon='fa fa-exclamation~blue';
		$f=$this->addField('is_resizable')->type('boolean')->group('a~4');
		$f->icon='fa fa-exclamation~blue';

		$this->addHook('beforeSave',$this);
		$this->addHook('afterSave',$this);

		// $this->add('dynamic_model/Controller_AutoCreator');
	}

	function afterSave(){
		if(!$this->isInstalling)
			$this->ref('component_id')->createPackage($bypasszip=true);
	}

	function beforeSave(){
		// Check if tool already exists
		
		if($this->loaded() and $this->dirty['name'])
			throw $this->exception('Tool Name Cannot Be Edited','ValidityCheck')->setField('name');
			

		$namespace = $this->ref('component_id')->get('namespace');

		$check_existing = $this->add('Model_Tools');
		$check_existing->join('epan_components_marketplace','component_id')
						->addField('namespace');
		$check_existing->addCondition('namespace',$namespace);
		$check_existing->addCondition('name',$this['name']);

		if($this->loaded())
			$check_existing->addCondition('id','<>',$this->id);

		$check_existing->tryLoadAny();
		if($check_existing->loaded())
			throw $this->exception('The Tool Name for this component is already used', 'ValidityCheck')->setField('name');


		if(!$this->isInstalling) //Added in AddComponentTorepository View
			$this->createNewFiles();

	}

	function createNewFiles(){
		$namespace = $this->ref('component_id')->get('namespace');
		// Copy tool template files from epan-addons/componentStructure/tool to appropriate locations
		
		// Responsible/Rendering class in tool 'View_Tools_'.$this->api->normalizeName($tool['name'])
		$new_tool_file_name = str_replace("_", "", $this->api->normalizeName($this['name']));
		$new_tool_class_name = 'View_Tools_'.$new_tool_file_name;

		$source_folder = getcwd().DS.'epan-addons'.DS.'componentStructure'.DS.'tool';
		$component_folder = getcwd().DS.'epan-components'.DS.$namespace;

		$new_file = $component_folder.DS.'lib'.DS.'View'.DS.'Tools'.DS.$new_tool_file_name.'.php';
		copy($source_folder.DS.'ViewTool.php',$new_file);
				
		$content = file_get_contents($new_file);
		$content = str_replace("{namespace}", $namespace, $content);
		$content = str_replace("{ToolName}", $new_tool_class_name, $content);

		file_put_contents($new_file, $content);

		
		// Option template for this tool
		$new_file = $component_folder.DS.'templates'.DS.'view'.DS.$namespace.'-'.$new_tool_file_name.'-options.html';
		copy($source_folder.DS.'tooloptions.html',$new_file);

		if(!$this['is_serverside']){
			// Drop template for this tool file copy and rename
			$new_file = $component_folder.DS.'templates'.DS.'view'.DS.$namespace.'-'.$new_tool_file_name.'.html';
			copy($source_folder.DS.'tooltemplate.html',$new_file);			
		}

	}

}
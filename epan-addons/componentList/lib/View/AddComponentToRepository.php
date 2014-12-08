<?php


namespace componentList;

class View_AddComponentToRepository extends \View{
	function init() {
		parent::init();

		if ( $_POST['addcomponenttorepository_form_submitted'] ) {
			// echo "<pre>";
			// print_r($_FILES);
			// echo "</pre>";
			// If not uplaoded sucessfully add error
			if ( $_FILES["component_file"]["error"] > 0 ) {
				$this->add( 'View_Error' )->set( "Error: " . $_FILES["component_file"]["error"] );
			}
			else {
				// only zip files allowed to upload
				if ( $_FILES['component_file']['type'] !='application/x-zip' and $_FILES['component_file']['type'] !='application/zip' ) {
					$this->add( 'View_Error' )->set( 'Component should be uploaded zipped only. found '.$_FILES['component_file']['type']  );
				}else {
					// Search for config.xml file in uploaded zip
					$zip = new \zip();
					$config_file = $zip->readFile( $_FILES['component_file']['tmp_name'], 'config.xml' );
					$config_file_data = $config_file['config.xml']['Data'];
					// if not found set error
					if ( ( $msg=$this->isValidConfig( $config_file_data ) ) !== true ) {
						$this->add( 'View_Error' )->set( $msg );
						return true;
					}

					if(!is_writable(getcwd().DS.'epan-components')){
						$this->add( 'View_Error' )->set( 'Writing Permissions Issue' );
						return;
					}

					// read config.xml and search for epan-system variables
					$xml = simplexml_load_string( $config_file_data );
					$json = json_encode( $xml );
					$config_array = json_decode( $json, TRUE );
					// set error if not found -- not proper xml
					if ( $config_array['namespace'] == "" ) {
						$this->add( 'View_Error' )->set( 'namespace not defined' );
						return;
					}

					// check entry in marketplace if this namespace is already used
					$marketplace = $this->add( 'Model_MarketPlace' );
					$marketplace->tryLoadBy( 'namespace', $config_array['namespace'] );
					if ( $marketplace->loaded() ) {
						if($_POST['replace_existing']){
							// Remove Existing entries
							$marketplace->ref('Tools')->deleteAll();
							$marketplace->ref('Plugins')->deleteAll();
							$marketplace->ref('InstalledComponents')->deleteAll();
							// $marketplace->delete();
						}else{
							$this->add( 'View_Error' )->set( 'This namespace is already used and application is installed.' );
							return;
						}
					}
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


					// extract uploaded zip file to epan-components
					if ( !$zip->extractZip( $_FILES['component_file']['tmp_name'], getcwd().DIRECTORY_SEPERATOR. 'epan-components'.DIRECTORY_SEPERATOR. $config_array['namespace'] ) ) {
						return "Couldn't Extract";
					}

					if($_POST['replace_existing']){
						$this->api->redirect($marketplace['namespace'].'_page_owner_update');
					}

					// TODO Execute install.sql file IF EXISTS

					if(file_exists(getcwd().DIRECTORY_SEPERATOR. 'epan-components'.DIRECTORY_SEPERATOR. $config_array['namespace'].DS. 'install.sql')){
						$sql = file_get_contents(getcwd().DIRECTORY_SEPERATOR. 'epan-components'.DIRECTORY_SEPERATOR. $config_array['namespace'].DS. 'install.sql');
						$this->api->db->dsql($this->api->db->dsql()->expr($sql))->execute();
					}

					// TODO or execute addcomponentpage etc like removecomponent
				}
			}
		}
	}

	function isValidConfig( $config_file_data ) {
		if ( $config_file_data=="" ) return "config.xml file not found";

		return true;
	}

	function defaultTemplate() {

		$this->app->pathfinder->base_location->addRelativeLocation(
		    'epan-addons/'.__NAMESPACE__, array(
		        'php'=>'lib',
		        'template'=>'templates',
		        'css'=>'css',
		        'js'=>'js',
		    )
		);

		return array( 'view/addnewcomponentotrepository' );
	}
}

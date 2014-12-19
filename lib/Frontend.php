<?php

class Frontend extends ApiFrontend{
	/**
	 * stores currrent website model object
	 *
	 * @var Model_Epan
	 */
	public $current_website=null;
	/**
	 * Stores current page about to render
	 *
	 * @var Model_EpanPage
	 */
	public $current_page=null;
	/**
	 * Stores Website requested from GET[config['website_request_variable']]
	 *
	 * @var String
	 */
	public $website_requested=null;
	/**
	 * Stores Page requested from GET[config['page_request_variable']]
	 *
	 * @var String
	 */
	public $page_requested=null;
	/**
	 * Handels CMS mode,
	 * If set to true all editing features will be enabled in frontend side
	 *
	 * @var boolean
	 */
	public $edit_mode=false;

	/**
	 * Edit Template Mode
	 * sets to true if editing Template
	 */
	public $edit_template = false;

	/**
	 * $epan_plugins contains all plugins associated with this website/epan
	 * loaded in frontend
	 *
	 * @var array
	 */
	public $website_plugins=array();


	function init() {
		parent::init();
		$this->requires( 'atk', '4.2.0' );

		if ( !file_exists('config-default.php') ) {
			// Not installed and installation required
			// TODO : check security issues
			$config['url_postfix']='';
			$config['url_prefix']='?page=';

			$this->setConfig($config);
			$this->add( 'jUI' );

			$this->js()
			// ->_load( 'atk4_univ' )
			->_load( 'ui.atk4_notify' )
			;
			
			$this->page = 'install';
		}else {
			// already installed connect to provided settings and go on
			$this->dbConnect();

			$this->addLocation(array(
				'js'=>'atk4/public/atk4/js'
				))->setParent( $this->pathfinder->base_location );

			$this->addLocation(array(
					'addons'=>array( 'epan-addons', 'epan-components', 'atk4-addons' ) )
			)->setParent( $this->pathfinder->base_location );

			$this->addLocation(array(
	            'page'=>array('epan-components','epan-addons'),
	            'js'=>array('templates/js'),
	            'css'=>array('templates/js','templates/css'),
	        ))->setParent($this->pathfinder->base_location);

			$this->add( 'jUI' );
			$this->api->template->appendHTML('js_include','<script src="templates/js/jquery-migrate-1.2.1.min.js"></script>'."\n");
			/**
			 * TODO: wrap in a IF(page does not contains owner_ / branch_ / system_ )
			 * only then you need to get all this, as you are looking front of a website
			 * -----------------------------------------------------------------------
			 * Get the request from browser query string and set various Api variables like
			 * current_website, current_page, website_requested and page_requested
			 * Once set that can be accessed all CMS vise like
			 * $this->api->current_website
			 */
			if ( true /*page does not contain owner_ / branch_ or system_ */ ) {
				$site_parameter= $this->getConfig( 'url_site_parameter' );
				$page_parameter= $this->getConfig( 'url_page_parameter' );

				$this->stickyGET( $site_parameter );
				$this->stickyGET( $page_parameter );

				$this->website_requested = $this->getConfig( 'default_site' );
				/**
				 * $this->page_requested finds and gets the requested page
				 * Always required in both multi site mode and single site mode
				 *
				 * @var String
				 */
				$this->page_requested=trim( $_GET[$page_parameter], '/' )
					?  trim( $_GET[$page_parameter], '/' )
					: $this->getConfig( 'default_page' );

				if ( $this->isAjaxOutput() or $_GET['cut_page'] ) {
					// set page_requested to referrer page not the page requested by
					// ajax request
					$this->add( 'Controller_AjaxRequest' );

				}


				
				$this->current_website = $this->add( 'Model_Epan' )->tryLoadBy( 'name', $this->website_requested );
				if ( $this->current_website->loaded() ) {
					$this->current_page = $this->current_website->ref( 'EpanPage' )
					->addCondition( 'name', $this->page_requested )
					->tryLoadAny();
				}else {
					$this->exec_plugins( 'error404', $this->website_requested );
				}

				$this->add( 'Controller_EpanCMSApp' )->frontEnd();
				
				// MULTISITE CONTROLER
				// $this->load_plugins();
				// $this->add( 'Controller_EpanCMSApp' )->frontEnd();
				// if ( $this->current_website->loaded() )
				// 	$this->exec_plugins( 'website-loaded', $this->api->current_website );
				// if ( $this->current_page->loaded() )
				// 	$this->exec_plugins( 'website-page-loaded', $this->api->page_requested );

			}

			$auth=$this->add( 'BasicAuth' );
			$auth->setModel( 'Users', 'username', 'password' );

			if($this->api->auth->isLoggedIn() AND $this->api->auth->model->ref('epan_id')->get('name')==$this->api->website_requested AND $this->api->auth->model['type'] >= 80){
				$this->edit_mode = true;
			}

			if($_GET['edit_template']){
				$this->edit_template = true;
				// $this->api->template->appendHTML('js_include','\nsfjdhkj;\n');
				$this->stickyGET('edit_template');
			}

			$this->load_plugins();

			// Global Template Setting
			if(in_array('shared', $this->defaultTemplate()) and $this->page == 'index'){
				
				$temp = array();
				$this->exec_plugins('beforeTemplateInit',$temp);

				if($this->edit_template){
					$current_template = $this->add('Model_EpanTemplates')->load($_GET['edit_template']);
				}else{
					$current_template = $this->current_page->ref('template_id');
				}

				if($current_template->loaded()){
					if(!$this->edit_template){
						// Remove contenteditable from template strings
						// In General Page View Mode
						$this->api->exec_plugins('content-fetched',$current_template);
						
					}

					$shared_template = file_get_contents('templates/shared.html');
					/*$content .= '<?$Content?>';*/
					if(!$this->edit_template){
						include_once (getcwd().'/lib/phpQuery.php');
						$pq = new \phpQuery();
						$doc = $pq->newDocument($current_template['content']);
						
						$content_divs = $doc['div:contains("~~Content~~")'];
						$i=0;
						foreach($content_divs as $temp){
							$i++;
						}

						if($i==0){
							$current_template['content'] .= "~~Content~~";
						}


						$current_template['content'] = str_replace("~~Content~~", '{$Content}', $current_template['content']);
						$shared_template = str_replace('{$Content}', $current_template['content'], $shared_template);	
					}else{
						$shared_template = str_replace('{$Content}', $current_template['content'], $shared_template);
						$shared_template .= '{$Content}';
					}

					// Saving since serverside components have been run already 
					// as plugin and they may have set some js_include ect in shared
					// But now shared template is about to load from string and 
					// old includes etc will be lost so ...
					$old_jui = $this->api->jui;
					$old_js_include = $this->template->tags['js_include'];
					// throw new Exception(print_r($old_js_include,true) , 1);
					
					$this->template->loadTemplateFromString($shared_template);
					$this->template->appendHTML('js_include',implode("\n", $old_js_include[1]));
					$this->template->trySet('template_css',$current_template['css']);
					$this->template->trySet('style',$current_template['body_attributes']);
					
					$this->api->jui  = $old_jui;
					
				}
				
				if ( $this->current_website->loaded() )
					$this->exec_plugins( 'website-model-loaded', $this->api->current_website );
				if ( $this->current_page->loaded() )
					$this->exec_plugins( 'website-page-model-loaded', $this->api->page_requested );
			
			}
			// unset($this->api->jui);
			// $this->add( 'jUI' );


			// A lot of the functionality in Agile Toolkit requires jUI
			$this->js()
			// ->_load( 'ui.atk4_univ' )
			->_load( 'ui.atk4_notify' )
			;

		}
	}

	function load_plugins() {

		$this->website_plugins_array=array();
		$this->website_plugins=array();

		$plugins = $this->add( 'Model_Plugins' );
		$marketplace_j = $plugins->join('epan_components_marketplace','component_id');
		$installed_j= $marketplace_j->leftJoin('epan_installed_components.component_id');
		
		$marketplace_j->addField('namespace');
		$installed_j->addField('epan_id');

		$plugins->setOrder('is_system');

		$plugins->_dsql()->where(
				$plugins->dsql()->orExpr()
					->where('epan_id', $this->api->current_website->id )
					->where($plugins->table.'.is_system', true )
				);

		foreach ( $plugins->getRows() as $plg ) {
			if ( !in_array( $plg_url=$plg['namespace'].'/Plugins_'.$plg['name'], $this->website_plugins_array ) ) {
				$p = $this->add( $plg['namespace'].'/Plugins_'.str_replace("_", "", $this->api->normalizeName($plg['name'] )));
				$this->website_plugins_array[] = $plg_url;
				$this->website_plugins[] = $p;
			}
		}
	}

	function event($event_hook, &$param){
		$this->exec_plugins($event_hook,$param);
	}

	function exec_plugins( $event_hook, &$param ) {
		if ( !is_array( $param ) )
			$param_array = array( &$param );
		else
			$param_array = $param;

		// if(empty($this->website_plugins))
		//  throw $this->exception("No Plugin loaded");

		foreach ( $this->website_plugins as $p ) {
			// echo $event_hook. " on ". $p ."<br/>";
			$p->hook( $event_hook, $param_array );
		}
		return;
	}

	function defaultTemplate() {
		if ( strpos( str_replace( "/", "_", $_GET['page'] ), 'owner_' )!==false ) {
			return array( 'owner' );
		}

		return array( 'shared' );
	}

	function xcopy($source, $dest) {

		if(!file_exists($dest)) mkdir($dest, 0777);
		foreach (
		  $iterator = new RecursiveIteratorIterator(
		  new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
		  RecursiveIteratorIterator::SELF_FIRST) as $item) {
		  if ($item->isDir()) {
		    mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
		  } else {
		    copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
		  }
		}
	}

	function rrmdir($dir) {
	   if (is_dir($dir)) {
	    	$objects = scandir($dir);
	    	foreach ($objects as $object) {
	    		if ($object != "." && $object != "..") {
	        		if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object);
	    		}
	    	}
	    	reset($objects);
	    	rmdir($dir);
	   	}
	}

	function getxEpanVersion(){
		return file_get_contents('version');
	}


	function addSharedLocations($path_finder, $base_directory){
		
	}

}

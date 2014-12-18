<?php

class page_componentBase_page_owner_main extends page_base_owner {
	public $toolbar;
	public $component_namespace;
	public $component_name;

	function init() {
		$class = get_class( $this );
		preg_match( '/page_(.*)_page_(.*)/', $class, $match );

		$this->component_namespace = $match[1];
		$mp=$this->add('Model_MarketPlace')->loadBy('namespace',$this->component_namespace);
		$this->component_name = $mp['name'];

		// Parent page is now also having component_namespace and component_name if SET
		parent::init();


		// Check for Autheticity of current user to use this page base
		if(!$this->api->auth->model->isAllowedApp($mp->ref('InstalledComponents')->tryLoadAny()->get('id'))){
			$this->api->redirect('owner/not-allowed');
			exit;
		}

		$this->app->pathfinder->base_location->addRelativeLocation(
		    'epan-components/'.$this->component_namespace, array(
		        'php'=>'lib',
		        'template'=>'templates',
		        'css'=>'css',
		        'js'=>'js',
		    )
		);

		$about_page= $this->add( "VirtualPage" );
		$about_page->set( function( $p )use( $match ) {
			$p->add( 'View', null, null, array( 'view/'.$match[1].'-about' ) );
		});

		$uninstall_page= $this->add( "VirtualPage" );
		$uninstall_page->set( function( $p )use( $match ) {
				$p->add( 'View_Error')->set('Are you sure, you want to uninstall this application');
				$btn=$p->add('Button')->set('Yes');
				if($btn->isClicked()){
					$p->api->redirect($p->api->url($match[1].'_page_uninstall'));
				}
			}
		);

		$update_page= $this->add( "VirtualPage" );
		$update_page->set( function( $p )use( $match ) {
				$p->add( 'View_Info')->set('Are you sure, you want to update this application');
				$form = $p->add('Form');
				$form->addField('line','git_exec_path')->set('/usr/bin/git')->validateNotNull();
				$form->addField('DropDown','git_branch')->setValueList(array('master'=>'master','develop'=>'develop'))->set('master')->validateNotNull();
				$form->addSubmit('Update');

				if($form->isSubmitted()){
					$p->js()->univ()->frameURL( 'Update This Component', $p->api->url($p->api->url($match[1].'_page_owner_update',array('git_exec_path'=>$form['git_exec_path'],'git_branch'=>$form['git_branch']))) )->execute();
				}

			}
		);

		// User Menu Setup 
		$um=$this->app->layout->user_menu;
		$this->api->component_common_menu = $component_common = $um->addMenu('Component');
		$x=$component_common->addItem('About','#');
		$x->js('click')->univ()->frameURL( 'About This Component', $about_page->getURL() );
		$x=$component_common->addItem('Update','#');
		$x->js('click')->univ()->frameURL( 'Update This Component', $update_page->getURL() );
		$x=$component_common->addItem('Uninstall','#');
		$x->js('click')->univ()->frameURL( 'Uninstall This Component', $uninstall_page->getURL() );


	}
}

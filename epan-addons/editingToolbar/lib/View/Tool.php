<?php

namespace editingToolbar;
/**
 * This is tool class for all all tools
 */
class View_Tool extends \View {
	/**
	 * $namespace it contain the namespace of child class 
	 * @var String
	 */
	public $namespace=null;
	/**
	 * $title It is name of Tool 
	 * @var string
	 */
	public $title='Tool';
	public $display_name='Tool';
	/**
	 * $class Which component to be rendered as on Drop by this Tool
	 * @var String
	 */
	public $class = null; // Which component to be rendered as on Drop by this Tool
	/**
	 * $icon_file Image/ Icon file of Tool, Only png type file.
	 * @var String
	 */
	public $icon_file=null;
	/**
	 * $drag_html It contain complete HTML which about to drag on page
	 * if defined in Inherited class then it will be dropped, otherwise class html will be dropped.
	 * @var String
	 */
	public $drag_html=null;

	/**
	 * if $is_serverside then do not render provided class view but instead render serverside
	 * component div with is_serversidecomponent and component-namespace etc attributes
	 * @var Boolean
	 */
	public $is_serverside=null;

	/**
	 * if $is_sortable then component can get any other comonent dropped in itself 
	 * @var boolean
	 */
	public $is_sortable=null;

	/**
	 * if $is_resizable component is resiable from front end
	 * @var boolean
	 */
	public $is_resizable=null;

	function init(){
		parent::init();

		if($this->class == null)
			throw $this->exception('Please define a public variable \'class\' containing Class name of Component to be created by dropping ')
						->addMoreInfo('for',$this->title);

		if($this->namespace == null)
			throw $this->exception('Please provide namespace')
						->addMoreInfo('for',$this->title);

		if($this->title == null)
			throw $this->exception('Please provide namespace')
						->addMoreInfo('for',$this->title);
	}

	function recursiveRender(){
		$this->template->trySet('namespace',$this->namespace);
		$this->template->set('title',$this->display_name);
		$this->template->set('class',$this->class);
		$icon_path='epan-components/'.$this->namespace.'/templates/images/'; 
		if($this->icon_file == null){
			$this->icon_file= strtolower(str_replace(" ", "", $this->title))."_icon.png";
			if(!file_exists($icon_path.$this->icon_file)){
				// throw $this->exception($icon_path.$this->icon_file);
				$icon_path='epan-addons/editingToolbar/templates/images/'; 
				$this->icon_file='defaultTool.png';
			}

		}
		$this->template->trySet('icon',$icon_path.$this->icon_file);


		// What to drop by Tool
		// if serverside then serverside view with proper attributes or
		// html of the required view
		
		if($this->is_serverside ){
			$drag_html = $this->add('View',null,null,array('view/editingToolbar-tool-ssc'));
			$drag_html->template->append('attributes','component_namespace="'.$this->namespace .'"');
			$drag_html->template->append('attributes','component_type="'.str_replace("View_Tools_", "", $this->class).'"');
			$drag_html->setAttr('data-responsible-namespace',$this->namespace);
			$drag_html->setAttr('data-responsible-view',$this->class);
			$drag_html->setAttr('data-is-serverside-component','true');
			$drag_html->template->append('class',' epan-component ');

			$this->drag_html = $drag_html->getHTML();
		}else{
			// No Drag HTML defined by tool, lets try to make here
			$drag_html = $this->add($this->namespace.'/'.$this->class);
			
			if($this->is_sortable){
				$drag_html->template->append('class',' epan-sortable-component ');
				$this->template->trySet('create_sortable','true');
			}
			else
				$this->template->trySet('create_sortable','false');

			if($this->is_resizable){
				$drag_html->template->append('class',' ui-resizable ');
			}

			if($drag_html->items_allowed !== null)
				$this->template->trySet('items_allowed',$drag_html->items_allowed);

			if($drag_html->items_cancelled !== null)
				$this->template->trySet('items_cancelled',$drag_html->items_cancelled);

			$drag_html->template->append('attributes','component_namespace="'.$this->namespace .'"');
			$drag_html->template->append('attributes','component_type="'.str_replace("View_Tools_", "", $this->class).'"');
			$drag_html->template->append('class','epan-component ');

			$this->drag_html = $drag_html->getHTML();
		}
		$this->template->trySetHTML('drag_html',$x=str_replace("'", '"', trim(preg_replace('#\R+#', ' ', str_replace("/s", "\/s", $this->drag_html)))));


		// OPTIONS  to be shown on Quick Component options
		if(file_exists($template_file = getcwd().'/epan-components/'.$this->namespace.'/templates/view/'.$this->namespace.'-'.str_replace("View_Tools_", "", $this->class).'-options.html')){
			$options = $this->add('componentBase/View_Options',array('namespace'=>$this->namespace,'component_type'=>str_replace("View_Tools_", "", $this->class)),'options');
		}else{
			
		}
		
		$drag_html->destroy();

		parent::recursiveRender();
	}


	function defaultTemplate(){

		$this->app->pathfinder->base_location->addRelativeLocation(
		    'epan-addons/'.__NAMESPACE__, array(
		        'php'=>'lib',
		        'template'=>'templates',
		        'css'=>'css',
		        'js'=>'js',
		    )
		);

		return array('view/editingToolbar-tool');
	}

}

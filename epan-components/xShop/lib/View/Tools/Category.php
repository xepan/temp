<?php

namespace xShop;

class View_Tools_Category extends \componentBase\View_Component{
	function init(){
		parent::init();

		$category_group=$this->html_attributes['xshop_categorygroup_id'];
		$categories = $this->add('xShop/Model_Category',array('table_alias'=>'mc'));

		if(!$category_group){
			// throw new \Exception($category_group);
			$this->add('View_Error')->set('Please Select Category Group or First Create Category Group');		
			return;
			// $this->js(true)->univ()->errorMessage('Please Select category group first');
		}
		elseif(!$this->html_attributes['xshop_category_url_page']){
			$this->add('View_Error')->set('Please Specify Category URL Page Name (epan page name like.. about,contactus etc..)');		
			return;
			// $this->js(true)->univ()->errorMessage('Please Specify Category URL Page');
		}else{
			
			$categories->addCondition('categorygroup_id',$category_group);		
			$categories->addCondition('is_active',true);
			$categories->setOrder('order','asc');

			//todo OR Condition Using _DSQL 
	        $categories->addCondition(
	        	$categories->_dsql()->orExpr()
	            	->where('mc.parent_id', null)
	            	->where('mc.parent_id', 0)
	            	);
	        // $categories->addCondition('parent_id',Null);    
	        $categories->tryLoadAny();
	        if(!$categories->loaded()){
	        	$this->add('View_Error')->setHTML('No Category Found in Selected Category Group');
	        	return;
	        }

			$output ="<div class='body epan-sortable-component epan-component  ui-sortable ui-selected'>";
			$output ="<ul class='sky-mega-menu sky-mega-menu-anim-slide sky-mega-menu-response-to-stack'>";
					foreach ($categories as $junk_category) {
					$output .= $this->getText($categories,$this->html_attributes['xshop_category_url_page']);
					}
			$output.="</ul></div>";
			$this->setHTML($output);
		}
		
		//loading custom CSS file	
		$category_css = 'epans/'.$this->api->current_website['name'].'/xshopcategory.css';
		$this->api->template->appendHTML('js_include','<link id="xshop-category-customcss-link" type="text/css" href="'.$category_css.'" rel="stylesheet" />'."\n");		
	}

	function getText($category,$page_name){
		if($category->ref('SubCategories')->count()->getOne() > 0){
			$sub_category = $category->ref('SubCategories');
			$output = "<li aria-haspopup='true' class='xshop-category'>";
			$output .="<a href='#'>"; 
			$output .= $category['name'];
			$output .="</a>" ;
			$output .= "<div class='grid-container3'>";			
			$output .= "<ul>";
			foreach ($sub_category as $junk_category) {
				$output .= $this->getText($sub_category,$page_name);
			}
			$output .= "</ul>";
			$output .= "</div>";
			$output .= "</li>";
		}else{
			// throw new \Exception($category['id'], 1);
			if($this->html_attributes['xshop_category_layout']=='Thumbnail'){
				$output = "<li class='text-center'><a href='index.php?subpage=".$page_name."&category_id=".$category['id']."'><img src='$category[image_url]' /><div class='sky-menu-thumbnail-name'>".$category['name']."</div></a></li>";
			}else
				$output = "<li><a href='index.php?subpage=".$page_name."&category_id=".$category['id']."'>".$category['name']."</a></li>";

		}

		return $output;
	}
	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html

	function defaultTemplate(){
		$l=$this->api->locate('addons',__NAMESPACE__, 'location');
		$this->api->pathfinder->addLocation(
			$this->api->locate('addons',__NAMESPACE__),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css',
		  		'js'=>'templates/js'
				)
			)->setParent($l);

		if($this->html_attributes['xshop_category_layout']=='Horizontal')
			return array('view/xShop-Category-Horizontal');
		elseif($this->html_attributes['xshop_category_layout']=='Vertical')
			return array('view/xShop-Category-Vertical');
		elseif($this->html_attributes['xshop_category_layout']=='MegaMenu')
			return array('view/xShop-Category-MegaMenu');
		elseif($this->html_attributes['xshop_category_layout']=='Thumbnail')
			return array('view/xShop-Category-Thumbnail');
		else
			return array('view/xShop-Category-Horizontal');
	}
}

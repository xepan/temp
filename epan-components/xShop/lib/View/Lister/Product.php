<?php

namespace xShop;
class View_Lister_Product extends \CompleteLister{
	public $xshop_product_display_layout;
	public $xshop_product_grid_column;
	public $xshop_product_topbar;
	public $xshop_product_categorygroup_id;
	public $fancy_box_on;
	public $item_detail_url;
	public $item_detail_onclick;
	public $xshop_product_detail_on_image_click;
	public $item_short_description;
	function formatRow(){
		
		if($this->xshop_product_display_layout=='xShop-productgrid'){						
			$this->current_row_html['xshop_item_list_view_image_start']=" ";
			$this->current_row_html['xshop_item_list_view_image_end']=" ";
			$this->current_row_html['xshop_item_list_view_btn_start']=" ";
			$this->current_row_html['xshop_item_list_view_btn_end']=" ";
			$this->current_row_html['xshop_item_list_view_row_start']=" ";
			$this->current_row_html['xshop_item_list_view_row_end']=" ";
			$this->current_row_html['xshop_list_btn_class']="col-md-12";
		}else{
			$this->current_row_html['xshop_list_btn_class']="col-md-6";
		}
		
		if(!$this->item_short_description){		
			$this->current_row_html['xshop_item_short_description'] = " ";
		}

		if(!$this->item_detail_url)
			$this->item_detail_url = '#2221212$%';

		$this->current_row['image_url'] = $this->model->ref('xShop/ProductImages')->tryLoadAny()->get('image_url')?:"epan-components/xShop/templates/images/item_no_image.png";			

		if($this->item_detail_onclick){
			if($this->item_detail_url)
				$this->current_row_html['xshop_product_hover_detail_page']=$this->item_detail_url;			
		}else{
			$this->current_row_html['xshop_product_hover_detail_page_start']=" ";
			$this->current_row_html['xshop_product_hover_detail_page_end']=" ";
		}

		//TODO ITEM DETAIL ON IMAGE CLICK
		if($this->xshop_product_detail_on_image_click){
			if($this->item_detail_url)
				$this->current_row_html['xshop_product_detail_url']=$this->item_detail_url;
		}else{
			$this->current_row_html['xshop_product_detail_on_image_click_start']=" ";
			$this->current_row_html['xshop_product_detail_on_image_click_end']=" ";	
		}	
		//END OF Item DETAIL ON IMAGE CLICK

		if($this->model['allow_saleable']){
			$this->current_row_html['aj']=str_replace('"', "'", $this->js(null, $this->js()->_selector('body')->attr('xshop_add_product_id',$this->model->id))->_selector(' .xshop-cart ')->trigger('reload')->_render());
			$this->current_row_html['addtocart_display']="true";
		}else{
			$this->current_row_html['aj']=" ";
			$this->current_row_html['addtocart_display']="none";
		}

		if(!$this->model['show_detail']){			
			$this->current_row_html['xshop_product_show_detail_display'] = "none";
		}else{
			$this->current_row_html['xshop_product_show_detail_display'] = "block";
			$this->current_row_html['xshop_item_detail_url'] = $this->item_detail_url;
		}

		if($this->model['sale_price'] >= 0){
			$this->current_row_html['xShop_product_price'] = "";			
		}							

		if(!$this->model['show_price']){
			$this->current_row_html['xshop_product_price_display'] = "none";							
		}else{
			$this->current_row_html['xshop_product_price_display'] = "block";										
		}	

		if($this->fancy_box_on){
			$this->current_row_html['item_image_fancybox']='true';
			$this->current_row_html['item_image_no_fancybox']='none';
		}else{
			$this->current_row_html['item_image_fancybox']='none';
			$this->current_row_html['item_image_no_fancybox']='true';
		}

		if((!$this->xshop_product_topbar )or $this->xshop_product_topbar == 'false'){
			$this->template->trySet('xshop_product_topbar_display','none');
		}else
			$this->template->trySet('xshop_product_topbar_display','block');

		// univ().frameURL('Product Detail','index.php?epan=web&subpage=xshop-productdetail&product_id='+product_id)
		$this->current_row_html['short_description'] = $this->model['short_description'];
		$this->current_row_html['xshop_product_grid_column'] = $this->xshop_product_grid_column;	
		$this->current_row_html['xshop_product_created_at'] = $this->model['created_at'];	
		// throw new \Exception("Error Processing Request".$this->xshop_product_grid_column);							
	}

	
	function defaultTemplate(){
		$this->app->pathfinder->base_location->addRelativeLocation(
		    'epan-components/'.__NAMESPACE__, array(
		        'php'=>'lib',
		        'template'=>'templates',
		        'css'=>'templates/css',
		        'js'=>'templates/js',
		    )
		);
		
		// $l=$this->api->locate('addons',__NAMESPACE__, 'location');
		// $this->api->pathfinder->addLocation(
		// 	$this->api->locate('addons',__NAMESPACE__),
		// 	array(
		//   		'template'=>'templates',
		//   		'css'=>'templates/css',
		//   		'js'=>'templates/js'
		// 		)
		// 	)->setParent($l);
		
		// throw new \Exception("Error Processing Request", 1);
						
		// if($this->xshop_product_display_layout=='xShop-productlist'){
		// 	return array('view/xShop-ProductLister');
		// }else
			return array('view/xShop-ProductListerGrid');
	}
	
}


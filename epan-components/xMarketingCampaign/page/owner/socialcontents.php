<?php

class page_xMarketingCampaign_page_owner_socialcontents extends page_xMarketingCampaign_page_owner_main{




	function init(){
		$this->rename('y');
		parent::init();

	
		$preview_vp = $this->add('VirtualPage');
		$preview_vp->set(function($p){


			$m=$p->add('xMarketingCampaign/Model_SocialPost')->load($_GET['socialpost_id']);
			$p->add('View')->set('Created '. $this->add('xDate')->diff(Carbon::now(),$m['created_at']) .', Last Modified '. $this->add('xDate')->diff(Carbon::now(),$m['updated_at']) )->addClass('atk-size-micro pull-right')->setStyle('color','#555');
			$p->add('HR');
			$p=$p->add('View')->addClass('panel panel-default')->setStyle('padding','20px');
			
			$cols = $p->add('Columns');
			$share_col =$cols->addColumn(4);
			$title_col =$cols->addColumn(8);

			$share_col->addClass('text-center');
			$share_col->add('View')->setElement('a')->setAttr(array('href'=>$m['url'],'target'=>'_blank'))->set($m['url']);
			$share_col->add('View')->setElement('img')->setAttr('src',$m['image'])->setStyle('max-width','100%');



			$title_col->add('H4')->set($m['post_title']);

			$cols_hrs=$p->add('Columns');
			$l_c= $cols_hrs->addColumn(4);
			$l_c->add('View')->set('Share URL')->addClass('atk-size-micro pull-right')->setStyle('color','#555');
			$l_c->add('HR');
			
			$r_c= $cols_hrs->addColumn(8);
			$r_c->add('View')->set('Post Title')->addClass('atk-size-micro pull-right')->setStyle('color','#555');
			$r_c->add('HR');

			if($m['message_160_chars']){
				$p->add('View')->set($m['message_160_chars']);
				$p->add('View')->set('Message in 160 Characters')->addClass('atk-size-micro pull-right')->setStyle('color','#555');
				$p->add('HR');
			}

			if($m['message_255_chars']){
				$p->add('View')->set($m['message_255_chars']);
				$p->add('View')->set('Message in 255 Characters')->addClass('atk-size-micro pull-right')->setStyle('color','#555');
				$p->add('HR');
			}

			if($m['message_3000_chars']){
				$p->add('View')->set($m['message_3000_chars']);
				$p->add('View')->set('Message in 3000 Characters')->addClass('atk-size-micro pull-right')->setStyle('color','#555');
				$p->add('HR');
			}

			if($m['message_blog']){
				$p->add('View')->setHTML($m['message_blog']);
				$p->add('View')->set('Message for Blogs')->addClass('atk-size-micro pull-right')->setStyle('color','#555');
				$p->add('HR');
			}

		});

		$this->app->layout->template->trySetHTML('page_title','<i class="fa fa-bullhorn"></i> '.$this->component_name. '<small> Social Posts </small>');

		$bg=$this->app->layout->add('View_BadgeGroup');
		$data=$this->add('xEnquiryNSubscription/Model_NewsLetter')->count()->getOne();
		$v=$bg->add('View_Badge')->set('Total NewsLetters')->setCount($data)->setCountSwatch('ink');

		$data=$this->add('xEnquiryNSubscription/Model_NewsLetter')->addCondition('created_by','xMarketingCampaign')->count()->getOne();
		$v=$bg->add('View_Badge')->set('By This App')->setCount($data)->setCountSwatch('ink');

		$cols = $this->app->layout->add('Columns');
		$cat_col = $cols->addColumn(3);
		$social_col = $cols->addColumn(9);

		$social_category_model = $this->add('xMarketingCampaign/Model_SocialPostCategory');

		$cat_crud=$cat_col->add('CRUD');

		$cat_crud->setModel($social_category_model,array('name'));

		if(!$cat_crud->isEditing()){
			$g=$cat_crud->grid;
			$g->addMethod('format_filtersocial',function($g,$f)use($social_col){
				$g->current_row_html[$f]='<a href="javascript:void(0)" onclick="'. $social_col->js()->reload(array('category_id'=>$g->model->id)) .'">'.$g->current_row[$f].'</a>';
			});
			$g->addFormatter('name','filtersocial');
			$g->add_sno();
		}

		$social_model = $this->add('xMarketingCampaign/Model_SocialPost');

		// filter social letter as per selected category
		if($_GET['category_id']){
			$this->api->stickyGET('category_id');
			$filter_box = $social_col->add('View_Box')->setHTML('Social Posts for <b>'. $social_category_model->load($_GET['category_id'])->get('name').'</b>' );
			
			$filter_box->add('Icon',null,'Button')
            ->addComponents(array('size'=>'mega'))
            ->set('cancel-1')
            ->addStyle(array('cursor'=>'pointer'))
            ->on('click',function($js) use($filter_box,$social_col) {
                $filter_box->api->stickyForget('category_id');
                return $filter_box->js(null,$social_col->js()->reload())->hide()->execute();
            });

			$social_model->addCondition('category_id',$_GET['category_id']);
		}

		$social_crud = $social_col->add('CRUD');
		
		$cols_array = array('category','name','is_active');

		if($_GET['sort_by']){
			$this->api->stickyGET('sort_by');
			$this->api->stickyGET('order');
			// $social_crud->grid->add('View_Box',null,'grid_buttons')->set($_GET['sort_by']);

			switch ($_GET['sort_by']) {
				case 'created_at':
					$social_model->setOrder('created_at',$_GET['order']);
					break;
				case 'updated_at':
					$social_model->setOrder('updated_at',$_GET['order']);
					break;
				
				default:
					# code...
					break;
			}

		}

		$social_crud->setModel($social_model,null,$cols_array);
		// $social_crud->add('Controller_FormBeautifier');
		if(!$social_crud->isEditing()){
			$g=$social_crud->grid;
			$g->add_sno();

			$sort_form = $g->buttonset->add('Form');
			$sort_form->addClass('atk-form atk-move-right');
        	// $sort_form->template->trySet('fieldset', 'atk-row');
        	$sort_form->template->tryDel('button_row');
			$sort_form_field= $sort_form->addField('DropDown','sort_by')->setValueList(array(0=>'Default','created_at'=>'Created Date','updated_at'=>'Updated Date','recent_posted'=>'Recent Posted On','recent_scheduled'=>'Recent Scheduled On'))->set($_GET['sort_by']?:"Default");
			$btn=$sort_form_field->beforeField()->add('Button')->set(array('','icon'=>'sort-alt-up'));
			$btn->js('click',$g->js()->reload(array('sort_by'=>$sort_form_field->js()->val(),'order'=>'asc')));
			$btn=$sort_form_field->afterField()->add('Button')->set(array('','icon'=>'sort-alt-down'));
			$btn->js('click',$g->js()->reload(array('sort_by'=>$sort_form_field->js()->val(),'order'=>'desc')));


			$g->addQuickSearch(array('name'));
			$g->addClass('social_grid');
			$g->js('reload')->reload();

			$g->addMethod('format_preview',function($g,$f)use($preview_vp){
				$g->current_row_html[$f]='<a href="javascript:void(0)" onclick="'. $g->js()->univ()->frameURL($g->model['name'],$this->api->url($preview_vp->getURL(),array('socialpost_id'=>$g->model->id))) .'">'.$g->current_row[$f].'</a>';
			});
			$g->addFormatter('name','preview');

			$g->addColumn('Expander','post');
			$social_crud->add_button->setIcon('ui-icon-plusthick');
			
		}
	
	}
}		
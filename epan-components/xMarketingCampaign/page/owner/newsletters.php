<?php

class page_xMarketingCampaign_page_owner_newsletters extends page_xMarketingCampaign_page_owner_main{

	function init(){
		parent::init();

		// $newsletter_model = $this->add('xEnquiryNSubscription/Model_NewsLetter');

		// $crud = $this->app->layout->add('CRUD');
		// $crud->setModel($newsletter_model,null,null);
		// // $crud->add('Controller_FormBeautifier');
		
		// if(!$crud->isEditing()){
		// 	$crud->add_button->setIcon('ui-icon-plusthick');
		// }
		$preview_vp = $this->add('VirtualPage');
		$preview_vp->set(function($p){
			$m=$p->add('xEnquiryNSubscription/Model_NewsLetter')->load($_GET['newsletter_id']);
			$p->add('HR');
			$p->add('View')->setHTML($m['matter']);
		});

		$this->app->layout->template->trySetHTML('page_title','<i class="fa fa-bullhorn"></i> '.$this->component_name. '<small> NewsLetters </small>');

		$config_model=$this->add('xEnquiryNSubscription/Model_Config')->tryLoadAny();

		$bg=$this->app->layout->add('View_BadgeGroup');
		$data=$this->add('xEnquiryNSubscription/Model_NewsLetter')->count()->getOne();
		$v=$bg->add('View_Badge')->set('Total NewsLetters')->setCount($data)->setCountSwatch('ink');

		$data=$this->add('xEnquiryNSubscription/Model_NewsLetter')->addCondition('created_by','xEnquiryNSubscription')->count()->getOne();
		$v=$bg->add('View_Badge')->set('By This App')->setCount($data)->setCountSwatch('ink');

		$cols = $this->app->layout->add('Columns');
		$cat_col = $cols->addColumn(3);
		$news_col = $cols->addColumn(9);

		$newsletter_category_model = $this->add('xEnquiryNSubscription/Model_NewsLetterCategory');

		$cat_crud=$cat_col->add('CRUD');

		$cat_crud->setModel($newsletter_category_model,array('name'));

		if(!$cat_crud->isEditing()){
			$g=$cat_crud->grid;
			$g->addMethod('format_filternewsletter',function($g,$f)use($news_col){
				$g->current_row_html[$f]='<a href="javascript:void(0)" onclick="'. $news_col->js()->reload(array('category_id'=>$g->model->id)) .'">'.$g->current_row[$f].'</a>';
			});
			$g->addFormatter('name','filternewsletter');
		}

		$newsletter_model = $this->add('xEnquiryNSubscription/Model_NewsLetter');
		$newsletter_model->addExpression('unsend_emails')->set(function($m,$q){
			$mq= $m->add('xEnquiryNSubscription/Model_EmailQueue');
			$mq->join('xEnquiryNSubscription_EmailJobs','emailjobs_id')->addField('newsletter_id');
			return $mq->addCondition('newsletter_id',$q->getField('id'))->addCondition('is_sent',false)->count();
		})->sortable(true);

		if(!$config_model['show_all_newsletters']){
			$newsletter_model->addCondition('created_by','xEnquiryNSubscription');
		}
		
		// filter news letter as per selected category
		if($_GET['category_id']){
			$this->api->stickyGET('category_id');
			$filter_box = $news_col->add('View_Box')->setHTML('NewsLetters for <b>'. $newsletter_category_model->load($_GET['category_id'])->get('name').'</b>' );
			
			$filter_box->add('Icon',null,'Button')
            ->addComponents(array('size'=>'mega'))
            ->set('cancel-1')
            ->addStyle(array('cursor'=>'pointer'))
            ->on('click',function($js) use($filter_box,$news_col) {
                $filter_box->api->stickyForget('category_id');
                return $filter_box->js(null,$news_col->js()->reload())->hide()->execute();
            });

			$newsletter_model->addCondition('category_id',$_GET['category_id']);
		}

		$newsletter_crud = $news_col->add('CRUD');
		$newsletter_crud->setModel($newsletter_model,null,array('category','is_active','name','email_subject','unsend_emails','created_by'));
		// $newsletter_crud->add('Controller_FormBeautifier');

		if(!$newsletter_crud->isEditing()){
			$g=$newsletter_crud->grid;

			$g->removeColumn('email_subject');

			$g->addClass('newsletter_grid');
			$g->js('reload')->reload();

			if(!$config_model['show_all_newsletters']){
				$g->removeColumn('created_by');
			}

			$g->addMethod('format_preview',function($g,$f)use($preview_vp){
				$g->current_row_html[$f]='<a href="javascript:void(0)" onclick="'. $g->js()->univ()->frameURL($g->model['email_subject'],$this->api->url($preview_vp->getURL(),array('newsletter_id'=>$g->model->id))) .'">'.$g->current_row[$f].'</a>';
			});
			$g->addFormatter('name','preview');

			$filter_btn=$g->addButton($config_model['show_all_newsletters']?"All Apps NewsLetters":"This App NewsLetters");
			if($filter_btn->isClicked()){
				$config_model['show_all_newsletters'] = $config_model['show_all_newsletters']?0:1;
				$config_model->save();
				$news_col->js()->reload()->execute();
			}

			$g->addColumn('Expander','send');
			$newsletter_crud->add_button->setIcon('ui-icon-plusthick');
			
			$btn=$g->addButton("");
			
			if($btn->isClicked()){
				$this->js()->univ()->frameURL('Executing Email Sending Process',$this->api->url('xEnquiryNSubscription_page_emailexec'))->execute();
			}

			$email_to_process = $this->add('xEnquiryNSubscription/Model_EmailQueue');
			$email_to_process->addCondition('is_sent',false);
			$email_to_process->setOrder('id','asc');
			$email_to_process->setOrder('emailjobs_id','asc');

			$job_j = $email_to_process->join('xEnquiryNSubscription_EmailJobs','emailjobs_id');
			$job_j->addField('process_via');
			$email_to_process->addCondition('process_via','xEnquiryNSubscription');
			$pending_count = $email_to_process->count()->getOne();

			$btn->setIcon('ui-icon-seek-end');
			$btn->set("Start Processing Sending, Now ($pending_count)");
			$btn->addClass('processing_btn');
			$btn->js('reload')->reload();
		}
	}
}		
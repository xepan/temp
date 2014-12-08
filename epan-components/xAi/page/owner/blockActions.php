<?php


class page_xAi_page_owner_blockActions extends page_xAi_page_owner_main {

	function init(){
		parent::init();

			$ib_model = $this->add('xAi/Model_IBlockContent');
			$ib_model->addCondition('iblock_id',$_GET['id']);
			$ib_model->addCondition('parent_iblock_id',$_GET['parent_id']?:null);
			$ib_model->addCondition('dimension_id', $_GET['current_dimension_id']);
			$ib_model->tryLoadAny();

			$ib_model['content'] = urldecode($_GET['content']);
			$ib_model->saveAndUnload();

			$new_model = $this->add('xAi/Model_IBlockContent');
			$new_model->addCondition('iblock_id',$_GET['id']);
			$new_model->addCondition('parent_iblock_id',$_GET['parent_id']?:null);
			$new_model->addCondition('dimension_id', $_GET['required_dimension_id']);
			$new_model->tryLoadAny();

			echo $new_model['content'];
			exit;

	}
}
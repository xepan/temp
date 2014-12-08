<?php

class page_owner_frontapplauncher extends Page {
	function init(){
		parent::init();

		$this->add('View')->set('Not Working Now')->addClass('alert alert-info');

		return;

		$cols = $this->add('Columns');
		
		$newLetter_col = $cols->addColumn(3);
		
		$btn = $newLetter_col->add('Button')->set('Convert');
		
		$newsletter_form = $newLetter_col->add('Form');
		$newsletter_form->addField('DropDown','news_letter')->setModel('xEnquiryNSubscription/NewsLetter');
		$content_field = $newsletter_form->addField('Text','content');
		$newsletter_form->addSubmit('Save To');

		$js=array();
		$js[] = $this->js()->_load('jquery.inlineStyler.src14');
		$js[] = $this->js()->_selector('[component_type=Row]')->width('98%');
		$js[] = $this->js()->_selector('.email-div')->inlineStyler();
		$js[] = $this->js()->_selector('.email-div *')->removeAttr('class');
		$js[] = $content_field->js()->val($this->js()->_selector('.email-div')->html());

		$btn->js('click',$js);

		if($newsletter_form->isSubmitted()){
			$newsletter = $this->add('xEnquiryNSubscription/Model_NewsLetter');
			$newsletter->load($newsletter_form['news_letter']);
			$newsletter['matter'] = $newsletter_form['content'];
			$newsletter->save();
			$this->js()->univ()->closeDialog()->execute();
		}


	}
}
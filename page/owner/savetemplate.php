<?php

class page_owner_savetemplate extends Page {

	function init() {
		parent::init();		

		if(!$this->api->auth->isLoggedIn())	{
			$this->js()->univ()->errorMessage('You Are Not Logged In')->execute();
		}
		
		if ( $_POST['length'] != strlen( $_POST['body_html'] ) ) {
			$this->js()->univ()->successMessage( 'Length send ' . $_POST['length'] . " AND Length calculated again is " . strlen( $_POST['body_html'] ) )->execute();
		}

		if ( $_POST['crc32'] != sprintf("%u",crc32( $_POST['body_html'] ) )) {
			$this->js()->univ()->successMessage( 'CRC send ' . $_POST['crc32'] . " AND CRC calculated again is " . sprintf("%u",crc32( $_POST['body_html'] )) )->execute();
		}

		try{

			$content = urldecode($_POST['body_html']);
			$template = $this->add('Model_EpanTemplates')->load($_GET['template_id']);
			$template['content'] = $content;
			$template['body_attributes'] = urldecode( $_POST['body_attributes'] );
			$template->save();
		}
		catch( Exception_StopInit $e ) {

		}
		catch( Exception $e ) {
			$this->js()->univ()->errorMessage( 'Error... Could not save your page ' . $e->getMEssage() )->excute();
			exit;
		}

		echo "saved";
		exit;
	}

}

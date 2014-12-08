<?php
namespace xShop;
class Model_Users extends \Model_Users{

	function init(){
		parent::init();

		$this->hasMany('xShop/MemberDetails','users_id');
	}
}		
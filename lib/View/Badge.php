<?php

class View_Badge extends View{
	public $my_title;
	public $count;
	public $count_swatch;
	public $badge_swatch;

	function setCount($count){
		$this->count = $count;
		return $this;
	}

	function setCountSwatch($swatch){
		$this->count_swatch = $swatch;
		return $this;
	}

	function setBadgeSwatch($swatch){
		$this->badge_swatch = $swatch;
		return $this;
	}

	function set($title){
		$this->my_title =$title;
		return $this;
	}

	function recursiveRender(){
		$this->addClass('pull-left');
		$this->setStyle('padding-left','5px');
		$ext = $this->add('View')->setElement('span')
                    ->addClass('atk-label')
                    ->set(array($this->count,'swatch'=>$this->count_swatch))->getHTML();
		$this->add('View')->setHTML($this->my_title.' '. $ext);
		parent::recursiveRender();
	}

}
<?php


class View_BackEndView extends View{
	public $has_top_bar=true;
	public $top_bar = null;
	public $top_bar_class = 'alert alert-info';
	public $top_bar_left_col;


	public $top_bar_right_col;
	public $option_button=null;

	public $cols=array();
	public $cols_widths=array(12);

	function init(){
		parent::init();
		if($this->has_top_bar){
			$this->addTopBar();
		}

		$cols = $this->add('Columns');

		foreach ($this->cols_widths as $cw) {
			$this->cols[] = $cols->addColumn($cw);
		}

	}

	function addTopBar($options=array()){
		$this->top_bar =  $this->add('Columns',$options);

		$this->top_bar_left_col = $this->top_bar->addColumn(10);
		$this->top_bar_right_col = $this->top_bar->addColumn(2);
		
		$this->top_bar->addClass($this->top_bar_class);
	}

	function addToTopBar($obj,$options=array()){
		return $this->top_bar_left_col->add($obj,$options);
	}

	function removeTopBar(){
		$this->top_bar->destroy();
	}


	function getTopBar(){
		return $this->top_bar;
	}

	function addOptionButton($page='', $title='Options'){
		$this->option_button = $this->top_bar_right_col->add('Button')->setHTML('<i class="fa fa-cog"></i>');
		$this->option_button->addClass('pull-right');

		if($page==''){
			$this->option_button->setAttr('disabled',true);
			return $this->option_button;
		}

		$this->option_button->js('click')->univ()->frameURL($title,$page);
		return $this->option_button;
	}

	function addToColumn($number,$obj,$options=array()){
		if(is_string($obj))
			$obj= $this->add($obj,$options);

		return $this->cols[$number]->add($obj,$options);
	}

	function addToDetailSection($obj,$options=array()){
		if(is_string($obj))
			$obj= $this->add($obj,$options);

		return $this->details_section->add($obj,$options);
	}

}
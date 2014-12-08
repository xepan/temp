<?php


class Controller_FormBeautifier extends AbstractController{
	public $param=array();
	public $form=false;

	public $header_type='panel';
	public $modifier='default';


	function init(){
		parent::init();

		
		if(!($this->owner instanceof CRUD) and !($this->owner instanceof Form)){
			throw $this->exception('Must be added on CRUD or Form')->addMoreInfo('Current Owner',$this->owner);
		}

		if($this->owner instanceof CRUD and $this->owner->isEditing()){
			$this->form = $this->owner->form;
		}

		if($this->owner instanceof Form){
			$this->form = $this->owner;
		}

		if(!$this->form) return;

		$model_fields = $this->form->getModel();

		if(!$model_fields){
			return;
		}
		
		$last_group="";
		$last_form_field=null;
		$running_column=null;
		$running_cell=null;
		$order = $this->form->add('Order');

		foreach ($model_fields->elements as $position => $fld) {
			if(!($fld instanceof Field)) {
				continue;
			}

			// if fld->group is same as last group and last group is not ""
			if(isset($fld->group))
				$group_details = explode("~", $fld->group);
			else
				$group_details = array();

			// echo $fld->short_name .' in form ?? ' . $this->form->hasField($fld->short_name). '<br/>';
			if(count($group_details) < 2 or !($elm=$this->form->hasElement($fld->short_name))){
				if($elm=$this->form->hasElement($fld->short_name)){
					$last_form_field=$elm;
					$elm->addClass('form-control');
					if(isset($fld->icon)){
						$icon = explode("~", $fld->icon);
						$color='';

						if(isset($icon[1])) $color= "style=' color:".$icon[1]."'";

						$elm->setCaption(array($fld->caption(),'swatch'=>'red'));
					}
				}
				continue;
			}

			// beautify field by adding bootstrap classes
			$elm->addClass('form-control');
			if(isset($fld->icon)){
				$icon = explode("~", $fld->icon);
				$color='';

				if(isset($icon[1])) $color= "style=' color:".$icon[1]."'";

				$elm->setCaption("<i class='".$icon[0]."' $color ></i> ".$fld->caption());
			}

			if($group_details[0]!= $last_group){
				if(isset($group_details[2]) and $group_details[2]!='bl'){
					$group_header = $this->form->add('View');
					$group_header->addClass('panel panel-'. $this->modifier);
					// $fieldset = $running_cell->add('View');//->setElement('fieldset');
					$group_header->add('H4')->setHTML($group_details[2])->addClass('panel-heading');
					$running_cell = $group_header->add('View')->addClass('panel-body');
					$running_column = $running_cell->add('Columns');
					$move = $group_header;
				}else{
					if(isset($group_details[2]) and $group_details[2] !='bl'){
						$running_cell = $running_column->addColumn(12);
						$group_header = $running_cell->add('View');
						$group_header->addClass('panel panel-'.$this->modifier);
						// $fieldset = $running_cell->add('View');//->setElement('fieldset');
						$group_header->add('H4')->setHTML($group_details[2])->addClass('panel-heading');
						$container = $group_header->add('View')->addClass('panel-body');
						$running_column = $running_cell->add('Columns');
						$running_cell = $running_column->addColumn($group_details[1]);
					}else{
						$running_column = $this->form->add('Columns');
					}
					$move = $running_column;
				}

				if(!$last_form_field){
					$order->move($move,'first');
				}else{
					$order->move($move,'after',$elm->short_name);
				}
			}

			if(isset($group_details[2]) and $group_details[2] == 'bl'){ // Bellow Last Column
				$running_cell->add($elm);
			}else{
				$running_cell = $running_column->addColumn($group_details[1]);
				$running_cell->add($elm);
			}

			$last_group = $group_details[0];
			$last_form_field = $elm;

			// create a new columns object
			// if same as last group
			// add Column to column object and move object into it
		}

		$order->now();
		$this->form->addClass('stacked');

		foreach ($this->form->elements as $ff) {
			if($ff instanceof Form_Submit){
				// $ff->addClass('btn btn-warning btn-block');
				// $this->form->getElement($ff->name)->addClass('btn btn-danger');
			}
		}

	}

}
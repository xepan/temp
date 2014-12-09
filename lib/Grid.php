<?php

class Grid extends Grid_Advanced{
	public $order=null;
	public $sno=1;

	function init(){
		parent::init();
		$this->order= $this->addOrder();
	}

	function add_sno(){
		$this->addColumn('sno','s_no');
		$this->order->move('s_no','first');
	}

	function format_sno($field){
		$this->current_row[$field] = (($this->sno++) + ($_GET[$this->name.'_paginator_skip']?:0));
	}

	function init_boolean($field)
    {
        @$this->columns[$field]['thparam'] .= ' style="text-align: center"';
    }

    function format_boolean($field)
    {
        if ($this->current_row[$field] && $this->current_row[$field] !== 'N') {
            $this->current_row_html[$field] =
                '<div align=center>'.
                    '<span class="fa fa-circle" style="color:green"></span>'.
                '</div>';
        } else {
            $this->current_row_html[$field] = '<div align=center>'.
                    '<span class="fa fa-circle" style="color:red"></span>'.
                '</div>';
        }
    }

    function init_boolean_rev($field)
    {
        @$this->columns[$field]['thparam'] .= ' style="text-align: center"';
    }

    function format_boolean_rev($field)
    {
        if ($this->current_row[$field] && $this->current_row[$field] !== 'N') {
            $this->current_row_html[$field] = '<div align=center>'.
                    '<span class="fa fa-circle" style="color:red"></span>'.
                '</div>';
        } else {
            $this->current_row_html[$field] =
                '<div align=center>'.
                    '<span class="fa fa-circle" style="color:green"></span>'.
                '</div>';
        }
    }

    function format_expander($field, $column)
    {

        $class = $this->columns[$field]['button_class'].' button_'.$field;
        $icon = isset($this->columns[$field]['icon'])
                    ? $this->columns[$field]['icon']
                    : '';

        if (!@$this->current_row[$field]) {
            $this->current_row[$field] = $column['descr'];
        }

        // TODO:
        // reformat this using Button, once we have more advanced system to
        // bypass rendering of sub-elements.
        // $this->current_row[$field] = $this->add('Button',null,false)
        $key   = $this->name . '_' . $field . '_';
        $id    = $key . $this->api->normalizeName($this->model->id);
        $class = $key . 'expander';

        @$this->current_row_html[$field] =
            '<button type="button" '.
                'class="'.$class.'" '.
                'id="'.$id.'" '.
                'rel="'.$this->api->url(
                    $column['page'] ?: './'.$field,
                    array(
                        'expander' => $field,
                        'expanded' => $this->name,
                        'cut_page' => 1,
                        // TODO: id is obsolete
                        //'id' => $this->model->id,
                        $this->columns[$field]['refid'].'_id' => $this->model->id
                    )
                ).'" '.
            '>'.
            $this->current_row[$field] . 
            '</button>';
    }

	function recursiveRender(){
		if($this->hasColumn('edit'))
			$this->order->move('edit','last');

		if($this->hasColumn('delete'))
			$this->order->move('delete','last');
		
		if($this->order) $this->order->now();

		parent::recursiveRender();
	}

	// Overrided function from GridBasic.. to strip html tags from headers

	function addColumn($formatters, $name = null, $descr = null)
    {
        if ($name === null) {
            $name = $formatters;
            $formatters = 'text';
        }

        if ($descr === null) {
            $descr = ucwords(str_replace('_', ' ', $name));
        }
        $descr = $this->api->_($descr);

        $this->columns[$name] = array('type' => $formatters);

        if (is_array($descr)) {
            $this->columns[$name] = array_merge($this->columns[$name], $descr);
        } else {
            // $this->columns[$name]['descr'] = $descr; // Original Line
            if(($start_pos = strpos($descr, '<')) !==false ){
	            $this->columns[$name]['descr'] = substr($descr,0,$start_pos);
	            $this->columns[$name]['descr'] = $descr;
            }else
	            $this->columns[$name]['descr'] = $descr;
        }

        $this->last_column = $name;

        if (!is_string($formatters) && is_callable($formatters)) {
            $this->columns[$name]['fx'] = $formatters;
            return $this;
        }

        // TODO call addFormatter instead!
        $subtypes = explode(',', $formatters);
        foreach ($subtypes as $subtype) {
            if (strpos($subtype, '/')) {

                // add-on functionality:
                // http://agiletoolkit.org/codepad/gui/grid#codepad_gui_grid_view_example_7_ex
                if (!$this->elements[$subtype.'_'.$name]) {
                    $addon = $this->api->normalizeClassName($subtype, 'Controller_Grid_Format');
                    $this->elements[$subtype.'_'.$name] = $this->add($addon);
                }

                $addon = $this->getElement($subtype.'_'.$name);
                $addon->initField($name, $descr);
                return $addon;

            } elseif (!$this->hasMethod($m = 'init_'.$subtype)) {
                if (!$this->hasMethod($m = 'format_'.$subtype)) {
                    // exception if formatter doesn't exist
                    throw $this->exception('No such formatter')
                        ->addMoreInfo('formater', $subtype);
                }
            } else {
                // execute formatter init_*
                $this->$m($name, $descr);
            }
        }

        return $this;
    }

}
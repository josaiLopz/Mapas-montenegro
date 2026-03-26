<?php

class IntranetController extends AppController
{
    var $name = 'Intranet';
    var $uses = array();


	function index(){
		$this->redirect(array('controller'=>'usuarios','action'=>'intranet'));

	}


	function inicio(){}

}

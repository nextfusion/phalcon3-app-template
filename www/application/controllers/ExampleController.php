<?php

class ExampleController extends CController {

    public function initialize(){
        parent::initialize();
    }
    
    public function indexAction(){ 

        $this->setTheme('main');
        $this->setLayout('partials/main');
        
    }

}
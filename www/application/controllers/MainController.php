<?php

class MainController extends CController {

    public function initialize() {
        parent::initialize();
        $this->setAssetsBase();
    }
    
    public function indexAction() {

        $this->setTheme('main');
        $this->setLayout('partials/main');

     }

}
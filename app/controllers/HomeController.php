<?php

class HomeController extends BaseController {

    public function getIndex(){
        return View::make('home');
    }
    
    public function getIndexV1(){
        return View::make("apiv1");
    }
    
}

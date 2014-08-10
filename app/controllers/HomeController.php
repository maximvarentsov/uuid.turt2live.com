<?php

class HomeController extends BaseController {

    private function uuid($name){
        return array("uuid" => "c465b1543c294dbfa7e3e0869504b8d8", "name" => $name, "last-updated" => "1231231414");
    }
    
    private function name($uuid){
        
    }

    public function getIndex(){
        return View::make('home');
    }
    
    public function getErrorTest(){
        $arr = array("message" => "This is a test error", "error" => true, "errorId" => -1);
        
        return Response::json($arr);
    }
    
    public function getUuid($name){
        $arr = $this->uuid($name);
        $arr["query"] = $name;
        
        return Response::json($arr);
    }
    
    public function getName($uuid){
        return $uuid;
    }
    
    public function getUuidList($names){
        $names = explode(";", $names);
        
        return Response::json($names);
    }
    
    public function getNameList($uuids){
        $uuids = explode(";", $uuids);
        
        return Response::json($uuids);
    }

    public function getHistory($uuid){
        return $uuid;
    }
    
    public function getRandom($amount = 1){
        return $amount;
    }
    
    public function postName(){
        $uuids = Input::get('uuids');
        
        $arr = array();
        for($i = 0; $i < count($uuids); $i++){
            $arr[$i] = $uuids[$i];
        }
        
        $response = Response::json($arr);
        $response->header('Content-Type', "json");
        return $response;
    }
    
    public function postUuid(){
        $names = Input::get('names');
        
        $arr = array();
        for($i = 0; $i < count($names); $i++){
            $arr[$i] = $names[$i];
        }
        
        $response = Response::json($arr);
        $response->header('Content-Type', "json");
        return $response;
    }
}

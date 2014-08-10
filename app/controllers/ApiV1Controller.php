<?php

class ApiV1Controller extends ApiController {

    private function uuid($name){
        $profile = $this->uuidProfile($name);
        
        if(array_key_exists("error", $profile)){
            return array("uuid" => "unknown", "name" => $name, "status" => "error");
        }
                
        return array("uuid" => $this->stripDashes($profile["uuid"]), "name" => $profile["name"]);
    }
    
    private function name($uuid){
        $profile = $this->nameProfile($uuid);
        
        if(array_key_exists("error", $profile)){
            return array("uuid" => $this->stripDashes($uuid), "name" => "unknown");
        }
                
        return array("uuid" => $this->stripDashes($profile["uuid"]), "name" => $profile["name"], "status" => "ok");
    }
    
    private function names($uuids){
        $arr = array("maximum" => 50);
        $processed = array();
        $duplicates = array();
        $results = array();
        $notParsed = array();
        
        foreach($uuids as $uuid){
            if(count($processed) == 50){
                $notParsed[count($notParsed)] = $uuid;
                continue;
            }
            
            if(in_array($uuid, $processed)){
                $duplicates[count($duplicates)] = $uuid;
                continue;
            }
            
            $profile = $this->uuid($uuid);
            $results[$profile["name"]] = $profile["uuid"];
            $processed[count($processed)] = $uuid;
        }
        
        $arr["results"] = $results;
        $arr["duplicates"] = $duplicates;
        $arr["not-parsed"] = count($notParsed);
        
        return $arr;
    }
    
    private function uuids($names){
        $arr = array("maximum" => 50);
        $processed = array();
        $duplicates = array();
        $results = array();
        $notParsed = array();
        
        foreach($names as $name){
            if(count($processed) == 50){
                $notParsed[count($notParsed)] = $name;
                continue;
            }
            
            if(in_array($name, $processed)){
                $duplicates[count($duplicates)] = $name;
                continue;
            }
            
            $profile = $this->name($name);
            $results[$profile["uuid"]] = array("name" => $profile["name"], "status" => $profile["status"]);
            $processed[count($processed)] = $name;
        }
        
        $arr["results"] = $results;
        $arr["duplicates"] = $duplicates;
        $arr["not-parsed"] = count($notParsed);
        
        return $arr;
    }
    
    public function getUuid($name){
        $arr = $this->uuid($name);        
        return Response::json($arr);
    }
    
    public function getName($uuid){
        if(strlen($uuid) == 32) $uuid = $this->insertDashes($uuid);
        
        $arr = $this->name($uuid);        
        return Response::json($arr);
    }
    
    public function getUuidList($names){
        $names = explode(";", $names);
        
        $arr = $this->names($names);
        
        return Response::json($arr);
    }
    
    public function getNameList($uuids){
        $uuids = explode(";", $uuids);
        
        $arr = array();
        for($i = 0; $i < count($uuids); $i++){
            $uuid = $uuids[$i];
            if(strlen($uuid) == 32) $uuid = $this->insertDashes($uuid);
            $arr[count($arr)] = $uuid;
        }
        
        $arr = $this->uuids($arr);
        
        return Response::json($arr);
    }

    public function getHistory($uuid){
        if(strlen($uuid) == 32) $uuid = $this->insertDashes($uuid);
        
        $current = $this->name($uuid);
        $past = $this->history($uuid)["names"];
        
        $names = array();
        foreach($past as $n){
            if($n != $current["name"]){
                $names[count($names)] = $n;
            }
        }
        
        $arr = array("current" => $current, "names" => $names, "size" => count($names));
        
        return Response::json($arr);
    }
    
    public function getRandom($amount = 1){
        $amount = (int)$amount;
        if($amount > 100) $amount = 100;
        
        $players = Player::random($amount); // Handles the capped range
        
        $arr = array();
        
        foreach($players as $player){
            $arr[count($arr)] = array("name" => $player->name, "uuid" => $this->stripDashes($player->uuid));
        }
        
        return Response::json($arr);
    }
    
    public function postName(){
        $uuids = Input::get('uuids');
        
        $arr = array();
        for($i = 0; $i < count($uuids); $i++){
            $uuid = $uuids[$i];
            if(strlen($uuid) == 32) $uuid = $this->insertDashes($uuid);
            $arr[count($arr)] = $uuid;
        }
        
        $arr = $this->uuids($arr);
        
        $response = Response::json($arr);
        $response->header('Content-Type', "json");
        return $response;
    }
    
    public function postUuid(){
        $names = Input::get('names');
        
        $arr = $this->names($names);
        
        $response = Response::json($arr);
        $response->header('Content-Type', "json");
        return $response;
    }
}

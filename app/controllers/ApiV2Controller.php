<?php

class ApiV2Controller extends ApiController {
    
    private $maximumListSize = 100;
    private $maximumRandom = 100;
    
    private function uuids($names){
        $skipped = array();
        $duplicates = array();
        $processed = array();
        
        for($i = 0; $i < count($names); $i++){
            $name = $names[$i];
            
            if(count($processed) > $this->maximumListSize){
                $skipped[count($skipped)] = $name;
                continue;
            }
            
            if(array_key_exists($name, $processed)){
                $duplicates[count($duplicates)] = $name;
                continue;
            }
            
            $profile = $this->uuidProfile($name);
            
            if(array_key_exists("error", $profile))
                return $profile;
                
            $processed[$name] = $profile["uuid"];
        }
        
        $arr = array("results" => $processed, "skipped" => $skipped, "duplicates" => $duplicates);
        return $arr;
    }
    
    private function names($uuids){
        $skipped = array();
        $duplicates = array();
        $processed = array();
        
        for($i = 0; $i < count($uuids); $i++){
            $uuid = $uuids[$i];
            if(strlen($uuid) == 32) $uuid = $this->insertDashes($uuid);
            
            if(count($processed) > $this->maximumListSize){
                $skipped[count($skipped)] = $uuid;
                continue;
            }
            
            if(array_key_exists($uuid, $processed)){
                $duplicates[count($duplicates)] = $uuid;
                continue;
            }
            
            $profile = $this->nameProfile($uuid);
            
            if(array_key_exists("error", $profile))
                return $profile;
                
            $processed[$uuid] = $profile["name"];
        }
        
        $arr = array("results" => $processed, "skipped" => $skipped, "duplicates" => $duplicates);
        return $arr;
    }
    
    public function getInsertDashes($uuid){
        $arr = array();
        
        if(strlen($uuid) != 32){
            $arr = $this->raiseError(array("message" => "UUID is not 32 characters long", "error" => true));
        }else{
            $arr = array("input" => $uuid, "output" => $this->insertDashes($uuid));
        }
        
        return Response::json($arr);
    }
    
    public function getStripDashes($uuid){
        $arr = array();
        
        if(strlen($uuid) != 36){
            $arr = $this->raiseError(array("message" => "UUID is not 36 characters long", "error" => true));
        }else{
            $arr = array("input" => $uuid, "output" => $this->stripDashes($uuid));
        }
        
        return Response::json($arr);
    }
    
    public function getErrorTest(){
        $arr = array("message" => "This is a test error", "error" => true, "errorId" => -1);
        
        return Response::json($arr);
    }
    
    public function getInfo(){
        $arr = array("maximum-list-size" => $this->maximumListSize, "maximum-random-amount" => $this->maximumRandom, "current-time" => time());
        
        $total = Player::count();
        $arr["total-players-cached"] = $total;
        
        $expired = Player::where('expires_on', '<=', time())->count();
        $arr["known-expired-players"] = $expired;
        
        return Response::json($arr);
    }
    
    public function getUuid($name){
        $arr = $this->uuidProfile($name);
        $arr["query"] = $name;
        
        return Response::json($arr);
    }
    
    public function getUuidOffline($name){
        $player = new Player();
        $player->name = $name;
        
        $arr = $player->asArray();
        $arr["query"] = "OfflinePlayer:" . $name;
        
        return Response::json($arr);
    }
    
    public function getName($uuid){
        if(strlen($uuid) == 32) $uuid = $this->insertDashes($uuid);
        
        $arr = $this->nameProfile($uuid);
        $arr["query"] = $uuid;
        
        return Response::json($arr);
    }
    
    public function getUuidList($names){
        $names = explode(";", $names);
        
        $arr = $this->uuids($names);
        
        return Response::json($arr);
    }
    
    public function getNameList($uuids){
        $uuids = explode(";", $uuids);
        
        $arr = $this->names($uuids);
        
        return Response::json($arr);
    }

    public function getHistory($uuid){
        if(strlen($uuid) == 32) $uuid = $this->insertDashes($uuid);
        
        $arr = $this->history($uuid);
        
        $arr["query"] = $uuid;
        return Response::json($arr);
    }
    
    public function getRandom($amount = 1){
        $amount = (int)$amount;
        $requested = $amount;
        if($amount > $this->maximumRandom) $amount = $this->maximumRandom;
        
        $players = Player::random($amount); // Auto-corrects range
        
        $arr = array();
        
        foreach($players as $player){
            $arr[count($arr)] = $player->asArray();
        }
        
        $arr = array("requested" => (int)$requested, "got" => count($arr), "results" => $arr);
        
        return Response::json($arr);
    }
    
    public function postName(){
        $uuids = Input::get('uuids');
        
        $arr = $this->names($uuids);
        
        $response = Response::json($arr);
        $response->header('Content-Type', "json");
        return $response;
    }
    
    public function postUuid(){
        $names = Input::get('names');
        
        $arr = $this->uuids($names);
        
        $response = Response::json($arr);
        $response->header('Content-Type', "json");
        return $response;
    }
}

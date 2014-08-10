<?php

class HomeController extends BaseController {

    private function raiseError($error){
        $log = new ErrorLog();
        
        $log->requestUrl = Request::url();
        $log->errorResponse = print_r($error, true);
        $log->clientIp = Request::getClientIp();
        $log->userAgent = Agent::getUserAgent();
        
        $browser = Agent::browser();
        $log->clientBrowser = $browser . ' v' . Agent::version($browser);
        
        $platform = Agent::platform();
        $log->clientPlatform = $platform . ' v' . Agent::version($platform);
        
        $log->save();
        
        $error["errorId"] = $log->id;
        return $error;
    }

    private function insertDashes($uuid){   
        if(strlen($uuid) != 32) return "unknown";
        
        return substr($uuid, 0, 8) . '-'
            . substr($uuid, 8, 4) . '-'
            . substr($uuid, 12, 4) . '-'
            . substr($uuid, 16, 4) . '-'
            . substr($uuid, 20, 12);
    }

    private function uuidMojang($usernames){
        $ch = curl_init();
        $curlConfig = array(
            CURLOPT_URL => "https://api.mojang.com/profiles/minecraft",
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => json_encode($usernames),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        );
        curl_setopt_array($ch, $curlConfig);
        $json = curl_exec($ch);
        curl_close($ch);
        
        $arr = json_decode($json, true);
        if(array_key_exists("error", $arr)){
            return $this->raiseError(array("message" => $arr["error"] . ': ' . $arr["errorMessage"], "error" => true));
        }
        
        $out = array();
        if(count($arr) > 0){
            for($i = 0; $i < count($arr); $i++){
                $out[$i] = array("uuid" => $this->insertDashes($arr[$i]["id"]), "name" => $arr[$i]["name"]);
            }
        }	

        if(count($out) != count($usernames)){
            foreach($usernames as $user){
                $has = false;
                foreach($out as $record){
                    if(in_array($user, $record)){
                        $has = true;
                        break;
                    }
                }
                
                if(!$has){
                    $out[count($out)] = array("uuid" => "unknown", "name" => $user);
                }
            }
        }
        
        return $out;
    }
    
    private function nameMojang($uuid){
        
    }

    private function uuid($name){
        // First check DB
        $player = Player::where('name', $name)->first();
        if($player == null){
            $profiles = $this->uuidMojang(array($name));
            if(array_key_exists("error", $profiles)){
                return $profiles;
            }
            
            $player = new Player();
            $player->name = $name;
            $player->uuid = $profiles[0]["uuid"];
            
            if($player->uuid != null)
                $player->save();
        }
                
        return $player->asArray();
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
        $arr = $this->name($uuid);
        $arr["query"] = $uuid;
        
        return Response::json($arr);
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

<?php
class ApiController extends BaseController{
    
    protected function raiseError($error){
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
    
    protected function stripDashes($uuid){
        if(strlen($uuid) == 36){
            return str_replace('-', '', $uuid);
        }else return "unknown";
    }

    protected function insertDashes($uuid){   
        if(strlen($uuid) != 32) return "unknown";
        
        return substr($uuid, 0, 8) . '-'
            . substr($uuid, 8, 4) . '-'
            . substr($uuid, 12, 4) . '-'
            . substr($uuid, 16, 4) . '-'
            . substr($uuid, 20, 12);
    }

    protected function uuidMojang($usernames){
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
    
    protected function nameMojang($uuids){
        $profiles = array();
        
        foreach($uuids as $uuid){
            $profile = $this->nameMojangSingle($uuid);
            if(array_key_exists("error", $profile)) 
                return $profile;
            
            $profiles[count($profiles)] = $profile;
        }
        
        return $profiles;
    }
    
    private function nameMojangSingle($uuid){
        $uuidShort = $this->stripDashes($uuid);
        $ch = curl_init();
        $curlConfig = array(
            CURLOPT_URL => "https://sessionserver.mojang.com/session/minecraft/profile/" . $uuidShort,
            CURLOPT_RETURNTRANSFER => true,
        );
        curl_setopt_array($ch, $curlConfig);
        $json = curl_exec($ch);
        curl_close($ch);
        
        if($json == null || strlen(trim($json)) == 0){
            return $this->raiseError(array("message" => "Session server did not return a result", "error" => true));
        }
        
        $arr = json_decode($json, true);
        
        if(array_key_exists("error", $arr)){
            return $this->raiseError(array("message" => $arr["error"] . ': ' . $arr["errorMessage"], "error" => true));
        }
        
        if(!array_key_exists("name", $arr)){
            return $this->raiseError(array("message" => "Session server did not return a result with a name", "error" => true, "returned-result" => $arr));
        }
        
        $arr = array("uuid" => $uuid, "name" => $arr["name"]);
        return $arr;
    }

    protected function historyMojang($uuid){
        $uuidShort = $this->stripDashes($uuid);
        $ch = curl_init();
        $curlConfig = array(
            CURLOPT_URL => "https://api.mojang.com/user/profiles/" . $uuidShort . "/names",
            CURLOPT_RETURNTRANSFER => true,
        );
        curl_setopt_array($ch, $curlConfig);
        $json = curl_exec($ch);
        curl_close($ch);
        
        $json = json_decode($json, true);
        
        return $json; // It's already in array format
    }
    
    protected function uuidProfile($name){
        $fromDb = true;
    
        $count = Player::where('name', $name)->get();
        if($count->count() > 1){
            $count->each(function ($p){
                $p->delete(); // We'll just request fresh data...
            });
        }
    
        $player = Player::where('name', $name)->first();
        if($player != null && $player->isExpired()){
            $player->delete();
            $player = null;
        }
        
        if($player == null){
            $profiles = $this->uuidMojang(array($name));
            if(array_key_exists("error", $profiles)){
                return $profiles;
            }
            
            $player = new Player();
            $player->name = $name;
            $player->uuid = $profiles[0]["uuid"];
            
            $fromDb = false;
            
            if($player->uuid != null){
                $player->expires_on = time() + (60 * 60 * 2); // 2 hours
                $player->save();
            }
        }
                
        $arr = $player->asArray();
        $arr["source"] = $fromDb ? "cache" : "mojang";
        return $arr;
    }
    
    protected function nameProfile($uuid){
        $fromDb = true;
        
        if(strlen($uuid) == 32) $uuidDashed = $this->insertDashes($uuid);
        else $uuidDashed = $uuid;
        
        $count = Player::where('uuid', $uuidDashed)->get();
        if($count->count() > 1){
            $count->each(function ($p){
                $p->delete(); // We'll just request fresh data...
            });
        }
         
        $player = Player::where('uuid', $uuidDashed)->first();
        if($player != null && $player->isExpired()){
            $player->delete();
            $player = null;
        }
        
        if($player == null){
            $profiles = $this->nameMojang(array($uuid));
            if(array_key_exists("error", $profiles)){
                return $profiles;
            }
            
            $profile = $profiles[0];
            
            if($player == null){
                $player = new Player();
                $player->name = $profile["name"];
                $player->uuid = $profile["uuid"];
                
                $fromDb = false;
                
                if($player->name != "unknown" && $player->uuid != "unknown"){
                    $player->expires_on = time() + (60 * 60 * 2); // 2 hours
                    $player->save();
                }
            }
        }
        
        $arr = $player->asArray();
        $arr["source"] = $fromDb ? "cache" : "mojang";
        return $arr;
    }
    
    protected function history($uuid){
        if(strlen($uuid) == 32) $uuid = $this->insertDashes($uuid);
        
        $count = PlayerHistory::where('uuid', $uuid)->get();
        if($count->count() > 1){
            $count->each(function($h){
                $children = $h->names();
                foreach($children as $child){
                    PlayerHistoryName::where('historyId', $child["historyId"])->delete();
                }
                
                $h->delete();
            });
        }
        
        $history = PlayerHistory::where('uuid', $uuid)->first();
        if($history != null && $history->isExpired()){
            $children = $history->names();
            foreach($children as $child){
                PlayerHistoryName::where('historyId', $child["historyId"])->delete();
            }
            
            $history->delete();
            $history = null;
        }
        
        if($history == null){
            $profile = $this->historyMojang($uuid);
            
            $history = new PlayerHistory();
            $history->uuid = $uuid;
            $history->expires_on = time() + (60 * 60 * 2); // 2 hours
            $history->save();
            
            $id = $history->id;
            
            foreach($profile as $name){
                $historyName = new PlayerHistoryName();
                $historyName->historyId = $id;
                $historyName->name = $name;
                $historyName->save();
            }
        }
        
        return $history->asArray();
    }
}
?>
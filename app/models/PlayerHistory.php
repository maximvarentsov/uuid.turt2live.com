<?php
class PlayerHistory extends Eloquent{

    protected $table = 'history';
    
    public function names(){
        return PlayerHistoryName::where('historyId', $this->id)->get()->toArray();
    }
    
    public function asArray(){
        $arr = array();
        
        $names = $this->names();
        $namesArr = array();
        foreach($names as $name){
            $namesArr[count($namesArr)] = $name["name"];
        }
        
        $arr['names'] = $namesArr;
        $arr['expires-in'] = $this->expires_on - time();
        $arr['expires-on'] = $this->expires_on;
        
        return $arr;
    }
    
    public function isExpired(){
        return $this->expires_on <= time();
    }
    
}
?>
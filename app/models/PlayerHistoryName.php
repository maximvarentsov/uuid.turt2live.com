<?php
class PlayerHistoryName extends Eloquent{

    protected $table = 'history-players';
    
    public function asArray(){
        $arr = array();
        
        $arr['name'] = $this->name == null ? "unknown" : $this->name;
        
        return $arr;
    }
    
    public function isExpired(){
        return $this->expires_on <= time();
    }
    
}
?>
<?php
class Player extends Eloquent{

    protected $table = 'players';
    
    public function asArray(){
        $arr = array();
        
        $arr['name'] = $this->name;
        $arr['uuid'] = $this->uuid;
        $arr['offline-uuid'] = $this->offlineUuid();
        
        return $arr;
    }
    
    public function offlineUuid(){
        $hashed = md5("OfflinePlayer:".$this->name);
        return $this->uuidFromHashedName($hashed, 3);
    }
    
    
    // Taken from UUID library because the Uuid::uuid3 method is not sufficient.
    protected function uuidFromHashedName($hash, $version)
    {
        // Set the version number
        $timeHi = hexdec(substr($hash, 12, 4)) & 0x0fff;
        $timeHi &= ~(0xf000);
        $timeHi |= $version << 12;

        // Set the variant to RFC 4122
        $clockSeqHi = hexdec(substr($hash, 16, 2)) & 0x3f;
        $clockSeqHi &= ~(0xc0);
        $clockSeqHi |= 0x80;

        $fields = array(
            'time_low' => substr($hash, 0, 8),
            'time_mid' => substr($hash, 8, 4),
            'time_hi_and_version' => sprintf('%04x', $timeHi),
            'clock_seq_hi_and_reserved' => sprintf('%02x', $clockSeqHi),
            'clock_seq_low' => substr($hash, 18, 2),
            'node' => substr($hash, 20, 12),
        );

        return vsprintf(
            '%08s-%04s-%04s-%02s%02s-%012s',
            $fields
        );
    }
}
?>
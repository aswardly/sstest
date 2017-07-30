<?php
/**
 * Emoneygw\Generator\UUID
 * 
 * @author Andi Wijaya
 * @version 1
 * 
 * UUID Generator class
 */
namespace Emoneygw\Generator;

class UUID
{
    /**
     * Generate a new random UUID value
     * 
     * @param integer $length
     * @return String
     */
    public static function Generate($length = null) {
        //if no length given then set to default
        if(is_null($length)) {
            $length = 24;
        }
        //divide length by 4
        $total_blocks = ceil($length/4);
        $last_block_len = $length%4;
        $val = null;
        for($i=0;$i<$total_blocks-1;$i++) {
            $val.= sprintf('%04x',mt_rand(0,0xffff));
        }
        //generate random value for last block
        if($last_block_len > 0)
            $val.= sprintf('%0'.$last_block_len.'x',mt_rand(0,0xffff));
        
        return substr($val, 0, $length);
    }
}
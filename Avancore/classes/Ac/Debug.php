<?php

class Ac_Debug {
    
    static $created = array();
    
    static $deleted = array();
    
    static $misc = array();
    
    static function clear() {
        self::$created = array();
        self::$deleted = array();
        self::$misc = array();
    }
    
    static function reportConstruct($obj) {
        $c = get_class($obj);
        if (!isset(self::$created[$c])) self::$created[$c] = 1;
            else self::$created[$c]++;
    }
    
    static function reportDestruct($obj) {
        $c = get_class($obj);
        if (!isset(self::$deleted[$c])) self::$deleted[$c] = 1;
            else self::$deleted[$c]++;
    }
    
    static function getInstanceCounters($class = false) {
        if ($class === false) 
            $class = array_unique(
                array_merge(
                    array_keys(self::$created), 
                    array_keys(self::$deleted)
                )
            );
        elseif (!is_array($class)) $class = array($class);
        $res = array();
        foreach ($class as $c) {
            $res[$c] = array(
                'created' => isset(self::$created[$c])? self::$created[$c] : 0,
                'deleted' => isset(self::$deleted[$c])? self::$deleted[$c] : 0,
            );
            $res[$c]['existing'] = $res[$c]['created'] - $res[$c]['deleted'];
        }
        return $res;
    }
    
    static function savageMode() {
        while(ob_get_level()) ob_end_clean();
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ALL);
    }
    
}
<?php

class Ac_Model_Association_Referenced extends Ac_Model_Association_ModelObject {
    
    /**
     * @param Ac_Model_Object $object
     */
    function beforeSave($object, & $errors) {
        $res = null;
        
        if (!$object instanceof Ac_Model_Object) throw Ac_E_InvalidCall::wrongClass('object', $object, 'Ac_Model_Object');
        
        $f = $this->getInMemoryField();
        if (($val = $object->$f)) {
            if (!$this->storeReferenced($object, $val, $errors)) $res = false;
        }
        
        return $res;        
    }
    
    protected function storeReferenced(Ac_Model_Object $object, $recordOrRecords, & $errors) {
        $res = true;
        
        $errorKey = $this->getErrorKey();
        $fieldLinks = $this->getFieldLinks();
        
        if (is_array($recordOrRecords)) $r = $recordOrRecords;
            else $r = array($recordOrRecords);
            
        foreach (array_keys($r) as $k) {
            $rec = $r[$k];
            if ((!$rec->isPersistent() || $rec->getChanges())) {
                if (!$rec->store()) {
                    $errors[$errorKey][$k] = $rec->getErrors();
                    $res = false;
                }
            }
            foreach ($fieldLinks as $sf => $df) $object->$sf = $rec->$df; 
        }
        
        return $res;
    }
    
    
    /**
     * @param Ac_Model_Object $object
     */
    function afterSave($object, & $errors) {
    }
    
}
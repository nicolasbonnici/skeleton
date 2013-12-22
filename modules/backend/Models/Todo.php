<?php

namespace modules\backend\Todo;

class Todo {
    /**
     * Load todos from a given user
     * 
     * @param int $iUserId
     * @return bool
     */
    public function loadByUserId($iUserId) {
        
        if (!empty($iUserId)) {
            return $this->loadByParameters(
                        array(
                            'users_iduser' => $iUserId
                        ), 
                        array(
                            'idtodo' => 'DESC'
                        ),
                        array(0,10)
                    );            
        }
        return false;
    }
}
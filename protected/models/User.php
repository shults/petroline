<?php

/**
 * Description of User
 *
 * @author shults
 */
class User extends CActiveRecord
{

    public function tableName()
    {
        return '{{users}}';
    }

    public function primaryKey()
    {
        return 'user_id';
    }

    /**
     * @see CActiveRecord
     * 
     * @param strint $className
     * @return User 
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

}

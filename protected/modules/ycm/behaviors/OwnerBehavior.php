<?php

/**
 * Description of OwnerBehaviour
 *
 * @author shults
 */
class OwnerBehavior extends CActiveRecordBehavior {

	public $created_by = 'created_by';
	public $updated_by = 'updated_by';

	public function beforeSave($event) {
		if ($this->owner->getIsNewRecord()) {
			$this->owner->{$this->created_by} = Yii::app()->user->id;
		} else {
			$this->owner->{$this->updated_by} = Yii::app()->user->id;
		}

		return parent::beforeSave($event);
	}

}
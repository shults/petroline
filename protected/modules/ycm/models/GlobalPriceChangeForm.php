<?php

class GlobalPriceChangeForm extends CFormModel
{

    /**
     * @var rise up/down price [-100: +many]
     */
    public $ratio;

    /**
     * @return CDbConnection
     */
    private function getDb()
    {
       return Yii::app()->getComponent('db');
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'ratio' => Yii::t('core', 'Percents')
        );
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('ratio', 'numerical',
                'integerOnly' => false,
                'min' => -100,
                'tooSmall' => Yii::t('core', 'You cannot reduce price more than -100%')
            ),
            array('ratio', 'required')
        );
    }

    /**
     * Return number of changed products
     *
     * @return int
     */
    public function changePrices()
    {
        return $this->getDb()->createCommand('UPDATE {{products}} SET price=CEIL(price*:ratio)')->execute(array(
            ':ratio' => 1 + $this->ratio / 100
        ));
    }

}
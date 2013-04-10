<div class="row-fluid">
    <div class="span10">
        <?php
        /* @var $form TbActiveForm */
        /* @var $this UserController */
        /* @var $user User */
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'type' => 'horizontal',
            'inlineErrors' => false,
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
        echo $form->textFieldRow($user, 'first_name');
        echo $form->textFieldRow($user, 'last_name');
        echo $form->textFieldRow($user, 'email');
        echo $form->dropDownListRow($user, 'role', User::$roles);
        if ($user->getIsNewRecord()) {
            echo $form->passwordFieldRow($user, 'password');
            echo $form->passwordFieldRow($user, 'password_repeat');
        }
        ?>
        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButtonGroup', array(
                'type' => '',
                'buttons' => array(
                    array(
                        'buttonType' => 'submit',
                        'type' => 'primary',
                        'label' => Yii::t('YcmModule.ycm', 'Save'),
                        'htmlOptions' => array('name' => '_save')
                    ),
                ),
            ));
            ?>
        </div>
        <?php $this->endWidget() ?>
    </div>
</div>
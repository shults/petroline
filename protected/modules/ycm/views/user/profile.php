<div class="row-fluid">
    <div class="span10 well well-small">
        <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'type' => 'horizontal',
            'inlineErrors' => false,
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
        echo $form->textFieldRow($user, 'first_name');
        echo $form->textFieldRow($user, 'last_name');
        echo $form->textFieldRow($user, 'email');
        echo CHtml::tag('div', array('class' => 'controls'), CHtml::submitButton('Обновить', array('class' => 'btn')));
        $this->endWidget();
        ?>
    </div>
</div>
<div class="row-fluid">
    <div class="span10 well well-small">
        <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'type' => 'horizontal',
            'inlineErrors' => false,
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
        echo $form->passwordFieldRow($changePasswordForm, 'old_password');
        echo $form->passwordFieldRow($changePasswordForm, 'password');
        echo $form->passwordFieldRow($changePasswordForm, 'password_repeat');
        echo CHtml::tag('div', array('class' => 'controls'), CHtml::submitButton('Обновить', array('class' => 'btn')));
        $this->endWidget();
        ?>

    </div>
</div>
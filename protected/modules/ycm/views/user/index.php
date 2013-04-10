<div class="btn-toolbar">
    <?php
    $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'type' => '',
        'buttons' => array(
            array(
                'type' => 'primary',
                'label' => Yii::t('user', 'Create user'),
                'url' => array('user/add'),
            ),
        ),
    ));
    ?>
</div>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => new CActiveDataProvider($userModel),
    'columns' => array(
        'first_name',
        'last_name',
        'email',
        'role',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'updateButtonUrl' => 'CHtml::normalizeUrl(array("user/edit", "user_id" => $data->user_id))',
            'deleteButtonUrl' => 'CHtml::normalizeUrl(array("user/delete", "user_id" => $data->user_id))',
            'viewButtonOptions' => array(
                'style' => 'display:none;',
            ),
        ),
    )
));
?>
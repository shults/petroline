<?php

/**
 * Description of Message
 *
 * @author shults
 */
class Message extends CActiveRecord
{

	public $adminNames = array('Сообщения', 'сообщение', 'сообщения');

	public function tableName()
	{
		return '{{messages}}';
	}

	public function primaryKey()
	{
		return 'message_id';
	}

	public function beforeSave()
	{
		if (!$this->sended_at)
			$this->sended_at = new CDbExpression('NOW()');
		return parent::beforeSave();
	}

	public function rules()
	{
		return array(
			array('author_name,author_email,text', 'required'),
			array('author_email', 'email'),
			array('checked', 'safe')
		);
	}

	public function attributeLabels()
	{
		return array(
			'checked' => 'Проверено',
			'author_name' => 'Имя',
			'author_email' => 'E-Mail',
			'text' => 'Текст сообщения',
			'sended_at' => 'Дата отправки',
		);
	}
	
	public function attributeWidgets()
	{
		return array(
			array('text', 'textArea'),
			array('sended_at', 'datetime'),
			array('checked', 'boolean')
		);
	}

	public function afterSave()
	{
		$this->sendEmailAfterSave();
		parent::afterSave();
	}

	private function sendEmailAfterSave()
	{
		$mail = new Mail;
		$mail->setTo($admin_email = Config::get('admin_email'));
		$mail->setFrom($admin_email);
		$mail->setSubject('Сообщение с сайта компании "' . Config::get('company') . '"');
		$mail->setSender(Config::get('company'));
		$mail->setText($content = Yii::app()->controller->renderPartial('application.views.mail._new_message', array('message' => $this), true));
		$mail->send();
	}

	public function search()
	{
		$critetia = new CDbCriteria();
		$critetia->order = 'checked ASC, sended_at DESC';
		return new CActiveDataProvider($this, array(
			'criteria' => $critetia
		));
	}

	public function adminSearch()
	{
		return array(
			'columns' => array(
				'author_name',
				'author_email',
				'text',
				'sended_at',
				array(
					'name' => 'checked',
					'value' => '$data->checked == 1 ? "Да" : "Нет"'
				)
			)
		);
	}

}

<?php

/**
 * Description of UserController
 *
 * @author shults
 */
class UserController extends AdminController
{

    const ACTION_ADD = 'add';
    const ACTION_EDIT = 'edit';

    public function accessRules()
    {
        return array(
            array(
                'allow',
                'actions' => array('edit', 'add', 'delete'),
                'roles' => array('administrator')
            ),
            array(
                'deny',
                'actions' => array('edit', 'add', 'delete'),
                'roles' => array('moderator')
            ),
        );
    }

    public function actionIndex()
    {
        $this->breadcrumbs = array(
            Yii::t('user', 'Users')
        );
        $this->render('index', array(
            'userModel' => User::model(),
        ));
    }

    public function actionProfile()
    {
        $user = Yii::app()->user->getModel();
        $changePasswordForm = new ChangePasswordForm();
        if (isset($_POST['User'])) {
            $user->attributes = $_POST['User'];
            if ($user->validate()) {
                $user->save(false);
                Yii::app()->user->setFlash('success', Yii::t('user', 'Your profile updated succesfully.'));
                $this->redirect(array('user/profile'));
            }
        }

        if (isset($_POST['ChangePasswordForm'])) {
            $changePasswordForm->attributes = $_POST['ChangePasswordForm'];
            if ($changePasswordForm->validate()) {
                $user->password = $changePasswordForm->password;
                $user->setScenario(User::SCENARIO_CHANGE_PASSWORD);
                $user->save();
                Yii::app()->user->setFlash('success', Yii::t('user', 'Password changed succesfully.'));
                $this->redirect(array('user/profile'));
            }
        }
        $this->render('profile', array(
            'user' => $user,
            'changePasswordForm' => $changePasswordForm
        ));
    }

    public function actionAdd()
    {
        $this->actionEdit();
    }

    public function actionEdit($user_id = null)
    {
        $actionId = strtolower(Yii::app()->controller->action->id);
        $this->breadcrumbs = array(
            Yii::t('user', 'Users') => array('user/index')
        );
        if ($actionId === self::ACTION_ADD) {
            $this->breadcrumbs += array(
                Yii::t('user', 'Add user')
            );
        } else if ($actionId === self::ACTION_EDIT) {
            $this->breadcrumbs += array(
                Yii::t('user', 'Edit user')
            );
        }
        if ($user_id === null)
            $user = new User(User::SCENARIO_CREATE);
        else {
            if (($user = User::model()->findByPk($user_id)) == null) {
                throw new CHttpException(404, 'User not found');
            }
            $user->setScenario(User::SCENARIO_UPDATE);
        }

        if (isset($_POST['User'])) {
            $user->attributes = $_POST['User'];
            if ($user->validate()) {
                $user->save(false);
                if ($user->getScenario() == User::SCENARIO_CREATE)
                    Yii::app()->user->setFlash('success', Yii::t('user', 'New user has been created succesfully.'));
                else
                    Yii::app()->user->setFlash('success', Yii::t('user', 'User data had been updated succesfully.'));
                $this->redirect(array('user/index'));
            }
        }

        $this->render('edit', array(
            'user' => $user
        ));
    }

    public function actionDelete($user_id)
    {
        if (($user = User::model()->findByPk($user_id)) === null)
            throw new CHttpException(404);
        $user->delete();
        if (!$_GET['ajax'])
            $this->redirect(array('user/index'));
    }

}
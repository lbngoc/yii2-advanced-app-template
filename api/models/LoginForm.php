<?php
namespace api\models;

use Yii;
use yii\base\Model;
use amnah\yii2\user\models\forms\LoginForm as BaseLoginForm;

class LoginForm extends BaseLoginForm {
    // public $username;
    // public $password;

    public function login()
    {
        if ($this->validate()) {
            return true;
        } else {
            return false;
        }
    }
}

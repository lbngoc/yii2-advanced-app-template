<?php

namespace backend\models;

use Yii;
use amnah\yii2\user\models\User as BaseUser;
use backend\models\UserProfile;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 */
class User extends BaseUser
{

}

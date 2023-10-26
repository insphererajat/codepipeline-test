<?php

namespace common\components;

use Yii;

/**
 * Description of Applicant
 *
 * @author Amit Handa <insphere.amit@gmail.com>
 */
class Applicant extends \yii\web\User
{

    public function beforeLogin($identity, $cookieBased, $duration) {
        \common\models\Applicant::beforeUserLogin();

        return parent::beforeLogin($identity, $cookieBased, $duration);
    }

    public function afterLogin($identity, $cookieBased, $duration) {
        \common\models\Applicant::afterUserLogin($identity);

        return parent::afterLogin($identity, $cookieBased, $duration);
    }

    public function afterLogout($identity) {
        \common\models\Applicant::afterUserLogout($identity);

        return parent::afterLogout($identity);
    }

}

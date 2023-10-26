<?php

namespace backend\controllers\api;

use Yii;
use common\models\location\MstDistrict;
use common\models\location\MstState;
use common\models\location\MstTehsil;
/**
 * Description of LocationController
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class LocationController extends ApiController
{
    public function behaviors()
    {
        return [
            'ajax' => [
                'class' => \common\components\filters\AjaxFilter::className()
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action) {
                            // check session hijacking prevention
                            if (!\common\models\User::checkSessionHijackingPreventions(\common\models\User::BACKEND_LOGIN_KEY, \common\models\User::BACKEND_FIXATION_COOKIE, \common\models\User::BACKEND_SESSION_VALUE)) {
                                Yii::$app->user->logout();
                                return false;
                            }
                            return true;
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionGetState()
    {
        $countryCode = Yii::$app->request->post('countrycode');
        if (empty($countryCode)) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }

        $stateModel = MstState::getStateDropdown(['countryCode' => $countryCode]);

        $template = $this->renderPartial('/api/location/_dropdown.php', ['dropdownArr' => $stateModel, 'prompt' => 'Select State']);

        return \components\Helper::outputJsonResponse(['success' => 1, 'template' => $template]);
    }

    public function actionGetDistrict()
    {
        $stateCode = Yii::$app->request->post('statecode');
        if (empty($stateCode)) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }

        $districtModel = MstDistrict::getDistrictDropdown(['stateCode' => $stateCode]);

        $template = $this->renderPartial('/api/location/_dropdown.php', ['dropdownArr' => $districtModel, 'prompt' => 'Select District']);

        return \components\Helper::outputJsonResponse(['success' => 1, 'template' => $template]);
    }

    public function actionGetTehsil()
    {
        $districtCode = Yii::$app->request->post('districtCode');
        if (empty($districtCode)) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }

        $TehsilModel = MstTehsil::getTehsilDropdown(['districtCode' => $districtCode]);

        $template = $this->renderPartial('/api/location/_dropdown.php', ['dropdownArr' => $TehsilModel, 'prompt' => 'Select Tehsil']);

        return \components\Helper::outputJsonResponse(['success' => 1, 'template' => $template]);
    }
}

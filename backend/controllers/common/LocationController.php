<?php

namespace backend\controllers\common;

use Yii;
/**
 * Description of LocationController
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class LocationController extends \backend\controllers\AdminController
{

    public function behaviors()
    {
        $controllerBehaviors = [
            'ajax' => [
                'class' => \components\filters\AjaxFilter::className(),
                'only' => ['get-state', 'get-district']
            ]
        ];

        return \yii\helpers\ArrayHelper::merge($controllerBehaviors, parent::behaviors());
    }

    public function actionGetState()
    {
        $countryCode = Yii::$app->request->post('countrycode');
        if (empty($countryCode)) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }

        $stateModel = \common\models\location\MstState::getStateDropdown(['countryCode' => $countryCode]);

        $template = $this->renderPartial('/common/location/_dropdown.php', ['dropdownArr' => $stateModel, 'prompt' => 'Select State']);

        return \components\Helper::outputJsonResponse(['success' => 1, 'template' => $template]);
    }

    public function actionGetDistrict()
    {
        $stateCode = Yii::$app->request->post('statecode');
        if (empty($stateCode)) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }

        $districtModel = \common\models\location\MstDistrict::getDistrictDropdown(['stateCode' => $stateCode]);

        $template = $this->renderPartial('/common/location/_dropdown.php', ['dropdownArr' => $districtModel, 'prompt' => 'Select District']);

        return \components\Helper::outputJsonResponse(['success' => 1, 'template' => $template]);
    }

}

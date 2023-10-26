<?php

namespace frontend\controllers\api;

use Yii;
use common\models\MstDistrict;
use common\models\MstState;
/**
 * Description of LocationController
 *
 * @author Amit Handa
 */
class LocationController extends ApiController
{

    public function actionGetState()
    {
        $countryCode = Yii::$app->request->post('countrycode');
        if (empty($countryCode)) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }

        $stateModel = \common\models\location\MstState::getStateDropdown(['countryCode' => $countryCode]);

        $template = $this->renderPartial('/api/location/_dropdown.php', ['dropdownArr' => $stateModel, 'prompt' => '']);

        return \components\Helper::outputJsonResponse(['success' => 1, 'template' => $template]);
    }

    public function actionGetDistrict()
    {
        $stateCode = Yii::$app->request->post('statecode');
        if (empty($stateCode)) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }

        $districtModel = \common\models\location\MstDistrict::getDistrictDropdown(['stateCode' => $stateCode]);

        $template = $this->renderPartial('/api/location/_dropdown.php', ['dropdownArr' => $districtModel, 'prompt' => '']);

        return \components\Helper::outputJsonResponse(['success' => 1, 'template' => $template]);
    }

    public function actionGetTehsil()
    {
        $districtCode = Yii::$app->request->post('districtcode');
        if (empty($districtCode)) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }

        $tehsilModel = \common\models\location\MstTehsil::getTehsilDropdown(['districtCode' => $districtCode]);

        $template = $this->renderPartial('/api/location/_dropdown.php', ['dropdownArr' => $tehsilModel, 'prompt' => '']);

        return \components\Helper::outputJsonResponse(['success' => 1, 'template' => $template]);
    }

}

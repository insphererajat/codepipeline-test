<?php

namespace backend\controllers\api;

use common\models\ExamCentre;
use common\models\ExamCentreDetail;
use Yii;

/**
 * Description of ExamController
 *
 * @author Amit Handa
 */
class ExamController extends ApiController
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

    public function actionGetExamCentre()
    {
        $classifiedId = Yii::$app->request->post('classifiedId');
        if (empty($classifiedId)) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }

        $examCentreModel = ExamCentre::getExamCentreDropdown(['classifiedId' => $classifiedId]);

        $template = $this->renderPartial('/api/location/_dropdown.php', ['dropdownArr' => $examCentreModel, 'prompt' => 'Select Exam Centre']);

        return \components\Helper::outputJsonResponse(['success' => 1, 'template' => $template]);
    }

    public function actionGetRoom()
    {
        $examCentreId = Yii::$app->request->post('examCentreId');
        if (empty($examCentreId)) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }

        $roomList = ExamCentreDetail::getRoomNoDropdown(['examCentreId' => $examCentreId]);

        $template = $this->renderPartial('/api/location/_dropdown.php', ['dropdownArr' => $roomList, 'prompt' => 'Select Room No']);

        return \components\Helper::outputJsonResponse(['success' => 1, 'template' => $template]);
    }
}

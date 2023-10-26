<?php

namespace common\filters\auth;

use Yii;
use yii\filters\auth\HttpBearerAuth;

/**
 * Description of UserHttpBearerAuth
 *
 * @author Amit Handa
 */
class UserHttpBearerAuth extends HttpBearerAuth
{

    /**
     * @inheritdoc
     */
    public $model = [];
    public $publicExamAuthorization = FALSE;

    public function authenticate($user, $request, $response)
    {
        $authToken = $request->getHeaders()->get('authToken');
        if ($this->publicExamAuthorization) {
            if ($authToken === null) {
               $this->handleFailure($response);
            }
            
            $publicExamCenterFacultyModel = \common\models\PublicExamCenterFaculty::findByAuthToken($authToken);
            if (empty($publicExamCenterFacultyModel)) {
                $this->handleFailure($response);
            }

            if (!\common\models\PublicExamCenterFaculty::isTokenValid($publicExamCenterFacultyModel['auth_token_expiry_at'])) {
                $this->handleFailure($response);
            }

            $this->model['PublicExamCenterFacultyModel'] = $publicExamCenterFacultyModel;
        }

        return $this->model;
    }

    public function handleFailure($response)
    {
        try {
            parent::handleFailure($response);
        }
        catch (\Exception $ex) {
            print_r(json_encode([
                'status' => 0,
                'errors' => ['message' => 'Please provide valid auth key.'],
                'data' => []
            ]));
            Yii::$app->end();
        }
    }
}

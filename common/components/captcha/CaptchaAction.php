<?php
namespace common\components\captcha;

use Yii;
use yii\captcha\CaptchaAction as CaptchaCaptchaAction;

/**
 * Description of User
 *
 * @author Amit Handa
 */
class CaptchaAction extends CaptchaCaptchaAction
{
    //     /**
    //  * Generates a new verification code.
    //  * @return string the generated verification code
    //  */
    protected function generateVerifyCode()
    {
        return $this->generateRendomString();
    }

    private function  generateRendomString($upper =2 , $lower = 4, $numeric = 1)
    {
        $characters = [];
        for ($i = 0; $i < $upper; $i++) {
            $characters[] = chr(rand(65, 90));
        }
        for ($i = 0; $i < $lower; $i++) {
            $characters[] = chr(rand(97, 122));
        }
        for ($i = 0; $i < $numeric; $i++) {
            $characters[] = chr(rand(48, 57));
        }

        //using shuffle() to shuffle the order
        shuffle($characters);
        return implode('', $characters);
    } 

} 
  
<?php

namespace frontend\components;

use Yii;
use yii\web\View;

/**
 * Description of AppView
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class AppView extends View
{

    
    protected function findViewFile($view, $context = null)
    {
        $filepath = '@frontend/views/base/registration/'.$view;
          
        try {
            $path = parent::findViewFile($filepath, $context);
            
        }
        catch (\yii\base\InvalidParamException $ex) {
            throw $ex;
        }
         
         
//        if(!file_exists($path)){
//             
//            $customViewPath = $this->_findViewFile($view);
//            if ($customViewPath !== FALSE) {
//                $path = $customViewPath;
//            }
//        }

        return $path;
    }

    private function _findViewFile($view)
    {

        $viewPath = false;

        if (Yii::$app->controller !== null) {

            if (strncmp($view, '//', 2) === 0) {
                $file = ltrim($view, '/');
            }
            else {
                //Yii::$app->controller->id . '/' .
                $file =  ltrim($view, '/');
            }

            if (pathinfo($file, PATHINFO_EXTENSION) === '') {
                $path = $file . '.' . $this->defaultExtension;
            }
            else {
                $path = $file;
            }

            /**
             * @$viewPathsArray BasePath
             */
            $viewPathsArray = [ Yii::getAlias('@frontend/views/base/default/' . $path)]; //Application base default - lowest & last level 

            /**
             * @$viewPathsArray School Path
             */
            $baseNetworkDefaultPath = Yii::getAlias('@frontend/views/' . \Yii::$app->school->getValue('folder_name') . '/default/' . $path);
            $viewPathsArray[] = $baseNetworkDefaultPath;

            foreach (array_reverse($viewPathsArray) as $viewPath) {
                if (is_file($viewPath)) {
                    /**
                     * current view file exists
                     */
                    $viewPath = $viewPath;
                    break;
                }
            }
        }
        return $viewPath;
    }

}

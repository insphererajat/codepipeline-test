<?php

namespace frontend\models;

use common\models\ApplicantDocument;
use common\models\Media;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    public $documents;
    private $path = 'uploads/';
    private $applicant_id;
    private $type = [
        'qualification',
        'user',
        'cast'
    ];

    public $file;
    
    public function rules()
    {
        return [
            [['documents'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            //[['file'], 'file'],
        ];
    }

    public function upload()
    {
        $this->applicant_id = Yii::$app->applicant->id;
        if ($this->validate()) {
            // try {
            // foreach ($this->documents as $key =>  $file) {
            $file =  $this->documents;
            //code...
            $file->saveAs($this->path . $file->baseName . '.' . $file->extension);
            $media = new Media();
            if (empty($file->error)) {
                $media->applicant_id = $this->applicant_id;
                $media->media_type = $file->type;
                $media->filetype = $file->type;
                $media->filename = $file->baseName;
                $media->filepath = $file->pathinfo;
                // $media->filepath = (new UploadedFile)->pathinfo;
                if (!$media->save()) {
                    throw new \components\exceptions\AppException("Oops! An exception occured while saving the media.");
                    return FALSE;
                }
                $applicantDocument = new ApplicantDocument();
                $applicantDocument->applicant_id = $this->applicant_id;
                $applicantDocument->media_id = $media->id;
                $applicantDocument->type = $media->filetype;
                $applicantDocument->type = $media->filetype;
            }
            // }
            // } catch (\Throwable $th) {
            //     throw new \components\exceptions\AppException("Oops! An exception occured while uploading the documents.");
            // }
            return $this->errors;
        } else {
            return false;
        }
    }

    public static function uploadSave($post, $model)
    {

        foreach ($this->type as $type) {
            foreach ($post['documents'][$type] as $key => $value) {
                $model = new Model();
                $model = UploadedFile::getInstance($model, 'documents[' . $type . '][' . $key . ']');
            }
        }
    }
}

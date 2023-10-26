<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "media".
 *
 * @property int $id
 * @property string $guid
 * @property string $media_type
 * @property string $filename
 * @property string|null $filepath
 * @property int|null $filesize
 * @property string|null $filetype
 * @property int|null $width
 * @property int|null $height
 * @property string|null $caption
 * @property string $cdn_path
 * @property int|null $cdn_uploaded
 * @property int|null $modified_on
 * @property int|null $created_on
 *
 * @property ApplicantDocument[] $applicantDocuments
 * @property LogProfileMedia[] $logProfileMedia
 */
class Media extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid', 'media_type', 'filename', 'cdn_path'], 'required'],
            [['filesize', 'width', 'height', 'cdn_uploaded', 'modified_on', 'created_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['media_type'], 'string', 'max' => 30],
            [['filename', 'filepath', 'filetype', 'caption', 'cdn_path'], 'string', 'max' => 255],
            [['guid'], 'unique'],
            [['filename'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'guid' => Yii::t('app', 'Guid'),
            'media_type' => Yii::t('app', 'Media Type'),
            'filename' => Yii::t('app', 'Filename'),
            'filepath' => Yii::t('app', 'Filepath'),
            'filesize' => Yii::t('app', 'Filesize'),
            'filetype' => Yii::t('app', 'Filetype'),
            'width' => Yii::t('app', 'Width'),
            'height' => Yii::t('app', 'Height'),
            'caption' => Yii::t('app', 'Caption'),
            'cdn_path' => Yii::t('app', 'Cdn Path'),
            'cdn_uploaded' => Yii::t('app', 'Cdn Uploaded'),
            'modified_on' => Yii::t('app', 'Modified On'),
            'created_on' => Yii::t('app', 'Created On'),
        ];
    }

    /**
     * Gets query for [[ApplicantDocuments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDocuments()
    {
        return $this->hasMany(ApplicantDocument::className(), ['media_id' => 'id']);
    }

    /**
     * Gets query for [[LogProfileMedia]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogProfileMedia()
    {
        return $this->hasMany(LogProfileMedia::className(), ['media_id' => 'id']);
    }
}

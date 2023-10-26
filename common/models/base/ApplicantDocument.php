<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "applicant_document".
 *
 * @property int $id
 * @property int $applicant_post_id
 * @property int $media_id
 * @property int|null $type
 * @property int|null $reference_id
 * @property string|null $name
 * @property string|null $url
 * @property int|null $created_on
 *
 * @property ApplicantPost $applicantPost
 * @property Media $media
 */
class ApplicantDocument extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant_document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicant_post_id', 'media_id'], 'required'],
            [['applicant_post_id', 'media_id', 'type', 'reference_id', 'created_on'], 'integer'],
            [['name', 'url'], 'string', 'max' => 255],
            [['applicant_post_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantPost::className(), 'targetAttribute' => ['applicant_post_id' => 'id']],
            [['media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['media_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'applicant_post_id' => Yii::t('app', 'Applicant Post ID'),
            'media_id' => Yii::t('app', 'Media ID'),
            'type' => Yii::t('app', 'Type'),
            'reference_id' => Yii::t('app', 'Reference ID'),
            'name' => Yii::t('app', 'Name'),
            'url' => Yii::t('app', 'Url'),
            'created_on' => Yii::t('app', 'Created On'),
        ];
    }

    /**
     * Gets query for [[ApplicantPost]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantPost()
    {
        return $this->hasOne(ApplicantPost::className(), ['id' => 'applicant_post_id']);
    }

    /**
     * Gets query for [[Media]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasOne(Media::className(), ['id' => 'media_id']);
    }
}

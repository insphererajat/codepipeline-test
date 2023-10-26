<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "applicant_post_detail".
 *
 * @property int $id
 * @property string|null $guid
 * @property int $applicant_post_id
 * @property int $post_id
 * @property int|null $created_on
 *
 * @property ApplicantCriteria[] $applicantCriterias
 * @property MstPost $post
 * @property ApplicantPost $applicantPost
 */
class ApplicantPostDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant_post_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicant_post_id', 'post_id'], 'required'],
            [['applicant_post_id', 'post_id', 'created_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['guid'], 'unique'],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstPost::className(), 'targetAttribute' => ['post_id' => 'id']],
            [['applicant_post_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantPost::className(), 'targetAttribute' => ['applicant_post_id' => 'id']],
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
            'applicant_post_id' => Yii::t('app', 'Applicant Post ID'),
            'post_id' => Yii::t('app', 'Post ID'),
            'created_on' => Yii::t('app', 'Created On'),
        ];
    }

    /**
     * Gets query for [[ApplicantCriterias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantCriterias()
    {
        return $this->hasMany(ApplicantCriteria::className(), ['applicant_post_detail_id' => 'id']);
    }

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(MstPost::className(), ['id' => 'post_id']);
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
}

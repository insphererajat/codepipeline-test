<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_qualification_subject".
 *
 * @property int $id
 * @property int|null $subject_id
 * @property int|null $qualification_id
 * @property int|null $created_by
 * @property int|null $created_on
 *
 * @property User $createdBy
 * @property MstQualification $qualification
 * @property MstSubject $subject
 */
class MstQualificationSubject extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_qualification_subject';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subject_id', 'qualification_id', 'created_by', 'created_on'], 'integer'],
            [['subject_id', 'qualification_id'], 'unique', 'targetAttribute' => ['subject_id', 'qualification_id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['qualification_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstQualification::className(), 'targetAttribute' => ['qualification_id' => 'id']],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstSubject::className(), 'targetAttribute' => ['subject_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'subject_id' => Yii::t('app', 'Subject ID'),
            'qualification_id' => Yii::t('app', 'Qualification ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_on' => Yii::t('app', 'Created On'),
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Qualification]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQualification()
    {
        return $this->hasOne(MstQualification::className(), ['id' => 'qualification_id']);
    }

    /**
     * Gets query for [[Subject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(MstSubject::className(), ['id' => 'subject_id']);
    }
}

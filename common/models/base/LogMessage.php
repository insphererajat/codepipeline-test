<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "log_message".
 *
 * @property int $id
 * @property string $type
 * @property string|null $subject
 * @property string $message
 * @property string|null $template_id
 * @property int|null $to_applicant_id
 * @property int|null $reference_id
 * @property string|null $sent_to
 * @property string|null $detail
 * @property int|null $created_on
 *
 * @property Applicant $toApplicant
 */
class LogMessage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'message'], 'required'],
            [['type', 'message'], 'string'],
            [['to_applicant_id', 'reference_id', 'created_on'], 'integer'],
            [['subject'], 'string', 'max' => 100],
            [['template_id'], 'string', 'max' => 200],
            [['sent_to'], 'string', 'max' => 255],
            [['detail'], 'string', 'max' => 1000],
            [['to_applicant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Applicant::className(), 'targetAttribute' => ['to_applicant_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'subject' => Yii::t('app', 'Subject'),
            'message' => Yii::t('app', 'Message'),
            'template_id' => Yii::t('app', 'Template ID'),
            'to_applicant_id' => Yii::t('app', 'To Applicant ID'),
            'reference_id' => Yii::t('app', 'Reference ID'),
            'sent_to' => Yii::t('app', 'Sent To'),
            'detail' => Yii::t('app', 'Detail'),
            'created_on' => Yii::t('app', 'Created On'),
        ];
    }

    /**
     * Gets query for [[ToApplicant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getToApplicant()
    {
        return $this->hasOne(Applicant::className(), ['id' => 'to_applicant_id']);
    }
}

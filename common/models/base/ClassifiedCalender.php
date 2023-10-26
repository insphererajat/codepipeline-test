<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "classified_calender".
 *
 * @property int $classified_id
 * @property string $start_date
 * @property string $end_date
 * @property string $extended_date
 * @property string $payment_last_date
 * @property string $document_upload_last_date
 * @property string $exam_date
 * @property string $result_date
 * @property string $interview_letter_start_date
 * @property string $interview_letter_end_date
 * @property string $pt_letter_start_date
 * @property string $pt_letter_end_date
 * @property int $is_active
 * @property int $is_deleted
 * @property int $created_on
 * @property int $created_by
 * @property int $modified_on
 * @property int $modified_by
 *
 * @property MstClassified $classified
 * @property User $createdBy
 * @property User $modifiedBy
 */
class ClassifiedCalender extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'classified_calender';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['classified_id', 'start_date', 'end_date', 'payment_last_date', 'document_upload_last_date', 'exam_date'], 'required'],
            [['classified_id', 'is_active', 'is_deleted', 'created_on', 'created_by', 'modified_on', 'modified_by'], 'integer'],
            [['start_date', 'end_date', 'extended_date', 'payment_last_date', 'document_upload_last_date', 'exam_date', 'result_date', 'interview_letter_start_date', 'interview_letter_end_date', 'pt_letter_start_date', 'pt_letter_end_date'], 'safe'],
            [['classified_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstClassified::className(), 'targetAttribute' => ['classified_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'classified_id' => 'Classified ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'extended_date' => 'Extended Date',
            'payment_last_date' => 'Payment Last Date',
            'document_upload_last_date' => 'Document Upload Last Date',
            'exam_date' => 'Exam Date',
            'result_date' => 'Result Date',
            'interview_letter_start_date' => 'Interview Letter Start Date',
            'interview_letter_end_date' => 'Interview Letter End Date',
            'pt_letter_start_date' => 'Pt Letter Start Date',
            'pt_letter_end_date' => 'Pt Letter End Date',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'created_on' => 'Created On',
            'created_by' => 'Created By',
            'modified_on' => 'Modified On',
            'modified_by' => 'Modified By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClassified()
    {
        return $this->hasOne(MstClassified::className(), ['id' => 'classified_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifiedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'modified_by']);
    }
}

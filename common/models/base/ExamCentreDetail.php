<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "exam_centre_detail".
 *
 * @property int $id
 * @property string $guid
 * @property int $exam_centre_id
 * @property string|null $room_name
 * @property string|null $date
 * @property string|null $examtime
 * @property string|null $examination
 * @property int $shift_type_id
 * @property int $capacity
 * @property int $allocated
 * @property string|null $entry_time
 * @property string|null $contact_person_name
 * @property int|null $contact_person_mobile
 * @property string|null $contact_person_email
 * @property int $is_deleted
 * @property int|null $created_by
 * @property int|null $created_on
 * @property int|null $modified_on
 * @property int|null $modified_by
 *
 * @property ApplicantExam[] $applicantExams
 * @property ExamCentre $examCentre
 * @property User $modifiedBy
 * @property MstListType $shiftType
 */
class ExamCentreDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exam_centre_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid', 'exam_centre_id', 'shift_type_id', 'capacity'], 'required'],
            [['exam_centre_id', 'shift_type_id', 'capacity', 'allocated', 'contact_person_mobile', 'is_deleted', 'created_by', 'created_on', 'modified_on', 'modified_by'], 'integer'],
            [['date'], 'safe'],
            [['guid'], 'string', 'max' => 36],
            [['room_name', 'examtime', 'entry_time', 'contact_person_name', 'contact_person_email'], 'string', 'max' => 100],
            [['examination'], 'string', 'max' => 255],
            [['guid'], 'unique'],
            [['exam_centre_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExamCentre::className(), 'targetAttribute' => ['exam_centre_id' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
            [['shift_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['shift_type_id' => 'id']],
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
            'exam_centre_id' => Yii::t('app', 'Exam Centre ID'),
            'room_name' => Yii::t('app', 'Room Name'),
            'date' => Yii::t('app', 'Date'),
            'examtime' => Yii::t('app', 'Examtime'),
            'examination' => Yii::t('app', 'Examination'),
            'shift_type_id' => Yii::t('app', 'Shift Type ID'),
            'capacity' => Yii::t('app', 'Capacity'),
            'allocated' => Yii::t('app', 'Allocated'),
            'entry_time' => Yii::t('app', 'Entry Time'),
            'contact_person_name' => Yii::t('app', 'Contact Person Name'),
            'contact_person_mobile' => Yii::t('app', 'Contact Person Mobile'),
            'contact_person_email' => Yii::t('app', 'Contact Person Email'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_on' => Yii::t('app', 'Modified On'),
            'modified_by' => Yii::t('app', 'Modified By'),
        ];
    }

    /**
     * Gets query for [[ApplicantExams]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantExams()
    {
        return $this->hasMany(ApplicantExam::className(), ['exam_centre_detail_id' => 'id']);
    }

    /**
     * Gets query for [[ExamCentre]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamCentre()
    {
        return $this->hasOne(ExamCentre::className(), ['id' => 'exam_centre_id']);
    }

    /**
     * Gets query for [[ModifiedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModifiedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'modified_by']);
    }

    /**
     * Gets query for [[ShiftType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShiftType()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'shift_type_id']);
    }
}

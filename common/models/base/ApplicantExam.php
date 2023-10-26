<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "applicant_exam".
 *
 * @property int $id
 * @property int $applicant_id
 * @property int $applicant_post_id
 * @property int|null $type
 * @property int|null $exam_centre_id
 * @property int|null $exam_centre_detail_id
 * @property int|null $shift_type_id
 * @property string|null $rollno
 * @property string|null $comments
 * @property int|null $is_downloaded
 * @property int|null $downloaded_on
 * @property int|null $is_notification
 * @property int|null $notification_on
 * @property int|null $created_on
 * @property int|null $modified_on
 *
 * @property Applicant $applicant
 * @property ApplicantPost $applicantPost
 * @property ExamCentreDetail $examCentreDetail
 * @property ExamCentre $examCentre
 * @property MstListType $shiftType
 */
class ApplicantExam extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant_exam';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicant_id', 'applicant_post_id'], 'required'],
            [['applicant_id', 'applicant_post_id', 'type', 'exam_centre_id', 'exam_centre_detail_id', 'shift_type_id', 'is_downloaded', 'downloaded_on', 'is_notification', 'notification_on', 'created_on', 'modified_on'], 'integer'],
            [['rollno'], 'string', 'max' => 50],
            [['comments'], 'string', 'max' => 100],
            [['rollno'], 'unique'],
            [['applicant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Applicant::className(), 'targetAttribute' => ['applicant_id' => 'id']],
            [['applicant_post_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantPost::className(), 'targetAttribute' => ['applicant_post_id' => 'id']],
            [['exam_centre_detail_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExamCentreDetail::className(), 'targetAttribute' => ['exam_centre_detail_id' => 'id']],
            [['exam_centre_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExamCentre::className(), 'targetAttribute' => ['exam_centre_id' => 'id']],
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
            'applicant_id' => Yii::t('app', 'Applicant ID'),
            'applicant_post_id' => Yii::t('app', 'Applicant Post ID'),
            'type' => Yii::t('app', 'Type'),
            'exam_centre_id' => Yii::t('app', 'Exam Centre ID'),
            'exam_centre_detail_id' => Yii::t('app', 'Exam Centre Detail ID'),
            'shift_type_id' => Yii::t('app', 'Shift Type ID'),
            'rollno' => Yii::t('app', 'Rollno'),
            'comments' => Yii::t('app', 'Comments'),
            'is_downloaded' => Yii::t('app', 'Is Downloaded'),
            'downloaded_on' => Yii::t('app', 'Downloaded On'),
            'is_notification' => Yii::t('app', 'Is Notification'),
            'notification_on' => Yii::t('app', 'Notification On'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_on' => Yii::t('app', 'Modified On'),
        ];
    }

    /**
     * Gets query for [[Applicant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicant()
    {
        return $this->hasOne(Applicant::className(), ['id' => 'applicant_id']);
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
     * Gets query for [[ExamCentreDetail]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamCentreDetail()
    {
        return $this->hasOne(ExamCentreDetail::className(), ['id' => 'exam_centre_detail_id']);
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
     * Gets query for [[ShiftType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShiftType()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'shift_type_id']);
    }
}

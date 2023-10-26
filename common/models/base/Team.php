<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "team".
 *
 * @property int $id
 * @property string $guid
 * @property string $name
 * @property string|null $description
 * @property int $is_active
 * @property int $is_deleted
 * @property int|null $modified_on
 * @property int|null $created_on
 *
 * @property TeamUser[] $teamUsers
 */
class Team extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'team';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid', 'name'], 'required'],
            [['is_active', 'is_deleted', 'modified_on', 'created_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 500],
            [['guid'], 'unique'],
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
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'modified_on' => Yii::t('app', 'Modified On'),
            'created_on' => Yii::t('app', 'Created On'),
        ];
    }

    /**
     * Gets query for [[TeamUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeamUsers()
    {
        return $this->hasMany(TeamUser::className(), ['team_id' => 'id']);
    }
}

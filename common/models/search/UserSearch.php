<?php

namespace common\models\search;

use common\models\Role;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * Description of UserSearch
 *
 * @author Amit Handa
 */
class UserSearch extends User
{
    public $search;
    public $email;
    public $role_id;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function rules()
    {
        return [
            [['search', 'email'], 'string'],
            [['role_id'], 'integer'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find()
                ->where('user.is_deleted = :isDeleted', [':isDeleted' => \common\models\caching\ModelCache::IS_DELETED_NO]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['paginationLimit']
            ],
             'sort' => [
                'attributes' => [
                    'email', 'firstname','username', 'role','status',
                    'role_id' => [
                        'asc' => ['role.name' => SORT_ASC],
                        'desc' => ['role.name' => SORT_DESC],
                    ], 
            ]]
        ]);

        $this->load($params);


        $query->andFilterWhere([
            'email' => $this->email,
            'user_role.role_id' => $this->role_id
        ]);
        
        $query->andFilterWhere(['or',
            ['like', 'user.firstname', $this->search],
            ['like', 'user.lastname', $this->search],
            ['like', 'user.username', $this->search],
        ]);

        return $dataProvider;
    }

}

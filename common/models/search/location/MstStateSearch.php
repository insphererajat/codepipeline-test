<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models\search\location;

use Yii;
use yii\base\Model;
use common\models\location\MstState;
use yii\data\ActiveDataProvider;
/**
 * Description of MstStateSearch
 *
 * @author Amit Handa
 */
class MstStateSearch extends MstState
{
    public $status;
    public $search;

    public function rules()
    {
        return [
            [['code', 'pincode'], 'integer'],
            [['name'], 'string'],
            [['search'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
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
        $query = MstState::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['paginationLimit']
            ],
            'sort' => [
                'attributes' => [
                    'name', 'is_active', 'code',
                ]]
        ]);

        $this->load($params);

        $query->andWhere(['mst_state.is_deleted' => \common\models\caching\ModelCache::IS_DELETED_NO]);
        $query->andFilterWhere(['or',
            ['like', 'mst_state.name', $this->search],
            ['like', 'mst_state.code', $this->search],
        ]);

        return $dataProvider;
    }

}

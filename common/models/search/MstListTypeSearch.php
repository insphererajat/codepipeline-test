<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MstListType;

class MstListTypeSearch extends MstListType
{
     /**
     * @inheritdoc
     */
    public $status;
    public $is_parent;
    
    public function rules()
    {
        return [
            [['display_order','is_parent'], 'integer'],
            [['name'], 'string'],
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
        $query = MstListType::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['paginationLimit']
            ],
             'sort' => [
                'attributes' => [
                    'name', 'is_active',
            ]]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andWhere(['mst_list_type.is_deleted' => \common\models\caching\ModelCache::IS_DELETED_NO]);
        $query->andFilterWhere(['like', 'mst_list_type.name', $this->name]);
        
        //$query->andFilterWhere(['=', 'list_type.type', $this->code]);

        return $dataProvider;
    }
}

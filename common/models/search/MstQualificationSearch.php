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
use common\models\MstQualification;

class MstQualificationSearch extends MstQualification
{
     /**
     * @inheritdoc
     */
    public $parent_id;
    public $status;
    public $search;
    public $type;
    
    public function rules()
    {
        return [
            [['display_order', 'parent_id'], 'integer'],
            [['name', 'type'], 'string'],
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
        $query = MstQualification::find();

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
        $query->andFilterWhere([
            'mst_qualification.parent_id' => $this->parent_id
        ]);

        $query->andWhere(['mst_qualification.is_deleted' => \common\models\caching\ModelCache::IS_DELETED_NO]);
        $query->andFilterWhere(['like', 'mst_qualification.name', $this->name]);


        return $dataProvider;
    }

}

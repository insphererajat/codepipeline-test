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
use common\models\MstUniversity;

class MstUniversitySearch extends MstUniversity
{
     /**
     * @inheritdoc
     */
    public $status;
    public $name;
    public $stateCode;
    public $parent_id;

    public function rules()
    {
        return [
            [['stateCode', 'parent_id'], 'integer'],
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
        $query = MstUniversity::find();

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
        if (!empty($this->parent_id)) {
            $query->andWhere(['mst_university.parent_id' => $this->parent_id]);
        } else {
            $query->andWhere('mst_university.parent_id IS NOT NULL');
        }
        $query->andWhere(['mst_university.is_deleted' => \common\models\caching\ModelCache::IS_DELETED_NO]);
        $query->andFilterWhere(['like', 'mst_university.name', $this->name]);

        return $dataProvider;
    }

}

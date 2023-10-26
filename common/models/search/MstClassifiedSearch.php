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

/**
 * Description of MstClassifiedSearch
 *
 * @author Amit Handa
 */
class MstClassifiedSearch extends \common\models\MstClassified
{

    public $search;
    public $title;
    public $status;

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
            [['search'], 'string'],
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
        $query = \common\models\MstClassified::find()
                ->where('mst_classified.is_deleted = :isDeleted', [':isDeleted' => \common\models\caching\ModelCache::IS_DELETED_NO]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['paginationLimit']
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'is_active' => $this->status
        ]);

        $query->andFilterWhere(['or',
            ['like', 'mst_classified.title', $this->title]
        ]);

        return $dataProvider;
    }

}

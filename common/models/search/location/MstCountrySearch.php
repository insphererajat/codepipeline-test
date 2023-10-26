<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models\search\location;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MstCountry;

/**
 * Description of MstCountrySearch
 *
 * @author Amit Handa
 */
class MstCountrySearch extends MstCountry
{
     /**
     * @inheritdoc
     */
    public $status;
    
    public function rules()
    {
        return [
            [['code'], 'integer'],
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
        $query = MstCountry::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['paginationLimit']
            ],
             'sort' => [
                'attributes' => [
                    'name', 'is_active','code',
            ]]
        ]);

        $this->load($params);

        $query->andWhere(['mst_country.is_deleted' => \common\models\caching\ModelCache::IS_DELETED_NO]);
        $query->andFilterWhere(['like', 'mst_country.name', $this->name]);
        $query->andFilterWhere(['=', 'mst_country.code', $this->code]);

        return $dataProvider;
    }
}

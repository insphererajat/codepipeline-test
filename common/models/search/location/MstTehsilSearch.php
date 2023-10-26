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
use common\models\location\MstTehsil;
/**
 * Description of MstDistrictSearch
 *
 * @author Amit Handa
 */
class MstTehsilSearch extends MstTehsil
{

    public $status;
    public $state_code;
    public $district_code;
    public $search;

    public function rules()
    {
        return [
            [['code','state_code','district_code'], 'integer'],
            [['name', 'guid', 'search'], 'string'],
            [['status', 'state'], 'safe'],
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
        $query = MstTehsil::find();

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

        $query->andWhere(['mst_tehsil.is_deleted' => \common\models\caching\ModelCache::IS_DELETED_NO]);
        $query->andFilterWhere(['or', 
            ['like', 'mst_tehsil.name', $this->search],
            ['=', 'mst_tehsil.code', $this->search],
        ]);
        $query->andFilterWhere(['state_code' => $this->state_code]);
//        $query->andFilterWhere(['district_code' => $this->district_code]);
   

        return $dataProvider;
    }


}

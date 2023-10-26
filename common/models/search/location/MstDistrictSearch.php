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
use common\models\location\MstDistrict;
/**
 * Description of MstDistrictSearch
 *
 * @author Amit Handa
 */
class MstDistrictSearch extends MstDistrict
{

    public $status;
    public $name;
    public $code;
    public $state_code;
    public $search;

    public function rules()
    {
        return [
            [['code','state_code'], 'integer'],
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
        $query = MstDistrict::find();

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

        $query->andWhere(['mst_district.is_deleted' => \common\models\caching\ModelCache::IS_DELETED_NO]);
        $query->andFilterWhere(['or',
            ['like', 'mst_district.name', $this->search],
            ['like', 'mst_district.code', $this->search],
        ]);
        $query->andFilterWhere(['state_code' => $this->state_code,]);
   

        return $dataProvider;
    }


}

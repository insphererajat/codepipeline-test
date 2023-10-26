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
use common\models\MstSubject;

class MstSubjectSearch extends MstSubject
{
     /**
     * @inheritdoc
     */
    public $status;
    public $name;

    public function rules()
    {
        return [

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
        $query = MstSubject::find();

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
        $query->andWhere(['mst_subject.is_deleted' => \common\models\caching\ModelCache::IS_DELETED_NO]);
        $query->andFilterWhere(['like', 'mst_subject.name', $this->name]);

        return $dataProvider;
    }

}

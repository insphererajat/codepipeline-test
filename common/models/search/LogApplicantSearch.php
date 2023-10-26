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
use common\models\LogApplicant;

/**
 * Description of LogApplicantSearch
 *
 * @author Amit Handa <insphere.amit@gmail.com>
 */
class LogApplicantSearch extends LogApplicant
{
    
    public $from_date;
    public $to_date;

    public function rules() {
        return [
            [['device_type', 'ip_address', 'useragent', 'created_on', 'from_date', 'to_date', 'type'], 'string'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {
        $query = LogApplicant::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['paginationLimit']
            ],
            'sort' => [
                'attributes' => [
                    'type', 'device_type', 'created_on'
                ],
                'defaultOrder' => [
                    'created_on' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['or',
            ['like', 'old_value', $this->old_value],
            ['like', 'new_value', $this->new_value],
            ['like', 'device_type', $this->device_type]
        ]);
        
        if(!empty($this->from_date)){
            $fromDate = date('Y-m-d 00:00:00', strtotime($this->from_date));
            $query->andWhere('created_on >=:fromDate', [':fromDate' => strtotime($fromDate)]);
        }
        
        if(!empty($this->to_date)){
            $toDate = date('Y-m-d 23:59:59', strtotime($this->to_date));
            $query->andWhere('created_on <=:toDate', [':toDate' => strtotime($toDate)]);
        }
        return $dataProvider;
    }

}

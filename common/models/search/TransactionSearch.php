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
use common\models\Transaction;
/**
 * Description of transactionSearch
 *
 * @author HP
 */
class TransactionSearch extends Transaction
{
    public $from_date;
    public $to_date;
    public $search;
    public $type;
    public $applicant_guid;
    public $status;
    public $is_consumed;
    public $classified_id;


    public function rules()
    {
        return [
            [['from_date', 'to_date', 'search', 'type', 'applicant_guid', 'status'], 'string'],
            [['is_consumed', 'classified_id'], 'integer'],
        ];
    }
    
    public function scenarios()
    {
        return Model::scenarios();
    }
    
    public function search($params, $report = false)
    {
        $query = Transaction::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['paginationLimit']
            ],
             'sort' => [
                'attributes' => [
                    'id', 'created_on'
            ]]
        ]);
        $query->innerJoin('applicant', 'applicant.id = transaction.applicant_id');
       $this->load($params);
       if (!$this->validate()) {
        return $dataProvider;
    }
       if ($this->classified_id) {
            $query->innerJoin('applicant_fee', 'transaction.applicant_fee_id = applicant_fee.id')
                    ->innerJoin('applicant_post', 'applicant_post.id = applicant_fee.applicant_post_id');

            $query->andFilterWhere([
                'applicant_post.classified_id' => $this->classified_id
            ]);
        }
        if(!empty($this->from_date)){
           $fromDate = date('Y-m-d 00:00:00', strtotime($this->from_date));
            $query->andWhere('transaction.created_on >=:fromDate', [':fromDate' => strtotime($fromDate)]);
        }
        
        if(!empty($this->to_date)){
            $toDate = date('Y-m-d 23:59:59', strtotime($this->to_date));
            $query->andWhere('transaction.created_on <=:toDate', [':toDate' => strtotime($toDate)]);
        }
        $query->andFilterWhere([
            'transaction.is_consumed' => $this->is_consumed,
            'transaction.type' => $this->type,
            'transaction.status' => $this->status,
            'applicant.guid' => $this->applicant_guid
        ]);
        $query->andFilterWhere(['or',
            ['like', 'transaction.gateway_id', $this->search],
            ['like', 'transaction.transaction_id', $this->search],
            ['like', 'applicant.name', $this->search],
            ['like', 'applicant.email', $this->search],
            ['like', 'applicant.mobile', $this->search],
        ]);
        
        if($report) {
            return $query->all();
        }
        
        return $dataProvider;
    }
}

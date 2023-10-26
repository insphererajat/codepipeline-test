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
use common\models\LogOtp;

/**
 * Description of LogOtpSearch
 *
 * @author HP
 */
class LogOtpSearch extends LogOtp
{
    public function rules()
    {
        return [
            [['display_order','is_parent', 'otp_type', 'is_verified'], 'integer'],
            [['otp'], 'string'],
        ];
    }
    
    public function scenarios()
    {
        return Model::scenarios();
    }
    
    public function search($params)
    {
        $query = LogOtp::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['paginationLimit']
            ],
             'sort' => [
                'attributes' => [
                    'id','otp_type','is_verified'
            ]]
        ]);
        
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
       
       $query->andFilterWhere(['=', 'log_otp.otp', $this->otp]);
       $query->andFilterWhere(['=', 'log_otp.otp_type', $this->otp_type]);
       $query->andFilterWhere(['=', 'log_otp.is_verified', $this->is_verified]);
       return $dataProvider;
    }
}
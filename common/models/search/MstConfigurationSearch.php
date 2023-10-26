<?php

namespace common\models\search;

use Yii;
use common\models\MstConfiguration;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Description of MstConfigurationSearch
 *
 * @author Amit Handa
 */
class MstConfigurationSearch extends MstConfiguration {

    public $type;

    public function scenarios() {
        return Model::scenarios();
    }

    public function rules() {
        $baseRules = parent::rules();
        $myRules = [
            [['type'], 'string'],
        ];

        return \yii\helpers\ArrayHelper::merge($baseRules, $myRules);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = MstConfiguration::find();

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
            'mst_configuration.type' => $this->type
        ]);

        $query->orderBy(['mst_configuration.created_on' => SORT_DESC]);

        return $dataProvider;
    }

}

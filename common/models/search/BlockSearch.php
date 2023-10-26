<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MstBlock;

class BlockSearch extends MstBlock
{
    public function rules()
    {
        return [
            [['name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = MstBlock::find();
        $query->joinWith(['districtCode.stateCode.countryCode']);
        $query->joinWith(['districtCode.stateCode']);
        $query->joinWith(['districtCode']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andWhere(['mst_block.is_deleted' => \common\models\caching\ModelCache::IS_DELETED_NO]);
        $query->andFilterWhere([
            'like','mst_block.name', $this->name
        ]);

        return $dataProvider;
    }
}

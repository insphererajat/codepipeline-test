<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\TeamUser as BaseTeamUser;
use yii\helpers\ArrayHelper;

/**
 * Description of TeamUser
 *
 * @author Amit Handa
 */
class TeamUser extends BaseTeamUser
{

    private static function findByParams($params = [])
    {
        $modelAQ = self::find();

        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
        }
        else {
            $modelAQ->select('team_user.*');
        }

        if (isset($params['id'])) {
            $modelAQ->andWhere('team_user.id =:id', [':id' => $params['id']]);
        }

        if (isset($params['teamId'])) {
            $modelAQ->andWhere('team_user.team_id =:teamId', [':teamId' => $params['teamId']]);
        }

        if (isset($params['userId'])) {
            $modelAQ->andWhere('team_user.user_id =:userId', [':userId' => $params['userId']]);
        }
        if (isset($params['joinUser'])) {
            $modelAQ->{$params['joinUser']}('user', 'user.id = team_user.user_id');
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }

    public static function findByTeamId($teamId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['teamId' => $teamId], $params));
    }
    public static function findByUserId($userId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['userId' => $userId], $params));
    }

}

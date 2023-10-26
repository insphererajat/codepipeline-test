<?php

use yii\helpers\Url;
use common\models\MstPost;
use common\models\MstQualification;
use common\models\ApplicantCriteria;
use common\models\MstSubject;
use common\models\MstUniversity;

$params = \Yii::$app->request->queryParams;
$posts = MstPost::getPostDropdown(['classifiedId' => $model->classifiedId]);
?>
<?php if (isset($model->posts) && !empty($model->posts)): ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Post</th>
                <th><?= Yii::t('app', 'Condition 1'); ?></th>
                <th><?= Yii::t('app', 'Condition 2'); ?></th>
                <th><?= Yii::t('app', 'Condition 3'); ?></th>
                <th><?= Yii::t('app', 'Condition 4'); ?></th>
                <th><?= Yii::t('app', 'Condition 5'); ?></th>
                <th><?= Yii::t('app', 'Condition 6'); ?></th>                
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 1;
            foreach ($model->posts as $record):

                echo "<tr>";
                echo "<td scope='col'>" . ($count++) . "</td>";
                echo "<td scope='col'>";
                echo isset($posts[$record['post_id']]) ? $posts[$record['post_id']] : '';
                echo "</td>";

                $c = 1;
                foreach ($record as $key => $value) {
                    if ($key != 'post_id') {
                        $exq = explode("~", $value);
                        $str = "";
                        $str .= (isset($exq[0]) && !empty($exq[0])) ? MstQualification::getName($exq[0]) : '';
                        $str .= (isset($exq[1]) && !empty($exq[1])) ? ', (' . MstUniversity::getName($exq[1]) . ')' : '';
                        if (isset($exq[2]) && !empty($exq[2]) && !empty($exq[0])) {
                            $str .= ', (' . ucfirst($exq[2]) . ')';
                        } else {
                            $str .= ucfirst($exq[2]);
                        }
                        echo "<td scope='col'>";
                        echo $str;
                        echo "</td>";
                        $c++;
                    }
                }

                for ($i = $c; $i <= 6; $i++) {
                    echo "<td scope='col'></td>";
                }

                echo "</tr>";
            endforeach;
            ?>
        </tbody>
    </table>

<?php endif; ?>
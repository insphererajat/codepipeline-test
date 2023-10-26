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
                echo "<td scope='col'>";
                echo !empty($record['field1']) ? MstQualification::getName($record['field1']) : '';
                echo "</td>";
                echo "<td scope='col'>";
                echo $record['field2'];
                echo "</td>";

                echo "</tr>";
            endforeach;
            ?>
        </tbody>
    </table>

<?php endif; ?>
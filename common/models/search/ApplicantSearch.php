<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use common\models\Applicant;
use common\models\ApplicantPost;
use yii\data\ActiveDataProvider;
use common\models\MstPost;

/**
 * Description of ApplicantSearch
 *
 * @author Nitish
 */
class ApplicantSearch extends Model
{

    public $from_date;
    public $to_date;
    public $search;
    public $gender;
    public $application_status;
    public $payment_status;
    public $applicant_guid;
    public $applicant_id;
    public $classified_id;
    public $post_id;
    public $limit;
    public $exam_centre_id;
    public $exam_type;
    public $rollno;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Model Rules
     * @return type
     */
    public function rules()
    {
        return [
            [['search', 'from_date', 'to_date', 'gender'], 'string'],
            [['application_status', 'payment_status', 'applicant_guid', 'applicant_id', 'classified_id', 'post_id', 'limit', 'exam_type', 'rollno', 'exam_centre_id'], 'integer'],
        ];
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
        $query = Applicant::find()
                ->select([
                    'applicant.*',
                    'applicant_detail.gender',
                    'applicant_detail.date_of_birth',
                    'applicant_detail.father_name',
                    'applicant_detail.mother_name',
                ])
                ->innerJoin('applicant_post', 'applicant_post.applicant_id = applicant.id')
                ->innerJoin('applicant_detail', 'applicant_detail.applicant_post_id = applicant_post.id')
                ->where('applicant_post.post_id =:postId', [':postId' => MstPost::MASTER_POST]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['paginationLimit']
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'applicant_detail.gender' => $this->gender
        ]);

        $query->andFilterWhere(['or',
            ['like', 'applicant.name', $this->search],
            ['like', 'applicant.email', $this->search],
            ['like', 'applicant.mobile', $this->search],
            ['like', 'applicant_detail.mother_name', $this->search],
            ['like', 'applicant_detail.father_name', $this->search],
        ]);
        
        if(!empty($this->from_date)){
            $fromDate = date('Y-m-d 00:00:00', strtotime($this->from_date));
            $query->andWhere('applicant.created_on >=:fromDate', [':fromDate' => strtotime($fromDate)]);
        }
        
        if(!empty($this->to_date)){
            $toDate = date('Y-m-d 23:59:59', strtotime($this->to_date));
            $query->andWhere('applicant.created_on <=:toDate', [':toDate' => strtotime($toDate)]);
        }

        return $dataProvider;
    }
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchPost($params, $report = false)
    {
        $query = ApplicantPost::find();

        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $query->select($params['selectCols']);
        } else {
            $query->select([
                'applicant_post.*',
                'applicant.name',
                'applicant.email',
                'applicant.mobile',
                'applicant_detail.reference_no',
            ]);
        }
        $query->leftJoin('applicant', 'applicant.id = applicant_post.applicant_id')
                ->leftJoin('applicant_detail', 'applicant_detail.applicant_post_id = applicant_post.id')
                ->where('applicant_post.post_id !=:postId', [':postId' => MstPost::MASTER_POST])
                ->andWhere('applicant_post.application_status !=:applicationStatus', [':applicationStatus' => ApplicantPost::APPLICATION_STATUS_PENDING_ESERVICE]);

        $this->load($params);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => !empty($this->limit) ? $this->limit : Yii::$app->params['paginationLimit'],
                'page' => (isset($params['page']) && !empty($params['page'])) ? ($params['page']-1) : 0
            ],
            'sort' => [
                'attributes' => [
                    'id', 'applicant_post.created_on'
                ],
                'defaultOrder' => ['applicant_post.created_on' => SORT_DESC]
            ]
        ]);

        $query->andFilterWhere([
            'applicant.guid' => $this->applicant_guid,
            'applicant_post.payment_status' => $this->payment_status,
            'applicant_post.applicant_id' => $this->applicant_id,
            'applicant_post.classified_id' => $this->classified_id
        ]);

        $query->andFilterWhere(['or',
            ['like', 'applicant.name', $this->search],
            ['like', 'applicant.email', $this->search],
            ['like', 'applicant.mobile', $this->search],
            ['like', 'applicant_post.application_no', $this->search],
        ]);
        
        if(!empty($this->from_date)){
            $fromDate = date('Y-m-d 00:00:00', strtotime($this->from_date));
            $query->andWhere('applicant_post.created_on >=:fromDate', [':fromDate' => strtotime($fromDate)]);
        }
        
        if(!empty($this->to_date)){
            $toDate = date('Y-m-d 23:59:59', strtotime($this->to_date));
            $query->andWhere('applicant_post.created_on <=:toDate', [':toDate' => strtotime($toDate)]);
        }
        
        if ($this->application_status == ApplicantPost::APPLICATION_STATUS_CANCELED) {
            $query->andWhere(['IN', 'applicant_post.application_status', [ApplicantPost::APPLICATION_STATUS_CANCELED, ApplicantPost::APPLICATION_STATUS_REAPPLIED]]);
        } else {
            $query->andFilterWhere([
                'applicant_post.application_status' => $this->application_status
            ]);
        }
        
        if($report) {
            return $query->asArray()->all();
        }
        //echo $query->createCommand()->rawSql; die;
        return $dataProvider;
    }
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchProfile($params)
    {
        $query = Applicant::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['paginationLimit']
            ],
            'sort' => [
                'defaultOrder' => [
                    'modified_on' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        $query->andFilterWhere(['or',
            ['like', 'applicant.name', $this->search],
            ['like', 'applicant.email', $this->search],
            ['like', 'applicant.mobile', $this->search]
        ]);

        if (!empty($this->from_date)) {
            $fromDate = date('Y-m-d 00:00:00', strtotime($this->from_date));
            $query->andWhere('applicant.created_on >=:fromDate', [':fromDate' => strtotime($fromDate)]);
        }

        if (!empty($this->to_date)) {
            $toDate = date('Y-m-d 23:59:59', strtotime($this->to_date));
            $query->andWhere('applicant.created_on <=:toDate', [':toDate' => strtotime($toDate)]);
        }

        return $dataProvider;
    }
}

<?php

namespace common\models\search\report;

use Yii;
use yii\base\Model;
use common\models\Applicant;
use common\models\ApplicantPost;
use common\models\ApplicantFee;
use common\models\MstPost;
use common\models\MstClassified;

/**
 * Description of PostWiseForm
 *
 * @author Nitish
 */
class PostWiseForm extends Model
{
    public $type;
    public $from_date;
    public $to_date;
    public $classified_id;
    public $post_id;
    
    const TYPE_APPLICATION_STATUS = 1;
    const TYPE_PAYMENT_STATUS = 2;
    
    public function rules()
    {
        return [
            [['type', 'classified_id', 'post_id'], 'integer'],
            [['from_date', 'to_date'], 'string']
        ];
    }
    
    public static function getType()
    {
        return [
            self::TYPE_APPLICATION_STATUS => 'Application Status',
            self::TYPE_PAYMENT_STATUS => 'Payment Status'
        ];
    }
    
    public function search($params)
    {
        $this->load($params);
        
        if(empty($this->type)){
            return [];
        }
        
        $paid = ApplicantPost::STATUS_PAID;
        $unpaid = ApplicantPost::STATUS_UNPAID;
        
        $pending = ApplicantPost::APPLICATION_STATUS_PENDING;
        $submitted = ApplicantPost::APPLICATION_STATUS_SUBMITTED;
        
        $selectCols = [
            'mst_classified.recruitment_year year',
            'mst_classified.id classifiedId',
            'mst_post.id postId',
            new \yii\db\Expression("COUNT(applicant_post.`id`) totalCount"),
        ];
        if($this->type == self::TYPE_PAYMENT_STATUS){
            $selectCols[] = new \yii\db\Expression("COUNT(CASE WHEN applicant_post.payment_status = {$paid} THEN  applicant_post.`id` END) paid");
            $selectCols[] = new \yii\db\Expression("COUNT(CASE WHEN applicant_post.payment_status = {$unpaid} THEN  applicant_post.`id` END) unpaid");
        }elseif($this->type == self::TYPE_APPLICATION_STATUS){
            $selectCols[] = new \yii\db\Expression("COUNT(CASE WHEN applicant_post.application_status = {$pending} THEN  applicant_post.`id` END) pending");
            $selectCols[] = new \yii\db\Expression("COUNT(CASE WHEN applicant_post.application_status = {$submitted} THEN  applicant_post.`id` END) submitted");
        }
        
        $query = ApplicantPost::find()
                ->select($selectCols)
                ->innerJoin('mst_post', 'mst_post.id = applicant_post.post_id')
                ->innerJoin('mst_classified', 'mst_classified.id = mst_post.classified_id')
                ->andWhere('applicant_post.post_id !=:masterPostId', [':masterPostId' => MstPost::MASTER_POST])
                ->andWhere('mst_post.id !=:masterPostId', [':masterPostId' => MstPost::MASTER_POST])
                ->andWhere('mst_classified.id !=:masterClassifiedId', [':masterClassifiedId' => MstClassified::MASTER_CLASSIFIED]);
        
        $query->groupBy([
            'mst_classified.recruitment_year',
            'mst_classified.id',
            'mst_post.id'
        ]);
        
        $query->orderBy([
            'mst_classified.recruitment_year' => SORT_ASC,
            'mst_classified.id' => SORT_ASC,
            'mst_post.id' => SORT_ASC,
        ]);
        
        $query->andFilterWhere([
            'mst_classified.id' => $this->classified_id,
            'mst_post.id' => $this->post_id,
        ]);
        
        if(!empty($this->from_date)){
            $fromDate = date('Y-m-d 00:00:00', strtotime($this->from_date));
            $query->andWhere('applicant_post.modified_on >=:fromDate', [':fromDate' => strtotime($fromDate)]);
        }
        
        if(!empty($this->to_date)){
            $toDate = date('Y-m-d 23:59:59', strtotime($this->to_date));
            $query->andWhere('applicant_post.modified_on <=:toDate', [':toDate' => strtotime($toDate)]);
        }
        
        $queryData = $query->asArray()->all();

        if($this->type == self::TYPE_PAYMENT_STATUS){
           return $this->buildPaymentData($queryData);
           
        }elseif($this->type == self::TYPE_APPLICATION_STATUS){
            return $this->buildApplicationStatusData($queryData);
            
        }
    }
    
    protected function buildPaymentData($queryData)
    {
        $list = [];
        foreach ($queryData as $value) {
            // Total Year wise paid/unpaid count
            if(!empty($list[$value['year']]['totalPaid'])){
                $list[$value['year']]['totalPaid'] += $value['paid'];
            }else{
                $list[$value['year']]['totalPaid'] = $value['paid'];
            }
            
            if(!empty($list[$value['year']]['totalUnPaid'])){
                $list[$value['year']]['totalUnPaid'] += $value['unpaid'];
            }else{
                $list[$value['year']]['totalUnPaid'] = $value['unpaid'];
            }
            
            // Total advertisement wise paid/unpaid count
            if(!empty($list[$value['year']]['classified'][$value['classifiedId']]['totalPaid'])){
                $list[$value['year']]['classified'][$value['classifiedId']]['totalPaid'] += $value['paid'];
            }else{
                $list[$value['year']]['classified'][$value['classifiedId']]['totalPaid'] = $value['paid'];
            }
            
            if(!empty($list[$value['year']]['classified'][$value['classifiedId']]['totalUnPaid'])){
                $list[$value['year']]['classified'][$value['classifiedId']]['totalUnPaid'] += $value['unpaid'];
            }else{
                $list[$value['year']]['classified'][$value['classifiedId']]['totalUnPaid'] = $value['unpaid'];
            }
            
            // Total Post wise paid/unpaid count
            if(!empty($list[$value['year']]['classified'][$value['classifiedId']]['post'][$value['postId']]['totalPaid'])){
                $list[$value['year']]['classified'][$value['classifiedId']]['post'][$value['postId']]['totalPaid'] += $value['paid'];
            }else{
                $list[$value['year']]['classified'][$value['classifiedId']]['post'][$value['postId']]['totalPaid'] = $value['paid'];
            }
            
            if(!empty($list[$value['year']]['classified'][$value['classifiedId']]['post'][$value['postId']]['totalUnPaid'])){
                $list[$value['year']]['classified'][$value['classifiedId']]['post'][$value['postId']]['totalUnPaid'] += $value['unpaid'];
            }else{
                $list[$value['year']]['classified'][$value['classifiedId']]['post'][$value['postId']]['totalUnPaid'] = $value['unpaid'];
            }
        }

        return $list;
    }
    
    protected function buildApplicationStatusData($queryData)
    {
        $list = [];
        foreach ($queryData as $value) {
            // Total Year wise paid/unpaid count
            if(!empty($list[$value['year']]['totalPending'])){
                $list[$value['year']]['totalPending'] += $value['pending'];
            }else{
                $list[$value['year']]['totalPending'] = $value['pending'];
            }
            
            if(!empty($list[$value['year']]['totalSubmitted'])){
                $list[$value['year']]['totalSubmitted'] += $value['submitted'];
            }else{
                $list[$value['year']]['totalSubmitted'] = $value['submitted'];
            }
            
            // Total advertisement wise paid/unpaid count
            if(!empty($list[$value['year']]['classified'][$value['classifiedId']]['totalPending'])){
                $list[$value['year']]['classified'][$value['classifiedId']]['totalPending'] += $value['pending'];
            }else{
                $list[$value['year']]['classified'][$value['classifiedId']]['totalPending'] = $value['pending'];
            }
            
            if(!empty($list[$value['year']]['classified'][$value['classifiedId']]['totalSubmitted'])){
                $list[$value['year']]['classified'][$value['classifiedId']]['totalSubmitted'] += $value['submitted'];
            }else{
                $list[$value['year']]['classified'][$value['classifiedId']]['totalSubmitted'] = $value['submitted'];
            }
            
            // Total Post wise paid/unpaid count
            if(!empty($list[$value['year']]['classified'][$value['classifiedId']]['post'][$value['postId']]['totalPending'])){
                $list[$value['year']]['classified'][$value['classifiedId']]['post'][$value['postId']]['totalPending'] += $value['pending'];
            }else{
                $list[$value['year']]['classified'][$value['classifiedId']]['post'][$value['postId']]['totalPending'] = $value['pending'];
            }
            
            if(!empty($list[$value['year']]['classified'][$value['classifiedId']]['post'][$value['postId']]['totalSubmitted'])){
                $list[$value['year']]['classified'][$value['classifiedId']]['post'][$value['postId']]['totalSubmitted'] += $value['submitted'];
            }else{
                $list[$value['year']]['classified'][$value['classifiedId']]['post'][$value['postId']]['totalSubmitted'] = $value['submitted'];
            }
        }

        return $list;
    }
}

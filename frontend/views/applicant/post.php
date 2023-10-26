<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\ApplicantPost;
use common\models\LogProfile;
use common\models\MstClassified;

$this->title = 'Applicant Post';
$this->params['bodyClass'] = 'frame__body';
$noDataClass = ($dataProvider->getTotalCount() <= 0) ? ' no-data' : '';
$this->registerJs("ApplicantPostController.summary();");
$logProfile = common\models\LogProfile::findByApplicantId(Yii::$app->applicant->id);

$flash = '';
foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
    $flash .= $message;
}
if(!empty($flash))
$this->registerJs("bootbox.alert('".$flash."');");
?>

<div class="main-body">
    <div class="container">
        <div class="row">
            <div class="col-12">

                <div class="c-dashboardwarp">
                    <div class="c-dashboardwarp__contentwrap">
                        <div class="c-dashboarsection">
                            <marquee style="color:red"><?= Yii::t('app', 'flash1'); ?></marquee>
                            <div class="row">
                                <div class="col-12">
                                    <div class="c-dashboarsection__headwrap">
                                        <h2 class="c-dashboarsection__headwrap-head">Dashboard</h2>
                                        <div class="c-dashboarsection__headwrap-navlink"><a href="javascript:;">Menu Link</a></div>
                                    </div>
                                </div>
                            </div>


                            <div class="c-dashboardtiles">
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <div class="c-dashboardtiles__tiles profile">
                                            <div class="c-dashboardtiles__tiles-imgwrap"><i class="fas fa-user-circle"></i></div>
                                            <?php 
                                            $otrurl = 'javascript:;';
                                            if(empty($logProfile) || (isset($logProfile['status']) && ($logProfile['status'] != LogProfile::STATUS_PENDING))) {
                                                $otrurl = Url::toRoute(['applicant/update']);
                                            }
                                            ?>
                                            <a href="<?= $otrurl ?>" class="c-dashboardtiles__tiles-link">
                                                <h3 class="c-dashboardtiles__tiles-link--head">OTR Update</h3>
                                                <div class="c-dashboardtiles__tiles-link--content">
                                                    <div class="subcontent">Update (DOB, Name, Father Name)...</div>
                                                </div>
                                                <div class="c-dashboardtiles__tiles-link--line"><!--Blank--></div>
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-4">
                                        <div class="c-dashboardtiles__tiles hallticket">
                                            <div class="c-dashboardtiles__tiles-imgwrap"><i class="fas fa-user"></i></div>
                                            <a href="<?= Url::toRoute(['registration/personal-details']) ?>" class="c-dashboardtiles__tiles-link">
                                                <h3 class="c-dashboardtiles__tiles-link--head">Profile Update</h3>
                                                <div class="c-dashboardtiles__tiles-link--content">
                                                    <div class="subcontent">Update your master profile...</div>
                                                </div>
                                                <div class="c-dashboardtiles__tiles-link--line"><!--Blank--></div>
                                            </a>
                                        </div>
                                    </div>

                                    <!--<div class="col-md-6 col-lg-4">
                                        <div class="c-dashboardtiles__tiles hallticket">
                                            <div class="c-dashboardtiles__tiles-imgwrap"><i class="fas fa-id-card-alt"></i></div>
                                            <a href="javascript:;" class="c-dashboardtiles__tiles-link">
                                                <h3 class="c-dashboardtiles__tiles-link--head">Hall ticket</h3>
                                                <div class="c-dashboardtiles__tiles-link--content">
                                                    <div class="subcontent">View hall ticket</div>
                                                </div>
                                                <div class="c-dashboardtiles__tiles-link--line"></div>
                                            </a>
                                        </div>
                                    </div>-->


                                    <div class="col-md-6 col-lg-4">
                                        <div class="c-dashboardtiles__tiles result">
                                            <div class="c-dashboardtiles__tiles-imgwrap"><i class="fas fa-id-card-alt"></i></div>
                                            <a href="javascript:;" class="c-dashboardtiles__tiles-link">
                                                <h3 class="c-dashboardtiles__tiles-link--head">Result</h3>
                                                <div class="c-dashboardtiles__tiles-link--content">
                                                    <div class="subcontent">Application for the post...</div>
                                                    <div class="text"></div>
                                                </div>
                                                <div class="c-dashboardtiles__tiles-link--line"><!--Blank--></div>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <!--<div class="row">
                                <div class="col-12">

                                    <div class="c-dashboarsection__subhead">Application History</div>


                                    <section class="adm-c-tableGrid  adm-c-tableGrid-xs design2 withshadow">
                                        <div class="adm-c-tableGrid__wrapper">

                                            <div class="adm-c-tableGrid__container adm-c-tableGrid__container--scrolling">
                                                <div class="adm-c-tableGrid__box table-responsive withScroll">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th class="action__column">Action</th>
                                                                <th>Class</th>
                                                                <th>Section</th>
                                                                <th>Total</th>
                                                                <th>Present</th>
                                                                <th>Absent</th>
                                                                <th>Leave</th>
                                                                <th>Mark State</th>
                                                                <th>Message</th>
                                                                <th>Marked At</th>
                                                                <th>Field 1</th>
                                                                <th>Field 2</th>
                                                                <th>Field 3</th>
                                                                <th>Field 4</th>
                                                                <th>Field 5</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="action__column">
                                                                    <div class="action-bars dropdown">
                                                                        <button type="button" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                                            <div class="action-bars__label">
                                                                                <span class="icon fa fa-cog"></span>
                                                                                Action
                                                                            </div>
                                                                        </button>
                                                                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 18px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                            <a class="dropdown-item" href="javascript:;">
                                                                                <i class="icon far fa-eye"></i>
                                                                                View Details
                                                                            </a>
                                                                            <a class="dropdown-item" href="javascript:;">
                                                                                <i class="icon fa fa-print"></i>
                                                                                Print
                                                                            </a>
                                                                            <a class="dropdown-item" href="javascript:;">
                                                                                <i class="icon fa fa-key"></i>
                                                                                Auto Login
                                                                            </a>
                                                                            <a class="dropdown-item" href="javascript:;">
                                                                                <i class="icon fa fa-rocket"></i>
                                                                                Update Payment
                                                                            </a>
                                                                            <a class="dropdown-item red" href="javascript:;">
                                                                                <i class="icon far fa-trash-alt"></i>
                                                                                Delete
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>VI</td>
                                                                <td>A</td>
                                                                <td>38 </td>
                                                                <td>38 </td>
                                                                <td>0</td>
                                                                <td>0</td>
                                                                <td><span class="badge badge-warning">Pending</span></td>
                                                                <td>
                                                                    <i class="fa fa-times red__color"></i>
                                                                </td>
                                                                <td>-</td>
                                                                <td>Sub Field 1</td>
                                                                <td>Sub Field 2</td>
                                                                <td>Sub Field 3</td>
                                                                <td>Sub Field 4</td>
                                                                <td>Sub Field 5</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="action__column">
                                                                    <div class="action-bars dropdown">
                                                                        <button type="button" class="dropdown-toggle" data-toggle="dropdown">
                                                                            <div class="action-bars__label">
                                                                                <span class="icon fa fa-cog"></span>
                                                                                Action
                                                                            </div>
                                                                        </button>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="javascript:;">
                                                                                <i class="icon far fa-eye"></i>
                                                                                View Details
                                                                            </a>
                                                                            <a class="dropdown-item" href="javascript:;">
                                                                                <i class="icon fa fa-print"></i>
                                                                                Print
                                                                            </a>
                                                                            <a class="dropdown-item" href="javascript:;">
                                                                                <i class="icon fa fa-key"></i>
                                                                                Auto Login
                                                                            </a>
                                                                            <a class="dropdown-item" href="javascript:;">
                                                                                <i class="icon fa fa-rocket"></i>
                                                                                Update Payment
                                                                            </a>
                                                                            <a class="dropdown-item red" href="javascript:;">
                                                                                <i class="icon far fa-trash-alt"></i>
                                                                                Delete
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>VI</td>
                                                                <td>A</td>
                                                                <td>38 </td>
                                                                <td>38 </td>
                                                                <td>0</td>
                                                                <td>0</td>
                                                                <td><span class="badge badge-danger">Pending</span></td>
                                                                <td>
                                                                    <i class="fa fa-times red__color"></i>
                                                                </td>
                                                                <td>-</td>
                                                                <td>Sub Field 1</td>
                                                                <td>Sub Field 2</td>
                                                                <td>Sub Field 3</td>
                                                                <td>Sub Field 4</td>
                                                                <td>Sub Field 5</td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>-->
                            
                            <div class="row">
                                <div class="col-8"> 
                                    <div class="c-dashboarsection__subhead">Application History</div>
                                </div>
                                <div class="col-4"> 
                                    <a href="<?= Url::toRoute(['/home/index']) ?>" class="float-right button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button">Apply New Advertisement</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">

                                    <section class="adm-c-tableGrid  adm-c-tableGrid-xs design2 withshadow" id="collapseOne" data-time="<?= common\models\LogOtp::VALIDATION_TIME?>">
                                        <div class="adm-c-tableGrid__wrapper">
                                            <div class="adm-c-tableGrid__container adm-c-tableGrid__container--scrolling">
                                                
                                                    <?php
                                                    $gridView = GridView::begin([
                                                                'tableOptions' => [
                                                                    'class' => 'table'
                                                                ],
                                                                'dataProvider' => $dataProvider,
                                                                'summary' => "<div class='summary'>Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items.</div>",
                                                                // 'layout' => "<div class='c-table__elements c-table__elements--withborder'><div class='c-table__elements-gridinfo'><div class='grid__results'><div class='grid__results-summary'>{summary}</div><div class='grid__results-pagination'><div class='c-pagination'>{pager}</div></div></div></div></div><div class='$noDataClass'>{items}</div>",
                                                                'layout' => "<div class='c-table__elements-gridinfo'><div class='grid__results'><div class='grid__results-summary'>{summary}</div><div class='grid__results-pagination'><div class='c-pagination'>{pager}</div></div></div></div><div class='adm-c-tableGrid__box table-responsive withScroll'><div class='c-table__elements c-table__elements--withborder'></div><div class='$noDataClass'>{items}</div></div>",
                                                                'emptyTextOptions' => ['class' => 'empty text-center'],
                                                                'pager' => [
                                                                    'prevPageLabel' => 'Previous',
                                                                    'nextPageLabel' => 'Next',
                                                                    'linkOptions' => ['class' => 'page-link'],
                                                                    'linkContainerOptions' => ['class' => 'page-item']
                                                                ],
                                                                'columns' => [
                                                                    [
                                                                        'class' => 'yii\grid\SerialColumn'
                                                                    ],
                                                                    [
                                                                        'attribute' => 'created_on',
                                                                        'label' => 'Application Date',
                                                                        'filter' => false,
                                                                        'sortLinkOptions' => ['class' => 'sort'],
                                                                        'value' => function ($model) {
                                                                            return \components\Helper::convertNetworkTimeZone($model->created_on, 'd-m-Y', Yii::$app->timeZone, 'UTC');
                                                                        }
                                                                    ],
                                                                    [
                                                                        'attribute' => 'year',
                                                                        'filter' => false,
                                                                        'sortLinkOptions' => ['class' => 'sort'],
                                                                        'value' => function($model) {
                                                                            return !empty($model->post) && !empty($model->post->classified) ? $model->post->classified->recruitment_year : '';
                                                                        }
                                                                    ],
                                                                    [
                                                                        'attribute' => 'adv_id',
                                                                        'label' => 'Advertisement',
                                                                        'filter' => false,
                                                                        'sortLinkOptions' => ['class' => 'sort'],
                                                                        'value' => function($model) {
                                                                            return !empty($model->classified) && !empty($model->classified) ? $model->classified->title : '';
                                                                        }
                                                                    ],
                                                                    [
                                                                        'attribute' => 'application_no',
                                                                        'label' => 'Application No',
                                                                        'filter' => false,
                                                                        'sortLinkOptions' => ['class' => 'sort']
                                                                    ],
                                                                    [
                                                                        'attribute' => 'application_status',
                                                                        'format' => 'raw',
                                                                        'filter' => false,
                                                                        'sortLinkOptions' => ['class' => 'sort'],
                                                                        'value' => function($model) {
                                                                            $class = 'danger';
                                                                            switch ($model->application_status) {
                                                                                case ApplicantPost::APPLICATION_STATUS_SUBMITTED:
                                                                                    $class = 'success';
                                                                                    break;
                                                                                case ApplicantPost::APPLICATION_STATUS_ARCHIVE:
                                                                                    $class = 'success';
                                                                                    break;
                                                                            }
                                                                            return '<span class="badge badge-' . $class . '">' . ApplicantPost::getApplicationStatus($model->application_status) . '</span>';
                                                                        }
                                                                    ],
                                                                    [
                                                                        'attribute' => 'payment_status',
                                                                        'format' => 'raw',
                                                                        'filter' => false,
                                                                        'sortLinkOptions' => ['class' => 'sort'],
                                                                        'value' => function($model) {
                                                                            $class = 'danger';
                                                                            switch ($model->payment_status) {
                                                                                case ApplicantPost::STATUS_PAID:
                                                                                    $class = 'success';
                                                                                    break;
                                                                                case ApplicantPost::STATUS_ARCHIVE:
                                                                                    $class = 'success';
                                                                                    break;
                                                                            }
                                                                            return '<span class="badge badge-' . $class . '">' . ApplicantPost::getPaymentStatus($model->payment_status) . '</span>';
                                                                        }
                                                                    ],
                                                                    /*[
                                                                        'attribute' => 'post_id',
                                                                        'label' => 'Post',
                                                                        'filter' => false,
                                                                        'sortLinkOptions' => ['class' => 'sort'],
                                                                        'value' => function($model) {
                                                                            return !empty($model->post) ? $model->post->title.'/'.$model->post->code.'/'.$model->quota : '';
                                                                        }
                                                                    ],*/
                                                                    [
                                                                        'attribute' => 'name',
                                                                        'filter' => false,
                                                                        'sortLinkOptions' => ['class' => 'sort']
                                                                    ],
                                                                    [
                                                                        'attribute' => 'email',
                                                                        'filter' => false,
                                                                        'sortLinkOptions' => ['class' => 'sort'],
                                                                        'value' => function ($model) {
                                                                            return components\Helper::emailConversion($model->email);
                                                                        }
                                                                    ],
                                                                    [
                                                                        'attribute' => 'mobile',
                                                                        'filter' => false,
                                                                        'sortLinkOptions' => ['class' => 'sort']
                                                                    ],
                                                                    [
                                                                        'header' => 'Action',
                                                                        'class' => 'yii\grid\ActionColumn',
                                                                        'visibleButtons' => [
                                                                            'view' => true,
                                                                            'print' => true,
                                                                            'cancel' => true,
                                                                            'reapply' => true,
                                                                            'payment' => true
                                                                        ],
                                                                        'template' => '<div class="action-bars dropdown">'
                                                                        . '<button type="button" class="dropdown-toggle" data-toggle="dropdown"><div class="action-bars__label"><span class="icon fa fa-cog"></span>Action</div></button>'
                                                                        . '<div class="dropdown-menu">'
                                                                        . '{view}{cancel}{reapply}{payment}{pdf}{eservices}{admit-card}'
                                                                        . '</div>'
                                                                        . '</div>',
                                                                        'buttons' => [
                                                                                'view' => function ($url, $model, $key) {
                                                                                    return ($model->application_status === ApplicantPost::APPLICATION_STATUS_SUBMITTED) ? '<a class="dropdown-item" target="_blank" href="' . Url::toRoute(['applicant/preview', 'guid' => $model->guid]) . '"><i class="icon fa fa-eye"></i> View Post Details</a>' : '';
                                                                                },
                                                                                'print' => function ($url, $model, $key) {
                                                                                    return ($model->application_status === ApplicantPost::APPLICATION_STATUS_SUBMITTED) ? '<a class="dropdown-item" target="_blank" href="' . Url::toRoute(['applicant/print', 'guid' => $model->guid]) . '"><i class="icon fa fa-print"></i> Print Post Details</a>' : '';
                                                                                },
                                                                                'cancel' => function ($url, $model, $key) {
                                                                                    return (ApplicantPost::checkStatusForCancel($model->id) === ApplicantPost::APPLICATION_STATUS_SUBMITTED) ? '<a class="dropdown-item js-cancelPost" href="javascript:;" data-otp-scenario="' . \frontend\models\VerifyOTPForm::SCENARIO_VALIDATE_CANCEL_POST_OTP . '" data-otp-type="'.\common\models\LogOtp::CANCEL_POST_OTP.'" data-otp-scenario="' . \frontend\models\VerifyOTPForm::SCENARIO_VALIDATE_CANCEL_POST_OTP . '" data-guid="' . $model->guid . '" data-id="' . $model->id . '"><i class="icon fa fa-times"></i> Cancel Post</a>' : '';
                                                                                },
                                                                                'reapply' => function ($url, $model, $key) {
                                                                                    $applicantPostCompoment = new \frontend\components\ApplicantPostComponent();
                                                                                    $applicantPostCompoment->applicantId = Yii::$app->applicant->id;
                                                                                    
                                                                                    $mstClassified = MstClassified::findById($model->classified_id, ['selectCols' => ['id','guid']]);
                                                                                    return ($applicantPostCompoment->checkReApplyPost($model->id) && !empty($mstClassified)) ? '<a class="dropdown-item" href="' . Url::toRoute(['registration/criteria-details', 'guid' => $mstClassified['guid']]) . '"><i class="icon fa fa-plus"></i> Re-Apply Post</a>' : '';
                                                                                },
                                                                                'payment' => function ($url, $model, $key) {
                                                                                    $mstClassified = MstClassified::findById($model->classified_id, ['selectCols' => ['id','guid']]);
                                                                                    return ($model->payment_status === ApplicantPost::STATUS_UNPAID && MstClassified::isPaymentDateEnable($model->classified_id) && !empty($mstClassified)) ? '<a class="dropdown-item" target="_blank" href="' . Url::toRoute(['registration/review', 'guid' => $mstClassified['guid']]) . '"><i class="icon fa fa-credit-card"></i> Pay</a>' : '';
                                                                                },
                                                                                'pdf' => function ($url, $model, $key) {
                                                                                    if ($model->payment_status === ApplicantPost::STATUS_ARCHIVE) {
                                                                                        $applicantDocument = common\models\ApplicantDocument::findByApplicantPostId($model->id, [
                                                                                                    'selectCols' => [new \yii\db\Expression("media.cdn_path")],
                                                                                                    'joinWithMedia' => 'innerJoin'
                                                                                        ]);
                                                                                        return !empty($applicantDocument) ? '<a class="dropdown-item" target="_blank" href="' . Yii::$app->amazons3->getPrivateMediaUrl($applicantDocument['cdn_path']) . '"><i class="icon fa fa-print"></i> Print</a>' : '';
                                                                                    }
                                                                                },
                                                                                'eservices' => function ($url, $model, $key) {
                                                                                    return (ApplicantPost::checkStatusForEservice($model->id) && $model->application_status === ApplicantPost::APPLICATION_STATUS_SUBMITTED) ? '<a class="dropdown-item js-eservicePost" href="javascript:;" data-otp-type="' . \common\models\LogOtp::ESERVICE_POST_OTP . '" data-otp-scenario="' . \frontend\models\VerifyOTPForm::SCENARIO_VALIDATE_ESERVICE_POST_OTP . '" data-guid="' . $model->guid . '" data-id="' . $model->id . '"><i class="icon fa fa-edit"></i> Eservices</a>' : '';
                                                                                },
                                                                                'admit-card' => function ($url, $model, $key) {
                                                                                    return ($model->application_status === ApplicantPost::APPLICATION_STATUS_SUBMITTED && MstClassified::validateAdmitCardLink($model->classified_id, ['applicantPostId' => $model->id])) ? '<a class="dropdown-item" target="_blank" href="' . Url::toRoute(['/applicant/hall-ticket', 'guid' => $model->guid]) . '" ><i class="icon fa fa-file"></i> Admit Card</a>' : '';
                                                                                },
                                                                            ],
                                                                        'headerOptions' => [
                                                                            'width' => '15%',
                                                                            'class' => 'action__column'
                                                                        ],
                                                                        'contentOptions' => [
                                                                            'class' => 'action__column'
                                                                        ]
                                                                    ],
                                                                ],
                                                    ]);
                                                    $gridView->end();
                                                    ?>  
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <!--<div class="row">
                                    <div class="col-12">
                                        <div class="c-dashnotification"> 
                                            <div class="c-dashboarsection__subhead">Notification</div>
                                            <div class="c-f-news__wrapper-item">
    
                                                <div class="c-f-news__wrapper-item-content">
                                                    <ul class="news__list">
                                                        <li class="news__list-item">
                                                            <a href="javascript:;" class="news__list-link">
                                                                <div class="news__list-item-date">
                                                                    <span class="date">24 Nov</span>
                                                                    <span class="year">2019</span>
                                                                </div>
                                                                <div class="news__list-item-content">
                                                                    <span class="title">What is Lorem Ipsum?</span>
                                                                    <span class="discription">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has bee...</span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li class="news__list-item">
                                                            <a href="javascript:;" class="news__list-link">
                                                                <div class="news__list-item-date">
                                                                    <span class="date">24 Nov</span>
                                                                    <span class="year">2019</span>
                                                                </div>
                                                                <div class="news__list-item-content">
                                                                    <span class="title">Where does it come from?</span>
                                                                    <span class="discription">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of...</span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li class="news__list-item">
                                                            <a href="javascript:;" class="news__list-link">
                                                                <div class="news__list-item-date">
                                                                    <span class="date">24 Nov</span>
                                                                    <span class="year">2019</span>
                                                                </div>
                                                                <div class="news__list-item-content">
                                                                    <span class="title">Why do we use it?</span>
                                                                    <span class="discription">It is a long established fact that a reader will be distracted by the readable content of a pag...</span>
                                                                </div>
                                                            </a>
                                                        </li>
    
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>-->

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
<?php if (!\Yii::$app->session->has('firstLogin')): ?>
    <!-- Button to Open the Modal -->
    <button type="button" class="btn btn-primary d-none" data-toggle="modal" data-target="#myModal"></button>

    <!-- The Modal -->
    <div class="modal o-modal o-modal-large" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Guidelines</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body min-height500">
                    <iframe src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/pdf/instructions.pdf" width="100%" class="min-height500 height100p"></iframe>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
    <?php
    $script = <<< JS
    $('#myModal').modal('show');
JS;
    $this->registerJs($script);
    \Yii::$app->session->set('firstLogin', true);
endif;
?>
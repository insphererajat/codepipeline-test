<?php

use \yii\helpers\Url;


$this->title = 'Dashboard';

$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['home/index'])];
//echo '<pre>';print_r($data);die;
?>
<?= \backend\widgets\breadcrumb\BreadcrumbWidget::widget() ?>
<div class="adm-c-mainContainer">
    <div class="adm-c-tabbing adm-c-tabbing-xs design5 withBorder">
        <ul class="adm-c-tabbing__nav nav-tabs nav u-flexed">
            <li>
                <a class="adm-c-tabbing__nav__link active" id="tabOne" data-toggle="tab" href="#tabOne-a" role="tab"
                    aria-controls="tabOne-a" aria-selected="true">Dashboard</a>
            </li>
            <li class="">
                <a class="adm-c-tabbing__nav__link js-graphTab" id="tabTwo" data-toggle="tab" href="#tabTwo-a"
                    role="tab" aria-controls="tabTwo-a" aria-selected="false">Application Wise</a>
            </li>
            <li class="">
                <a class="adm-c-tabbing__nav__link" id="tabThree" data-toggle="tab" href="#tabThree-a" role="tab"
                    aria-controls="tabThree-a" aria-selected="false">Advertisement Wise</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tabOne-a" role="tabpanel" aria-labelledby="tabOne">
                <!-- <div class="c-media">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="c-media__wrap">
                                <img src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/images/background/glorious-50year.png" alt="BSF"
                                    class="img-fluid u-image">
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="adm-c-counter__wrapper ColBase col4 adm-c-counter__wrapper-xs cmt-15 design2">
                    <a href="javascript:;"
                        class="adm-u-cursor-pointer adm-c-counter__wrapper-item u-green adm-u-radius30">
                        <div class="adm-c-counter__wrapper-item__top">
                            <div class="adm-c-counter__wrapper-item__image">
                                <img src="https://d2f2ht52r1pvo3.cloudfront.net/backend/static/dist/images/icons/icon.svg"
                                    alt="image">
                            </div>
                            <div class="adm-c-counter__wrapper-item__updates hide">Last updated on 10 Dec 2021</div>
                            <div class="adm-c-counter__wrapper-item__title">No of Registrations</div>
                        </div>
                        <div class="adm-c-counter__wrapper-item__bottom">
                            <aside class="adm-c-counter__wrapper-item__bottom-ls">
                                <!-- <div class="adm-c-counter__wrapper-item__cat-txt">Referesh to update</div> -->
                                <div class="adm-c-counter__wrapper-item__count">
                                    <?= isset($data['registration']['count']) ? $data['registration']['count'] : 0 ?>
                                </div>
                            </aside>
                        </div>
                    </a>

                    <a href="javascript:;"
                        class="adm-u-cursor-pointer adm-c-counter__wrapper-item u-yellow adm-u-radius30">
                        <div class="adm-c-counter__wrapper-item__top">
                            <div class="adm-c-counter__wrapper-item__image">
                                <img src="https://d2f2ht52r1pvo3.cloudfront.net/backend/static/dist/images/icons/icon.svg"
                                    alt="image">
                            </div>
                            <div class="adm-c-counter__wrapper-item__updates hide">Last updated on 10 Dec 2021</div>
                            <div class="adm-c-counter__wrapper-item__title">No of Advertisement</div>
                        </div>
                        <div class="adm-c-counter__wrapper-item__bottom">
                            <aside class="adm-c-counter__wrapper-item__bottom-ls">
                                <!-- <div class="adm-c-counter__wrapper-item__cat-txt">Referesh to update</div> -->
                                <div class="adm-c-counter__wrapper-item__count">
                                    <?= isset($data['advt']['active']) && isset($data['advt']['completed']) ? ($data['advt']['active']+$data['advt']['completed']) : 0 ?>
                                </div>
                            </aside>
                        </div>
                    </a>
                    <a href="javascript:;"
                        class="adm-u-cursor-pointer adm-c-counter__wrapper-item u-green adm-u-radius30">
                        <div class="adm-c-counter__wrapper-item__top">
                            <div class="adm-c-counter__wrapper-item__image">
                                <img src="https://d2f2ht52r1pvo3.cloudfront.net/backend/static/dist/images/icons/icon.svg"
                                    alt="image">
                            </div>
                            <div class="adm-c-counter__wrapper-item__updates hide">Last updated on 10 Dec 2021</div>
                            <div class="adm-c-counter__wrapper-item__title">No of Applications</div>
                        </div>
                        <div class="adm-c-counter__wrapper-item__bottom">
                            <aside class="adm-c-counter__wrapper-item__bottom-ls">
                                <!-- <div class="adm-c-counter__wrapper-item__cat-txt">Referesh to update</div> -->
                                <div class="adm-c-counter__wrapper-item__count">
                                    <?= isset($data['applications']['count']) ? $data['applications']['count'] : 0 ?>
                                </div>
                            </aside>
                        </div>
                    </a>

                    <a href="javascript:;"
                        class="adm-u-cursor-pointer adm-c-counter__wrapper-item u-yellow adm-u-radius30">
                        <div class="adm-c-counter__wrapper-item__top">
                            <div class="adm-c-counter__wrapper-item__image">
                                <img src="https://d2f2ht52r1pvo3.cloudfront.net/backend/static/dist/images/icons/icon.svg"
                                    alt="image">
                            </div>
                            <div class="adm-c-counter__wrapper-item__updates hide">Last updated on 10 Dec 2021</div>
                            <div class="adm-c-counter__wrapper-item__title">No of Paid Applications</div>
                        </div>
                        <div class="adm-c-counter__wrapper-item__bottom">
                            <aside class="adm-c-counter__wrapper-item__bottom-ls">
                                <!-- <div class="adm-c-counter__wrapper-item__cat-txt">Referesh to update</div> -->
                                <div class="adm-c-counter__wrapper-item__count">
                                    <?= isset($data['paidApplications']['count']) ? $data['paidApplications']['count'] : 0 ?>
                                </div>
                            </aside>
                        </div>
                    </a>


                </div>
            </div>
            <div class="tab-pane fade" id="tabTwo-a" role="tabpanel" aria-labelledby="tabThree" style="">
                <div class="adm-c-counter__wrapper ColBase col4 adm-c-counter__wrapper-xs cmt-15 design2">
                    <a href="javascript:;"
                        class="adm-u-cursor-pointer adm-c-counter__wrapper-item u-green adm-u-radius30">
                        <div class="adm-c-counter__wrapper-item__top">
                            <div class="adm-c-counter__wrapper-item__image">
                                <img src="https://d2f2ht52r1pvo3.cloudfront.net/backend/static/dist/images/icons/icon.svg"
                                    alt="image">
                            </div>
                            <div class="adm-c-counter__wrapper-item__updates hide">Last updated on 10 Dec 2021</div>
                            <div class="adm-c-counter__wrapper-item__title">No of Registrations</div>
                        </div>
                        <div class="adm-c-counter__wrapper-item__bottom">
                            <aside class="adm-c-counter__wrapper-item__bottom-ls">
                                <!-- <div class="adm-c-counter__wrapper-item__cat-txt">Referesh to update</div> -->
                                <div class="adm-c-counter__wrapper-item__count">
                                    <?= isset($data['registration']['count']) ? $data['registration']['count'] : 0 ?>
                                </div>
                            </aside>
                        </div>
                    </a>

                    <a href="javascript:;"
                        class="adm-u-cursor-pointer adm-c-counter__wrapper-item u-yellow adm-u-radius30">
                        <div class="adm-c-counter__wrapper-item__top">
                            <div class="adm-c-counter__wrapper-item__image">
                                <img src="https://d2f2ht52r1pvo3.cloudfront.net/backend/static/dist/images/icons/icon.svg"
                                    alt="image">
                            </div>
                            <div class="adm-c-counter__wrapper-item__updates hide">Last updated on 10 Dec 2021</div>
                            <div class="adm-c-counter__wrapper-item__title">No of Profile Completed</div>
                        </div>
                        <div class="adm-c-counter__wrapper-item__bottom">
                            <aside class="adm-c-counter__wrapper-item__bottom-ls">
                                <!-- <div class="adm-c-counter__wrapper-item__cat-txt">Referesh to update</div> -->
                                <div class="adm-c-counter__wrapper-item__count">
                                    <?= isset($data['applications']['count']) ? $data['applications']['count'] : 0 ?>
                                </div>
                            </aside>
                        </div>
                    </a>

                </div>
                <?= $this->render('partials/application-graph.php') ?>
            </div>
            <div class="tab-pane fade" id="tabThree-a" role="tabpanel" aria-labelledby="tabThree" style="">
                <div class="adm-c-counter__wrapper ColBase col4 adm-c-counter__wrapper-xs cmt-15 design2">
                    <a href="javascript:;"
                        class="adm-u-cursor-pointer adm-c-counter__wrapper-item u-green adm-u-radius30">
                        <div class="adm-c-counter__wrapper-item__top">
                            <div class="adm-c-counter__wrapper-item__image">
                                <img src="https://d2f2ht52r1pvo3.cloudfront.net/backend/static/dist/images/icons/icon.svg"
                                    alt="image">
                            </div>
                            <div class="adm-c-counter__wrapper-item__updates hide">Last updated on 10 Dec 2021</div>
                            <div class="adm-c-counter__wrapper-item__title">No of Advertisement</div>
                        </div>
                        <div class="adm-c-counter__wrapper-item__bottom">
                            <aside class="adm-c-counter__wrapper-item__bottom-ls">
                                <!-- <div class="adm-c-counter__wrapper-item__cat-txt">Referesh to update</div> -->
                                <div class="adm-c-counter__wrapper-item__count">
                                    <?= isset($data['advt']['active']) && isset($data['advt']['completed']) ? ($data['advt']['active']+$data['advt']['completed']) : 0 ?>
                                </div>
                            </aside>
                        </div>
                    </a>

                    <a href="javascript:;"
                        class="adm-u-cursor-pointer adm-c-counter__wrapper-item u-yellow adm-u-radius30">
                        <div class="adm-c-counter__wrapper-item__top">
                            <div class="adm-c-counter__wrapper-item__image">
                                <img src="https://d2f2ht52r1pvo3.cloudfront.net/backend/static/dist/images/icons/icon.svg"
                                    alt="image">
                            </div>
                            <div class="adm-c-counter__wrapper-item__updates hide">Last updated on 10 Dec 2021</div>
                            <div class="adm-c-counter__wrapper-item__title">No of Active Advertisement</div>
                        </div>
                        <div class="adm-c-counter__wrapper-item__bottom">
                            <aside class="adm-c-counter__wrapper-item__bottom-ls">
                                <!-- <div class="adm-c-counter__wrapper-item__cat-txt">Referesh to update</div> -->
                                <div class="adm-c-counter__wrapper-item__count">
                                    <?= isset($data['advt']['active']) ? $data['advt']['active'] : 0 ?></div>
                            </aside>
                        </div>
                    </a>


                </div>
                <?= $this->render('partials/advt-graph.php', ['data' => $data]) ?>
            </div>
        </div>
    </div>
</div>
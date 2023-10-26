<?php
$this->params['bodyClass'] = 'themeClr-1';
$action = $this->context->action->id;
?>
<?= $this->render('/layouts/partials/flash-message.php') ?>
<div class="main-body">
    <div class="c-f-hiring__wrapper">
        <div class="container">
            <div class="c-f-hiring__wrapper-container u-flexed u-justify-btw u-align-center">
                <div class="c-f-hiring__wrapper-container-head">
                    <?php
                    if ($action == 'index') {
                        echo 'New Advertisement';
                    } else if ($action == 'posts') {
                        echo 'Select Post';
                    }
                    ?>
                </div>
                <!--                <div class="c-f-hiring__wrapper-container-action">
                                    <a href="/home/advertisement" class="c-f-hiring__wrapper-container-action--link">View All</a>
                                </div>-->
            </div>
        </div>
    </div>
    <!--    <div class="c-f-job__list">
            <div class="container">
                <div class="c-f-job__list-item">
                    <div class="c-f-job__list-item-content">
                        <div class="job-postTitle">Online Application for Assistant Conservator of Forest Examination-2019</div>
                        <div class="job-advNo">Advertisement Number A-1/E-1/ACF/2019-20<span class="job-lastDate">(Last Date of Submission - 30 Dec, 2019)</span></div>
                    </div>
                    <a href="../auth/login" class="c-f-job__list-item-action">
                        Apply Here
                    </a>
                </div>
                <div class="c-f-job__list-item">
                    <div class="c-f-job__list-item-content">
                        <div class="job-postTitle">Online Application for Assistant Conservator of Forest Examination-2019</div>
                        <div class="job-advNo">Advertisement Number A-1/E-1/ACF/2019-20<span class="job-lastDate">(Last Date of Submission - 30 Dec, 2019)</span></div>
                    </div>
                    <a href="../auth/login" class="c-f-job__list-item-action">
                        Apply Here
                    </a>
                </div>
    
            </div>
        </div>-->
    <div class="c-f-job__list">
        <div class="container">
            <?php
            echo $this->render('partials/advertisement');
            ?>
        </div>
    </div>
    <div class="c-marquee__wrapper">
        <div class="container">
            <div class="c-marquee__wrapper-content">
                <marquee onMouseOver="this.stop()" onMouseOut="this.start()" behavior="alternate">
                    <a target="_blank" href="/auth/get-admit-card" class="c-marquee__wrapper-link">Dear Candidate please click on the link https://www.hpslsarecruitment.in/auth/get-admit-card to download your Admit Cards.<span>New</span></a>
                </marquee>
                <marquee onMouseOver="this.stop()" onMouseOut="this.start()">
                    <a href="javascript:;" class="c-marquee__wrapper-link">Dear Candidate please login with your e-mail ID. The Help Desk contact number for complaints/queries are as: 0177-2623862 (all working days from 10.00 am to 5.00 p.m.)</a>
                </marquee>
            </div>
        </div>
    </div>
    <!--    <div class="c-f-guidline__wrapper">
            <div class="c-f-guidline__wrapper-item head">
                <div class="section-head">
                    <h3 class="heading">Guidelines</h3>
                    <p class="sub-heading">for Candidates</p>
                </div>
            </div>
            <div class="c-f-guidline__wrapper-item content">
                <div class="section-content">
                    <ul class="c-f-bullets__wrapper design1">
                        <li class="c-f-bullets__wrapper-item">
                            <a href="javascript:;" class="c-f-bullets__wrapper-item-link">
                                <div class="c-f-bullets__wrapper-item-link-icon"></div>
                                <div class="c-f-bullets__wrapper-item-link-content">How to apply online</div>
                            </a>
                        </li>
                        <li class="c-f-bullets__wrapper-item">
                            <a href="javascript:;" class="c-f-bullets__wrapper-item-link">
                                <div class="c-f-bullets__wrapper-item-link-icon"></div>
                                <div class="c-f-bullets__wrapper-item-link-content">Popup Blocker Guidance</div>
                            </a>
                        </li>
                        <li class="c-f-bullets__wrapper-item">
                            <a href="javascript:;" class="c-f-bullets__wrapper-item-link">
                                <div class="c-f-bullets__wrapper-item-link-icon"></div>
                                <div class="c-f-bullets__wrapper-item-link-content">Forgot Password?</div>
                            </a>
                        </li>
                        <li class="c-f-bullets__wrapper-item">
                            <a href="javascript:;" class="c-f-bullets__wrapper-item-link">
                                <div class="c-f-bullets__wrapper-item-link-icon"></div>
                                <div class="c-f-bullets__wrapper-item-link-content">Cropping Photo and Signature</div>
                            </a>
                        </li>
                        <li class="c-f-bullets__wrapper-item">
                            <a href="javascript:;" class="c-f-bullets__wrapper-item-link">
                                <div class="c-f-bullets__wrapper-item-link-icon"></div>
                                <div class="c-f-bullets__wrapper-item-link-content">OTR Instructions English</div>
                            </a>
                        </li>
                        <li class="c-f-bullets__wrapper-item">
                            <a href="javascript:;" class="c-f-bullets__wrapper-item-link">
                                <div class="c-f-bullets__wrapper-item-link-icon"></div>
                                <div class="c-f-bullets__wrapper-item-link-content">OTR Instructions Hindi</div>
                            </a>
                        </li>
                        <li class="c-f-bullets__wrapper-item">
                            <a href="javascript:;" class="c-f-bullets__wrapper-item-link">
                                <div class="c-f-bullets__wrapper-item-link-icon"></div>
                                <div class="c-f-bullets__wrapper-item-link-content">FAQs English</div>
                            </a>
                        </li>
                        <li class="c-f-bullets__wrapper-item">
                            <a href="javascript:;" class="c-f-bullets__wrapper-item-link">
                                <div class="c-f-bullets__wrapper-item-link-icon"></div>
                                <div class="c-f-bullets__wrapper-item-link-content">FAQs Hindi</div>
                            </a>
                        </li>
                        <li class="c-f-bullets__wrapper-item">
                            <a href="javascript:;" class="c-f-bullets__wrapper-item-link">
                                <div class="c-f-bullets__wrapper-item-link-icon"></div>
                                <div class="c-f-bullets__wrapper-item-link-content">My Request Instructions</div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>-->
</div>
<!--<div class="c-f-news__wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 col-12">
                <div class="c-f-news__wrapper-item">
                    <div class="c-f-news__wrapper-item-head u-flexed u-justify-btw u-align-center">
                        <span>What's New </span>
                        <a href="javascript:;">View all</a>
                    </div>
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
            <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 col-12">
                <div class="c-f-news__wrapper-item">
                    <div class="c-f-news__wrapper-item-head u-flexed u-justify-btw u-align-center">
                        <span>Latest News </span>
                        <a href="javascript:;">View all</a>
                    </div>
                    <div class="c-f-news__wrapper-item-content">
                        <div class="holder">
                            <ul id="ticker01">
                                <li><a href="javascript:;">The standard Lorem Ipsum passage, used since the 1500s</a></li>
                                <li><a href="javascript:;">Section 1.10.32 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC</a></li>
                                <li><a href="javascript:;">1914 translation by H. Rackham</a></li>
                                <li><a href="javascript:;">Section 1.10.33 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC</a></li>
                                <li><a href="javascript:;">1914 translation by H. Rackham</a></li>
                                <li><a href="javascript:;">Problematically, however, the Javascript code</a></li>
                                <li><a href="javascript:;">The first thing that most Javascript programmers</a></li>
                                <li><a href="javascript:;">The standard Lorem Ipsum passage, used since the 1500s</a></li>
                                <li><a href="javascript:;">Section 1.10.32 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC</a></li>
                                <li><a href="javascript:;">1914 translation by H. Rackham</a></li>
                                <li><a href="javascript:;">Section 1.10.33 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC</a></li>
                                <li><a href="javascript:;">1914 translation by H. Rackham</a></li>
                                <li><a href="javascript:;">Problematically, however, the Javascript code</a></li>
                                <li><a href="javascript:;">The first thing that most Javascript programmers</a></li>
                                <li><a href="javascript:;">1914 translation by H. Rackham</a></li>
                                <li><a href="javascript:;">Problematically, however, the Javascript code</a></li>
                                <li><a href="javascript:;">The first thing that most Javascript programmers</a></li>
                                <li><a href="javascript:;">The standard Lorem Ipsum passage, used since the 1500s</a></li>
                                <li><a href="javascript:;">Section 1.10.32 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC</a></li>
                                <li><a href="javascript:;">1914 translation by H. Rackham</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>-->
<!--<div class="c-f-info">
    <div class="container">
        <div class="c-f-info__wrapper">
            <div class="c-f-info__wrapper-item">
                <div class="c-f-info__wrapper-item-icon">
                    <img src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/images/icons/ip-address.svg" class="img-fluid" alt="Your Machine IP Address">
                </div>
                <div class="c-f-info__wrapper-item-lable">
                    Your Machine IP Address
                </div>
                <div class="c-f-info__wrapper-item-count clr1">
                    IP1:103.92.40.119
                </div>
            </div>

            <div class="c-f-info__wrapper-item">
                <div class="c-f-info__wrapper-item-icon">
                    <img src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/images/icons/visitor.svg" class="img-fluid" alt="Total Visitors">
                </div>
                <div class="c-f-info__wrapper-item-lable">
                    Total Visitors
                </div>
                <div class="c-f-info__wrapper-item-count clr2">
                    693464
                </div>
            </div>

            <div class="c-f-info__wrapper-item">
                <div class="c-f-info__wrapper-item-icon">
                    <img src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/images/icons/last-updated.svg" class="img-fluid" alt="Your Machine IP Address">
                </div>
                <div class="c-f-info__wrapper-item-lable">
                    Last Updated on
                </div>
                <div class="c-f-info__wrapper-item-count">
                    26-11-2019
                </div>
            </div>

        </div>
    </div>
</div>-->
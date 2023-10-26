<?php
use common\models\MstClassified;
use common\models\MstPost;

$classifieds = MstClassified::getClassifiedDropdown();
$posts = MstPost::getPostDropdown();

?>
<style type="text/css">
    *{
        margin:0px;
        padding:0px;
    }


    a, a:hover, a:focus{
        text-decoration:none;	
    }
    .second-table {
        display:none;	
    }

    body {
        font-size:14px;
        color:#333;
        font-family: 'Roboto', sans-serif;	
    }
    .mainsection {
        max-width:1200px;
        width:auto;
        margin:auto;
        padding-top:20px;
        padding-bottom:20px;	
    }

    .report-name {
        width:100%;
        text-align:center;
        margin-bottom:20px;

    }

    .report-name span {
        display:inline-block;	
        font-size:25px;
        font-weight:700;
        color:#000000;
        padding:7px;
        letter-spacing:1px;
    }


    .filtertabel {
        width:100%;	
        border:none;
        margin-bottom:20px;
    }

    .filtertabel {
        font-family: 'Roboto', sans-serif;	
        border-collapse: collapse;
        width: 100%;
    }

    .filtertabel td, .filtertabel th {
        border: 1px solid #ddd;
        padding: 4px;
    }

    .filtertabel tr:nth-child(even){background-color: #f2f2f2;}


    .filtertabel th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #f58b72;
        color: white;
    }

    .filtertabel i { font-size:18px; color:#4CAF50; display:inline-block; margin-left:10px;}
    .filtertabel i a { color:#4CAF50}
    .text-c {
        text-align:center !important;	
    }

    ul.filtervlaue {
        list-style:none;	
    }
    ul.filtervlaue li {
        margin-bottom:4px;
        font-size:13px;	
    }
    ul.filtervlaue li  span { font-weight:500;
                              display:inline-block;
                              padding-right:15px;
                              width:140px;}


    .searchtable { width:100%; border:1px #333 solid !important;}
    .searchtable td, .searchtable th { 
        border:1px #333 solid !important;
    }
    .searchtable th:last-child{ text-align:center;}
    .searchtable  th {
        font-size:12px;
        text-align:center;
    }
    .searchtable td { text-align:right;}
    .searchtable td:first-child { text-align:left; width:200px;}


    .searchtable tr.levelone td:first-child {
        background:#bfdafe;
    }
    .searchtable tr.levelone td:first-child a {
        color:#000;

    }

    .searchtable tr.leveltwo td:first-child {
        background:#dfeafa;
        text-align:center !important;
    }
    .searchtable tr.leveltwo td:first-child a {
        color:#000;
    }

    .searchtable tr.levelthree td:first-child {
        background:#f1f5fb;
        text-align:right !important;
    }
    .searchtable tr.levelthree td:first-child a {
        color:#000;
    }
    .searchtable tr.levelone table td{ background: white !important;}
    .searchtable tr.levelone table td:first-child { background: none; text-align: right;}
    .searchtable table { margin-bottom: 0px;}
    .searchtable tr.levelfour td:first-child {
        background:#f9fbff;
        text-align:center !important;
    }
    .searchtable td table td, .searchtable tr.leveltwo td table td:first-child, .searchtable tr.levelfour td table td:first-child {
        background:white !important;
        text-align:right !important; 
    }
</style>
</head>

<div class="mainsection">
    <div class="report-name"><span>Report</span></div>

    <div class="table-responsive">
        <table class="searchtable table table-bordered table-striped">
            <tr>
                <th><span style="display:inline-block; width:200px;">Aggregate</span></th>
                <th>
                    <table class="searchtable table table-bordered table-striped">
                        <tr>
                            <th colspan="3">TOTAL </th>
                        </tr>

                    </table>
                </th>
                <th class="js-showColoumns" style="display:none">
                    <table class="searchtable table table-bordered table-striped">
                        <tr>
                            <th colspan="3">PAID</th>
                        </tr>

                    </table>
                </th>
                <th class="js-showColoumns" style="display:none">
                    <table class="searchtable table table-bordered table-striped">
                        <tr>
                            <th colspan="3">UNPAID</th>
                        </tr>

                    </table>
                </th>
                <th width="10%">Details</th>
            </tr>

            <!--for total Row-->
            <?php
                $dataNum = 1;
                foreach ($records as $year => $record): ?>
                <tr class="levelone">
                    <td><a class="js-showClassWise" href="javascript:;"><?= $year; ?> <i class="fa fa-chevron-right" aria-hidden="true"></i></a></td>
                    <td>
                        <table class="searchtable table table-bordered table-striped">
                            <tr>
                                <td><?= $record['totalPaid'] + $record['totalUnPaid']; ?></td>
                            </tr>
                        </table>

                    </td>
                    <td class="js-showColoumns" style="display:none">
                        <table class="searchtable table table-bordered table-striped">
                            <tr>
                                <td><?= $record['totalPaid']; ?></td>

                            </tr>
                        </table>
                    </td>
                    <td class="js-showColoumns" style="display:none">
                        <table class="searchtable table table-bordered table-striped">
                            <tr>
                                <td ><?= $record['totalUnPaid']; ?></td>
                            </tr>
                        </table>
                    </td>
                    <td class="text-c"><a class="js-showAllColoumns" href="javascript:;"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
                </tr>
                <?php if (!empty($record['classified'])): ?>
                    <?php foreach ($record['classified'] as $classifiedId => $classified): ?>
                        <tr class="leveltwo"  style="display:none;">
                            <td>
                                <a class="js-classWiseClick" data-num="<?= $dataNum; ?>" href="javascript:;">
                                    <?= !empty($classifieds[$classifiedId]) ? $classifieds[$classifiedId] : ''; ?> 
                                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                </a>
                            </td>
                            <td>
                                <table class="searchtable table table-bordered table-striped">
                                    <tr>
                                        <td><?= $classified['totalPaid'] + $classified['totalUnPaid']; ?></td>
                                    </tr>
                                </table>
                            </td>
                            <td class="js-showColoumns" style="display:none">
                                <table class="searchtable table table-bordered table-striped">
                                    <tr>
                                        <td><?= $classified['totalPaid']; ?></td>
                                    </tr>
                                </table>
                            </td>
                            <td class="js-showColoumns" style="display:none">
                                <table class="searchtable table table-bordered table-striped">
                                    <tr>
                                        <td><?= $classified['totalUnPaid']; ?></td>
                                    </tr>
                                </table>
                            </td>

                            <td  class="text-c"><a class="js-showAllColoumns" href="javascript:;"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
                        </tr>
                        <?php foreach ($classified['post'] as $postId => $post): ?>
                            <tr class="levelthree js-sectionWise<?= $dataNum; ?>" data-sec="<?= $post['totalPaid']; ?>" style="display:none">
                                <td>
                                    <a class="js-showEnrollWise" data-sec="<?= $post['totalPaid']; ?>" href="javascript:;">
                                        <?= !empty($posts[$postId]) ? $posts[$postId] : '';; ?>
                                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                    </a>
                                </td>
                                <td>
                                    <table class="searchtable table table-bordered table-striped">
                                        <tr>
                                            <td><?= $post['totalPaid'] + $post['totalUnPaid']; ?></td>
                                        </tr>
                                    </table>
                                </td>        

                                <td class="js-showColoumns" style="display:none">
                                    <table class="searchtable table table-bordered table-striped">
                                        <tr>
                                            <td><?= $post['totalPaid']; ?></td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="js-showColoumns" style="display:none">
                                    <table class="searchtable table table-bordered table-striped">
                                        <tr>
                                            <td><?= $post['totalUnPaid']; ?></td>
                                        </tr>
                                    </table>
                                </td>
                                <td  class="text-c"><a class="js-showAllColoumns" href="javascript:;"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
                            </tr>          
                        <?php endforeach; ?>
                        <?php ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php $dataNum++; ?>
            <?php endforeach; ?>
        </table>
    </div>

</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $(".js-showClassWise").click(function () {
            $(".leveltwo").toggle(500);
            $(this).find('i').toggleClass('fa-chevron-right fa-chevron-down');
            $(".levelthree").hide(500);
            $(".levelfour").hide(500);
            $(".levelfive").hide(500);
            $(".levelsix").hide(500);
            $(".js-classWiseClick").find('i').removeClass('fa-chevron-down');
            $(".js-classWiseClick").find('i').addClass('fa-chevron-right');
            $(".js-showEnrollWise").find('i').removeClass('fa-chevron-down');
            $(".js-showEnrollWise").find('i').addClass('fa-chevron-right');
            $(".js-showStreamWise").find('i').removeClass('fa-chevron-down');
            $(".js-showStreamWise").find('i').addClass('fa-chevron-right');
        });
        $('.js-classWiseClick').click(function () {
            var num = $(this).attr('data-num');

            $(".js-sectionWise" + num).toggle(500);
            $(this).find('i').toggleClass('fa-chevron-right fa-chevron-down');
            var getEnrollClass = $(".js-sectionWise" + num).attr('data-sec');
            $(".js-acYearHide" + num).hide(500);
            $(".js-sectionWise" + num).find('i').removeClass('fa-chevron-down');
            $(".js-sectionWise" + num).find('i').addClass('fa-chevron-right');
            $(".js-strYeardata" + num).find('i').removeClass('fa-chevron-down');
            $(".js-strYeardata" + num).find('i').addClass('fa-chevron-right');
        });
        $('.js-showEnrollWise').click(function () {
            var sec = $(this).attr('data-sec');
            $(".js-regionalWise" + sec).toggle(500);
            $(this).find('i').toggleClass('fa-chevron-right fa-chevron-down');
            $(".js-addSelectHide" + sec).hide(500);
            $(".js-studentWise" + sec).find('i').removeClass('fa-chevron-down');
            $(".js-studentWise" + sec).find('i').addClass('fa-chevron-right');
            $(".js-showStreamWise").find('i').removeClass('fa-chevron-down');
            $(".js-showStreamWise").find('i').addClass('fa-chevron-right');
            $(".js-addSelectHide" + sec).find('i').removeClass('fa-chevron-down');
            $(".js-addSelectHide" + sec).find('i').addClass('fa-chevron-right');
        });
        $('.js-showStreamWise').click(function () {
            var rc = $(this).attr('data-rc');
            $(".js-studentWise" + rc).toggle(500);
            $(this).find('i').toggleClass('fa-chevron-right fa-chevron-down');
            $(".js-regionalHide" + rc).hide(500);
            $(".js-regionalHide" + rc).find('i').removeClass('fa-chevron-down');
            $(".js-regionalHide" + rc).find('i').addClass('fa-chevron-right');
        });
        $('.js-showBlockWise').click(function () {
            var str = $(this).attr('data-str');
            $(".js-blockWise" + str).toggle(500);
            $(this).find('i').toggleClass('fa-chevron-right fa-chevron-down');
        });




        $(".js-showAllColoumns").click(function () {
            $(".js-showColoumns").toggle(500);
            $(".js-showAllColoumns").find('i').toggleClass('fa-plus fa-minus');
        });

        $(document).on('click', 'a.export', function (event) {
            event.preventDefault();
            var url = $(this).attr("href");
            window.location = url + '?download=true&' + window.location.search.substring(1);
        });
    });
</script>
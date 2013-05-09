<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?php echo $this->getPageTitle(); ?></title>
        <base href="<?php echo Yii::app()->request->getBaseUrl(true) ?>" />
        <link rel="stylesheet" type="text/css" href="css/constants.css">
        <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
        <link rel="stylesheet" type="text/css" href="css/stylesheet_boxes.css">
        <link rel="stylesheet" type="text/css" href="css/stylesheet_content.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <!--[if IE]>
            <link href="css/ie_stylesheet.css" rel="stylesheet" type="text/css" />
        <![endif]-->
        <script type="text/javascript" src="/js/cufon-yui.js"></script>
        <script type="text/javascript" src="/js/cufon-replace.js"></script>
        <script type="text/javascript" src="/js/Myriad_Web_400.font.js"></script>
        <script type="text/javascript" src="/js/jquery-ui.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#featured > ul").tabs({fx: {opacity: "toggle"}}).tabs("rotate", 7000, true);
            });
        </script>
        <script type="text/javascript" src="/js/jzcarousellite.js"></script>
    </head>
    <body>
        <div class="bg2_body" align="center">
            <div class="row_1">
                <div class="width_table">
                    <div class="cl_both">
                        <?php $this->renderPartial('application.views.layouts._logo') ?>
                        <div class="fl_right" align="right">
                            <?php $this->renderPartial('application.views.layouts._cart') ?>
                            <?php $this->renderPartial("application.views.layouts._navigation") ?>
                        </div>
                    </div>
                    <div class="cl_both">
                        <div class="menu_wrapper_tl">
                            <div class="menu_wrapper_tr">
                                <div class="menu_wrapper_rep fs_lh">
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td class="menu">
                                                <?php $this->renderPartial("application.views.layouts._header_menu") ?>
                                            </td>
                                            <?php $this->renderPartial("application.views.layouts._search") ?>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div align="center">
                <div class="row_2">
                    <table border="0" class="main_table" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="box_width_td_left">
                                <table border="0" class="box_width_left" cellspacing="0" cellpadding="0">    
                                    <tr>
                                        <td>
                                            <?php $this->renderPartial("application.views.layouts._categories_list") ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td class="content_width_td">
                                <?php $this->renderPartial("application.views.layouts._breadcrumbs") ?>
                                <?php echo $content ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="row_4">
                    <div class="width_table footer">
                        <div align="center">
                            <?php $this->renderPartial("application.views.layouts._footer_menu") ?>
                            <?php $this->renderPartial("application.views.layouts._footer2_td") ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!--</div>-->
</body>
</html>
<div class="navigation">
    <div class="navigation_wrapper_tl">
        <div class="navigation_wrapper_tr">
            <div class="navigation_wrapper_rep fs_lh">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td class="languages">
                            <?php
                            /* @var $language Language */
                            $langData = array();
                            $selected = '/';
                            foreach (($languages = Language::model()->findAll()) as $language) {
                                $url = $language->default ? '/' : '/' . $language->code;
                                if (Yii::app()->lang->code == $language->code) {
                                    $selected = $url;
                                }
                                $langData[$url] = $language->title;
                            }
                            Yii::app()->clientScript->registerScript('lang-selector', "
                                jQuery('#lang-selector').change(function(){
                                    window.location.href = jQuery(this).val();
                                })
                            ");
                            ?>
                            <form class="form-horizontal">
                                <div class="control-group">
                                    <label class="control-label" style="color: #fff; width: 100px;"><?php echo Yii::t('common', 'Language') ?>:</label>
                                    <div class="controls" style="margin-left: 0px;">
                                        <?php echo CHtml::dropDownList('language', $selected, $langData, array('id' => 'lang-selector')) ?>
                                    </div>
                                </div>
                            </form>
                        </td>                                              
                    </tr>
                </table> 
            </div>
        </div>
    </div>
</div>
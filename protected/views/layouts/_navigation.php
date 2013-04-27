<div class="navigation">
    <div class="navigation_wrapper_tl">
        <div class="navigation_wrapper_tr">
            <div class="navigation_wrapper_rep fs_lh">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td class="languages">
                            <?php
                            /* @var $language Language */
                            foreach (($languages = Language::model()->findAll()) as $language) {
                                $imageFile = '/images/flags/' . $language->flag;
                                $url = $language->default ? '/' : '/' . $language->code;
                                echo CHtml::link(CHtml::image($imageFile), $url, array(
                                    'class' => 'language',
                                    'title' => $language->title
                                ));
                            }
                            ?>
                        </td>                                              
                    </tr>
                </table> 
            </div>
        </div>
    </div>
</div>
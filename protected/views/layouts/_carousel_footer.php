<?php
/* @var $item Slider */
$sliderItems = Slider::model()->findAll();
?>
<?php if (count($sliderItems) >= 5): ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="0"  class="infoBoxHeadingTitle_table">
        <tr>
            <td class="infoBoxHeading_td infoBoxHeading_br">Our Spotlight</td>
        </tr>
    </table>
    <div class="carousel-box main">
        <div class="inner">
            <button class="prev"></button>
            <button class="next"></button>
            <div class="carousel">
                <ul>
                    <?php foreach ($sliderItems as $item): ?>
                        <li>
                            <div class="div">
                                <table cellpadding="0" cellspacing="0" border="0" class="wrapper_box ie_width">
                                    <tr>
                                        <td class="pic2_padd">
                                            <a href="<?php echo $item->getFrontUrl() ?>">
                                                <img src="<?php echo $item->getImageUrl(115, 107) ?>" border="0" alt="<?php echo $item->getTitle() ?>" title="<?php echo $item->getTitle() ?>" width="115" height="107">
                                            </a>
                                        </td>
                                        <td style="width:100%;">
                                            <table cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                    <td class="name name_2_padd">
                                                        <div style="height: 40px; overflow: hidden; ">
                                                            <b>
                                                                <a href="<?php echo $item->getFrontUrl() ?>"><?php echo $item->getTitle() ?></a>
                                                            </b>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="price_2_padd">
                                                        <span class="productSpecialPrice"><?php echo $item->getPrice() ?></span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>									   
                            </div>		
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function() {

            $(".carousel").jCarouselLite({
                btnNext: ".next",
                btnPrev: ".prev"
            });

        });
    </script>
<?php endif; ?>
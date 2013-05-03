<?
/* @var $item Carousel */
$carouselItems = Carousel::model()->front()->findAll();
?>
<?php if (count($carouselItems) > 0): ?>
<div class="width_div">
    <div id="slides-box" class="slides-box-table">
        <div class="container">
            <div id="featured">
                <ul class="ui-tabs-nav">
                    <?php foreach($carouselItems as $key => $item):?>
                    <li class="ui-tabs-nav-item ui-tabs-selected" id="nav-fragment-<?=$key?>">
                        <a href="#fragment-<?=$key?>" class="un">
                            <span><strong><?php echo $item->title ?></strong></span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php foreach($carouselItems as $key => $item):?>
                <div id="fragment-<?=$key?>" class="ui-tabs-panel">
                    <a href="<?php echo $item->url ?>" target="_self">
                        <img src="<?php echo $item->getImageUrl() ?>" border="0" alt="<?php echo $item->title ?>" title="<?php echo $item->title ?>">
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
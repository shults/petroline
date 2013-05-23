<?php

/**
 * Description of Breadcrumbs
 *
 * @author shults
 */
class TbBreadcrumbs extends CWidget
{

    public $links;
    public $home = 'Home';
    public $separator = '/';
    public $homeUrl = '/';

    public function run()
    {
        if ($this->links) {
            $numberOfLinks = count($this->links);
            echo '<ul class="breadcrumb">';
            echo '<li><a href="' . $this->homeUrl . '">' . $this->home . '</a><span class="divider">' . $this->separator . '</span></li>';
            $counter = 0;
            foreach ($this->links as $title => $url) {
                if ($counter < $numberOfLinks - 1) {
                    echo '<li>'
                            . '<div itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">' 
                                . '<a href="' . CHtml::normalizeUrl($url) . '" itemprop="url" title="' . $title . '" >' 
                                    . '<span itemprop="title">' . $title . '</span>' 
                                . '</a>' 
                            . '</div>'
                            . '<span class="divider">' . $this->separator . '</span>' 
                       . '</li>';
                } else {
                    echo '<li class="active"><h1>' . $url . '</h1></li>';
                }
                $counter++;
            }
            echo '</ul>';
        }
    }

}

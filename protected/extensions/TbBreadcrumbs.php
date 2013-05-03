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
                    echo '<li><a href="' . CHtml::normalizeUrl($url) . '">' . $title . '</a><span class="divider">' . $this->separator . '</span></li>';
                } else {
                    echo '<li class="active">' . $url . '</li>';
                }
                $counter++;
            }
            echo '</ul>';
        }
    }

}

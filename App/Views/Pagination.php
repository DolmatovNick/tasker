<?php

namespace App\Views;

/**
 * Class Pagination
 * @package App\Views
 */
class Pagination
{
    /**
     * Rendering result
     * @var
     */
    private $html;

    /**
     * Uri with added to every pagination <a> element
     * @var
     */
    private $uri;

    /**
     * Total count pages
     * @var float
     */
    private $pages;

    /**
     * Current pagination page
     * @var int
     */
    private $current;

    /**
     * Size of pagination windows. [3, 4, 5] - 3 elements
     * @var array
     */
    private $windowsSize;

    public function __construct($uri, $countElements, $itemsOnPage) {
        $this->uri = $uri;
        $this->pages = ceil($countElements/$itemsOnPage);
    }

    /**
     * Get pagination
     * @param int $current
     * @param int $windowsSize
     * @return mixed
     */
    public function getHtml(int $current, int $windowsSize)
    {
        $this->current = $current;
        $this->windowsSize = $windowsSize;

        $this->divStart('pagination');
            $this->ulStart();

                if ($this->windowsSize > $this->pages) {
                    $this->short();
                } else {
                    $this->common();
                }

            $this->ulEnd();
        $this->divEnd();

        return $this->html;
    }

    /**
     * Short pagination
     */
    private function short() {

        $this->createPrev();

        for($i = 1; $i <= min($this->windowsSize, $this->pages); $i++) {
            $active = ($i == $this->current ? 'active' : null);
            
            $this->createOrderItem($i, $active);
        }

        $this->createNext();
    }

    /**
     * Common pagination
     */
    private function common() {

        $this->createPrev();

        $this->html .= $this->threeDots();

        $current = $this->current;
        $halfWindowsSize = ($this->windowsSize - 1) / 2;
        for($i = 1; $i <= $halfWindowsSize; $i++) {
            if ($current <= 1) {
                break;
            }
            $this->createOrderItem(--$current);
        }

        $this->createOrderItem($this->current, 'active');

        $current = $this->current;
        for($i = 1; $i <= $halfWindowsSize; $i++) {
            if ($current >= $this->pages) {
                break;
            }
            $this->createOrderItem(++$current);
        }

        $this->html .= $this->threeDots();

        $this->createNext();
    }

    private function createOrderItem($number, $class = null)
    {
        $this->html .= $this->li($number, $class, $number);
    }

    /**
     * Add next arrow
     * @param string|null $class
     */
    private function createNext($class = null)
    {
        $number = ($this->current + 1) > $this->pages ? $this->pages : ($this->current + 1);
        $this->html .= $this->li($number, $class, '→');
    }

    /**
     * Add prev arrow
     * @param string|null $class
     */
    private function createPrev($class = null)
    {
        $number = ($this->current - 1) < 0 ? 0 : ($this->current - 1);
        $this->html .= $this->li($number, $class, '←');
    }

    /**
     * Add 3 dots
     * @return string
     */
    private function threeDots() {
        return '<li ><a href="#" >...</a></li>';
    }

    /**
     * Add <li> element
     * @param $number
     * @param string $class
     * @param string $text
     * @return string
     */
    private function li($number, ?string $class, ?string $text) {
        $class = $class == null ?  '' : "class=\"$class\"";
        return "<li {$class}><a href=\"{$this->uri}&page={$number}\" >{$text}</a></li>";
    }
    
    private function ulStart()
    {
        $this->html .= '<ul>';
    }
    private function ulEnd()
    {
        $this->html .= '</ul>';
    }
    
    private function divStart($class = null) {
        $class = $class ?? "class=\"$class\"";
        $this->html .= "<div class=\"$class\">"; 
    }
    private function divEnd() {
        $this->html .= '</div>'; 
    }
}
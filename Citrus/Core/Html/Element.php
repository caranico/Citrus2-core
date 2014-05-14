<?php
/*
 * This file is part of Citrus.
 *
 * (c) Rémi Cazalet <remi@caramia.fr>
 * Nicolas Mouret <nicolas@caramia.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @package Citrus\Core\Html
 * @subpackage Citrus\Core\Html\Element
 * @author Rémi Cazalet <remi@caramia.fr>
 * @license http://opensource.org/licenses/mit-license.php The MIT License
 */


namespace Citrus\Core\Html;

/**
 * This class manages an html element
 */
class Element
{
    /**
     * @var string
     */
    public $tag_name = '';

    /**
     * @var string
     */
    public $id = '';

    /**
     * @var array
     */
    public $classes = array();

    /**
     * @var array
     */
    public $attributes = array();

    /**
     * @var string
     */
    public $inner_html = '';

    /**
     * @var boolean
     */
    public $inline = false;

    /**
     * @var array
     */
    public $children = array();

    /**
     * @var boolean
     */
    public $close_tag = false;

    /**
     * Constructor
     * Adds an element to the document
     *
     * @param string  $tag_name  name of the html tag
     * @param array  $params  parameters for the tag : attributes, etc…
     */
    public function __construct($tag_name, $params = array())
    {
        $this->tag_name   = $tag_name;
        $this->id         = isset($params['id']) ? $params['id'] : '';
        $this->classes    = isset($params['classes']) ? $params['classes'] : array();
        $this->attributes = isset($params['attributes']) ? $params['attributes'] : array();
        $this->inline     = isset($params['inline']) ? $params['inline'] : false;
        $this->close_tag  = isset($params['close_tag']) ? $params['close_tag'] : false;
    }

    /**
     * Render the html code for the tag.
     *
     * @return string  $html  the html code
     */
    public function renderHtml()
    {
        $html = '<' . $this->tag_name;
        if (count($this->classes)) {
            $html .= ' ' . implode(' ', $this->classes);
        }
        if ($this->id) $html .= ' id="' . $this->id . '"';
        if (count($this->attributes)) {
            foreach ($this->attributes as $name => $value) {
                $html .= ' ' . $name . '="' . $value . '"';
            }
        }
        $close_tag = $this->close_tag && $this->inline ? ' /' : '';

        $inner_html = '';
        if ($this->inner_html != '') $inner_html .= $this->inner_html;
        if (count($this->children)) foreach ($this->children as $child) {
            $inner_html .= $child->renderHtml();
        }

        $html .= $close_tag . ">";
        if ($inner_html != '') $html .= "\r\n";
        $html .= $inner_html;

        if (!$this->inline) {
            $html .= '</' . $this->tag_name . '>' . "\r\n";
        } else {
            $html .= "\r\n";
        }
        return $html;
    }

    /**
     * Adds a class to the tag
     *
     * @param string  $class  the name of the class
     *
     * @return  Citrus\Core\Html\Element  $this  this object.
     */
    public function addClass($className)
    {
     if (!in_array($className, $this->classes)) {
            $this->classes[] = $className;
        }
        return $this;
    }

    /**
     * Removes one of the classes of the tag
     *
     * @param string  $class  the name of the class
     *
     * @return  Citrus\Core\Html\Element  $this  this object.
     */
    public function removeClass($class_name)
    {
      $key = array_search($className, $this->classes);
        if ($key !== false) {
            unset($this->Class[$key]);
        }
        return $this;
    }

    /**
     * Adds HTML code into the tag
     *
     * @param string  $html  the html code
     *
     * @return  Citrus\Core\Html\Element  $this  this object.
     */
    public function addHtml($html)
    {
       if ($html != '') {
            $this->inner_html .= $html . "\r\n";
        }
        return $this;
    }

    /**
     * Adds a child to the tag
     *
     * @param string  $tag_name  the name of the child tag
     * @param array  $params  parameters for the tag : attributes, inline
     *
     * @return  Citrus\Core\Html\Element  $this  this object.
     */
    public function addChild($tag_name, $params = array())
    {

        $elt= new Element($tag_name, $params);
        $this->children[] = $elt;
        // $elt->close_tag = $this->close_tag;
        //$this->addHtml($elt->renderHtml());
        return $elt;
    }

    /**
     * Adds children to the tag
     *
     * @param array  $children  An array of Citrus\Core\Html\Elements
     *
     * @return  Citrus\Core\Html\Element  $this  this object.
     */
    public function addChildren($children = array())
    {
        $this->children = array_merge($this->children, $children);
    }
}

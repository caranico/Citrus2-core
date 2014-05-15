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
 * Thanks to http://blog.sosedoff.com/2009/09/20/rails-like-php-url-router/
 */


namespace Citrus\Core\Routing;

class Route
{
    private $is_matched = false;

    private $params;

    private $url;

    private $conditions;

    private $target;

    public function __construct($url, $request_uri, $target = null, $conditions = Array())
    {
        $this->url        = $url;
        $this->params     = array();
        $this->conditions = $conditions;
        $p_names          = array();
        $p_values         = array();

        preg_match_all('@{([\w]+)}@', $url, $p_names, PREG_PATTERN_ORDER);
        $p_names = $p_names[0];

        $url_regex = preg_replace_callback(
            '@{[\w]+}@',
            array($this, 'regexURL'),
            $url
        );
        $url_regex .= '/?';

        if (preg_match('@^' . $url_regex . '$@', $request_uri, $p_values)) {
            array_shift($p_values);
            foreach($p_names as $index => $value) {
                $value = str_replace(Array("{", "}"), "", $value);
                $this->params[$value] = urldecode($p_values[$index]);
            }

            if ($target) $this->target = $target;
            $this->is_matched = true;
        }

        unset($p_names);
        unset($p_values);
    }

    private function regexURL($matches)
    {
        $key = str_replace(Array("{", "}"), '', $matches[0]);
        if (array_key_exists($key, $this->conditions)) {
            return '(' . $this->conditions[$key] . ')';
        } else {
            return '([a-zA-Z0-9_\+\-%]+)';
        }
    }

    public function getParam($name)
    {
        if (array_key_exists($name, $this->params)) {
            return $this->params[$name];
        }
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function isMatched()
    {
        return $this->is_matched;
    }
}

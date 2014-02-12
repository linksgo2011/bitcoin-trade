<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Helper', 'View');
App::uses('PaginatorHelper', 'View/Helper');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class MyPagesHelper extends PaginatorHelper {

    public function numbers($options = array()) {
        if ($options === true) {
            $options = array(
                'before' => ' | ', 'after' => ' | ', 'first' => 'first', 'last' => 'last'
            );
        }

        $defaults = array(
            'tag' => 'span', 'before' => null, 'after' => null, 'model' => $this->defaultModel(), 'class' => null,
            'modulus' => '8', 'separator' => ' | ', 'first' => null, 'last' => null, 'ellipsis' => '...',
            'currentClass' => 'current', 'currentTag' => null
        );
        $options += $defaults;

        $params = (array)$this->params($options['model']) + array('page' => 1);

        unset($options['model']);

        if ($params['pageCount'] <= 1) {
            return false;
        }

        extract($options);
        unset($options['tag'], $options['before'], $options['after'], $options['model'],
            $options['modulus'], $options['separator'], $options['first'], $options['last'],
            $options['ellipsis'], $options['class'], $options['currentClass'], $options['currentTag']
        );

        $out = '';

        if ($modulus && $params['pageCount'] > $modulus) {
            $half = intval($modulus / 2);
            $end = $params['page'] + $half;

            if ($end > $params['pageCount']) {
                $end = $params['pageCount'];
            }
            $start = $params['page'] - ($modulus - ($end - $params['page']));
            if ($start <= 1) {
                $start = 1;
                $end = $params['page'] + ($modulus - $params['page']) + 1;
            }

            if ($first && $start > 1) {
                $offset = ($start <= (int)$first) ? $start - 1 : $first;
                if ($offset < $start - 1) {
                    $out .= $this->first($offset, compact('tag', 'separator', 'ellipsis', 'class'));
                } else {
                    $out .= $this->first($offset, compact('tag', 'separator', 'class', 'ellipsis') + array('after' => $separator));
                }
            }

            $out .= $before;

            for ($i = 1; $i < $params['page']; $i++) {
                if ($i >= 3 && $i < ($params['page']-1)) {
                    if ($i == 3) {  
                        $out .= $this->Html->tag($tag,'<a>......</a>');
                    }
                    continue;
                }
                $out .= $this->Html->tag($tag, $this->link($i, array('page' => $i), $options), compact('class')) . $separator;
            }

            if ($class) {
                $currentClass .= ' ' . $class;
            }
            if ($currentTag) {
                $out .= $this->Html->tag($tag, $this->Html->tag($currentTag, $params['page']), array('class' => $currentClass));
            } else {
                $out .= $this->Html->tag($tag, $params['page'], array('class' => $currentClass));
            }
            if ($i != $params['pageCount']) {
                $out .= $separator;
            }

            $start = $params['page'] + 1;
            for ($i = $start; $i < $params['pageCount']; $i++) {
                if ($i == 3) {
                    $out .= $this->Html->tag($tag,'<a>......</a>');
                }
                if ($i>=3 && $i <= $end-3 ) {
                    continue ;
                }
                $out .= $this->Html->tag($tag, $this->link($i, array('page' => $i), $options), compact('class')) . $separator;
            }

            if ($end != $params['page']) {
                $out .= $this->Html->tag($tag, $this->link($i, array('page' => $end), $options), compact('class'));
            }

            $out .= $after;

            if ($last && $end < $params['pageCount']) {
                $offset = ($params['pageCount'] < $end + (int)$last) ? $params['pageCount'] - $end : $last;
                if ($offset <= $last && $params['pageCount'] - $end > $offset) {
                    $out .= $this->last($offset, compact('tag', 'separator', 'ellipsis', 'class'));
                } else {
                    $out .= $this->last($offset, compact('tag', 'separator', 'class', 'ellipsis') + array('before' => $separator));
                }
            }

        } else {
            $out .= $before;

            for ($i = 1; $i <= $params['pageCount']; $i++) {
                if ($i == $params['page']) {
                    if ($class) {
                        $currentClass .= ' ' . $class;
                    }
                    if ($currentTag) {
                        $out .= $this->Html->tag($tag, $this->Html->tag($currentTag, $i), array('class' => $currentClass));
                    } else {
                        $out .= $this->Html->tag($tag, $i, array('class' => $currentClass));
                    }
                } else {
                    $out .= $this->Html->tag($tag, $this->link($i, array('page' => $i), $options), compact('class'));
                }
                if ($i != $params['pageCount']) {
                    $out .= $separator;
                }
            }

            $out .= $after;
        }

        $options_str = '<select  style="width:200px;" class="redirect">
            <option>点击跳转</option>
        ';
        for ($i = 1; $i <= $params['pageCount']; $i++){
            $url = $this->url(array('page' => $i), $options);
            $str = "<option value=\"{$url}\">{$i}</option>";
            $options_str .= $str;
        }
        $options_str .= '</select>';

        $out .= $options_str;
        return $out;
    }
}

<?php

namespace app\helpers;

class PrintTree
{

    public static function run($tree)
    {
        return '<h5>Коренной вузел</h5>' . self::innerHtml($tree);
    }

    public static function innerHtml($tree)
    {

        $str = '';
        // если у текущей ветки дерева есть собственные ветки
        if (!is_null($tree) && count($tree) > 0) {
            $str .= '<ul>';
            // пробегаем по этим веткам, обозначая их
            foreach ($tree as $kn => $node) {
                $str .= '<li><a class="various" href="#inline">' . $node[$kn]['name'];
                // а также по своим веткам каждой обозначенной ветки
                $str .= self::innerHtml($node['children']);
                $str .= '</a> </li>';
            }
            $str .= '</ul>';
        }
        return $str;
    }
}


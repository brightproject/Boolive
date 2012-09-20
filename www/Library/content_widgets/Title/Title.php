<?php
/**
 * Виджет заголовка
 *
 * @version 1.0
 */
namespace Library\content_widgets\Title;

use Library\views\Widget\Widget;

class Title extends Widget
{
    public function work($v = array())
    {
        $v['value'] = $this->_input['GET']['object']->getValue();
        if ($parent = $this->_input['GET']['object']->parent()){
            $v['parent_uri'] = $parent['uri'];
            if (mb_substr($v['parent_uri'],0,10)=='/Contents/'){
                $v['parent_uri'] = mb_substr($v['parent_uri'],10);
            }
        }else{
            $v['parent_uri'] = '';
        }
        return parent::work($v);
    }
}
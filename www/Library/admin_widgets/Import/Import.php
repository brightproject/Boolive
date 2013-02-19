<?php
/**
 *
 */

namespace Library\admin_widgets\Import;
use Library\views\Widget\Widget,
    Boolive\values\Rule,
    Boolive\session\Session;

class Import extends Widget
{
    public function getInputRule()
    {
        return Rule::arrays(array(
            'REQUEST' => Rule::arrays(array(
                'object' => Rule::entity()->required(),
                'call' => Rule::string()->default('import')->required(),
                'path' => Rule::string()->required(),
            )),
        ));
    }

    public function work($v = array())
    {
        if ($this->_input['REQUEST']['call'] == 'import') {
            $rootPath = DOCUMENT_ROOT . DIR_WEB . substr($this->_input['REQUEST']['object']->uri(), 1);
            $rootObjectName = substr($this->_input['REQUEST']['path'], strrpos($this->_input['REQUEST']['path'], '/') + 1);
            $infoFile = $rootPath . '/' . $rootObjectName . '.info';

            if (is_readable($infoFile)) {
                /*if (Session::isExist('import')) {

                } else {

                }*/
                $this->processFile($infoFile);
            }
        }

        return parent::work($v);
    }

    public function processDir($dir)
    {
        $objectName = substr($dir, strrpos($dir, '/') + 1);
        $infoFile = $dir . '/' . $objectName . '.info';

        if (is_readable($infoFile)) {
            $attrs = $this->processFile($infoFile);
        }

        $files = scandir($dir);
        foreach ($files as $file) {
            $fullPath = $dir . '/' . $file;
            if ($file != '.' && $file != '..') {
                if (is_dir($fullPath)) {
                    $this->processDir($fullPath);
                } else if (is_readable($fullPath) && is_file($fullPath)) {
                    $this->processFile($fullPath);
                }
            }
        }
    }

    public function processFile($file)
    {
        $childs = array();
        $content = file_get_contents($file);
        $attrs = array('value', 'is_file', 'is_history', 'is_hidden',
            'is_link', 'is_virtual', 'proto', 'order', 'is_delete', 'is_link', 'uri');
        $file_content = file_get_contents($file);

        if ($content != null) {
            $json = json_decode($file_content, true);
            foreach ($json as $k => $val) {
                if (in_array($k, array_keys($attrs))) {
                    $attrs[$k] = $val;
                }
            }

            if (!isset($attrs['uri']) || $uri == null) {
                $attrs['uri'] = substr(str_replace(DOCUMENT_ROOT . DIR_WEB, '', $file), 0, -5);
                $attrs['uri'] = substr($attrs['uri'], 0, -(strlen($attrs['uri']) - strrpos($attrs['uri'], '/')));
            }

            if (!isset($attrs['proto']) || $attrs['proto'] == null) {
                $proto = '\Boolive\data\Entity';
            }

            $parent = substr($attrs['uri'], 0, -(strlen($attrs['uri']) - strrpos($attrs['uri'], '/')));
            $proto = new $attrs['proto']();
            $obj = $proto->birth($parent);
            $obj->save(false, false, $error);

            die($attrs['uri']);
        }

        //$object = Data::read($attrs['proto'])
    }
}

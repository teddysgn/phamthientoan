<?php
namespace System\Core;

abstract class BaseBlock
{   
    protected $name; // Tên slug block
    protected $label; // Ten block
    protected $props = []; // Các thuộc tính của block

    // Trả về tên block, ví dụ "HeaderBlock"
    protected function getName(){
        return ucfirst($this->name);
    }
    protected function setName($value){
        $this->name = $value;
    }
    protected function getLabel(){
        return $this->label;
    }
    protected function setLabel($value){
        $this->label = $value;
    }
    public function setProps(array $props) {
        $this->props = array_merge($this->props, $props);
        return $this;
    }
    protected function getProps() {
        return $this->props;
    }

    // handle data đưa về định dạng mà layout file cần
    abstract public function handleData();

    public function render() {
        $layout = !empty($this->props['layout']) ? $this->props['layout'] : 'default';
        $themeName = config('theme');
        $themeName = $themeName['theme_name'] ?? 'default';
        echo $themeBlockPath = APP_PATH . 'Views/'.$themeName.'/' . 'Blocks/' . $this->getName() . '/' . $layout . '.php';
        if (!file_exists($themeBlockPath)) {
            $themeBlockPath = APP_PATH . 'Blocks/' . $this->getName() . '/Views/' . $layout . '.php';
        }
        if(file_exists($themeBlockPath)) {
            $data = $this->handleData();
            extract($data);
            include $themeBlockPath;
        } else {
            echo "File Layout: $layout.php of Block ". $this->getName() . " not found!";
        }
    }
    public function json() {
        $layout = !empty($this->props['layout']) ? $this->props['layout'] : 'default';
        $themeName = config('theme');
        $themeName = $themeName['theme_name'] ?? 'default';
        $themeBlockPath = APP_PATH . 'Views/'.$themeName.'/' . 'Blocks/' . $this->getName() . '/' . $layout . '.php';
        if (!file_exists($themeBlockPath)) {
            $themeBlockPath = APP_PATH . 'Blocks/' . $this->getName() . '/Views/' . $layout . '.php';
        }
        
        if(file_exists($themeBlockPath)) {
            $data = $this->handleData();
            extract($data);
            ob_start();
            include $themeBlockPath;
            $preview_html = ob_get_clean();
        } else {
            $preview_html = "File Layout: $layout.php of Block ". $this->getName() . " not found!";
        }
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'props' => $this->getProps(),
            'preview' => $preview_html
        ];
    }
}

<?php

namespace System\Commands;

class BlockCommand
{
    /**
     * Create a new model file
     */
    public function create($blockName)
    {
        // Define the path for the new model
        $blockName = ucfirst($blockName);
        $blockPath = ROOT_PATH . '/application/Blocks/' . ucfirst($blockName) . '/' . ucfirst($blockName) . 'Block.php';
        $blockViewsPath = ROOT_PATH . '/application/Blocks/' . ucfirst($blockName) . '/Views/default.php';

        $className = $namespace = $blockName;
        //  = $blockName;
        if (strpos($blockName, '/')) {
            $temp = explode('/', $blockName);
            $name = end($temp);
            unset($temp[count($temp) - 1]);
            $path = implode('/', $temp);
            $blockPath = ROOT_PATH . '/application/Blocks/' . ucfirst($blockName) . '/' . ucfirst($name) . 'Block.php';
            $blockViewsPath = ROOT_PATH . '/application/Blocks/' . ucfirst($path) . '/' . ucfirst($name) . '/Views/default.php';

            $className = $name;
            // $namespace = implode("\\", $temp);
            $namespace = str_replace("/", "\\", $blockName);
        }


        // Check if the model already exists
        if (file_exists($blockPath)) {
            echo "Block {$blockName} already exists.\n";
            return;
        }

        // Define the contents of the block controller
        $blockContent = <<<PHP
<?php

namespace App\Blocks\\{$namespace};

use System\Core\BaseBlock;

class {$className}Block extends BaseBlock
{

    public function __construct()
    {
        \$this->setLabel('{$className} Block');
        \$this->setName('{$namespace}');
        \$this->setProps([
            'layout'      => 'default',
        ]);
    }

    /**
     * Funtion process and  extract data to block view
     * @return variables be extracted from data
     * */
    public function handleData()
    {   \$props = \$this->getProps();
        \$data = \$props;
        return \$data;
    }
}

PHP;

        // Create the block control file
        $viewsDir11 = dirname($blockPath);
        if (!is_dir($viewsDir11) && !mkdir($viewsDir11, 0777, true) && !is_dir($viewsDir11)) {
            echo "Không thể tạo thư mục Views.\n";
            return;
        }
        file_put_contents($blockPath, $blockContent);

        // create block default view
        $viewsDir = dirname($blockViewsPath);
        if (!is_dir($viewsDir) && !mkdir($viewsDir, 0777, true) && !is_dir($viewsDir)) {
            echo "Không thể tạo thư mục Views.\n";
            return;
        }
        file_put_contents($blockViewsPath, '');


        echo "Block {$blockName}Block has been created successfully.\n";
    }
}

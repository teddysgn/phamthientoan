<?php

namespace App\Models;

use System\Core\BaseModel;

class FilesModel extends BaseModel
{

    protected $table = 'fast_files';

    // Columns that are fillable (can be added or modified)
    protected $fillable = ['name', 'path', 'type', 'size', 'autoclean', 'created_at', 'updated_at'];
    // Columns that are guarded (cannot be modified)
    protected $guarded = ['id'];

    /**
     * Define the table schema
     * 
     * @return array Table schema
     */
    public function _schema()
    {
        return [
            'id' => [
                'type' => 'int(10) unsigned',
                'auto_increment' => true,
                'key' => 'primary',
                'null' => false
            ],
            'name' => [
                'type' => 'varchar(150)',
                'null' => false,
                'default' => ''
            ],
            'path' => [
                'type' => 'varchar(255)',
                'null' => false,
                'default' => ''
            ],
            'type' => [
                'type' => 'varchar(50)',
                'null' => false,
                'default' => ''
            ],
            'size' => [
                'type' => 'bigint(20)',
                'null' => false,
                'default' => 0
            ],
            'autoclean' => [
                'type' => 'tinyint(1)',
                'null' => false,
                'default' => 0
            ],
            'post_used' => [
                'type' => 'json',
                'null' => true,
                'default' => '{}'
            ],
            'created_at' => [
                'type' => 'datetime',
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP'
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP'
            ]
        ];
    }

    /**
     * Fetches paginated files from the database with optional conditions and ordering
     *
     * @param string $where
     * @param array $params
     * @param string $orderBy
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getFiles($where = '', $params = [], $orderBy = 'id DESC', $page = 1, $limit = null)
    {
        return $this->listpaging($this->table, $where, $params, $orderBy, $page, $limit);
    }

    /**
     * Get file by ID
     *
     * @param int $id
     * @return array|false
     */
    public function getFileById($id)
    {
        return $this->row($this->table, 'id = ?', [$id]);
    }

    public function getFileByPath($path)
    {
        return $this->row($this->table, 'path = ?', [$path]);
    }

    /**
     * Add a new file
     *
     * @param array $data
     * @return bool
     */
    public function addFile($data)
    {
        $data = $this->fill($data);
        return $this->add($this->table, $data);
    }

    /**
     * Update file information
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updateFile($id, $data)
    {
        $data = $this->fill($data);
        return $this->set($this->table, $data, 'id = ?', [$id]);
    }

    /**
     * Delete a file
     *
     * @param int $id
     * @return int
     */
    public function deleteFile($id)
    {
        return $this->del($this->table, 'id = ?', [$id]);
    }

    // fdrxoi viết dỡ

    public function replacePath($oldPath, $newPath)
    {

        // Cấu trúc câu lệnh SQL để cập nhật đường dẫn
        $sql = "UPDATE {$this->table} 
                SET path = CONCAT(?, SUBSTRING(path, LENGTH(?) + 1)) 
                WHERE path LIKE ?";

        // Tham số cho câu lệnh SQL
        $params = [
            $newPath,          // Giá trị mới để nối vào path
            $oldPath,          // Giá trị oldPath để tính độ dài
            $oldPath . '%'     // Điều kiện để tìm các path bắt đầu bằng oldPath
        ];

        // Thực thi câu lệnh SQL
        $affectedRows = $this->execute($sql, $params);

        // Kiểm tra xem có bản ghi nào được cập nhật không
        if ($affectedRows === 0) {
            return ['error', 'No files found with the specified old path prefix.'];
        }

        // Thông báo thành công
        return ['success', "Successfully replaced paths from '{$oldPath}' to '{$newPath}'. Total records updated: {$affectedRows}."];
    }

    /**
     * Generate and insert 100 rows of demo data
     */
    public function generateDemoData()
    {
        $filePaths = [
            '2024/10/23_copy1/avatar2_copy.png',
            '2024/10/23_copy1/avatar2.png',
            '2024/10/23_copy1/bg-intro.png',
            '2024/10/23_copy1/placeholder.png',
            '2024/10/23_copy1/placeholder.svg'
        ];

        $fileTypes = ['png', 'jpg', 'jpeg', 'gif', 'svg'];
        $currentTime = date('Y-m-d H:i:s');

        for ($i = 0; $i < 100; $i++) {
            // Randomly select a file path
            $randomPath = $filePaths[array_rand($filePaths)];

            // Extract file name and type from the path
            $pathParts = pathinfo($randomPath);
            $name = $pathParts['basename'];
            $type = $pathParts['extension'];

            // Randomize size between 10KB to 5MB
            $size = rand(10240, 5242880); // Size in bytes

            // Randomize created_at and updated_at within the past year
            $createdAt = date('Y-m-d H:i:s', strtotime("-" . rand(0, 365) . " days"));
            $updatedAt = date('Y-m-d H:i:s', strtotime($createdAt . " +" . rand(0, 30) . " days"));

            // Prepare data
            $fileData = [
                'name' => $name,
                'path' => $randomPath,
                'type' => $type,
                'size' => $size,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];

            // Insert into database
            $this->addFile($fileData);
        }
    }
}

<?php

namespace App\Models;

use System\Core\BaseModel;

class UsersModel extends BaseModel
{

    protected $table = 'user';

    // Các cột được phép thêm hoặc sửa
    protected $fillable = ['username', 'password'];

    // Các cột không được phép sửa
    protected $guarded = ['id'];

    /**
     * Định nghĩa cấu trúc bảng với schema builder
     * 
     * @return array Cấu trúc bảngP
     */
    public function _schema()
    {
        return [
            'id' => [
                'type' => 'int unsigned',
                'auto_increment' => true,
                'key' => 'primary',
                'null' => false
            ],
            'username' => [
                'type' => 'varchar(40)',
                'key' => 'unique',
                'null' => false
            ],
            'password' => [
                'type' => 'varchar(255)',
                'null' => false
            ],
        ];
    }

    /**
     * Lấy tất cả người dùng
     * 
     * @param string|null $where Điều kiện truy vấn (tùy chọn)
     * @param array $params Mảng giá trị tương ứng với chuỗi điều kiện
     * @param string|null $orderBy Sắp xếp theo cột (tùy chọn)
     * @param int|null $limit Giới hạn số lượng kết quả (tùy chọn)
     * @return array Danh sách người dùng
     */
    public function getUsers($where = '', $params = [], $orderBy = 'id DESC', $page = 1, $limit = null)
    {
        return $this->list($this->table, $where, $params, $orderBy, $page, $limit);
    }

    /**
     * Lấy thông tin người dùng theo ID
     * 
     * @param int $id ID người dùng
     * @return array|false Thông tin người dùng hoặc false nếu không tìm thấy
     */
    public function getUserById($id)
    {
        return $this->row($this->table, 'id = ?', [$id]);
    }

    public function getUserByQuery($fields, $query)
    {
        $sql = "SELECT {$fields} FROM user {$query}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

}

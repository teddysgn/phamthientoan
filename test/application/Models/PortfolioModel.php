<?php

namespace App\Models;

use System\Core\BaseModel;

class PortfolioModel extends BaseModel
{
    protected $table;

    public function __construct($posttype = '', $lang = null)
    {
        // Gọi hàm khởi tạo của lớp cha để đảm bảo các thiết lập trong BaseModel được thực hiện
        parent::__construct();
        if (!empty($posttype) && (empty($lang) || $lang === 'all')) {
            $this->table = $posttype;
        } elseif (!empty($posttype) && !empty($lang)) {
            $this->table = $posttype . '_' . $lang;
        }
    }
    public function Lists()
    {
        return $this->listpaging($this->table);
    }

    public function ListsFields($fields = '*', $where = '', $params = [], $orderBy = 'id DESC', $page = 1, $limit = null)
    {
        return $this->listfieldpaging($this->table, $fields, $where, $params, $orderBy, $page, $limit);
    }
    public function getAllPosts($tableName)
    {
        return $this->list($tableName);
    }

    public function getById($id, $fields = "*")
    {
        $sql = "SELECT $fields FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getPostById($tableName, $id, $fields = "*")
    {
        $sql = "SELECT $fields FROM {$tableName} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    public function getPostByQuery($tableName, $fields, $query)
    {
        $sql = "SELECT {$fields} FROM {$tableName} {$query}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    public function getPostBySlug($tableName, $slug)
    {
        $sql = "SELECT * FROM {$tableName} WHERE slug = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    public function getBySlug($slug)
    {
        $sql = "SELECT * FROM {$this->table} WHERE slug =?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function addPost($tableName, $data)
    {
        return $this->add($tableName, $data);
    }

    public function insert($data)
    {
        return $this->add($this->table, $data);
    }

    public function editPostTable($id, $data)
    {
        return $this->set($this->table, $data, 'id = ?', [$id]);
    }

    public function editPost($tableName, $id, $data)
    {
        return $this->set($tableName, $data, 'id = ?', [$id]);
    }
    public function deletePost($tableName, $id)
    {
        return $this->del($tableName, 'id = ?', [$id]);
    }

    public function addLoaction($tableName, $id, $column, $location = []) {}

    public function getCurrentID(array $tablesName)
    {
        if (empty($tablesName)) {
            throw new \InvalidArgumentException("Mảng tên bảng không được để trống.");
        }

        // Kiểm tra tính hợp lệ của từng tên bảng
        foreach ($tablesName as $table) {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
                throw new \InvalidArgumentException("Tên bảng không hợp lệ: {$table}");
            }
        }

        // Tạo phần UNION ALL cho từng bảng
        $unionQueries = [];
        foreach ($tablesName as $table) {
            $unionQueries[] = "SELECT '{$table}' AS table_name, MAX(id) AS max_id FROM {$table}";
        }

        // Kết hợp các phần truy vấn bằng UNION ALL
        $fullQuery = "WITH max_ids AS (
          " . implode("\n        UNION ALL\n        ", $unionQueries) . "
      )
      SELECT table_name, max_id
      FROM max_ids
      WHERE max_id IS NOT NULL
      ORDER BY max_id DESC
      LIMIT 1;";

        try {
            $stmt = $this->db->prepare($fullQuery);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($result) {
                return $result['max_id'];
            } else {
                return null; // Không tìm thấy kết quả
            }
        } catch (\PDOException $e) {
            // Xử lý lỗi truy vấn
            throw new \RuntimeException("Lỗi khi thực hiện truy vấn: " . $e->getMessage());
        }
    }

    public function duplicateLanguage($tablesName, $oldTableName, $id)
    {

        $sql = "START TRANSACTION;";

        foreach ($tablesName as $tableName) {
            $sql .= "    INSERT INTO $tableName \n";
            $sql .= "    SELECT * \n";
            $sql .= "    FROM $oldTableName \n";
            $sql .= "    WHERE id = $id; \n";
        }

        $sql .= "COMMIT;";
        return $this->query($sql);
    }

    public function getPostIdByTerm($posttype, $term_id, $lang = LANG)
    {
        $tableRel = "fast_posts_{$posttype}_rel";
        $sql = "SELECT post_id 
            FROM {$tableRel} 
            WHERE term_id = {$term_id} 
              AND (lang = '{$lang}' OR lang = 'all')";
        return $this->query($sql);
    }

    public function getPostTermsByPostId($posttype, $post_id, $lang = LANG)
    {
        $table = "fast_posts_{$posttype}_rel";
        $query = "SELECT * FROM fast_terms WHERE lang =? AND id_main IN (SELECT term_id FROM {$table} WHERE post_id= ? AND (lang='all' OR lang= ? )) LIMIT 999;";
        $params = [$lang, $post_id, $lang];
        return $this->query($query, $params);
    }

    public function getTermsbyPostID($tableName, $post_id)
    {
        $sql = "SELECT `term_id` FROM `$tableName` WHERE `post_id`= $post_id";
        return $this->query($sql);
    }
    public function getTermsbyPostIDAndLang($tableName, $post_id, $lang)
    {
        $sql = "SELECT `term_id` 
            FROM `$tableName` 
            WHERE `post_id` = ? 
              AND (`lang` = ? OR `lang` = 'all') AND `term_id` IS NOT NULL";

        return $this->query($sql, [$post_id, $lang]);
    }

    public function createRelationship($tableName, $post_id, $term_id, $lang)
    {
        $sql = "INSERT INTO `$tableName` (`post_id`, `term_id`, `lang`) VALUES ("
            . intval($post_id) . ", "
            . intval($term_id) . ", '"
            . $lang . "')";
        return $this->query($sql);
    }

    public function addReferenceRelationship($tableName, $post_id, $postype_slug, $field_id,  $post_rel_id, $lang)
    {
        $sql = "INSERT INTO `$tableName` (`post_id`, `postype_slug`, `post_rel_id`, `field_id`, `lang`) VALUES ("
            . intval($post_id) . ", '"
            . $postype_slug . "', "
            . intval($post_rel_id) . ", "
            . intval($field_id) . ", '"
            . $lang . "')";
        return $this->query($sql);
    }

    public function deleteReferenceRelationship(
        $tableName,
        $post_id = null,
        $postype_slug = null,
        $field_id = null,
        $post_rel_id = null,
        $lang = null
    ) {
        $conditions = [];
        $params = [];

        // Build conditions dynamically based on non-null parameters
        if ($post_id !== null) {
            $conditions[] = "post_id = :post_id";
            $params[':post_id'] = $post_id;
        }

        if ($postype_slug !== null) {
            $conditions[] = "postype_slug = :postype_slug";
            $params[':postype_slug'] = $postype_slug;
        }

        if ($field_id !== null) {
            $conditions[] = "field_id = :field_id";
            $params[':field_id'] = $field_id;
        }

        if ($post_rel_id !== null) {
            $conditions[] = "post_rel_id = :post_rel_id";
            $params[':post_rel_id'] = $post_rel_id;
        }

        if ($lang !== null) {
            $conditions[] = "lang = :lang";
            $params[':lang'] = $lang;
        }

        // Construct the SQL query
        $sql = "DELETE FROM `$tableName`";
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        // Prepare and execute the query
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $type = is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $type);
        }

        return $stmt->execute();
    }
    public function getAllPostIdByRenference($tableName, $postype_slug, $field_id, $post_rel_id, $lang = null, $allLang = true)
    {
        // Tạo điều kiện truy vấn cơ bản
        $query = "WHERE postype_slug = :postype_slug 
              AND field_id = :field_id 
              AND post_rel_id = :post_rel_id";

        // Thêm điều kiện lang nếu được chỉ định
        if ($lang !== null) {
            if ($allLang) {
                $query .= " AND (lang = :lang OR lang = 'all')";
            } else {
                $query .= " AND lang = :lang";
            }
        }

        // Tạo câu lệnh SQL - chỉ lấy post_id
        $sql = "SELECT post_id FROM {$tableName} {$query}";
        $stmt = $this->db->prepare($sql);

        // Gán giá trị tham số
        $stmt->bindParam(':postype_slug', $postype_slug, \PDO::PARAM_STR);
        $stmt->bindParam(':field_id', $field_id, \PDO::PARAM_INT);
        $stmt->bindParam(':post_rel_id', $post_rel_id, \PDO::PARAM_INT);

        if ($lang !== null) {
            $stmt->bindParam(':lang', $lang, \PDO::PARAM_STR);
        }

        // Thực thi và trả về kết quả
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN); // Trả về mảng các post_id
    }

    public function getAllRelPostByPostId($tableName, $postype_slug, $field_id, $post_id, $lang = null, $allLang = true)
    {
        // Tạo điều kiện truy vấn cơ bản
        $query = "WHERE post_id = :post_id 
              AND postype_slug = :postype_slug 
              AND field_id = :field_id";

        // Thêm điều kiện lang nếu được chỉ định
        if ($lang !== null) {
            if ($allLang) {
                $query .= " AND (lang = :lang OR lang = 'all')";
            } else {
                $query .= " AND lang = :lang";
            }
        }
        // Tạo câu lệnh SQL - sửa thành chỉ lấy post_rel_id
        $sql = "SELECT post_rel_id FROM {$tableName} {$query}";
        $stmt = $this->db->prepare($sql);

        // Gán giá trị tham số
        $stmt->bindParam(':post_id', $post_id, \PDO::PARAM_INT);
        $stmt->bindParam(':postype_slug', $postype_slug, \PDO::PARAM_STR);
        $stmt->bindParam(':field_id', $field_id, \PDO::PARAM_INT);

        if ($lang !== null) {
            $stmt->bindParam(':lang', $lang, \PDO::PARAM_STR);
        }

        // Thực thi và trả về kết quả
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN); // Trả về mảng các post_rel_id
    }

    public function removeTerms($tableName, $post_id, $term_id)
    {
        $sql = "DELETE FROM `$tableName` WHERE `post_id` = $post_id AND `term_id` = $term_id";
        return $this->query($sql);
    }

    public function removeTermRelsbyPost($tableName, $post_id)
    {
        $sql = "DELETE FROM `$tableName` WHERE `post_id` = $post_id AND `term_id` IS NOT NULL";
        return $this->query($sql);
    }
    public function removeTermRelsbyPostAndLang($tableName, $post_id, $lang)
    {
        $sql = "DELETE FROM `$tableName` WHERE `post_id` = $post_id AND `lang` = '$lang' AND `term_id` IS NOT NULL";
        return $this->query($sql);
    }
    public function checkPosttypeExists()
    {
        $query = "SHOW TABLES LIKE '{$this->table}'";
        $result = $this->db->query($query);
        return $result && count($result) > 0;
    }
    public function getPostByIdTable($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // get posts by list id
    public function getPostsByIds($ids)
    {
        $id = implode(',', $ids);
        $where = "id IN ({$id})";

        return $this->listpaging($this->table, $where);
    }
}

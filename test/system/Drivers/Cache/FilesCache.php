<?php
namespace System\Drivers\Cache;

/**
 * Class FilesCache
 * Cache đơn giản dạng key-value bằng file
 */
class FilesCache extends Cache {

    protected $cacheDir;

    /**
     * Kết nối đến hệ thống lưu trữ file
     */
    protected function connect() {
        $this->cacheDir = ROOT_PATH . '/writeable/Cache/';
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    /**
     * Lưu giá trị vào cache
     *
     * @param string $key
     * @param mixed $value
     * @param int $ttl Thời gian sống (giây)
     * @return bool
     */
    public function set($key, $value, $ttl = 3600) {
        $cacheFile = $this->cacheDir . md5($key);
        $data = [
            'value' => serialize($value),
            'expiry' => time() + $ttl
        ];
        return file_put_contents($cacheFile, json_encode($data)) !== false;
    }

    /**
     * Lấy giá trị từ cache
     *
     * @param string $key
     * @return mixed|null
     */
    public function get($key) {
        $cacheFile = $this->cacheDir . md5($key);
        if (file_exists($cacheFile)) {
            $data = @json_decode(file_get_contents($cacheFile), true);
            if (isset($data['expiry']) && $data['expiry'] > time()) {
                return unserialize($data['value']);
            }
            $this->delete($key);
        }
        return null;
    }

    /**
     * Xoá cache theo key
     *
     * @param string $key
     * @return bool
     */
    public function delete($key) {
        $cacheFile = $this->cacheDir . md5($key);
        return file_exists($cacheFile) ? @unlink($cacheFile) : false;
    }

    /**
     * Kiểm tra có key trong cache không
     *
     * @param string $key
     * @return bool
     */
    public function has($key) {
        $cacheFile = $this->cacheDir . md5($key);
        return file_exists($cacheFile);
    }

    /**
     * Xoá toàn bộ cache
     *
     * @return bool
     */
    public function clear() {
        $files = glob($this->cacheDir . '*');
        foreach ($files as $file) {
            @unlink($file);
        }
        return true;
    }
}

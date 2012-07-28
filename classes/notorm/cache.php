<?php

namespace NotORM;

/** Loading and saving data, it's only cache so load() does not need to block until save()
 */
class NotORM_Cache {

    private $expiration = null;
    private $base = '';

    public function __construct($key, $expiration = null) {
        $this->expiration = $expiration;
        $this->base = $base;
    }

    /** Load stored data
     * @param string
     * @return mixed or null if not found
     */
    public function load($key) {
        try {
            return \Cache::get("{$this->base}.{$key}");
        } catch (\CacheNotFoundException $e) {
            return null;
        }
    }

    /** Save data
     * @param string
     * @param mixed
     * @return null
     */
    public function save($key, $data) {
        \Cache::set("{$this->base}.{$key}", $data, $this->expiration);
    }

}

// eAccelerator - user cache is obsoleted




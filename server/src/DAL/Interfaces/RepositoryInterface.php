<?php

namespace DAL\Interfaces;

interface RepositoryInterface {
    public function getById($id, $fields = '*');
    public function get($condiction = null, $fields = '*', $limit = null, $offset = null);
    public function post($tableName, $data);
    public function put($tableName, $condiction, $data);
    public function delete($tableName, $condiction);
    public function query($sql);
}
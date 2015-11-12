<?php

namespace FRD\Interfaces;

interface DbModelInterface {
    public function getById($id, $fields = '*');
    public function get($condiction = null, $fields = '*', $limit = null, $offset = null);
    public function post($data);
    public function put($condiction, $data);
    public function delete($condiction);
    public function query($sql);
}
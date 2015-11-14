<?php

namespace FRD\Interfaces;

interface DbModelInterface {
    public function getById($id, $fields = '*');
    public function get($condiction = null, $fields = '*', $page = 1, $itemPerPage = 10);
    public function post($data);
    public function put($condiction, $data);
    public function delete($condiction);
    public function query($sql);
}
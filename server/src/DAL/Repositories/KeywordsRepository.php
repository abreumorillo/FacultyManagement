<?php

namespace FRD\DAL\Repositories;

use FRD\DAL\Repositories\base\BaseRepository;
use FRD\Model\Keyword;

/**
* Keywords repository
*/
class KeywordsRepository extends BaseRepository
{
    private $keyword;

    function __construct(argument)
    {
        parent::__construct();
        $this->keyword = new Keyword();

    }

}
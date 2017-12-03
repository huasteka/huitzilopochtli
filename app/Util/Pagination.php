<?php
namespace App\Util;

use Illuminate\Http\Request;

class Pagination
{
    const DEFAULT_PAGE_SIZE = 10;

    private $pageSize;

    public function __construct(Request $request)
    {
        $this->pageSize = $request->get('page_size', self::DEFAULT_PAGE_SIZE);
    }

    public function getPageSize()
    {
        return $this->pageSize;
    }

    public static function getInstance(Request $request)
    {
        return new Pagination($request);
    }
}

<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\BaseController;
use App\Services\StaffService;
use Illuminate\Http\Request;

class StaffController extends BaseController
{
    /** @var  StaffService */
    protected $staff_service;

    /**
     * 初始化数据
     * EnterpriseController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->staff_service = new StaffService();
    }
}

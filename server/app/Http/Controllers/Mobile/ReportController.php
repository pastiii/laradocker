<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\BaseController;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends BaseController
{
    /** @var  ReportService */
    protected $report_service;

    /**
     * 初始化数据
     * EnterpriseController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->report_service = new ReportService();
    }

    public function AreaReport()
    {
        $result = $this->report_service->AreaReport($this->is_page, $this->per_page);

        return $this->Response($result);
    }

    public function TotalReport()
    {
        $result = $this->report_service->TotalReport();

        return $this->Response($result);
    }
}

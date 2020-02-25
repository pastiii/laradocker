<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\StatisticsService;
use Illuminate\Http\Request;

class StatisticsController extends BaseController
{
    /** @var  StatisticsService */
    protected $statistics_service;

    /**
     * 初始化数据
     * EnterpriseController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->statistics_service = new StatisticsService();
    }

    /**
     * 企业
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     */
    public function Enterprise()
    {
        $result = $this->statistics_service->Enterprise($this->params, $this->is_page, $this->per_page);

        return $this->Response($result);
    }

    /**
     * 地区
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     */
    public function Region()
    {
        $result = $this->statistics_service->Region($this->params, $this->is_page, $this->per_page);

        return $this->Response($result);
    }

    /**
     * 总计
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     */
    public function Total()
    {
        $result = $this->statistics_service->Total();

        return $this->Response($result);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends BaseController
{
    /** @var  ReportService */
    protected $report_service;

    /** @var array 字段别名 */
    protected $custom = [
        'contact_num' => '高危接触人数',
        'routine'     => '例行隔离人数',
        'suspected'   => '疑似隔离人数',
        'diagnosis'   => '确诊人数',
        'death'       => '死亡人数',
        'overview'    => '区域发布数据',
    ];

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

    public function region()
    {
        $result = $this->report_service->Region();

        return $this->Response($result);
    }

    public function Overview()
    {
        $result = $this->report_service->Overview();

        return $this->Response($result);
    }

    public function Publishing(Request $request)
    {
        #校验规则
        $rule = [
            'contact_num' => 'required|int',
            'routine'     => 'required|int',
            'suspected'   => 'required|int',
            'diagnosis'   => 'required|int',
            'death'       => 'required|int',
            'overview'    => 'required|array',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        $result = $this->report_service->Publishing($this->params);

        return $this->Response($result);
    }
}

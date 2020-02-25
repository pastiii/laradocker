<?php

namespace App\Http\Controllers;

use App\Exceptions\ExampleException;
use App\Services\AreaService;
use App\Services\EnterpriseService;
use App\Traits\ApiRequestTrait;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CommonController extends BaseController
{
    use ApiRequestTrait, ResponseTrait;

    /** @var  AreaService */
    protected $area_service;

    /** @var array 字段别名 */
    protected $custom = [
        'exc_file' => '文件',
        'phone'    => '手机号码',
        'type'     => '文件类型',
    ];

    /**
     * 初始化数据
     * EnterpriseController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->area_service = new AreaService();
    }

    /**
     * 地区下拉菜单
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     */
    public function AreaSelect()
    {
        #获取数据
        $result = $this->area_service->AreaSelect();

        return $this->Response($result);
    }

    /**
     * 文件上传
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ExampleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function UploadFile(Request $request)
    {
        #校验规则
        $rule = [
            'exc_file' => 'required|file',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        #允许上传文件格式
        $file_array = [
            'xls', 'xlsx'
        ];

        $file = $request->file('exc_file');

        #获取文件后缀并校验
        $suffix = $file->getClientOriginalExtension();

        if (!in_array($suffix, $file_array)) {
            throw new ExampleException(CHINESE_MSG[FILE_ERROR], FILE_ERROR);
        }

        #将文件移动到指定文件夹
        $file_path = $file->getRealPath();
        $file_name = date('Y-m-d') . '/' . time() . rand(10000, 99999) . '.' . $suffix;

        $ret = Storage::disk('public')->put($file_name, file_get_contents($file_path));

        if (!$ret) {
            throw new ExampleException(CHINESE_MSG[RETURN_FILED], RETURN_FILED);
        }

        $data = [
            'status' => RETURN_SUCCESS,
            'data'   => ['file_url' => '/storage' . '/app/public/' . $file_name],
            'msg'    => CHINESE_MSG[RETURN_SUCCESS],
        ];

        return $this->Response($data);
    }

    /**
     * 文件上传
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ExampleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function UploadDoc(Request $request)
    {
        #校验规则
        $rule = [
            'doc_file' => 'required|file',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        #允许上传文件格式
        $file_array = [
            'doc', 'docx'
        ];

        $file = $request->file('doc_file');

        #获取文件后缀并校验
        $suffix = $file->getClientOriginalExtension();

        if (!in_array($suffix, $file_array)) {
            throw new ExampleException(CHINESE_MSG[RETURN_FILED_T], RETURN_FILED_T);
        }

        #将文件移动到指定文件夹
        $file_path = $file->getRealPath();
        $file_name = 'apply/' . date('Y-m-d') . '/' . time() . rand(10000, 99999) . '.' . $suffix;

        $ret = Storage::disk('public')->put($file_name, file_get_contents($file_path));

        if (!$ret) {
            throw new ExampleException(CHINESE_MSG[RETURN_FILED], RETURN_FILED);
        }

        $data = [
            'status' => RETURN_SUCCESS,
            'data'   => ['file_url' => '/app/public/' . $file_name],
            'msg'    => CHINESE_MSG[RETURN_SUCCESS],
        ];

        return $this->Response($data);
    }

    /**
     * 下载模板
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function DownFile()
    {
        $file = public_path() . '/down/Template.xls';
        return response()->download($file, 'Template.xls');
    }


    /**
     * 复工文件模板
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function DownDoc(Request $request)
    {
        #校验规则
        $rule = [
            'type' => 'required|int',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);


        switch ($this->params['type']) {
            case 1:
                $file      = public_path() . '/down/file_one.doc';
//                $file =  storage_path().'/app/public/apply/2020-02-14/158169281213634.docx';
                $file_name = 'file_one.doc';
                break;
            case 2:
                $file      = public_path() . '/down/file_two.doc';
                $file_name = 'file_two.doc';
                break;
            case 3:
                $file      = public_path() . '/down/file_three.doc';
                $file_name = 'file_three.doc';
                break;
            default:
                $file      = public_path() . '/down/file_one.doc';
                $file_name = 'file_one.doc';
        }

        return response()->download($file, $file_name);
    }

    /**
     * 发送验证码
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function SendSms(Request $request)
    {
        #校验规则
        $rule = [
            'phone' => 'required|regex:/^1\d{10}$/',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        if (is_array($this->params['phone'])) {
            $mobile = implode(',', $this->params['phone']);
        } else {
            $mobile = $this->params['phone'];
        }

        $code    = rand(1000, 9999);
        $content = '【乐亭县工业和信息化局】验证码 【' . $code . '】，您正在登录企业复工防疫系统，5分钟内有效。若非本人操作，请勿泄漏。';

        $argv   = [
            'action'   => 'send',
            'account'  => SMS_USER,
            'password' => SMS_PWD,
            'mobile'   => $mobile,
            'content'  => $content,
            'extno'    => SMS_EXTNO,
            'rt'       => 'json',
        ];
        $flag   = 0;
        $params = "";
        foreach ($argv as $key => $value) {
            if ($flag != 0) {
                $params .= "&";
            }
            $params .= $key . "=" . urlencode($value);
            $flag   = 1;
        }

        $url = '?' . $params;
        $res = $this->SendRequest($url, 'GET', [], SMS_URL);

        if ($res['data']['status'] == 0) {
            #将验证码存入缓存
            Cache::put($mobile, $code, 300);
            return $this->HandleData('edit', true);
        } else {
            return $this->HandleData('other', false);
        }
    }

    /**
     * 上报企业列表
     * @return \Illuminate\Http\JsonResponse
     * @throws ExampleException
     */
    public function ApplyEnterprise()
    {
        #获取数据
        $result = (new EnterpriseService())->ApplyEnterprise();

        return $this->Response($result);
    }

    /**
     * 下载文件
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function FileDown(Request $request)
    {
        #校验规则
        $rule = [
            'file_url'  => 'required|string',
            'file_name' => 'required|string',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        $file =  storage_path().$this->params['file_url'];


        return response()->download($file, $this->params['file_name']);
    }
}

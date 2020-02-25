<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ExampleException;
use App\Http\Controllers\BaseController;
use App\Models\demo\DemoModel;
use App\Traits\ApiRequestTrait;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class DemoController extends BaseController
{
    use ApiRequestTrait, ResponseTrait;

    public function Demo(Request $request)
    {
        $model = new DemoModel();
        $where = [
            'id' => 1,
        ];
        return $model->GetOne('*', $where);
        $data = [
//            [
//                'id'       => 'xiaoming',
//                'email'    => '593343662@qq.com',
//                'password' => '1234546',
//                'name'     => '1234546',
////            'id' => [1, 3],
////            'join' => "(select * from user) as asd"
//            ],
//            [
//                'id'       => 'xiaoming',
//                'email'    => '5933435642@qq.com',
//                'password' => '1234546',
//                'name'     => '1234546',
////            'id' => [1, 3],
////            'join' => "(select * from user) as asd"
//            ],
//            [
//                'id'       => 'xiaoming',
//                'email'    => '593343661@qq.com',
//                'password' => '1234546',
//                'name'     => '1234546',
////            'id' => [1, 3],
////            'join' => "(select * from user) as asd"
//            ],
//            [
//                'id'       => 'xiaoming',
//                'email'    => '5933435641@qq.com',
//                'password' => '1234546',
//                'name'     => '1234546',
////            'id' => [1, 3],
////            'join' => "(select * from user) as asd"
//            ],
        ];

//        $user_alias = MODEL_ALIAS[$model->TableName()];

//        $join = [
//            [
//                'left',
//                $user_alias[0].' as '.$user_alias[1],
//                [
//                    'and'   => [$user_alias[1].'.email', '=', 'a.email'],
//                    'or'    => [$user_alias[1].'.email', '>', 'a.email'],
//                    'where' => $user_alias[1].".email = '593343693@qq.com'",
//                ],
//            ],
//        ];

//        $ret = $model->GetList('a.*', [], [], '', '', $this->is_page, $this->per_page);
//        $result = $this->HandleData('list', $ret);
//        dd($result);
//        return $ret;
//        $ret = $model->SqlQuery('where id = 12', 'update');

//        $array1 = [
//            ['email' => 'abigail@example.com', 'position' => 'Developer'],
//            ['email' => 'james@example.com', 'position' => 'Designer'],
//            ['email' => 'victoria@example.com', 'position' => 'Developer'],
//        ];
//        $data = collect($array1);
//        $ret = $data->dump();
//dd($ret);
//        return $this->Response(1, $ret, 1);

        if ($request->post()) {
            return $model;
        }

        $rule = [
            'name' => 'required',
        ];

//        $msg = [
//            'name.required' => 'dfkdskfljsdjf'
//        ];

//        $custom = [
//            'name' => 'xiaoming'
//        ];

        $this->validate(request(), $rule);

//        try {
//
//        } catch (ExampleException $e) {
//            throw new ExampleException('xiaoming', 145545654);
//        }

    }

    public function Asd()
    {
        require_once __DIR__ . './../../../../vendor/autoload.php';
//        dd(__DIR__);

        $mpdf = new \Mpdf\Mpdf();

        $stay = '<style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #fafafa;
        }

        .main {
            border-collapse: collapse;
            width: 900px;
            height: 56px;
            font-family: 宋体;
            font-size: 18px;
            position: relative;
        }

        .main-firstRow {
            height: 60px;
            text-align: center
        }

        .main-firstCol {
            font-weight: bold;
            font-size: 30px;
            text-align: center
        }

        .main-secondRow {
            height: 20000px;
            font-size: 14px
        }

        .main-secondCol-one {
            border: 1px solid #000;
            font-weight: normal;
            width: 10%;
            text-align: center;
            font-size: 14px;
        }

        .main-secondCol-two {
            border: 1px solid #000;
            font-weight: normal;
            width: 90%;
            font-size: 14px;
            text-align: center
        }
    </style>';

//        $header = '<table width="95%" style="margin:0 auto;border-bottom: 1px solid #4F81BD; vertical-align: middle; font-family:serif; font-size: 9pt; color: #000088;">
//    <tr><td width="10%"></td><td width="80%" align="center" style="font-size:16px;color:#A0A0A0">页眉</td><td width="10%" style="text-align: right;"></td></tr></table>';
//        //设置PDF页脚内容 (自定义编辑样式)
//        $footer = '<table width="100%" style=" vertical-align: bottom; font-family:serif; font-size: 9pt; color: #000088;"><tr style="height:30px"></tr><tr>
//    <td width="10%"></td><td width="80%" align="center" style="font-size:14px;color:#A0A0A0">页脚</td><td width="10%" style="text-align: left;">';

        $html = '';


//        ob_start();
//        include 'demo (2).html';
//        $html = ob_get_contents();
//        ob_end_clean();

        $mpdf->SetDisplayMode('real');
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;
//        $mpdf->WriteHTML('<pagebreak sheet-size="210mm 297mm" />');
//        $mpdf->SetHTMLHeader($header);
//        $mpdf->SetHTMLFooter($footer);
//        $mpdf->WriteHTML($stay,1);
        $mpdf->WriteHTML($html, 0);
        $mpdf->Output();
//        $mpdf->Output('mpdf.pdf', 'D');
    }

}

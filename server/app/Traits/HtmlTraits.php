<?php
/**
 * Created by PhpStorm.
 * User: YYWQ
 * Date: 2020/2/19
 * Time: 4:05
 */

namespace App\Traits;


trait HtmlTraits
{
    public function GetHtml($data)
    {
        $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style type="text/css">
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
            overflow: wrap;
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
            font-size: 18px;
        }

        .main-secondCol-one {
            border: 1px solid #000;
            font-weight: normal;
            width: 13%;
            text-align: center;
            font-size: 18px;
            
        }

        .main-secondCol-two{
            border: 1px solid #000;
            font-weight: normal;
            width: 87%;
            font-size: 18px;
            height: 300px;
            border-bottom: none;
            padding: 8px;
        }
        
        .main-secondCol-three{
            border: 1px solid #000;
            font-weight: normal;
            width: 87%;
            font-size: 18px;
            height: 32px;
            border-top: none;
            line-height: 32px;
            text-align: right;
            padding-right: 8px;
        }
        .main-secondCol-two-two{
            height: 65px;
            border: 1px solid #000;
        }
    </style>
</head>

<body>
    <table class="main">
        <tbody>
            <tr class="main-firstRow">
                <td class="main-firstCol" colspan="8">
                    乐亭县大中小工业企业复产复工审批表
                </td>
            </tr>

            <tr style="height:16px;">
                <td colspan="2">
                    &nbsp;
                </td>
            </tr>

            <tr class="main-secondRow">
                <td class="main-secondCol-one">
                    企业名称
                </td>
                <td class="main-secondCol-two main-secondCol-two-two">
                    <p class="p1">
                        <span style="font-family:宋体;">'.$data['enterprise_name'].'</span>
                    </p>
                    
                </td>
            </tr>
            

            <tr class="main-secondRow">
                <td class="main-secondCol-one" rowspan="2">
                    镇乡街道园区意见
                </td>
                <td class="main-secondCol-two" valign="top">
                    <p class="p1">
                        <span style="font-family:宋体;"> &nbsp;&nbsp;'.$data['opinion1'].'</span>
                    </p>
                    
                </td>
            </tr>
            <tr class="main-secondRow">
                <td class="main-secondCol-three" valign="top">
                    <p class="p2">
                        <span>'.$data['str1'].'</span>
                    </p>
                </td>
            </tr>
            

            <tr class="main-secondRow">
                <td class="main-secondCol-one" rowspan="2">
                    县公信局意见
                </td>
                <td class="main-secondCol-two" valign="top">
                    <p class="p1">
                        <span style="font-family:宋体;">&nbsp;&nbsp;'.$data['opinion2'].'</span>
                    </p>
                    
                </td>
            </tr>
            <tr class="main-secondRow">
                <td class="main-secondCol-three" valign="top">
                    <p class="p2">
                        <span>'.$data['str2'].'</span>
                    </p>
                </td>
            </tr>
            

            <tr class="main-secondRow">
                <td class="main-secondCol-one" rowspan="2">
                    县指挥部专家组意见
                </td>
                <td class="main-secondCol-two" valign="top">
                    <p class="p1">
                        <span style="font-family:宋体;">&nbsp;&nbsp;'.$data['opinion3'].'</span>
                    </p>
                    
                </td>
            </tr>
            <tr class="main-secondRow">
                <td class="main-secondCol-three" valign="top">
                    <p class="p2">
                        <span>'.$data['str3'].'</span>
                    </p>
                </td>
            </tr>
            

            <tr class="main-secondRow">
                <td class="main-secondCol-one">
                    备注
                </td>
                <td class="main-secondCol-two-two">
                    <p class="p1">
                        <span style="font-family:宋体;">&nbsp; &nbsp; '.$data['remark'].'</span>
                    </p>
                </td>
            </tr>
            
             <tr style="height:16px;">
                <td colspan="2">
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td style="font-weight: normal;" colspan="2">
                    <p style="margin:0;">
                        <span>注：1、此表根据《唐政办字 [2020] 2号通知》 《乐工信 [2020] 3号通知》 制定执行； 2、企业提交申请及相关材料附后； 3、申请2月9日24时之前复产复工企业涉及行业主管部门的，行业主管部门意见附后； 4、最终审批结果报县工信局备案。</span>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>';

        return $html;
    }
}

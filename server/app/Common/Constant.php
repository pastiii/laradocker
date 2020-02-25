<?php
/**
 * Created by PhpStorm.
 * User: ZXQ
 * Date: 2020/1/2
 * Time: 上午11:42
 */

#数据库前缀
const FIX = 'yqsb_';

#地区数据
const LAOTING     = [4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21];
const LAOTING_NUM = 15;

#短信常量
const SMS_URL   = 'http://120.79.227.54:7862/sms';
const SMS_USER  = '999236';
const SMS_PWD   = 'yWMQJe';
const SMS_EXTNO = '10690956';

#状态码
const RETURN_SUCCESS    = 10000; #请求成功
const RETURN_DATA_EMPTY = 10001; #请求成功，数据不存在
const RETURN_FILED_FAIL = 10002; #字段校验错误
const RETURN_ERROR      = 10003; #接口抛出异常错误
const MYSQL_ERROR       = 10004; #数据库异常
const RETURN_FILED      = 10005; #操作失败
const RETURN_LIST_EMPTY = 10006; #列表为空
const FILE_ERROR        = 10007; #文件格式
const LOGIN_FAIL        = 10008; #登陆校验错误
const USER_LOCK         = 10009; #账号被冻结
const LOGIN_ERROR       = 10010; #登陆过期
const TEMPLATE          = 10011; #模板异常
const CHECK_CODE        = 10012; #手机验证码校验
const CHECK_CODE_FAIL   = 10013; #手机验证码校验错误
const APPLY_ERROR       = 10014; #企业已存在
const APPLY_SAME        = 10015; #重复提交申请
const RETURN_FILED_T    = 10016; #文件类型错误
const APPLY_ERROR_T     = 10017; #复工申请审核中或已通过
const APPLY_ERROR_S     = 10018; #复工申请审核中或已通过

#提示消息
const CHINESE_MSG = [
    10000 => '操作成功',
    10001 => '请求成功，数据为空',
    10002 => '字段校验错误',
    10003 => '接口异常错误',
    10004 => '数据库异常',
    10005 => '操作失败',
    10006 => '列表为空',
    10007 => '请上传正确的企业上报模板文件！',
    10008 => '请输入正确的账号、密码！',
    10009 => '账号已被冻结',
    10010 => '登陆已失效请重新登陆',
    10011 => '请上传正确的企业上报模板文件!',
    10012 => '验证码已失效!',
    10013 => '验证码错误!',
    10014 => '该企业已提交复工审批!',
    10015 => '该帐号已提交申请!',
    10016 => '请上传正确的模板文件!',
    10017 => '复工申请审核中或已通过!',
    10018 => '该账户已存在储存数据,不可新增!',
];

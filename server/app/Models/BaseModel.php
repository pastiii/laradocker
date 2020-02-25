<?php
/**
 * Created by PhpStorm.
 * User: ZXQ
 * Date: 2019/12/31
 * Time: 下午2:53
 */

namespace App\Models;


use App\Exceptions\ExampleException;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Facades\DB;

class BaseModel extends EloquentModel
{
    /**
     * 添加数据
     * @param $data
     * @return mixed
     */
    public function AddData($data)
    {
        $res = $this->create($data);
        return $res;
    }

    /**
     * 批量添加数据
     * @param $data
     * @return mixed
     */
    public function AddAll($data)
    {
        $res = $this->insert($data);
        return $res;
    }

    /**
     * 修改数据 返回受影响行数
     * @param $where
     * @param $data
     * @return mixed
     */
    public function EditData($where, $data)
    {
        $res = $this->where($where)->update($data);
        return $res;
    }

    /**
     * 获取单条数据
     * @param string $filed
     * @param array $where
     * @param array $join
     * @param string $orderBy
     * @return array
     * @throws ExampleException
     */
    public function GetOne($filed = '', $where = [], $join = [], $orderBy = '')
    {
        try {
            $orderBy = $orderBy ?: 'a.id desc';
            $filed   = $filed ?: 'a.*';
            $res     = $this->from($this->table . ' as a')->selectRaw($filed)->orderByRaw($orderBy);
            isset($where) && $this->sqlWhere($res, $where);
            isset($join) && $this->sqlJoin($res, $join);
            $ret = $res->first();
            $ret = empty($ret) ? $ret : $ret->toArray();
            if ($ret) {
                $result = [
                    'status' => RETURN_SUCCESS,
                    'data'   => $ret,
                    'msg'    => CHINESE_MSG[RETURN_SUCCESS],
                ];
            } else {
                $result = [
                    'status' => RETURN_DATA_EMPTY,
                    'data'   => NULL,
                    'msg'    => CHINESE_MSG[RETURN_DATA_EMPTY],
                ];
            }
        } catch (ExampleException $e) {
            throw new ExampleException(CHINESE_MSG[MYSQL_ERROR], MYSQL_ERROR);
        }
        return $result;
    }

    /**
     * 获取数据列表
     * @param string $filed
     * @param array $where
     * @param array $join
     * @param string $orderBy
     * @param string $group
     * @param null $is_page
     * @param int $per_page
     * @return array
     * @throws ExampleException
     */
    public function GetList($filed = '', $where = [], $join = [], $orderBy = '', $group = 'a.id', $is_page = NULL, $per_page = 10)
    {
        try {
            $orderBy = $orderBy ?: 'a.id desc';
            $filed   = $filed ?: 'a.*';
            $res     = $this->from($this->table . ' as a')->selectRaw($filed)->orderByRaw($orderBy);
            isset($where) && $this->sqlWhere($res, $where);
            isset($join) && $this->sqlJoin($res, $join);
            !empty($group) && $res->groupBy($group);
            if (!empty($is_page)) {
                $ret = $res->paginate($per_page)->toArray();
                #数据整理
                $result = [
                    'list'     => $ret['data'],
                    'total'    => $ret['total'],
                    'per_page' => $ret['per_page'],
                    'page'     => $is_page,
                ];
            } else {
                $ret    = $res->get();
                $result = empty($ret) ? $ret : $ret->toArray();
            }

            if ($result) {
                if ($is_page) {
                    if ($result['list']) {
                        $result = [
                            'status' => RETURN_SUCCESS,
                            'data'   => $result,
                            'msg'    => CHINESE_MSG[RETURN_SUCCESS],
                        ];
                    } else {
                        $result = [
                            'status' => RETURN_LIST_EMPTY,
                            'data'   => $result,
                            'msg'    => CHINESE_MSG[RETURN_LIST_EMPTY],
                        ];
                    }
                } else {
                    $result = [
                        'status' => RETURN_SUCCESS,
                        'data'   => $result,
                        'msg'    => CHINESE_MSG[RETURN_SUCCESS],
                    ];
                }
            } else {
                $result = [
                    'status' => RETURN_LIST_EMPTY,
                    'data'   => NULL,
                    'msg'    => CHINESE_MSG[RETURN_LIST_EMPTY],
                ];
            }
        } catch (ExampleException $e) {
            throw new ExampleException(CHINESE_MSG[MYSQL_ERROR], MYSQL_ERROR);
        }

        return $result;
    }

    /**
     * 获取Sql
     * @param string $filed
     * @param array $where
     * @param array $join
     * @param string $orderBy
     * @param string $group
     * @param null $is_page
     * @param int $offset
     * @param int $limit
     * @return string
     */
    public function GetSqlCommand($filed = '', $where = [], $join = [], $orderBy = 'a.id desc', $group = 'a.id', $is_page = NULL, $offset = 0, $limit = 10)
    {
        DB::connection()->enableQueryLog();
        $orderBy = $orderBy ?: 'a.id desc';
        $filed   = $filed ?: 'a.*';
        $res     = $this->from($this->table . ' as a')->selectRaw($filed)->orderByRaw($orderBy);
        if (!empty($is_page)) {
            $res->limit($limit)->offset($offset);
        }
        isset($where) && $this->sqlWhere($res, $where);
        isset($join) && $this->sqlJoin($res, $join);
        !empty($group) && $res->groupBy($group);
        $res->get();
        $queries = DB::getQueryLog();

        if (!empty($queries)) {
            foreach ($queries as &$query) {
                $query['full_query'] = vsprintf(str_replace('?', '%s', $query['query']), $query['bindings']);
            }
        }

        return $query['full_query'];
    }

    /**
     * 获取数据条数
     * @param array $where
     * @param array $join
     * @return mixed
     */
    public function GetCount($where = [], $join = [])
    {
        $res = $this->from($this->table . ' as a');
        isset($where) && $this->sqlWhere($res, $where);
        isset($join) && $this->sqlJoin($res, $join);

        $count = $res->count();
        return $count;
    }

    /**
     * @param $sql
     * @param string $type
     * @return array|bool
     */
    public function SqlQuery($sql, $type = 'select')
    {
        switch ($type) {
            case 'select':
                $ret = DB::select($sql);
                break;
            case 'other':
                $ret = Db::statement($sql);
                break;
        }
        return $ret;
    }

    /**
     * 连表
     * @param $res
     * @param $join
     * @return mixed
     */
    public function sqlJoin($res, $join)
    {
        if (empty($join)) {
            return $res;
        }

        foreach ($join as $val) {
            switch ($val[0]) {
                case 'left':
                    $res->leftJoin($val[1], function ($join) use ($val) {
                        foreach ($val[2] as $k => $v) {
                            switch ($k) {
                                case 'and':
                                    $join->on($v[0], $v[1], $v[2]);
                                    break;
                                case 'or':
                                    $join->orOn($v[0], $v[1], $v[2]);
                                    break;
                                case 'where':
                                    $join->whereRaw($v);
                                    break;
                            }
                        }
                    });
                    break;
                case 'right':
                    $res->RightJoin($val[1], function ($join) use ($val) {
                        foreach ($val[2] as $k => $v) {
                            switch ($k) {
                                case 'and':
                                    $join->on($v[0], $v[1], $v[2]);
                                    break;
                                case 'or':
                                    $join->orOn($v[0], $v[1], $v[2]);
                                    break;
                                case 'where':
                                    $join->whereRaw($v);
                                    break;
                            }
                        }
                    });
                    break;
                case 'inner':
                    $res->join($val[1], function ($join) use ($val) {
                        foreach ($val[2] as $k => $v) {
                            switch ($k) {
                                case 'and':
                                    $join->on($v[0], $v[1], $v[2]);
                                    break;
                                case 'or':
                                    $join->orOn($v[0], $v[1], $v[2]);
                                    break;
                                case 'where':
                                    $join->whereRaw($v);
                                    break;
                            }
                        }
                    });
                    break;
            }
        }
    }

    /**
     * 你的名字
     * @return string
     */
    public function TableName()
    {
        return $this->table;
    }
}

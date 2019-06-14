<?php
// +----------------------------------------------------------------------
// | Common 
// +----------------------------------------------------------------------
// | 版权所有 2015-2020 武汉市微易科技有限公司，并保留所有权利。
// +----------------------------------------------------------------------
// | 网站地址: https://www.kdfu.cn
// +----------------------------------------------------------------------
// | 这不是一个自由软件！您只能在不用于商业目的的前提下使用本程序
// +----------------------------------------------------------------------
// | 不允许对程序代码以任何形式任何目的的再发布
// +----------------------------------------------------------------------
// | Author: 微小易    Date: 2018-12-01
// +----------------------------------------------------------------------
namespace app\common\model;
use think\facade\Cache;
/**
 * 商家用户角色模型
 * Class Role
 * @package app\common\model\admin
 */
class Auth extends BaseModel
{
    protected $name = 'auth';
    protected $pk = 'role_id';
    /**
     * 角色信息
     * @param $where
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($where)
    {
        return self::get($where);
    }
    /**
     * 获取角色列表
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList()
    {
        return $this->where('user_id','=',self::$user_id)->All();
        // return $this->formatTreeData($all);
    }
    /**
     * 新增记录
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function edit($data)
    {
        if (empty($data['access'])) {
            $this->error = '请选择权限';
            return false;
        }
        $where='';
        $ruleid=$data['role_id'];
        if($ruleid) $where=['role_id','=',$ruleid];
          $data['user_id']=self::$user_id;
        $this->allowField(true)->save($data,$where);
        // 更新角色权限关系记录
        (new RoleAccess)->edit($this['role_id'], $data['access']);
        $this->commit();
        return true;
    }
    /**
     * 获取所有上级id集
     * @param $role_id
     * @param null $all
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getTopRoleIds($role_id, &$all = null)
    {
        static $ids = [];
        is_null($all) && $all = $this->getAll();
        foreach ($all as $item) {
            if ($item['role_id'] == $role_id && $item['parent_id'] > 0) {
                $ids[] = $item['parent_id'];
                $this->getTopRoleIds($item['parent_id'], $all);
            }
        }
        return $ids;
    }
    /**
     * 删除记录
     * @return bool|int
     * @throws \think\exception\DbException
     */
    public function remove()
    {
        AuthGroup::deleteAll(['role_id' => $this['role_id']]);
        // 删除对应的权限关系
        RoleAccess::deleteAll(['role_id' => $this['role_id']]);
        return $this->delete();
    }
    /**
     * 获取所有角色
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getAll()
    {
        $data = $this->order(['sort' => 'asc', 'create_time' => 'asc'])->select();
        return $data ? $data->toArray() : [];
    }
    /**
     * 获取权限列表
     * @param $all
     * @param int $parent_id
     * @param int $deep
     * @return array
     */
    private function formatTreeData(&$all, $parent_id = 0, $deep = 1)
    {
        static $tempTreeArr = [];
        foreach ($all as $key => $val) {
            if ($val['parent_id'] == $parent_id) {
                // 记录深度
                $val['deep'] = $deep;
                // 根据角色深度处理名称前缀
                $val['role_name_h1'] = $this->htmlPrefix($deep) . $val['role_name'];
                $tempTreeArr[] = $val;
                $this->formatTreeData($all, $val['role_id'], $deep + 1);
            }
        }
        return $tempTreeArr;
    }
    /**
     * 角色名称 html格式前缀
     * @param $deep
     * @return string
     */
    private function htmlPrefix($deep)
    {
        // 根据角色深度处理名称前缀
        $prefix = '';
        if ($deep > 1) {
            for ($i = 1; $i <= $deep - 1; $i++) {
                $prefix .= '&nbsp;&nbsp;&nbsp;├ ';
            }
            $prefix .= '&nbsp;';
        }
        return $prefix;
    }
    /**
     * 验证指定url是否有访问权限
     * @param string|array $url
     * @array $user
     * @param bool $strict 严格模式(必须全部通过才返回true)
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function check($url,$user, $strict = true)
    {
        if (!is_array($url)){
            return $this->checkAccess(strtolower($url),$user);
        }else{
            foreach ($url as $val){
                if ($strict && !$this->checkAccess($val,$user)) {
                    return false;
                }
                if (!$strict && $this->checkAccess($val,$user)) {
                    return true;
                }
            }
        }
        return true;
    }
    /**
     * @param string $url
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function checkAccess($url,$user)
    {
        // 超级管理员无需验证
        if (self::$is_super==self::$user_id) {
            return true;
        }
        // 验证当前请求是否在白名单
        if (in_array($url, $this->allowAllAction)) {
            return true;
        }
        //$role = WebUser::detail($user_id);
        $list =(new Menu)->getAll();
        // 根据已分配的权限
        $accessIds = (new RoleAccess)->getAccessIds($user['role_id']);
        foreach ($list as $k=>$v){
            if($v['url']==$url && in_array($v['id'],$accessIds)){
                return true;
            }
        }
        return false;
    }
}
<?php
namespace app\common\model;
/**
 * 商家用户角色权限关系模型
 * Class RoleAccess
 * @package app\common\model\admin
 */
class RoleAccess extends BaseModel
{
    protected $name = 'auth_list';

    /**
     * 更新关系记录
     * @param $role_id
     * @param array $newAccess 新的权限集
     * @return array|false
     * @throws \Exception
     */
    public function edit($role_id, $newAccess)
    {

        $where['role_id']=$role_id;
        self::deleteAll($where);
        /**
         * 找出添加的权限
         * 假如已有的权限集合是A，界面传递过得权限集合是B
         * 权限集合B当中的某个权限不在权限集合A当中，就应该添加
         * 使用 array_diff() 计算补集
         */
        $data = [];
        foreach ($newAccess as $key) {
            $data[] = [
                'role_id' => $role_id,
                'access_id' => $key,
                'app_id' => self::$app_id,
            ];
        }
        return $this->saveAll($data);
    }
    /**
     * 获取指定角色的所有权限id
     * @param int|array $role_id 角色id (支持数组)
     * @return array
     */
    public static function getAccessIds($role_id)
    {
        $roleIds = is_array($role_id) ? $role_id : [(int)$role_id];
        return (new self)->where('role_id', 'in', $roleIds)->column('access_id');
    }
     /**
     * 删除记录
     * @param $where
     * @return int
     */
    public static function deleteAll($data)
    {	
		
      return self::destroy($data);
    }
}
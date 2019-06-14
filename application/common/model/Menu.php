<?php
namespace app\common\model;
use think\Model;
/**
 * 商家用户权限模型
 * Class Menu
 * @package app\common\model\admin
 */
class Menu extends Model
{
    protected $name = 'menu';
	protected $tree = '';
    /**
     * 获取所有权限
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public  function getAll($where=[])
    {
        $data = static::useGlobalScope(false)->where($where)->order(['sort' => 'asc', 'id' => 'asc'])->select();
        return $data;
    }
    /**
     * 权限信息
     * @param $access_id
     * @return array|false|\PDOStatement|string|\think\Model|static
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function detail($access_id)
    {
        return static::useGlobalScope(false)->where(['id' => $access_id])->find();
    }
    /**
     * 获取权限url集
     * @param $accessIds
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getAccessUrls($accessIds)
    {
        $urls = [];
        foreach (static::getAll() as $item) {
            in_array($item['id'], $accessIds) && $urls[] = $item['url'];
        }
        return $urls;
    }
	/**
     * 获取权限列表 jstree格式
     * @param int $role_id 当前角色id
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getJsTree($role_id = null)
    {
		$model =new self;
		$list =$model->order('id asc')->column("*",'id');
        $accessIds = is_null($role_id) ? [] : RoleAccess::getAccessIds($role_id);
		foreach ($list as $k=>$v) {
			$list[$k]['checked'] = in_array($v['id'], $accessIds);
			if ($v['pid'] != 0)
				$list[$v['pid']]['list'][$v['id']] = &$list[$k];
			else
			$parent[] = &$list[$k];
		}
		return $parent;
    }
}
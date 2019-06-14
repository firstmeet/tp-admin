<?php
namespace app\common\model;
/**
 * 微信小程序导航栏模型
 * Class AppNavbar
 * @package app\common\model
 */
class AppNavbar extends BaseModel
{
    protected $name = 'app_navbar';
    /**
     * 顶部导航文字颜色
     * @param $value
     * @return array
     */
    public function getTopTextColorAttr($value)
    {
        $color = [10 => '#000000', 20 => '#ffffff'];
        return ['text' => $color[$value], 'value' => $value];
    }
    /**
     * 小程序导航栏详情
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($app_id ='')
    {
        $app_id=self::$app_id;
        return self::get(['app_id' => $app_id]);
    }
    /**
     * 新增小程序导航栏默认设置
     * @param $app_id
     * @param $app_title
     * @return false|int
     */
    public function insertDefault($app_id, $app_title)
    {
        return $this->save([
            'app_title' => $app_title,
            'top_text_color' => 20,
            'top_background_color' => '#fd4a5f',
            'app_id' => $app_id
        ]);
    }
	  /**
     * 更新页面数据
     * @param $data
     * @return bool
     */
    public function edit($data)
    {

        // 删除app缓存
        //(new App)->deleteCache();
       $data['app_id']=self::$app_id;
        return $this->allowField(true)->save($data);
    }
}
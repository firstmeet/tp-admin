<?php
namespace app\common\model;
/**
 * 配送模板区域及运费模型
 * Class DeliveryRule
 * @package app\store\model
 */
class DeliveryRule extends BaseModel
{
    protected $name = 'delivery_rule';
	protected $pk = 'rule_id';
	protected $append = ['region_content'];
    static $regionAll;
    static $regionTree;
	/**
     * 可配送区域
     * @param $value
     * @param $data
     * @return string
     */
    public function getRegionContentAttr($value, $data)
    {
        // 当前区域记录转换为数组
        $regionIds = explode(',', $data['region']);
        if (count($regionIds) === 373) return '全国';
        // 所有地区
        if (empty(self::$regionAll)) {
            self::$regionAll = Region::getCacheAll();
            self::$regionTree = Region::getCacheTree();
        }
        // 将当前可配送区域格式化为树状结构
        $alreadyTree = [];
        foreach ($regionIds as $regionId)
            $alreadyTree[self::$regionAll[$regionId]['pid']][] = $regionId;
        $str = '';
        foreach ($alreadyTree as $provinceId => $citys) {
            $str .= self::$regionTree[$provinceId]['name'];
            if (count($citys) !== count(self::$regionTree[$provinceId]['city'])) {
                $cityStr = '';
                foreach ($citys as $cityId)
                    $cityStr .= self::$regionTree[$provinceId]['city'][$cityId]['name'];
                $str .= ' (' . mb_substr($cityStr, 0, -1, 'utf-8') . ')';
            }
            $str .= '、';
        }
        return mb_substr($str, 0, -1, 'utf-8');
    }
	  /**
     * 添加模板区域及运费
     * @param $data
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function createDeliveryRule($data,$delivery_id)
    {
		
        $save = [];
        $connt = count($data['region']);
		
        for ($i = 0; $i < $connt; $i++) {
            $save[] = [
			    'delivery_id'=>$delivery_id,
                'region' => $data['region'][$i],
                'first' => $data['first'][$i],
                'first_fee' => $data['first_fee'][$i],
                'additional' => $data['additional'][$i],
                'additional_fee' => $data['additional_fee'][$i],
                'app_id' => self::$app_id
            ];
        }
		$this->delete();
		
        return $this->saveAll($save);
    }
}
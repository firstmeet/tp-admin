<?php
namespace app\api\controller;
use think\Controller;
use app\common\model\Order as OrderModel;
use app\common\model\App as AppModel;
use app\common\model\Integral;
/**
 * 支付成功异步通知接口
 * Class Notify
 * @package app\api\controller
 */
class Notify extends Controller
{
    /**
     * 支付成功异步通知
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
	public function api()
	{
		if (!$xml = file_get_contents('php://input')) {
            $this->returnCode(false, 'Not found DATA');
        }
        // 将服务器返回的XML数据转化为数组
        $data = $this->fromXml($xml);
		$order = (new OrderModel)->payDetail($data['out_trade_no']);
		empty($order) && $this->returnCode(false, '订单不存在');
		$wxConfig = AppModel::getAppCache($order['app_id']);
        // 设置支付秘钥
        $this->config['apikey'] = $wxConfig['apikey'];
        // 保存微信服务器返回的签名sign
        $dataSign = $data['sign'];
        // sign不参与签名算法
        unset($data['sign']);
        // 生成签名
        $sign = $this->makeSign($data);
        // 判断签名是否正确  判断支付状态
         if (($sign === $dataSign)
            && ($data['return_code'] == 'SUCCESS')
            && ($data['result_code'] == 'SUCCESS')) { 
            // 订单支付成功业务处理
            $order->paySuccess('wx201411101639507cbf6ffd8b0779950874');
			
			(new Integral)->add($order['give_integral'],$order['user_id'],'购买产品:订单号'.$data['out_trade_no'],$wxConfig['app_id']);die;
            // 返回状态
            $this->returnCode(true, 'OK');
        }
        // 返回状态
        $this->returnCode(false, '签名失败');
	}
	  /**
     * 将xml转为array
     * @param $xml
     * @return mixed
     */
    private function fromXml($pay)
    {
        // 禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($pay, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }
	/**
     * 生成签名
     * @param $values
     * @return string 本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    private function makeSign($values)
    {
        //签名步骤一：按字典序排序参数
        ksort($values);
        $string = $this->toUrlParams($values);
        //签名步骤二：在string后加入KEY
        $string = $string . '&key=' . $this->config['apikey'];
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }
	/**
     * 格式化参数格式化成url参数
     * @param $values
     * @return string
     */
    private function toUrlParams($values)
    {
        $buff = '';
        foreach ($values as $k => $v) {
            if ($k != 'sign' && $v != '' && !is_array($v)) {
                $buff .= $k . '=' . $v . '&';
            }
        }
        return trim($buff, '&');
    }
	/**
     * 返回状态给微信服务器
     * @param boolean $return_code
     * @param string $msg
     */
    private function returnCode($return_code = true, $msg = null)
    {
        // 返回状态
        $return = [
            'return_code' => $return_code ? 'SUCCESS' : 'FAIL',
            'return_msg' => $msg ?: 'OK',
        ];
        // 记录日志
        log_write([
            'describe' => '返回微信支付状态',
            'data' => $return
        ]);
        die($this->toXml($return));
    }
	 /**
     * 输出xml字符
     * @param $values
     * @return bool|string
     */
    private function toXml($values)
    {
        if (!is_array($values)
            || count($values) <= 0
        ) {
            return false;
        }
        $xml = "<xml>";
        foreach ($values as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }
}
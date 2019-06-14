<?php
namespace app\install\controller;
use think\Controller;
use think\Db;
use think\facade\App;
use think\facade\Request;
use think\facade\Cache;
class Index extends Controller
{
    // UPDATE 正则条件
    private static $updateExpression = '/UPDATE[\\s`]+?(\\w+)[\\s`]+?/is';
	//创建数据表CREATE
	private static $createExpression = '/CREATE\\s+?TABLE[\\s`]+?(\\w+)[\\s`]+?/is'; 
    // INSERT 正则条件
    private static $insertExpression = '/INSERT\\s+?INTO[\\s`]+?(\\w+)[\\s`]+?/is';
    // SELECT 正则条件
    private static $selectExpression = '/((SELECT.+?FROM)|(LEFT\\s+JOIN|JOIN|LEFT))[\\s`]+?(\\w+)[\\s`]+?/is';
	/**
     * @return array 返回创建的所有表名
     */
    public static  function createTableNames($sql)
    {
        return self::getTableNames(self::$createExpression,$sql);
    }
	  /**
     * @return array 返回插入操作的所有表名
     */
    public static function insertTableNames($sql)
    {
        return self::getTableNames(self::$insertExpression,$sql);
    }
	 /**
     * 根据正则表达式获取所有操作的表名
     * @param $expression
     * @return array
     */
    public static  function getTableNames($expression,$sql)
    {
        preg_match_all($expression, $sql, $matches);
        return array_unique(array_pop($matches));
    }
    public function index($step = 0)
    {
        switch ($step) {
            case 2:
                Cache::set('install_error', false);
                return self::step2();
                break;
            case 3:
                if (Cache::get('install_error')) {
                    return $this->error('环境检测未通过，不能进行下一步操作！');
                }
                return self::step3();
                break;
            case 4:
                if (Cache::get('install_error')) {
                    return $this->error('环境检测未通过，不能进行下一步操作！');
                }
                return self::step4();
                break;
            case 5:
                if (Cache::get('install_error')) {
                    return $this->error('初始失败！');
                }
                return self::step5();
                break;
            default:
                Cache::set('install_error', null);
				$this->view->engine->layout(false);
                return $this->fetch('install@/index/index');
                break;
        }
    }
    /**
     * 第二步：环境检测
     * @return mixed
     */
    private function step2()
    {
        $data = [];
        $data['env'] = self::checkNnv();
        $data['dir'] = self::checkDir();
        $data['func'] = self::checkFunc();
        $this->assign('data', $data);
		$this->view->engine->layout(false);
        return $this->fetch('install@index/step2');
    }
    /**
     * 第三步：初始化配置
     * @return mixed
     */
    private function step3()
    {
        $install_dir = $_SERVER["SCRIPT_NAME"];
        $install_dir = self::mac_substring($install_dir, strripos($install_dir, "/")+1);
        $this->assign('install_dir',$install_dir);
		$this->view->engine->layout(false);
        return $this->fetch('install@index/step3');
    }
    /**
     * 第四步：执行安装
     * @return mixed
     */
    public function updb()
    {
        if ($this->request->isPost()) {
            if (!is_writable(App::getRootPath().'config/database.php')) {
                return $this->error('[config/database.php]无读写权限！');
            }
            $data = input('post.');
            $data['type'] = 'mysql';
            $rule = [
                'hostname|服务器地址' => 'require',
                'hostport|数据库端口' => 'require|number',
                'database|数据库名称' => 'require',
                'username|数据库账号' => 'require',
                'prefix|数据库前缀' => 'require|regex:^[a-z0-9]{1,20}[_]{1}',
            ];
            $validate = $this->validate($data, $rule);
            if (true !== $validate) {
                return $this->error($validate);
            }
            $config = include App::getRootPath().'config/database.php';
            foreach ($data as $k => $v) {
                if (array_key_exists($k, $config) === false) {
                    return $this->error('参数'.$k.'不存在！');
                }
            }
            // 不存在的数据库会导致连接失败
            $database = $data['database'];
            unset($data['database']);
            // 创建数据库连接
            $db_connect = Db::connect($data);
            // 检测数据库连接
            try{
                $db_connect->execute('select version()');
            }catch(\Exception $e){
                return $this->error('数据库连接失败，请检查数据库配置！');
            }
            // 生成数据库配置文件
            $data['database'] = $database;
            $this->mkDatabase($data);
            // 不覆盖检测是否已存在数据库
			$check = $db_connect->execute('SELECT * FROM information_schema.schemata WHERE schema_name="'.$database.'"');
			if ($check) {
				return $this->success('该数据库已存在，可直接安装！');
			}
            // 创建数据库
            if (!$db_connect->execute("CREATE DATABASE IF NOT EXISTS `{$database}` DEFAULT CHARACTER SET utf8")) {
                return $this->error($db_connect->getError());
            }
            return $this->success('数据库连接成功');
        } 
    }
    private function step4(){
		$account = input('post.account');
        $password = input('post.password');
		$real_name =input('post.real_name');
        $install_dir = input('post.install_dir');
        $config = include (App::getRootPath().'config/database.php');
        if (empty($config['hostname']) || empty($config['database']) || empty($config['username'])) {
            return $this->error('请先点击测试数据库连接！');
        }
        if (empty($account) || empty($password)) {
            return $this->error('请填写管理账号和密码！');
        }
        $rule = [
            'account|管理员账号' => 'require|alphaNum',
            'password|管理员密码' => 'require|length:6,20',
        ];
        $validate = $this->validate(['account' => $account, 'password' => $password], $rule);
        if (true !== $validate) {
            return $this->error($validate);
        }
        if(empty($install_dir)) {
            $install_dir='/';
        }
        // 导入系统初始数据库结构
        $sql_file = App::getRootPath().'application/install/sql/install.sql';
        if (file_exists($sql_file)) {
            $sql = file_get_contents($sql_file);
            $sql_list = self::mac_parse_sql($sql, 0, ['bfb_' => $config['prefix']]);
			$sql_list = array_filter($sql_list);
			$i=0;
			foreach($sql_list as $k=>$v){
				$array = $this->get_sql($v);
				$se =Cache($array);
				if($se!=true && $array ){
					$i++;
					Cache($array,$i);
				}
				$data[$i][]=$v;
			}
			Cache::set('updata',$data);
        }
		$this->view->engine->layout(false);
        return $this->fetch('install@index/step4');
	}
	/**
     * 检测建表还是插入数据
     * @return array
     */
	public function get_sql($sql){
		if(strpos($sql,'CREATE') !== false && $table = self::createTableNames($sql)){
			return $table[0];
		}
		if(strpos($sql,'INSERT') !== false && $table = self::insertTableNames($sql)){
			return $table[0];
		}
	}
    /**
     * 第五步：数据库安装
     * @return mixed
     */
    private function step5()
    {
		//注册管理员账号
        $data = [
            'user_name' => $account,
			'real_name' =>$real_name,
            'password' => wymall_pass($password),
            'is_super' =>1,
        ];
        $res = db('manager')->where('user_id',10015)->data($data)->update();
        if (!$res['code']>1) {
            return $this->error('管理员账号设置失败:'.$res['msg']);
        }
        file_put_contents(APP_PATH.'install/data/install.lock', date('Y-m-d H:i:s'));
        // 获取站点根目录
        return $this->success('百分百小程序系统安装成功,欢迎您使用！', url('user/login/login'));
    }
    public function updata(){
		$sql_list = Cache::get('updata');
		$id = Request::get('id');
		if($id<count($sql_list)){
			$array = $this->get_sql($sql_list[$id][0]);
			foreach($sql_list[$id] as $k){
				try {
					Db::execute($k);
					if($array)
					$data='表'.$array.'导入成功！';
				} catch(\Exception $e) {
					$data = '导入表'.$array.'失败,可能已经导入。';
				}
			}
		}else{
			$data = '安装完成';
		}
		return compact('data');
	}
    /**
     * 环境检测
     * @return array
     */
    private function checkNnv()
    {
        $items = [
            'os'      => ['操作系统', '不限制', 'Windows/Unix', PHP_OS, 'ok'],
            'php'     => ['PHP版本', '5.5', '5.5及以上', PHP_VERSION, 'ok'],
            'gd'      => ['GD库', '2.0', '2.0及以上', '未知', 'ok'],
        ];
        if ($items['php'][3] < $items['php'][1]) {
            $items['php'][4] = 'no';
            Cache::set('install_error', true);
        }
        $tmp = function_exists('gd_info') ? gd_info() : [];
        if (empty($tmp['GD Version'])) {
            $items['gd'][3] = '未安装';
            $items['gd'][4] = 'no';
            Cache::set('install_error', true);
        } else {
            $items['gd'][3] = $tmp['GD Version'];
        }
        return $items;
    }
    /**
     * 目录权限检查
     * @return array
     */
    private function checkDir()
    {
        $items = [
            ['dir', '../application', '读写', '读写', 'ok'],
            ['dir', '../config', '读写', '读写', 'ok'],
            ['dir', '../runtime', '读写', '读写', 'ok'],
            ['dir', '../public/uploads', '读写', '读写', 'ok'],
        ];
        foreach ($items as &$v) {
            if ($v[0] == 'dir') {// 文件夹
                if(!is_writable($v[1])) {
                    if(is_dir($v[1])) {
                        $v[3] = '不可写';
                        $v[4] = 'no';
                    } else {
                        $v[3] = '不存在';
                        $v[4] = 'no';
                    }
                    Cache::set('install_error', true);
                }
            } else {// 文件
                if(!is_writable($v[1])) {
                    $v[3] = '不可写';
                    $v[4] = 'no';
                    Cache::set('install_error', true);
                }
            }
        }
        return $items;
    }
    /**
     * 函数及扩展检查
     * @return array
     */
    private function checkFunc()
    {
        $items = [
            ['pdo', '支持', 'yes', '类'],
            ['pdo_mysql', '支持', 'yes', '模块'],
            ['zip', '支持', 'yes', '模块'],
            ['curl', '支持', 'yes', '模块'],
            ['xml', '支持', 'yes', '函数'],
            ['file_get_contents', '支持', 'yes', '函数'],
            ['mb_strlen', '支持', 'yes', '函数'],
            ['gzopen', '支持', 'yes', '函数'],
        ];
        foreach ($items as &$v) {
            if(('类'==$v[3] && !class_exists($v[0])) || ('模块'==$v[3] && !extension_loaded($v[0])) || ('函数'==$v[3] && !function_exists($v[0])) || ('配置'==$v[3] && ini_get('always_populate_raw_post_data')!=-1)) {
                $v[1] = '不支持';
                $v[2] = 'no';
                Cache::set('install_error', true);
            }
        }
        return $items;
    }
	private function mac_parse_sql($sql='',$limit=0,$prefix=[])
	{
		// 被替换的前缀
		$from = '';
		// 要替换的前缀
		$to = '';
		// 替换表前缀
		if (!empty($prefix)) {
			$to   = current($prefix);
			$from = current(array_flip($prefix));
		}
		if ($sql != '') {
			// 纯sql内容
			$pure_sql = [];
			// 多行注释标记
			$comment = false;
			// 按行分割，兼容多个平台
			$sql = str_replace(["\r\n", "\r"], "\n", $sql);
			$sql = explode("\n", trim($sql));
			// 循环处理每一行
			foreach ($sql as $key => $line) {
				// 跳过空行
				if ($line == '') {
					continue;
				}
				// 跳过以#或者--开头的单行注释
				if (preg_match("/^(#|--)/", $line)) {
					continue;
				}
				// 跳过以/**/包裹起来的单行注释
				if (preg_match("/^\/\*(.*?)\*\//", $line)) {
					continue;
				}
				// 多行注释开始
				if (substr($line, 0, 2) == '/*') {
					$comment = true;
					continue;
				}
				// 多行注释结束
				if (substr($line, -2) == '*/') {
					$comment = false;
					continue;
				}
				// 多行注释没有结束，继续跳过
				if ($comment) {
					continue;
				}
				// 替换表前缀
				if ($from != '') {
					$line = str_replace('`'.$from, '`'.$to, $line);
				}
				if ($line == 'BEGIN;' || $line =='COMMIT;') {
					continue;
				}
				// sql语句
				array_push($pure_sql, $line);
			}
			// 只返回一条语句
			if ($limit == 1) {
				return implode($pure_sql, "");
			}
			// 以数组形式返回sql语句
			$pure_sql = implode($pure_sql, "\n");
			$pure_sql = explode(";\n", $pure_sql);
			return $pure_sql;
		} else {
			return $limit == 1 ? '' : [];
		}
	}
    private function mac_substring($str, $lenth, $start=0)
	{
		$len = strlen($str);
		$r = array();
		$n = 0;
		$m = 0;
		for($i=0;$i<$len;$i++){
			$x = substr($str, $i, 1);
			$a = base_convert(ord($x), 10, 2);
			$a = substr( '00000000 '.$a, -8);
			if ($n < $start){
				if (substr($a, 0, 1) == 0) {
				}
				else if (substr($a, 0, 3) == 110) {
					$i += 1;
				}
				else if (substr($a, 0, 4) == 1110) {
					$i += 2;
				}
				$n++;
			}
			else{
				if (substr($a, 0, 1) == 0) {
					$r[] = substr($str, $i, 1);
				}else if (substr($a, 0, 3) == 110) {
					$r[] = substr($str, $i, 2);
					$i += 1;
				}else if (substr($a, 0, 4) == 1110) {
					$r[] = substr($str, $i, 3);
					$i += 2;
				}else{
					$r[] = ' ';
				}
				if (++$m >= $lenth){
					break;
				}
			}
		}
		return  join('',$r);
	}
    /**
     * 生成数据库配置文件
     * @return array
     */
    private function mkDatabase(array $data)
    {
        $code = <<<INFO
<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
return [
    // 数据库类型
    'type'            => 'mysql',
    // 服务器地址
    'hostname'        => '{$data['hostname']}',
    // 数据库名
    'database'        => '{$data['database']}',
    // 用户名
    'username'        => '{$data['username']}',
    // 密码
    'password'        => '{$data['password']}',
    // 端口
    'hostport'        => '{$data['hostport']}',
    // 连接dsn
    'dsn'             => '',
    // 数据库连接参数
    'params'          => [],
    // 数据库编码默认采用utf8
    'charset'         => 'utf8',
    // 数据库表前缀
    'prefix'          => '{$data['prefix']}',
    // 数据库调试模式
    'debug'           => false,
    // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'deploy'          => 0,
    // 数据库读写是否分离 主从式有效
    'rw_separate'     => false,
    // 读写分离后 主服务器数量
    'master_num'      => 1,
    // 指定从服务器序号
    'slave_no'        => '',
    // 是否严格检查字段是否存在
    'fields_strict'   => false,
    // 数据集返回类型
    'resultset_type'  => 'array',
    // 自动写入时间戳字段
    'auto_timestamp'  => false,
    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',
    // 是否需要进行SQL性能分析
    'sql_explain'     => false,
    // Builder类
    'builder'         => '',
    // Query类
    'query'           => '\\think\\db\\Query',
];
INFO;
        file_put_contents(App::getRootPath().'config/database.php', $code);
        // 判断写入是否成功
        $config = include App::getRootPath().'config/database.php';
        if (empty($config['database']) || $config['database'] != $data['database']) {
            return $this->error('[config/database.php]数据库配置写入失败！');
            exit;
        }
    }
}
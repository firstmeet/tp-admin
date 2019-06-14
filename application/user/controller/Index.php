<?php
namespace app\user\controller;
use think\facade\Config;
use think\facade\App;
use ZipArchive;
use think\Db;
use app\common\model\Market as MarketModel;
use app\common\model\LoginLog;
use app\common\model\App as AppModel;
/**
 * 后台首页
 * Class Index
 * @package app\user\controller
 */
class Index extends Controller
{
    /**
     * 后台首页
     * @return mixed
     */
    public function index()
    {
        $model = new MarketModel;
        $data = $model->getHomeData();
        $log=(new LoginLog)->getAll();
		$version=AppModel::getAppCache();
        return $this->fetch('index', compact('data','version','log'));
    }
	 /**
     * 在线升级
     * @return mixed
     */
	public function update()	
	{	
		$version=AppModel::getAppCache();
        $hosturl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
        $updatehost = "http://day.iirr5.com/index.php/Home/Index/updata";
        $updatehosturl = $updatehost . "?a=update&v=" . $version['ver'] . "&u=" . $hosturl;
        $updatenowinfo = file_get_contents($updatehosturl);
		$file = json_decode($updatenowinfo,true);
        if (strstr($file['file'], 'zip')){
			$rootPath = App::getRootPath();
			$updatedir = $rootPath.'runtime/log/';
            $updatezip = $this->getFile($file['file'],$file['version'],$updatedir);
			$zip = new ZipArchive;
			$res=$zip->open($updatezip['save_path']); 
			//解压缩到test文件夹 
			$archive = $zip->extractTo($rootPath);
			$zip->close(); 
            if ($archive !==true){
                $data['msg'] = "升级失败";
            }else{
                $sqlfile = $updatedir .$file['version'] .'/update.sql';
				if(file_exists($sqlfile)){
					$sql = file_get_contents($sqlfile);
					if($sql){
						$str = explode(PHP_EOL, $sql);
						foreach($str as $v){
							$v=str_replace('bfb_',Config::get('database.prefix'),$v);
							Db::execute($v);
						
						}
					}
				}
                $data['msg'] = "升级完成！请检查是否还有新的更新包";
            }
			unlink($updatezip['save_path']);
			$data['ver']=$file['version'];
		    (new AppModel)->edit($data);
			 return $this->renderSuccess($data['msg']);
        }else{
			$data['msg'] = $file['msg'];
			return $this->renderError($data['msg']);
		}
	}
	 /**
     * 获取文件
     * @return mixed
     */
	function getFile($url, $filename,$save_dir,$type = 0) {
		$filename=$filename.'.zip';
		//获取远程文件所采用的方法
		ob_start();
		readfile($url);
		$content = ob_get_contents();
		ob_end_clean();
		$fp2 = @fopen($save_dir . $filename, 'a');
		fwrite($fp2, $content);
		fclose($fp2);
		unset($content, $url);
		return array(
			'file_name' => $filename,
			'save_path' => $save_dir . $filename
		);
	}
}
<?php

header('content-type:text/html;charset=utf-8');

/**
 * Class LogTools
 * 日志工具类
 */

class LogTools{

    
    // 日志根目录
    private $_log_path = '.';

    // 日志文件，默认为 default.log
    private $_log_file = 'default.log';

    // 日志自定义目录， 默认为 年/月/日
    private $_format = 'Y/m/d';

    // 日志标签
    private $_tag = 'default';

    // 总配置设定
    private static $_CONFIG;


    /**
     * @desc 初始化
     * @param array $config 配置设定
     */
    public function __construct($config=array()){

        // 日志根目录
        if(isset($config['log_path'])){
            $this->_log_path = $config['log_path'];
        }

        // 日志文件
        if(isset($config['log_file'])){
            $this->_log_file = $config['log_file'];
        }

        // 日志自定义目录
        if(isset($config['format'])){
            $this->_format = $config['format'];
        }

        // 日志标签
        if(isset($config['tag'])){
            $this->_tag = $config['tag'];
        }

    }
    
    
    /**
     * 设置配置
     * @param  array $config 总配置设定
     */
    public static function set_config($config=array()){
        
        self::$_CONFIG = $config;
        
    }

    /**
     * @desc 获取日志类对象
     * @param  array $config 总配置设定
     * @return obj
     */
    public static function get_logger($tag='default'){

        // 根据tag从总配置中获取对应设定，如不存在使用default设定
        $config = isset(self::$_CONFIG[$tag])? self::$_CONFIG[$tag] : (isset(self::$_CONFIG['default'])? self::$_CONFIG['default'] : array());

        // 设置标签
        $config['tag'] = $tag!='' && $tag!='default'? $tag : '-';

        // 返回日志类对象
        return new LogTools($config);

    }

    /**
     * @desc 写入信息日志
     * @param  String $data 信息数据
     * @return Boolean
     */
    public function info($data){
        return $this->add('INFO', $data);
    }

    /**
     * @desc 写入警告日志
     * @param  String  $data 警告数据
     * @return Boolean
     */
    public function warn($data){
        
        return $this->add('WARN', $data);
        
    }

    /**
     * @desc 写入错误日志
     * @param  String  $data 错误数据
     * @return Boolean
     */
    public function error($data){
        
        return $this->add('ERROR', $data);
        
    }

    /**
     * @desc 写入日志
     * @param  String  $type 日志类型
     * @param  String  $data 日志数据
     * @return Boolean
     */
    private function add($type, $data){

        // 获取日志文件
        $log_file = $this->get_log_file();

        // 创建日志目录
        $is_create = $this->create_log_path(dirname($log_file));

        // 创建日期时间对象
        $dt = new DateTime;

        // 日志内容
        $log_data = sprintf('[%s] %-5s %s %s'.PHP_EOL, $dt->format('Y-m-d H:i:s'), $type, $this->_tag, $data);

        // 写入日志文件
        if($is_create){
            return file_put_contents($log_file, $log_data, FILE_APPEND);
        }

        return false;

    }

    /**
     * @desc 创建日志目录
     * @param  String  $log_path 日志目录
     * @return Boolean
     */
    private function create_log_path($log_path){

        if(!is_dir($log_path)){
            $mode = 0777;
            $mk_dir = @mkdir($log_path, $mode, true);
            $chmod = @chmod($log_path, $mode);
            return ($mk_dir&&$chmod) ? true : false;
        }
        return true;

    }

    /**
     * @desc 获取日志文件名称
     * @return String
     */
    private function get_log_file(){

        // 创建日期时间对象
        $dt = new DateTime;

        // 计算日志目录格式
        return sprintf("%s/%s/%s", $this->_log_path, $dt->format($this->_format), $this->_log_file);

    }

}


/**

define('LOG_PATH', dirname(__FILE__).'/logs');

// 总配置设定
$config = array(
'default' => array(
'log_path' => LOG_PATH,       // 日志根目录
'log_file' => 'default.log',  // 日志文件
'format' => 'Y/m/d',          // 日志自定义目录，使用日期时间定义
),
'user' => array(
'log_path' => LOG_PATH,
'log_file' => 'user.log',
'format' => 'Y/m/d',
),
'order' => array(
'log_path' => LOG_PATH,
'log_file' => 'order.log',
'format' => 'Y/m/d',
),
);

// 设置总配置
LogTools::set_config($config);

// 调用日志类，使用默认设定
$logger = LogTools::get_logger();
$logger->info('Test Add Info Log');
$logger->warn('Test Add Warn Log');
$logger->error('Test Add Error Log');

// 调用日志类，使用user设定
$logger1 = LogTools::get_logger('user');
$logger1->info('Test Add User Info Log');
$logger1->warn('Test Add User Warn Log');
$logger1->error('Test Add User Error Log');

// 调用日志类，使用order设定
$logger2 = LogTools::get_logger('order');
$logger2->info('Test Add Order Info Log');
$logger2->warn('Test Add Order Warn Log');
$logger2->error('Test Add Order Error Log');

// 调用日志类，设定类型不存在，使用默认设定
$logger3 = LogTools::get_logger('notexists');
$logger3->info('Test Add Not Exists Info Log');
$logger3->warn('Test Add Not Exists Warn Log');
$logger3->error('Test Add Not Exists Error Log');

 */
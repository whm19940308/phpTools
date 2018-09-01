# phpTools
自己做工具--封装PHP的工具类, whmblog.cn自定义php工具类，实现把php项目开发中常用的方法进行归类总结，方便平时的项目开发

// 使用示例  
// 引入两个核心文件  
require_once './_autoload.php';  
require_once './functions.php';  


// 如调用DateTools工具类里的方法，调用示例如下  
$date_tools = new DateTools();  
var_dump($date_tools->getSomeZeroTimeStamp('today'));

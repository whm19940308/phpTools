<?php

header('content-type:text/html;charset=utf-8');

class FileTools{


    /**
     * 说明：实现文件下载的功能
     * @param string $file_path 绝对路径
     */
    static public function downFile($file_path) {

        //判断文件是否存在
        $file_path = iconv('utf-8', 'gb2312', $file_path); //对可能出现的中文名称进行转码
        if (!file_exists($file_path)) {
            exit('文件不存在！');
        }
        $file_name = basename($file_path); //获取文件名称
        $file_size = filesize($file_path); //获取文件大小
        $fp = fopen($file_path, 'r'); //以只读的方式打开文件
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: {$file_size}");
        header("Content-Disposition: attachment;filename={$file_name}");
        $buffer = 1024;
        $file_count = 0;
        //判断文件是否结束
        while (!feof($fp) && ($file_size-$file_count>0)) {
            $file_data = fread($fp, $buffer);
            $file_count += $buffer;
            echo $file_data;
        }
        fclose($fp); //关闭文件

    }


    /**
     * 说明：判断 文件/目录 是否可写（取代系统自带的 is_writeable 函数）
     * @param string $file 文件/目录
     * @return boolean
     */
    public function isWriteAble($file) {
        if (is_dir($file)){
            $dir = $file;
            if ($fp = @fopen("$dir/test.txt", 'w')) {
                @fclose($fp);
                @unlink("$dir/test.txt");
                $writeable = 1;
            } else {
                $writeable = 0;
            }
        } else {
            if ($fp = @fopen($file, 'a+')) {
                @fclose($fp);
                $writeable = 1;
            } else {
                $writeable = 0;
            }
        }
        return $writeable;
    }


    /**
     * 保存数组变量到php文件
     * @param string $path 保存路径
     * @param mixed $value 要保存的变量
     * @return boolean 保存成功返回true,否则false
     */
    function saveArrayToFiles($path,$value){

        $ret = file_put_contents($path, "<?php\treturn " . var_export($value, true) . ";?>");
        return $ret;

    }


    /**
     * 转化格式化的字符串为数组
     * @param string $tag 要转化的字符串,格式如:"id:2;cid:1;order:post_date desc;"
     * @return array 转化后字符串
     * <pre>
     * array(
     *  'id'=>'2',
     *  'cid'=>'1',
     *  'order'=>'post_date desc'
     * )
     */
    function paramLabel($tag = ''){

        $param = array();
        $array = explode(';',$tag);
        foreach ($array as $v){
            $v=trim($v);
            if(!empty($v)){
                list($key,$val) = explode(':',$v);
                $param[trim($key)] = trim($val);
            }
        }
        return $param;

    }


    /**
     * 获取文件扩展名
     * @param string $filename
     */
    function get_file_extension($filename){

        $path_info = pathinfo($filename);
        return strtolower($path_info['extension']);

    }

    /**
     * @desc 目录列表
     * @param	string	$dir		路径
     * @param	int		$parentid	父id
     * @param	array	$dirs		传入的目录
     * @return	array	返回目录列表
     */
    function dirTree($dir, $parentid = 0, $dirs = array()) {

        global $id;
        if ($parentid == 0) $id = 0;
        $list = glob($dir.'*');
        foreach($list as $v) {
            if (is_dir($v)) {
                $id++;
                $dirs[$id] = array('id'=>$id,'parent_id'=>$parentid, 'name'=>basename($v), 'dir'=>$v.'/');
                $dirs = $this->dirTree($v.'/', $id, $dirs);
            }
        }
        return $dirs;

    }
    //    echo '<pre>';
    //    var_dump(dirTree('/home/wwwroot/'));

    
    /**
     * 转化 \ 为 /
     * @param	string	$path	路径
     * @return	string	路径
     */
    function dirPath($path) {
        
        $path = str_replace('\\', '/', $path);
        if(substr($path, -1) != '/') $path = $path.'/';
        return $path;
        
    }
    /**
     * 创建目录
     *
     * @param	string	$path	路径
     * @param	string	$mode	属性
     * @return	string	如果已经存在则返回true，否则为flase
     */
    function dirCreate($path, $mode = 0777) {
        
        if(is_dir($path)){
            return TRUE;
        }
        $ftp_enable = 0;
        $path = $this->dirPath($path);
        $temp = explode('/', $path);
        $cur_dir = '';
        $max = count($temp) - 1;
        for($i=0; $i<$max; $i++) {
            $cur_dir .= $temp[$i].'/';
            if (@is_dir($cur_dir)){
                continue;
            }
            @mkdir($cur_dir, 0777,true);
            @chmod($cur_dir, 0777);
        }
        return is_dir($path);
        
    }
    /**
     * 拷贝目录及下面所有文件
     *
     * @param	string	$fromdir	原路径
     * @param	string	$todir		目标路径
     * @return	string	如果目标路径不存在则返回false，否则为true
     */
    function dirCopy($fromdir, $todir) {

        $fromdir = $this->dirPath($fromdir);
        $todir = $this->dirPath($todir);
        if (!is_dir($fromdir)){
            return FALSE;
        }
        if (!is_dir($todir)) {
            $this->dirCreate($todir);
        }
        $list = glob($fromdir.'*');
        if (!empty($list)) {
            foreach($list as $v) {
                $path = $todir.basename($v);
                if(is_dir($v)) {
                    $this->dirCopy($v, $path);
                } else {
                    copy($v, $path);
                    @chmod($path, 0777);
                }
            }
        }
        return TRUE;

    }
    

    /**
     * 列出目录下所有文件
     *
     * @param	string	$path		路径
     * @param	string	$exts		扩展名
     * @param	array	$list		增加的文件列表
     * @return	array	所有满足条件的文件
     */
    function dirList($path, $exts = '', $list= array()) {

        $path = $this->dirPath($path);
        $files = glob($path.'*');
        foreach($files as $v) {
            if (!$exts || pathinfo($v, 4) == $exts) {
                $list[] = $v;
                if (is_dir($v)) {
                    $list = $this->dirList($v, $exts, $list);
                }
            }
        }
        return $list;

    }

    /**
     * 删除目录及目录下面的所有文件
     *
     * @param	string	$dir	路径
     * @return	bool	如果成功则返回 TRUE，失败则返回 FALSE
     */
    function dirDelete($dir) {

        $dir = $this->dirPath($dir);
        if (!is_dir($dir)){
            return FALSE;
        }
        $list = glob($dir.'*');
        foreach($list as $v) {
            is_dir($v) ? $this->dirDelete($v) : @unlink($v);
        }
        return @rmdir($dir);

    }
    
}
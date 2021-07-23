<?php 

if(!function_exists("convertStrStructure")){
    function convertStrStructure($info){
        $info = convertStdClassToArray($info);
        if(!is_array($info)){
            return $info."";
        }
        foreach($info as $key=>$item){
            if(is_array($item)){
                $info[$key] = convertStrStructure($item);
            }else{
                $info[$key] = $info[$key]."";
            }
        }
        return $info;
    }
}

if(!function_exists("convertStdClassToArray")){
    function convertStdClassToArray($class){
        $classStr = json_encode($class);
        $ret = json_decode($classStr,1);
        return $ret;
    }
}

if (!function_exists('makeUploadFileName')) {
    function makeUploadFileName()
    {
        $datetime = date('YmdHis');
        $datetime = $datetime . rand(1111, 9999);
        return $datetime;
    }
}

if (!function_exists('correctProImgPath')) {
    function correctProImgPath($path)
    {
        if($path == ""){
            return url("assets/img/default.jpg");
        }
        if (0 !== strpos($path, 'http')) { 
            return url($path);
        }
        return $path;
    }
}

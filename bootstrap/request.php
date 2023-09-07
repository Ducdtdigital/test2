<?php
/**
 * getRequest Lấy thông tin request gần như Slim Framework
 * @param  string $uri $_SERVER['REQUEST_URI']
 * @param  string $currentPath $_SERVER['PHP_SELF']
 * @return array
 */
function getRequest($uri= '', $currentPath='')
{
    $uri = filter_var($uri, FILTER_SANITIZE_URL);
    $pathInfo = pathinfo($currentPath);
    $dir_root = $pathInfo['dirname'];
    //$path = str_replace($dir_root, '',$uri); // localhot
    $path = $uri;  // remote
    if(strpos($path, '?')){
        $arr = explode('?', $path);
        $url = $arr[0]; $query = $arr[1];
    }else{
        $url = $path; $query = '';
    }
    $domain = 'https://thbvietnam.com';
    return array(
            'getBaseUrl'=> $domain.$dir_root,
            'getBasePath' => $dir_root,
            'getPath' => $path,
            'getUrl' => trim($url,'/'),
            'getQuery' => $query
            );
}
/**
 * get the query parameters as an associative array
 * @param  string $request getRequest['getQuery']
 * @return array
 */
function getQueryParams($request){
    if($request == ''){
        return false;
    }
    $params = array();
    if(strpos($request, '&')){
        $arr =  explode('&',$request);
        for ($i=0; $i < count($arr); $i++) {
            $arrs =  explode('=',$arr[$i]);
            $params[$arrs[0]] = $arrs[1];
        }
    }else{
        $arr =  explode('=',$request);
        $params[$arr[0]] = $arr[1];
    }
    return $params;
}
/**
 * withRedirect response with status 301
 * @param  string  $uri
 * @param  integer $status
 */
function withRedirect($uri, $status = 301){
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: {$uri}");
    exit;
}
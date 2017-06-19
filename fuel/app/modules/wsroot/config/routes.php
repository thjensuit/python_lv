<?php
return array(
    #'_root_' => 'wsroot/index/index',
    '_root_' => 'wsroot/product/index',
    'index' => 'wsroot/product/index',
    'product' => 'wsroot/product/themmoi',
    'product' => 'wsroot/product/index',
    'product/(:num)' => 'wsroot/product/chinhsua/$1',
    'product/del/(:num)' => 'wsroot/product/del/$1',
    'brand/(:num)' => 'wsroot/brand/chinhsua/$1',
    'brand/del/(:num)' => 'wsroot/brand/del/$1',
    'cate/(:num)' => 'wsroot/cate/chinhsua/$1',
    'cate/del/(:num)' => 'wsroot/cate/del/$1',

    'handtools' => 'wsroot/handtools/index',
    'logout' => 'wsroot/index/logout',
);

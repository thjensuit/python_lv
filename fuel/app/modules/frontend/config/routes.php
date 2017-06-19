<?php
return array(
    '_root_' => 'frontend/index',
    'index' => 'frontend/index',
    'laptop/(:any)' => 'frontend/index/laptop/$1',
    'cateajax' => 'frontend/index/cateajax',
    'search' => 'frontend/index/search',


     '_404_'   => 'frontend/index/404',
     'detail/(:any)'   => 'frontend/index/detail/$1/$2',

);
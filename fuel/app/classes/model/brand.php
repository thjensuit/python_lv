<?php
class Model_Brand extends \Orm\Model{
    protected static $_primary_key=array("id");
    protected static $_table_name="brand";

    public static function getAllItem()
    {   
        $query = Model_Brand::query()->where('status', 1)->order_by('id', 'desc');
    	return $query->get();
    }
    public static function getListBrand()
    {
    	$allBrand = self::query()->get();
    	$results = array();
    	foreach ($allBrand as $key => $value) {
    		 $results[$value['id']] = $value['value'];
    	}
    	return $results;
    }
}
?>
<?php
class Model_Cate extends \Orm\Model{
    protected static $_primary_key=array("id");
    protected static $_table_name="category";
    
    public static function getAllItem()
    {
    	return Model_Cate::query()->where('status', 1)->order_by('id', 'desc')->get();
    }
	public static function getListCate()
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
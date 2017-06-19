<?php
class Model_Product extends \Orm\Model{
    protected static $_primary_key=array("id");
    protected static $_table_name="product";
    protected static $_mysql_timestamp = true;

    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events'          => array('before_insert'),
            'mysql_timestamp' => true,
            'property'        => 'timestamp',
        ),
        // 'Orm\Observer_UpdatedAt' => array(
        //     'events'          => array('before_save'),
        //     'mysql_timestamp' => true,
        //     'property'        => 'up_datetime',
        // ),
    );
/*    public static function validate($factory)
    {
        $val = Validation::forge($factory);
        $val->add_field('admin_id', 'User ID', 'required|max_length[100]');
        $val->add_field('admin_pass', 'PASS', 'required|max_length[100]');
        $val->add_field('admin_name', 'User Name', 'required|max_length[100]');
        return $val;
    }*/
    public static function getAllItem($brandID =0,$cateID=0)
    {
    	$query = Model_Product::query()->select('id','link_seo','brandID','cateID','img','name','price','shortdetail')->where('status', 1)->order_by('id', 'desc');
        if($brandID != 0){
            $query->where('brandID',$brandID);
        }
        if($cateID != 0){
            foreach ($cateID as $key => $value) {
                $query->where('cateID','like',"%,".$value.",%");
            }
        }
    	return $query->get();
    }
    public static function getDetailbyName($name)
    {
        //->where('status', 1)
        return Model_Product::query()->where('link_seo',$name)->get_one();
    }
    public static function getDetailbyID($id)
    {
    	//->where('status', 1)
    	return Model_Product::query()->where('id',$id)->get_one();
    }
}
?>
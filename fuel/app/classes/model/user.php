<?php
class Model_User extends \Orm\Model{
    protected static $_primary_key=array("pk");
    protected static $_table_name="m_admin_user";
    protected static $_properties = array(
        'pk',
        'admin_id',
        'admin_pass',
        'admin_name',
        'login_time',
        'login_hash',
        'reg_datetime',
        'up_datetime',
        'sid',
    );

    public static function validate($factory)
    {
        $val = Validation::forge($factory);
        $val->add_field('admin_id', 'User ID', 'required|max_length[100]');
        $val->add_field('admin_pass', 'PASS', 'required|max_length[100]');
        $val->add_field('admin_name', 'User Name', 'required|max_length[100]');
        return $val;
    }
}
?>
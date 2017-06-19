<?php
/**
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.8
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2016 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Auth;

/**
 * SimpleAuth basic login driver
 *
 * @package     Fuel
 * @subpackage  Auth
 */
class Auth_Login_Simpleauth extends \Auth_Login_Driver
{
	/**
	 * Load the config and setup the remember-me session if needed
	 */
	public static function _init()
	{
		\Config::load('simpleauth', true);

		// setup the remember-me session object if needed
		if (\Config::get('simpleauth.remember_me.enabled', false))
		{
			static::$remember_me = \Session::forge(array(
				'driver' => 'cookie',
				'cookie' => array(
					'cookie_name' => \Config::get('simpleauth.remember_me.cookie_name', 'rmcookie'),
				),
				'encrypt_cookie' => true,
				'expire_on_close' => false,
				'expiration_time' => \Config::get('simpleauth.remember_me.expiration', 86400 * 31),
			));
		}
	}

	/**
	 * @var  Database_Result  when login succeeded
	 */
	protected $user = null;

	/**
	 * @var  array  value for guest login
	 */
	protected static $guest_login = array(
		'id' => 0,
		'admin_id' => 'guest',
		'group' => '0',
		'login_hash' => false,
		'email' => false,
	);

	/**
	 * @var  array  SimpleAuth class config
	 */
	protected $config = array(
		'drivers' => array('group' => array('Simplegroup')),
		'additional_fields' => array('profile_fields'),
	);

	/**
	 * Check for login
	 *
	 * @return  bool
	 */
	protected function perform_check()
	{
		// fetch the admin_id and login hash from the session
		$admin_id    = \Session::get('admin_id');
		$login_hash  = \Session::get('login_hash');

		// only worth checking if there's both a admin_id and login-hash
		if ( ! empty($admin_id) and ! empty($login_hash))
		{
			if (is_null($this->user) or ($this->user['admin_id'] != $admin_id and $this->user != static::$guest_login))
			{
				$this->user = \DB::select_array(\Config::get('simpleauth.table_columns', array('*')))
					->where('admin_id', '=', $admin_id)
					->from(\Config::get('simpleauth.table_name'))
					->execute(\Config::get('simpleauth.db_connection'))->current();
			}

			// return true when login was verified, and either the hash matches or multiple logins are allowed
			if ($this->user and (\Config::get('simpleauth.multiple_logins', false) or $this->user['login_hash'] === $login_hash))
			{
				return true;
			}
		}

		// not logged in, do we have remember-me active and a stored user_id?
		elseif (static::$remember_me and $user_id = static::$remember_me->get('user_id', null))
		{
			return $this->force_login($user_id);
		}

		// no valid login when still here, ensure empty session and optionally set guest_login
		$this->user = \Config::get('simpleauth.guest_login', true) ? static::$guest_login : false;
		\Session::delete('admin_id');
		\Session::delete('login_hash');

		return false;
	}

	/**
	 * Check the user exists
	 *
	 * @return  bool
	 */
	public function validate_user($admin_id_or_email = '', $password = '')
	{
		$admin_id_or_email = trim($admin_id_or_email) ?: trim(\Input::post(\Config::get('simpleauth.admin_id_post_key', 'admin_id')));
		$password = trim($password) ?: trim(\Input::post(\Config::get('simpleauth.password_post_key', 'password')));

		if (empty($admin_id_or_email) or empty($password))
		{
			return false;
		}

		$password = $this->hash_password($password);
		$user = \DB::select_array(\Config::get('simpleauth.table_columns', array('*')))
			->where_open()
			->where('admin_id', '=', $admin_id_or_email)
			->where_close()
			->where('admin_pass', '=', $password)
			->from(\Config::get('simpleauth.table_name'))
			->execute(\Config::get('simpleauth.db_connection'))->current();

		return $user ?: false;
	}

	/**
	 * Login user
	 *
	 * @param   string
	 * @param   string
	 * @return  bool
	 */
	public function login($admin_id_or_email = '', $password = '')
	{
		if ( ! ($this->user = $this->validate_user($admin_id_or_email, $password)))
		{
			$this->user = \Config::get('simpleauth.guest_login', true) ? static::$guest_login : false;
			\Session::delete('admin_id');
			\Session::delete('login_hash');
			return false;
		}

		// register so Auth::logout() can find us
		Auth::_register_verified($this);

		\Session::set('admin_id', $this->user['admin_id']);
		\Session::set('login_hash', $this->create_login_hash());
		\Session::instance()->rotate();
		return true;
	}

	/**
	 * Force login user
	 *
	 * @param   string
	 * @return  bool
	 */
	public function force_login($user_id = '')
	{
		if (empty($user_id))
		{
			return false;
		}

		$this->user = \DB::select_array(\Config::get('simpleauth.table_columns', array('*')))
			->where_open()
			->where('id', '=', $user_id)
			->where_close()
			->from(\Config::get('simpleauth.table_name'))
			->execute(\Config::get('simpleauth.db_connection'))
			->current();

		if ($this->user == false)
		{
			$this->user = \Config::get('simpleauth.guest_login', true) ? static::$guest_login : false;
			\Session::delete('admin_id');
			\Session::delete('login_hash');
			return false;
		}

		\Session::set('admin_id', $this->user['admin_id']);
		\Session::set('login_hash', $this->create_login_hash());

		// and rotate the session id, we've elevated rights
		\Session::instance()->rotate();

		// register so Auth::logout() can find us
		Auth::_register_verified($this);

		return true;
	}

	/**
	 * Logout user
	 *
	 * @return  bool
	 */
	public function logout()
	{
		$this->user = \Config::get('simpleauth.guest_login', true) ? static::$guest_login : false;
		\Session::delete('admin_id');
		\Session::delete('login_hash');
		return true;
	}

	/**
	 * Create new user
	 *
	 * @param   string
	 * @param   string
	 * @param   string  must contain valid email address
	 * @param   int     group id
	 * @param   Array
	 * @return  bool
	 */
	public function create_user($admin_id, $password, $admin_name)
	{
		$password = trim($password);
        $admin_id = trim($admin_id);
        $admin_name = trim($admin_name);
        $errors = array();


		if (empty($admin_id) or empty($password))
		{
			throw new \SimpleUserUpdateException('admin_id or password address is not given, or email address is invalid', 1);
		}

		$same_users = \DB::select_array(\Config::get('simpleauth.table_columns', array('*')))
			->where('admin_id', '=', $admin_id)
			->from(\Config::get('simpleauth.table_name'))
			->execute(\Config::get('simpleauth.db_connection'));

		if ($same_users->count() > 0)
		{
				return false;
		}

		$user = array(
			'admin_id'        => (string) $admin_id,
			'admin_pass'        => $this->hash_password((string) $password),
			'admin_name'      => (string) $admin_name,
            'up_datetime'     => date('Y-m-d H:i:s'),
            'login_time'      => date('Y-m-d H:i:s'),
			'login_hash'      => '',
            'sid'             => '0',
		);
		$result = \DB::insert(\Config::get('simpleauth.table_name'))
			->set($user)
			->execute(\Config::get('simpleauth.db_connection'));
		return ($result[1] > 0) ? : false;
	}

	/**
	 * Update a user's properties
	 * Note: admin_id cannot be updated, to update password the old password must be passed as old_password
	 *
	 * @param   Array  properties to be updated including profile fields
	 * @param   string
	 * @return  bool
	 */
	public function update_user($values, $admin_id = null)
	{
		$admin_id = $admin_id ?: $this->user['admin_id'];
		$current_values = \DB::select_array(\Config::get('simpleauth.table_columns', array('*')))
			->where('admin_id', '=', $admin_id)
			->from(\Config::get('simpleauth.table_name'))
			->execute(\Config::get('simpleauth.db_connection'));

		if (empty($current_values))
		{
			throw new \SimpleUserUpdateException('admin_id not found', 4);
		}

		$update = array();
		if (array_key_exists('admin_id', $values))
		{
			throw new \SimpleUserUpdateException('admin_id cannot be changed.', 5);
		}
		if (array_key_exists('password', $values))
		{
			if (empty($values['old_password'])
				or $current_values->get('password') != $this->hash_password(trim($values['old_password'])))
			{
				throw new \SimpleUserWrongPassword('Old password is invalid');
			}

			$password = trim(strval($values['password']));
			if ($password === '')
			{
				throw new \SimpleUserUpdateException('Password can\'t be empty.', 6);
			}
			$update['password'] = $this->hash_password($password);
			unset($values['password']);
		}
		if (array_key_exists('old_password', $values))
		{
			unset($values['old_password']);
		}
		if (array_key_exists('email', $values))
		{
			$email = filter_var(trim($values['email']), FILTER_VALIDATE_EMAIL);
			if ( ! $email)
			{
				throw new \SimpleUserUpdateException('Email address is not valid', 7);
			}
			$matches = \DB::select()
				->where('email', '=', $email)
				->where('id', '!=', $current_values[0]['id'])
				->from(\Config::get('simpleauth.table_name'))
				->execute(\Config::get('simpleauth.db_connection'));
			if (count($matches))
			{
				throw new \SimpleUserUpdateException('Email address is already in use', 11);
			}
			$update['email'] = $email;
			unset($values['email']);
		}
		if (array_key_exists('group', $values))
		{
			if (is_numeric($values['group']))
			{
				$update['group'] = (int) $values['group'];
			}
			unset($values['group']);
		}
		if ( ! empty($values))
		{
			$profile_fields = @unserialize($current_values->get('profile_fields')) ?: array();
			foreach ($values as $key => $val)
			{
				if ($val === null)
				{
					unset($profile_fields[$key]);
				}
				else
				{
					$profile_fields[$key] = $val;
				}
			}
			$update['profile_fields'] = serialize($profile_fields);
		}

		$update['updated_at'] = \Date::forge()->get_timestamp();

		$affected_rows = \DB::update(\Config::get('simpleauth.table_name'))
			->set($update)
			->where('admin_id', '=', $admin_id)
			->execute(\Config::get('simpleauth.db_connection'));

		// Refresh user
		if ($this->user['admin_id'] == $admin_id)
		{
			$this->user = \DB::select_array(\Config::get('simpleauth.table_columns', array('*')))
				->where('admin_id', '=', $admin_id)
				->from(\Config::get('simpleauth.table_name'))
				->execute(\Config::get('simpleauth.db_connection'))->current();
		}

		return $affected_rows > 0;
	}

	/**
	 * Change a user's password
	 *
	 * @param   string
	 * @param   string
	 * @param   string  admin_id or null for current user
	 * @return  bool
	 */
	public function change_password($old_password, $new_password, $admin_id = null)
	{
		try
		{
			return (bool) $this->update_user(array('old_password' => $old_password, 'password' => $new_password), $admin_id);
		}
		// Only catch the wrong password exception
		catch (SimpleUserWrongPassword $e)
		{
			return false;
		}
	}

	/**
	 * Generates new random password, sets it for the given admin_id and returns the new password.
	 * To be used for resetting a user's forgotten password, should be emailed afterwards.
	 *
	 * @param   string  $admin_id
	 * @return  string
	 */
	public function reset_password($admin_id)
	{
		$new_password = \Str::random('alnum', 8);
		$password_hash = $this->hash_password($new_password);

		$affected_rows = \DB::update(\Config::get('simpleauth.table_name'))
			->set(array('password' => $password_hash))
			->where('admin_id', '=', $admin_id)
			->execute(\Config::get('simpleauth.db_connection'));

		if ( ! $affected_rows)
		{
			throw new \SimpleUserUpdateException('Failed to reset password, user was invalid.', 8);
		}

		return $new_password;
	}

	/**
	 * Deletes a given user
	 *
	 * @param   string
	 * @return  bool
	 */
	public function delete_user($admin_id)
	{
		if (empty($admin_id))
		{
			throw new \SimpleUserUpdateException('Cannot delete user with empty admin_id', 9);
		}

		$affected_rows = \DB::delete(\Config::get('simpleauth.table_name'))
			->where('admin_id', '=', $admin_id)
			->execute(\Config::get('simpleauth.db_connection'));

		return $affected_rows > 0;
	}

	/**
	 * Creates a temporary hash that will validate the current login
	 *
	 * @return  string
	 */
	public function create_login_hash()
	{
		if (empty($this->user))
		{
			throw new \SimpleUserUpdateException('User not logged in, can\'t create login hash.', 10);
		}

		$login_time = date('Y-m-d H:i:s');
        $login_hash = sha1(\Config::get('simpleauth.login_hash_salt').$this->user['admin_id'].$login_time);

		\DB::update(\Config::get('simpleauth.table_name'))
			->set(array('login_time' => $login_time, 'login_hash' => $login_hash))
			->where('admin_id', '=', $this->user['admin_id'])
			->execute(\Config::get('simpleauth.db_connection'));

		$this->user['login_hash'] = $login_hash;

		return $login_hash;
	}

	/**
	 * Get the user's ID
	 *
	 * @return  Array  containing this driver's ID & the user's ID
	 */
	public function get_user_id()
	{
		if (empty($this->user))
		{
			return false;
		}

		return array($this->id, (int) $this->user['id']);
	}

	/**
	 * Get the user's groups
	 *
	 * @return  Array  containing the group driver ID & the user's group ID
	 */
	public function get_groups()
	{
		if (empty($this->user))
		{
			return false;
		}

		return array(array('Simplegroup', $this->user['group']));
	}

	/**
	 * Getter for user data
	 *
	 * @param  string  name of the user field to return
	 * @param  mixed  value to return if the field requested does not exist
	 *
	 * @return  mixed
	 */
	public function get($field, $default = null)
	{
		if (isset($this->user[$field]))
		{
			return $this->user[$field];
		}
		elseif (isset($this->user['profile_fields']))
		{
			return $this->get_profile_fields($field, $default);
		}

		return $default;
	}

	/**
	 * Get the user's emailaddress
	 *
	 * @return  string
	 */
	public function get_email()
	{
		return $this->get('email', false);
	}

	/**
	 * Get the user's screen name
	 *
	 * @return  string
	 */
	public function get_screen_name()
	{
		if (empty($this->user))
		{
			return false;
		}

		return $this->user['admin_id'];
	}

	/**
	 * Get the user's profile fields
	 *
	 * @return  Array
	 */
	public function get_profile_fields($field = null, $default = null)
	{
		if (empty($this->user))
		{
			return false;
		}

		if (isset($this->user['profile_fields']))
		{
			is_array($this->user['profile_fields']) or $this->user['profile_fields'] = (@unserialize($this->user['profile_fields']) ?: array());
		}
		else
		{
			$this->user['profile_fields'] = array();
		}

		return is_null($field) ? $this->user['profile_fields'] : \Arr::get($this->user['profile_fields'], $field, $default);
	}

	/**
	 * Extension of base driver method to default to user group instead of user id
	 */
	public function has_access($condition, $driver = null, $user = null)
	{
		if (is_null($user))
		{
			$groups = $this->get_groups();
			$user = reset($groups);
		}
		return parent::has_access($condition, $driver, $user);
	}

	/**
	 * Extension of base driver because this supports a guest login when switched on
	 */
	public function guest_login()
	{
		return \Config::get('simpleauth.guest_login', true);
	}
}

// end of file simpleauth.php

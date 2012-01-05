<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_User extends Model_Auth_User {

    protected $_table_name = 'phpbb3.phpbb_users';

    protected $_primary_key = 'user_id';

    protected $_belongs_to = array(
        'city' => array(
            'model' => 'city',
            'foreign_key' => 'city_id',
        ),
    );

    /**
     * Tests if a unique key value exists in the database.
     *
     * @param   mixed    the value to test
     * @param   string   field name
     * @return  boolean
     */
    public function unique_key_exists($value, $field = NULL)
    {
        if ($value === NULL) {
            return false;
        }

        // TODO: fix!
        if ($field !== 'email') {
            // return false;
        }

        if ($field === NULL)
        {
            // Automatically determine field by looking at the value
            $field = $this->unique_key($value);
        }

        if (in_array($field, array_keys($this->_aliased))) {
            $field = $this->_aliased[$field];
        }

        $query = DB::select(array('COUNT("*")', 'total_count'))
            ->from($this->_table_name)
            ->where($field, '=', $value)
            ->where($this->_primary_key, '!=', $this->pk());

        return (bool) $query->execute($this->_db)->get('total_count');
    }

    public function get_roles($extended = FALSE)
    {
        $roles = FALSE;

        if ($this->loaded()) {
            $roles = array_map(function($role) use ($extended)
            {
                return ($extended ? $role->as_array() : $role->name);
            }, $this->roles->find_all()->as_array());
        }

        return $roles;
    }

    public function has_role($rolename)
    {
        $role = FALSE;

        if ($this->loaded()) {
            $roles = $this->get_roles(FALSE);
            $role = in_array($rolename, $roles);
        }

        return $role;
    }

    /**
     * Allows a model use both email and username as unique identifiers for login
     *
     * @param   string  unique value
     * @return  string  field name
     */
    public function unique_key($value)
    {
        return Valid::email($value) ? 'user_email' : 'username';
    }

    /**
     * Password validation for plain passwords.
     *
     * @param  array $values
     * @return Validation
     */
    public static function get_password_validation($values)
    {
        $valid = Validation::factory($values)
            ->rule('password', 'min_length', array(':value', 4))
            ->rule('password_confirm', 'matches', array(':validation', ':field', 'password'));

        if ($valid->check() === FALSE) {
            throw new ORM_Validation_Exception('Model_User', $valid);
        }

        return $valid;
    }

    /* SSO Methods below */

    /**
     * Finds SSO user based on supplied data.
     *
     * @param   string  $provider_field
     * @param   array   $data
     * @return  ORM
     */
    public function find_sso_user($provider_field, $data)
    {
        $user = $this->where($provider_field, '=', $data['id']);
        if (isset($data['email'])) {
            $user->or_where('user_email', '=', $data['email']);
        }

        return $user->find();
    }

    /**
     * Sign-up using data from OAuth provider.
     *
     * Override this method to add your own sign up process.
     *
     * @param   ORM     $user
     * @param   array   $data
     * @param   string  $provider
     * @return  ORM
     */
    public function sso_signup(ORM $user, array $data, $provider_field)
    {
        if ( ! $user->loaded()) {
            // Add user
            $user->$provider_field = $data['id'];

            $user->user_type = 0;
            $user->username = $provider_field.$data['id'];
            $user->user_password = md5($user->username.microtime(TRUE));

            if ($provider_field == 'vkontakte_id' OR $provider_field == 'facebook_id') {
                $user->email = $user->username.'@bt-lady.com.ua';

                $user->firstname = $data['first_name'];
                $user->lastname = $data['last_name'];

                if (array_key_exists('birthday', $data) OR array_key_exists('bdate', $data)) {
                    $date_key = (isset($data['bdate']) ? 'bdate' : 'birthday');

                    $date = NULL;
                    try {
                        $_date = new DateTime($data[$date_key]);
                        $date = $_date->format('d-m-Y');
                    } catch (Exception $e) { }

                    $user->birthday = $date;
                }

                if (array_key_exists('email', $data)) {
                    $user->email = $data['email'];
                }

                if (array_key_exists('photo_big', $data)) {
                    $user->avatar = $data['photo_big'];
                }

                if (array_key_exists('location', $data) OR array_key_exists('hometown', $data)) {
                    $key = isset($data['location']) ? 'location' : 'hometown';

                    if (is_array($data[$key]) AND array_key_exists('name', $data[$key])) {
                        $user->user_from = $data[$key]['name'];
                    }
                }

                if (array_key_exists('city', $data) AND array_key_exists('country', $data)) {
                    $user->city_id = ORM::factory('city', array('vkontakte_cid' => $data['city']));
                }
            } elseif ($provider_field == 'twitter_id') {
                $user->email = $data['screen_name'].'@twitter.com';
            }

            // Save user
            $user->save();

            $user->add('roles', ORM::factory('role', array('name' => $provider_field)));
            $user->add('roles', ORM::factory('role', array('name' => 'social')));

            $user->update();
        } elseif ($user->loaded() AND empty($user->$provider_field)) {
            // If user is found, but provider id is missing add it to details.
            // We can do this merge, because this means user is found by email address,
            // that is already confirmed by this OAuth provider, so it's considered trusted.
            $user->$provider_field = $data['id'];

            // Save user
            $user->save();
        }

        // Return user
        return $user;
    }

    public function get_picture($email = NULL, $size = 80)
    {
        $img = '/i/default-user-avatar.png';
        if ($this->loaded()) {
            if ($this->avatar) {
                if (substr($this->avatar, 0, 7) != 'http://') {
                    $img = array(
                        '/thumbnails',
                        substr($this->avatar, 0, 2),
                        substr($this->avatar, 2, 4),
                        'cropr_100x100',
                        $this->avatar
                    );
                    $img = implode('/', array_filter($img));
                } else {
                    $img = $this->avatar;
                }
            } elseif ($this->user_avatar) {
                $img = 'http://forum.bt-lady.com.ua/download/file.php?avatar='.$this->user_avatar;
            } elseif ($this->facebook_id) {
                $img = 'http://graph.facebook.com/'.$this->facebook_id.'/picture/?type=normal';
            } elseif ($this->twitter_id) {
                $img = 'http://api.twitter.com/1/users/profile_image/'.$this->twitter_id.'.png?size=bigger';
            } elseif ($this->email) {
                $img = 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($this->email))).'?s='.$size.'&d=mm';
            }
        }

        return $img;
    }

    public function get_social_link()
    {
        return $this->get_user_link();
    }

    /**
     * Rules for the user model. Because the password is _always_ a hash
     * when it's set,you need to run an additional not_empty rule in your controller
     * to make sure you didn't hash an empty string. The password rules
     * should be enforced outside the model or with a model helper method.
     *
     * @return array Rules
     */
    public function rules()
    {
        return array(
            'username' => array(
                array('min_length', array(':value', 2)),
                array('max_length', array(':value', 32)),
                array('regex', array(':value', '/^[- \pL\pN_.]++$/uD')),
                array(array($this, 'username_available'), array(':validation', ':field')),
            ),
            'user_password' => array(
                array('not_empty'),
            ),
            'user_email' => array(
                // array('not_empty'),
                array('min_length', array(':value', 4)),
                array('max_length', array(':value', 127)),
                array('email'),
                array(array($this, 'email_available'), array(':validation', ':field')),
            ),
            'firstname' => array(
                array('min_length', array(':value', 2)),
                array('max_length', array(':value', 25)),
            ),
            'lastname' => array(
                array('min_length', array(':value', 2)),
                array('max_length', array(':value', 25)),
            ),
        );
    }

    public function filters()
    {
        return array(
            'user_password' => array(
                array(array(Auth::instance(), 'hash'))
            )
        );
    }

    /**
     * Insert a new object to the database
     *
     * @param  Validation $validation Validation object
     * @return ORM
     */
    public function create(Validation $validation = NULL)
    {
        /**
         * PHPBB fields
         * @var object(mixed)
         */
        $this->username_clean = NULL;
        if ( ! empty($this->username)) {
            $this->username_clean = mb_strtolower($this->username);
        }
        $this->user_pass_convert = 0;
        $this->group_id = 3316;
        $this->user_email_hash = Helper::phpbb_email_hash($this->email);

        // Опциональные для заполнения поля
        $this->user_style = 1;
        $this->user_lang = 'ru';
        $this->user_regdate = time();
        $this->user_passchg = time();
        $this->user_lastmark = time();
        $this->user_timezone = '2.00';
        $this->user_dateformat = 'D M d, Y H:i';
        $this->user_options = 230271;
        $this->user_form_salt = Helper::unique_id(); // FIXIT: см. внутрь функции

        $orm = parent::create($validation);

        if ($orm->loaded()) {
            $query = 'INSERT INTO phpbb3.phpbb_user_group(user_id, group_id, user_pending) ';
            $query.= 'VALUES(:user_id, :group_id, 0)';

            $user_group = DB::query(Database::INSERT, $query);
            $user_group->parameters(array(
                ':user_id' => $orm->user_id,
                ':group_id' => $orm->group_id,
            ));

            $user_group->execute();
        }

        return $orm;
    }

    public function update(Validation $validation = NULL)
    {
        if ( ! empty($this->username)) {
            $this->username_clean = mb_strtolower($this->username);
        }
        $this->user_email_hash = Helper::phpbb_email_hash($this->email);

        return parent::update($validation);
    }

    public function aliases()
    {
        return array(
            'id'         => 'user_id',
            'password'   => 'user_password',
            'email'      => 'user_email',
            'birthday'   => 'user_birthday',
            'last_login' => 'user_lastvisit',
            'reg_date'   => 'user_regdate',
            'ip'         => 'user_ip',
        );
    }

    /**
     * Complete the login for a user by incrementing the logins and saving login timestamp
     *
     * @return void
     */
    public function complete_login()
    {
        if ($this->_loaded)
        {
            // Update the number of logins
            // $this->logins = new Database_Expression('logins + 1');

            // Set the last login date
            $this->last_login = time();

            $this->ip = Request::$client_ip;

            // Save the user
            $this->update();
        }
    }
}

<?php

/**
* ownCloud
*
* @author Tom Needham
* @copyright 2012 Tom Needham tom@owncloud.com
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either
* version 3 of the License, or any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*
* You should have received a copy of the GNU Affero General Public
* License along with this library.  If not, see <http://www.gnu.org/licenses/>.
*
*/

/**
* Class for user authentication against an Amahi install
*/
class OC_User_Amahi extends OC_User_Backend {

	const HOSTNAME = 'localhost';
	const USERNAME = 'amahihda';
	const PASSWORD = 'AmahiHDARulez';
	const DATABASE = 'hda_production';
	
	self::init = false;
	
	/**
	 * create the database connection
	 */
	private function init(){
		if(!self::$init){
		// Make the database connection
		$conn = mysql_connect(HOSTNAME, USERNAME, PASSWORD);
		mysql_select_db(DATABASE);
		}
	}
	
	/**
	 * encrypt the password
	 * @param string $password
	 * @param string $salt
	 * return string the hashed password
	 */
	private function encrypt($password, $salt){
		$digest = $password . $salt;
		for ( $i = 0; $i < 20; $i++ )
		$digest = hash("sha512", $digest);
		return $digest;
	}
	
	/**
	 * @brief Check if the password is correct
	 * @param $uid The username
	 * @param $password The password
	 * @returns string
	 *
	 * Check if the password is correct without logging in the user
	 * returns the user id or false
	 */
	public function checkPassword( $uid, $password ) {
		$this->init();
		$sql = "SELECT `password_salt`, `crypted_password` FROM `users` WHERE `login` = $uid";
		$results = mysql_query($sql);
		$user = mysql_fetch_assoc();
		$user = $user[0];
		$password = $this->encrypt($password, $user['password_salt']);
		return ($password==$user['password']);
	}
	
	/**
	 * @brief Get a list of all users
	 * @returns array with all uids
	 *
	 * Get a list of all users.
	 */
	public function getUsers($search = '', $limit = null, $offset = null) {
		$this->init();
		$sql = "SELECT * FROM `users`";
		$results = mysql_query($sql);
		$users= mysql_fetch_assoc();
		return $users;
	}
	
	/**
	 * @brief check if a user exists
	 * @param string $uid the username
	 * @return boolean
	 */
	public function userExists($uid) {
		$this->init();
		$sql = "SELECT * FROM `user` WHERE `login`= $uid";
		$results = mysql_query($sql);
		return mysql_num_rows($results);
	}

}

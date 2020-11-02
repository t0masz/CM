<?php

namespace Model;

use Nette;
use Nette\Utils\Strings;
use Nette\Security\Passwords;
use Nette\Database\Context;


/**
* Users management.
*/
class Authenticator implements Nette\Security\IAuthenticator
{
	const
		TABLE_NAME = 'users',
		COLUMN_ID = 'id',
		COLUMN_NAME = 'username',
		COLUMN_PASSWORD = 'password',
		COLUMN_PASSWORD_HASH = 'password',
		COLUMN_ROLE = 'role',
		PASSWORD_MAX_LENGTH = 4096;


	/** @var Nette\Database\Context */
	private $database;
	public function __construct(Context $database, Passwords $passwords)
	{
		$this->database = $database;
        $this->passwords = $passwords;
	}

	/**
	 * Performs an authentication.
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials): Nette\Security\IIdentity
	{
		list($username, $password) = $credentials;
		$row = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_NAME, $username)->fetch();
		if (!$row) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
		} elseif ($row[self::COLUMN_PASSWORD] == hash('sha512', $credentials[self::PASSWORD])) {
			$row->update([
				self::COLUMN_PASSWORD_HASH => $this->passwords->hash($password),
			]);
		} elseif (!$this->passwords->verify($password, $row[self::COLUMN_PASSWORD_HASH])) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		} elseif ($this->passwords->needsRehash($row[self::COLUMN_PASSWORD_HASH])) {
			$row->update([
				self::COLUMN_PASSWORD_HASH => $this->passwords->hash($password),
			]);
		}
		$arr = $row->toArray();
		unset($arr[self::COLUMN_PASSWORD_HASH]);
		return new Nette\Security\Identity($row[self::COLUMN_ID], $row[self::COLUMN_ROLE], $arr);
	}

}

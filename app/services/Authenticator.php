<?php

use Nette\Security as NS;

/**
 * Users authenticator.
 *
 * @author     Martin Bazik
 */
class Authenticator extends Nette\Object implements NS\IAuthenticator
{

	/**
	 * Performs an authentication
	 * @param  array
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{

		$data = array(
			'name' => 'martin',
			'surname' => 'bazik'
		);
		
		return new NS\Identity(1, 'user', $data);
	}

}

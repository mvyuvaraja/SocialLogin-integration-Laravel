<?php 

return array( 
	
	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session', 

	/**
	 * Consumers
	 */
	'consumers' => array(

		/**
		 * Facebook
		 */
        'Facebook' => array(
            'client_id'     => '1758310244444523',
            'client_secret' => 'b4c344d1e1fabbc51ad851644c33331b',
            'scope'         => array('email','read_friendlists','user_online_presence'),
        ),
        'Google' => array(
            'client_id'     => '648157615653-k69i0i251n1qrjh2uonc26c9n5bmqd2e.apps.googleusercontent.com',
            'client_secret' => 'oQ4E6Vm7FFpZaq9AsKEc0Ls5',
            'scope'         => array('userinfo_email', 'userinfo_profile'),
            
        )  

	)

);


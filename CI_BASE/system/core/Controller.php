<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	/**
	 *
	 * @var CI_User_agent
	 */
	public $agent;
	
	/**
	 *
	 * @var CI_Benchmark
	 */
	public $benchmark;
	
	/**
	 *
	 * @var CI_Calendar
	 */
	public $calendar;
	
	/**
	 *
	 * @var CI_Cart
	 */
	public $cart;
	
	/**
	 *
	 * @var CI_Config
	 */
	public $config;
	
	/**
	 *
	 * @var CI_DB_active_record
	 */
	public $db;
	
	/**
	 *
	 * @var CI_Email
	 */
	public $email;
	
	/**
	 *
	 * @var CI_Encrypt
	 */
	public $encrypt;
	
	/**
	 *
	 * @var CI_Form_validation
	 */
	public $form_validation;
	
	/**
	 *
	 * @var CI_FTP
	 */
	public $ftp;
	
	/**
	 *
	 * @var CI_Input
	 */
	public $input;
	
	/**
	 *
	 * @var CI_Image_lib
	 */
	public $image_lib;
	
	/**
	 *
	 * @var CI_Language
	 */
	public $lang;
	
	/**
	 *
	 * @var CI_Loader
	 */
	public $load;
	
	/**
	 *
	 * @var CI_Output
	 */
	public $output;
	
	/**
	 *
	 * @var CI_Pagination
	 */
	public $pagination;
	
	/**
	 *
	 * @var CI_Parser
	 */
	public $parser;
	
	
	/**
	 * @var CI_Router
	 */
	public $router;
	
	/**
	 *
	 * @var CI_Session
	 */
	public $session;
	
	/**
	 *
	 * @var CI_Table
	 */
	public $table;
	
	/**
	 *
	 * @var CI_Trackback
	 */
	public $trackback;
	
	/**
	 *
	 * @var CI_Typography
	 */
	public $typography;
	
	/**
	 *
	 * @var CI_Unit_test
	 */
	public $unit;
	
	/**
	 *
	 * @var CI_Upload
	 */
	public $upload;
	
	/**
	 *
	 * @var CI_URI
	 */
	public $uri;
	
	/**
	 *
	 * @var CI_Xmlrpcs
	 */
	public $xmlrpcs;
	
	/**
	 *
	 * @var CI_Zip
	 */
	public $zip;
	
	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
		log_message('info', 'Controller Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}

}

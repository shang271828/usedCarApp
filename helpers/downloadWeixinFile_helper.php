<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * This helper helps the programmer to invoke weixin API
 */

if ( ! function_exists('downloadImage'))
{
	function downloadImage($url)
	{
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_NOBODY, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$package=curl_exec($curl);
		$httpinfo=curl_getinfo($curl);		
		curl_close($curl);
		return array_merge(array('body' => $package),array('header' => $httpinfo));
	}
}

/* End of file array_helper.php */
/* Location: ./system/helpers/array_helper.php */

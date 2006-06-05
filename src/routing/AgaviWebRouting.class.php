<?php

// +---------------------------------------------------------------------------+
// | This file is part of the Agavi package.                                   |
// | Copyright (c) 2003-2006 the Agavi Project.                                |
// | Based on the Mojavi3 MVC Framework, Copyright (c) 2003-2005 Sean Kerr.    |
// |                                                                           |
// | For the full copyright and license information, please view the LICENSE   |
// | file that was distributed with this source code. You can also view the    |
// | LICENSE file online at http://www.agavi.org/LICENSE.txt                   |
// |   vi: set noexpandtab:                                                    |
// |   Local Variables:                                                        |
// |   indent-tabs-mode: t                                                     |
// |   End:                                                                    |
// +---------------------------------------------------------------------------+

/**
 * AgaviWebRouting sets the prefix and input with some magic from the request 
 * uri and path_info 
 *
 * @package    agavi
 * @subpackage routing
 *
 * @author     Dominik del Bondio <ddb@bitxtender.com>
 * @copyright  (c) Authors
 * @since      0.11.0
 *
 * @version    $Id$
 */
class AgaviWebRouting extends AgaviRouting
{
	public function initialize(AgaviContext $context)
	{
		parent::initialize($context);
		$ru = ($p = strpos($_SERVER['REQUEST_URI'], '?')) === false ? $_SERVER['REQUEST_URI'] : substr($_SERVER['REQUEST_URI'], 0, $p);

		if(isset($_SERVER['PATH_INFO'])) {
			$this->prefix =  substr($ru, 0, -strlen($_SERVER['PATH_INFO']));
			$this->input = substr($ru, strlen($this->prefix));
		} else {
			$sn = $_SERVER['SCRIPT_NAME'];

			$this->prefix = '';
			for($i = 0; isset($sn[$i]) && isset($ru[$i]) && $sn[$i] == $ru[$i]; ++$i) {
				$this->prefix .= $sn[$i];
			}
			$this->input = substr($ru, $i);
		}
	}
	
	/**
	 * Generate a formatted Agavi URL.
	 *
	 * @param      string A route name.
	 * @param      array  An associative array of URL parameters.
	 *
	 * @return     string
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @author     David Zuelke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function gen($route, $params = array())
	{
		if(AgaviConfig::get('core.use_routing')) {
			
			return parent::gen($route, $params);
			
		} else {
			
			if ($url == null) {
				$url = $_SERVER['SCRIPT_NAME'];
			}

			// use GET format
			$divider  = '&';
			$equals   = '=';
			$url     .= '?';

			// loop through the parameters
			foreach ($parameters as $key => $value) {
				$url .= urlencode($key) . $equals . urlencode($value) . $divider;
			}

			// strip off last divider character
			$url = rtrim($url, $divider);

			// replace &'s with &amp;
			$url = str_replace('&', '&amp;', $url);

			return $url;
		}
	}
	
	public function execute()
	{
		$req = $this->getContext()->getRequest();
		
		// merge GET parameters
		$req->setParametersByRef($_GET);
		// merge POST parameters
		$req->setParametersByRef($_POST);
		
		// the real deal
		return parent::execute();
	}
}

?>
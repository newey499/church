<?php

// epd_cookie.class.php

// *************************************************************************
// *                                                                       *
// * (c) 2008-2010 Wolf Software Limited <info@wolf-software.com>          *
// * All Rights Reserved.                                                  *
// *                                                                       *
// * This program is free software: you can redistribute it and/or modify  *
// * it under the terms of the GNU General Public License as published by  *
// * the Free Software Foundation, either version 3 of the License, or     *
// * (at your option) any later version.                                   *
// *                                                                       *
// * This program is distributed in the hope that it will be useful,       *
// * but WITHOUT ANY WARRANTY; without even the implied warranty of        *
// * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
// * GNU General Public License for more details.                          *
// *                                                                       *
// * You should have received a copy of the GNU General Public License     *
// * along with this program.  If not, see <http://www.gnu.org/licenses/>. *
// *                                                                       *
// *************************************************************************

class epd_cookie
{
  private $class_name    = "epd_cookie";
  private $class_version = "1.0.0";
  private $class_author  = "Wolf Software";
  private $class_source  = "http://www.wolf-software.com/Downloads/epd_cookie_class";

  const Session            = null;
  const OneDay             = 86400;
  const SevenDays          = 604800;
  const ThirtyDays         = 2592000;
  const SixMonths          = 15811200;
  const OneYear            = 31536000;
  const Lifetime           = -1; // 2030-01-01 00:00:00

  private $cookie_name     = 'permit_cookies';

  private $master_redirect = 'http://www.google.com';

	
	public function __construct()
	{
		// Test purposes - always destroy cookie on class creation
		//$this->delete_epd_cookie();
	}

	public function __destruct()
	{
		// Test purposes - always destroy cookie on class creation
		//$this->delete_epd_cookie();
	}

  public function class_name()
    {
      return $this->class_name;
    }

  public function class_version()
    {
      return $this->class_version;
    }

  public function class_author()
    {
      return $this->class_author;
    }

  public function class_source()
    {
      return $this->class_source;
    }

  public function master_redirect($redirect = null)
    {
      if (isset($redirect))
        header("Location: $redirect");
      else
        header("Location: $this->master_redirect");
    }

  public function verify_epd_cookie($redirect_page)
    {
      if ((isset($_COOKIE[$this->cookie_name])) && ($_COOKIE[$this->cookie_name] == '1'))
        {
          setcookie($this->cookie_name, '1', (time() + epd_cookie::OneYear)); // Cookie lasts 1 year
          return true;
        }
      
      $file = $_SERVER["SCRIPT_NAME"];
      $break = Explode('/', $file);
      $pfile = $break[count($break) - 1];
			$pfile = $_SERVER['REFERRER'];
      header("Location: $redirect_page?referer=$pfile");
    }

  public function check_epd_cookie()
    {
      if ((isset($_COOKIE[$this->cookie_name])) && ($_COOKIE[$this->cookie_name] == '1'))
        {
          setcookie($this->cookie_name, '1', $this->OneYear);
          $referer = isset($_GET['referer'])?$_GET['referer']:'.';
          header("Location: $referer");
          return true;
        }
      return false;
    }

  public function set_epd_cookie()
    {
      $referer = isset($_GET['referer'])?$_GET['referer']:'.';

      setcookie($this->cookie_name, '1', $this->OneYear);
      header("Location: $referer");
    }

  public function delete_epd_cookie()
    {
      setcookie($this->cookie_name, '', time() - 3600);
    }
}

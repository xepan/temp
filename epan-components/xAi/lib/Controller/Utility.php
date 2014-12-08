<?php

namespace xAi;

class Controller_Utility extends \AbstractController{
	
	function existsInDataRow($data_rows,$value,$conditions=null){
		foreach ($data_rows as $arr_2) {
			if(in_array($value, $arr_2)){
				if($conditions==null)
					return $arr_2;
				else{
					$send = 1;
					foreach ($conditions as $condition_key => $condition_value) {
						if($arr_2[$conditions_key] != $condition_value)
							$send = 0;
					}

					if($send)
						return $arr_2;
				}
			}
		}

		return false;
	}

	function existsKeyInData($data_rows,$sensor_name, $key,$conditions=null){
		foreach ($data_rows as $arr_2) {
			$data_chunk = json_decode($arr_2['name'],true);
			if(!isset($data_chunk[$sensor_name])) return false;
			if(array_key_exists($key, $data_chunk[$sensor_name])){
				if($conditions==null)
					return $arr_2;
				else{
					$send = 1;
					foreach ($conditions as $condition_key => $condition_value) {
						if($arr_2[$conditions_key] != $condition_value)
							$send = 0;
					}

					if($send)
						return $arr_2;
				}
			}
		}

		return false;
	}

	function getBrowserOS($string=null) { 

    $user_agent     =   $string?:$_SERVER['HTTP_USER_AGENT']; 
    $browser        =   "Unknown Browser";
    $os_platform    =   "Unknown OS Platform";

    // Get the Operating System Platform

        if (preg_match('/windows|win32/i', $user_agent)) {

            $os_platform    =   'Windows';

            if (preg_match('/windows nt 6.2/i', $user_agent)) {

                $os_platform    .=  " 8";

            } else if (preg_match('/windows nt 6.1/i', $user_agent)) {

                $os_platform    .=  " 7";

            } else if (preg_match('/windows nt 6.0/i', $user_agent)) {

                $os_platform    .=  " Vista";

            } else if (preg_match('/windows nt 5.2/i', $user_agent)) {

                $os_platform    .=  " Server 2003/XP x64";

            } else if (preg_match('/windows nt 5.1/i', $user_agent) || preg_match('/windows xp/i', $user_agent)) {

                $os_platform    .=  " XP";

            } else if (preg_match('/windows nt 5.0/i', $user_agent)) {

                $os_platform    .=  " 2000";

            } else if (preg_match('/windows me/i', $user_agent)) {

                $os_platform    .=  " ME";

            } else if (preg_match('/win98/i', $user_agent)) {

                $os_platform    .=  " 98";

            } else if (preg_match('/win95/i', $user_agent)) {

                $os_platform    .=  " 95";

            } else if (preg_match('/win16/i', $user_agent)) {

                $os_platform    .=  " 3.11";

            }

        } else if (preg_match('/macintosh|mac os x/i', $user_agent)) {

            $os_platform    =   'Mac';

            if (preg_match('/macintosh/i', $user_agent)) {

                $os_platform    .=  " OS X";

            } else if (preg_match('/mac_powerpc/i', $user_agent)) {

                $os_platform    .=  " OS 9";

            }

        } else if (preg_match('/linux/i', $user_agent)) {

            $os_platform    =   "Linux";

        }

        // Override if matched

            if (preg_match('/iphone/i', $user_agent)) {

                $os_platform    =   "iPhone";

            } else if (preg_match('/android/i', $user_agent)) {

                $os_platform    =   "Android";

            } else if (preg_match('/blackberry/i', $user_agent)) {

                $os_platform    =   "BlackBerry";

            } else if (preg_match('/webos/i', $user_agent)) {

                $os_platform    =   "Mobile";

            } else if (preg_match('/ipod/i', $user_agent)) {

                $os_platform    =   "iPod";

            } else if (preg_match('/ipad/i', $user_agent)) {

                $os_platform    =   "iPad";

            }

    // Get the Browser

        if (preg_match('/msie/i', $user_agent) && !preg_match('/opera/i', $user_agent)) { 

            $browser        =   "Internet Explorer"; 

        } else if (preg_match('/firefox/i', $user_agent)) { 

            $browser        =   "Firefox";

        } else if (preg_match('/chrome/i', $user_agent)) { 

            $browser        =   "Chrome";

        } else if (preg_match('/safari/i', $user_agent)) { 

            $browser        =   "Safari";

        } else if (preg_match('/opera/i', $user_agent)) { 

            $browser        =   "Opera";

        } else if (preg_match('/netscape/i', $user_agent)) { 

            $browser        =   "Netscape"; 

        } 

        // Override if matched

            if ($os_platform == "iPhone" || $os_platform == "Android" || $os_platform == "BlackBerry" || $os_platform == "Mobile" || $os_platform == "iPod" || $os_platform == "iPad") { 

                if (preg_match('/mobile/i', $user_agent)) {

                    $browser    =   "Handheld Browser";

                }

            }

    // Create a Data Array

        return array(
            'browser'       =>  $browser,
            'platform'   =>  $os_platform
        );

} 




}
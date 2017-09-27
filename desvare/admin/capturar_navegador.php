<?php
class browser {
	var $name = NULL;
	var $version = NULL;
	var $useragent = NULL;
	var $platform;
	var $aol = FALSE;
	var $browsertype;
	function browser() {
		$agent = $_SERVER['HTTP_USER_AGENT'];
		//set the useragent property
		$this->useragent = $agent;
}
function getBrowserOS() {
$win = preg_match("/win/i", $this->useragent);
$linux = preg_match("/linux/i", $this->useragent);
$mac = preg_match("/mac/i", $this->useragent);
$os2 = preg_match("/OS2/i", $this->useragent);
$beos = preg_match("/BeOS/i", $this->useragent);
$is_mobile = preg_match("/ipod|iphone|ipad|android|opera mini|blackberry|palm os|windows ce|windows mobile|palm|hiptop|avantgo|plucker|xiino|blazer|elaine|iris|3g_t|
windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile|mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|
samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c810|_h797|mobx|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|
m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk|c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590
|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew|d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|
8325rc|kddi|phone|lg|sonyericsson|samsung|240x|x320|vx10|nokia|sonycmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo/i", $_SERVER['HTTP_USER_AGENT'], $this->useragent);
//now do the check as to which matches and return it
if($win) {
$this->platform = "Windows";
}
elseif ($linux) {
$this->platform = "Linux";
}
elseif ($mac) {
$this->platform = "Macintosh";
}
elseif ($os2) {
$this->platform = "OS/2";
}
elseif ($beos) 	{
$this->platform = "BeOS";
}
elseif ($is_mobile) 	{
$this->platform = $_SERVER['HTTP_USER_AGENT'];
}
return $this->platform;
}
function isOpera() {
if (preg_match("/opera/i",$this->useragent)) {
			$val = stristr($this->useragent, "opera");
if (eregi("/", $val)){
				$val = explode("/",$val);
				$this->browsertype = $val[0];
				$val = explode(" ",$val[1]);
				$this->version = $val[0];
} else {
				$val = explode(" ",stristr($val,"opera"));
				$this->browsertype = $val[0];
				$this->version = $val[1];
			}
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
function isFirefox() {
if(preg_match("/Firefox/i", $this->useragent)) {
			$this->browsertype = "Firefox";
			$val = stristr($this->useragent, "Firefox");
			$val = explode("/",$val);
			$this->version = $val[1];
			return true;
		}
		else {
			return FALSE;
		}
	}
function isChrome() {
if(preg_match("/Chrome/i", $this->useragent)) {
			$this->browsertype = "Chrome";
			$val = stristr($this->useragent, "Chrome");
			$val = explode("/",$val);
			$val = explode(" ",$val[1]);
			$this->version = $val[0];
			return true;
		}
		else {
			return FALSE;
		}
	}
function isKonqueror() {
if(preg_match("/Konqueror/i",$this->useragent)) {
			$val = explode(" ",stristr($this->useragent,"Konqueror"));
			$val = explode("/",$val[0]);
			$this->browsertype = $val[0];
			$this->version = str_replace(")","",$val[1]);
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
function isIEv1() {
if(preg_match("/microsoft internet explorer/i", $this->useragent))  {
			$this->browsertype = "IE";
			$this->version = "1.0";
			$var = stristr($this->useragent, "/");
			if (ereg("308|425|426|474|0b1/i", $var)) {
				$this->version = "1.5";
			}
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
function isMSIE() {
if(preg_match("/msie/i", $this->useragent) && !preg_match("/opera/i",$this->useragent)) {
			$this->browsertype = "IE";
			$val = explode(" /",stristr($this->useragent,"msie"));
			$this->browsertype = $val[0];
			$this->version = $val[1];
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
function isGaleon() {
if(preg_match("/galeon/i",$this->useragent)) {
			$val = explode(" ",stristr($this->useragent,"galeon"));
			$val = explode("/",$val[0]);
			$this->browsertype = $val[0];
			$this->version = $val[1];
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
function isWebTV() {
if(preg_match("/webtv/i",$this->useragent)) {
			$val = explode("/",stristr($this->useragent,"webtv"));
			$this->browsertype = $val[0];
			$this->version = $val[1];
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
function isNetPositive() {
if(preg_match("/NetPositive/i", $this->useragent)) {
			$val = explode("/",stristr($this->useragent,"NetPositive"));
			$this->platform = "BeOS";
			$this->browsertype = $val[0];
			$this->version = $val[1];
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
function isMSPIE() 	{
if(preg_match("/mspie/i",$this->useragent) || preg_match("/pocket/i", $this->useragent)) {
			$val = explode(" ",stristr($this->useragent,"mspie"));
			$this->browsertype = "MSPIE";
			$this->platform = "WindowsCE";
			if (preg_match("/mspie/i", $this->useragent))
				$this->version = $val[1];
			else {
				$val = explode("/",$this->useragent);
				$this->version = $val[1];
			}
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
function isIcab() {
if(preg_match("/icab/i",$this->useragent)) {
			$val = explode(" ",stristr($this->useragent,"icab"));
			$this->browsertype = $val[0];
			$this->version = $val[1];
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
function isOmniWeb() {
if(preg_match("/omniweb/i",$this->useragent)) {
			$val = explode("/",stristr($this->useragent,"omniweb"));
			$this->browsertype = $val[0];
			$this->version = $val[1];
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
function isPhoenix() {
if(preg_match("/Phoenix/i", $this->useragent)) {
			$this->browsertype = "Phoenix";
			$val = explode("/", stristr($this->useragent,"Phoenix/"));
			$this->version = $val[1];
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
function isFirebird() {
if(preg_match("/firebird/i", $this->useragent)) {
			$this->browsertype = "Firebird";
			$val = stristr($this->useragent, "Firebird");
			$val = explode("/",$val);
			$this->version = $val[1];
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
function isMozAlphaBeta() {
if(preg_match("/mozilla/i",$this->useragent) && preg_match("/rv:[0-9].[0-9][a-b]/i",$this->useragent) && !preg_match("/netscape/i",$this->useragent)) {
			$this->browsertype = "Mozilla";
			$val = explode(" ",stristr($this->useragent,"rv:"));
			preg_match("/rv:[0-9].[0-9][a-b]/i",$this->useragent,$val);
			$this->version = str_replace("rv:","",$val[0]);
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
function isMozStable() {
if(preg_match("/mozilla/i",$this->useragent) && preg_match("/rv:[0-9]\.[0-9]/i",$this->useragent) && !preg_match("/netscape/i",$this->useragent)) {
			$this->browsertype = "Mozilla";
			$val = explode(" ",stristr($this->useragent,"rv:"));
			preg_match("/rv:[0-9]\.[0-9]\.[0-9]/i",$this->useragent,$val);
			$this->version = str_replace("rv:","",$val[0]);
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
function isLynx() {
if(preg_match("/libwww/i", $this->useragent)) {
			if (preg_match("/amaya/i", $this->useragent)) {
				$val = explode("/",stristr($this->useragent,"amaya"));
				$this->browsertype = "Amaya";
				$val = explode(" ", $val[1]);
				$this->version = $val[0];
			} else {
				$val = explode("/",$this->useragent);
				$this->browsertype = "Lynx";
				$this->version = $val[1];
			}
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
function isSafari() {
if(preg_match("/safari/i", $this->useragent)) {
			$this->browsertype = "Safari";
			$this->version = "";
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
function isNetscape() {
if(preg_match("/netscape/i",$this->useragent)) {
			$val = explode(" ",stristr($this->useragent,"netscape"));
			$val = explode("/",$val[0]);
			$this->browsertype = $val[0];
			$this->version = $val[1];
			return TRUE;
		}
elseif(preg_match("/mozilla/i",$this->useragent) &&
				!preg_match("/rv:[0-9]\.[0-9]\.[0-9]/i",$this->useragent)) {
			$val = explode(" ",stristr($this->useragent,"mozilla"));
			$val = explode("/",$val[0]);
			$this->browsertype = "Netscape";
			$this->version = $val[1];
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
function isAOL() {
if (preg_match("/AOL/i", $this->useragent)){
			$var = stristr($this->useragent, "AOL");
			$var = explode(" ", $var);
			$this->aol = ereg_replace("[^0-9,.,a-z,A-Z]", "", $var[1]);
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
function whatBrowser() {
		$this->getBrowserOS();
		$this->isOpera();
		$this->isFirefox();
		$this->isKonqueror();
		$this->isIEv1();
		$this->isMSIE();
		$this->isGaleon();
		$this->isNetPositive();
		$this->isMSPIE();
		$this->isIcab();
		$this->isOmniWeb();
		$this->isPhoenix();
		$this->isFirebird();
		$this->isLynx();
		$this->isSafari();
		$this->isChrome();
		//$this->isMozAlphaBeta();
		//$this->isMozStable();
		//$this->isNetscape();
		$this->isAOL();
		return array('browsertype' => $this->browsertype,
					 'version' => $this->version,
					 'platform' => $this->platform,
					 'AOL' => $this->aol);
	}
}
?>
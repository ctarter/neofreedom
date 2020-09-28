<?php
/**
* Login Manager
* login.php?u=km&p=hi
*
*/
if (isset($_GET['kdb'])) {
    ini_set('display_errors',1);
    error_reporting(E_ALL);
}

include_once("neo-base.inc");
include_once("flourish/fCryptography.php");

class NeoLogin
{
    private static $mySess=null;
    const UID="u";
    const LOGIN_TIME="ltime";
    const PASSW="p";
    const WHERE="w";
    const SSO_SCRIPT = '/neoshared/neo-login.php';
    const LOGIN="l";
    const LOGOUT='logout';
    const SALT="John316love";
    /* static $sites = array('termans' => 'http://termans',
                        'neoxenos.info' => 'http://neoxenos.info'); */
    static $sites = array('neoxenos.net' => 'http://neoxenos.net',
                            'neoxenos.info' => 'http://neoxenos.info',
                            'neonets.org' => 'http://neonets.org',
                            'neoblogs.org' => 'http://neoblogs.org',
                            'neozine.org' => 'http://neozine.org',
                            'termans' => 'http://termans',);
    static $ssoMasterURL='http://neoxenos.info';
    static $mySite=null;
    static $headerData='';

    /**
    * Get the encrypted login data for given site
    *
    * @param mixed $decrypt - true, array[] returned, else encrypted string
    * @return mixed - encrypted string, else false
    */
    static function getLogin($decrypted=false)
    {
        $l=NeoSess::get(self::LOGIN);
        if (!$decrypted || !$l) return $l;
        else return self::decrypt($l);
    }

    /**
    * Login to give site
    *
    * @param mixed $uid
    * @param mixed $pwd
    * @param mixed $where
    */
    static function login($uid,$pwd,$from=null)
    {
        //if ( self::getLogIn() ) return 'Already logged in.';                       ## Already logged in here
        //if (!in_array(self::$sites[$site])) return false;               ## Bad site given
        NeoSess::set(self::UID,$uid);
        NeoSess::set(self::LOGIN,self::encrypt($uid,$pwd));
        NeoSess::set(self::LOGIN_TIME,date('n/j/y g:m.s a'));
        self::notifySites();
    }
    /**
    * Tell the sites of my current login state
    *
    */

    static function notifySites()
    {
        self::$headerData='';
        $tok= self::getLogin() ? self::LOGIN.'='.urlencode(self::getLogin()) : self::LOGOUT ;
        if (self::$mySite==self::$ssoMasterURL)                   ## The MASTER tells everyone else...
        {
            foreach(self::$sites as $kk => $vv)
            {
                if ($kk==self::$mySite) continue;
                $url="$vv".self::SSO_SCRIPT."?$tok";
                self::$headerData .= '<script language="javascript" type="text/javascript" src="'.$url.'"></script>';
            }
        }
        else                                                    ## Else tell the MASTER
        {
            if (empty($_REQUEST[self::LOGIN])){                        ## But ONLY if it wasn't a remote login
                $url=self::$ssoMasterURL.self::SSO_SCRIPT."?$tok";
                self::$headerData .= '<script language="javascript" type="text/javascript" src="'.$url.'"></script>';
            }
        }
    }

    /**
    * Checks request for 'uid' to logout or login (if request also has pwd)
    *
    */
    static function init()
    {
        if (!self::$mySite) {
            self::$mySite=$_SERVER['HTTP_HOST'];
        }
        if (!empty($_REQUEST[self::UID]))
        {
            //if (!empty($_REQUEST[self::PASSW]))
            if ($_REQUEST[self::UID]==self::LOGOUT) self::logout();
            elseif (!empty($_REQUEST[self::PASSW])) {
                self::login($_REQUEST[self::UID],$_REQUEST[self::PASSW]);
            }
        }
        else self::digestRemote();
    }
    static function digestRemote()
    {
        if(!empty($_REQUEST[self::LOGIN]))
        {
            $login=urldecode($_REQUEST[self::LOGIN]);
            $login=self::decrypt($login);
            self::login($login[0],$login[1]);
        }
        if (!empty($_REQUEST[self::LOGOUT]))
        {
            self::logout();
        }
    }
    static function logout()
    {
        NeoSess::destroy();
    }
    /**
    * Encrypt with our Salt the given usr/pwd
    *
    * @param mixed $usr
    * @param mixed $pwd
    * @return string - uid|pwd encrypted
    */
    static function encrypt($usr,$pwd)
    {
        return str_replace('fCryptography::symmetric#','', fCryptography::symmetricKeyEncrypt("$usr|$pwd",self::SALT));
    }
    /**
    * Decrypt uid/pwd into a 2-dim array
    *
    * @param mixed $what
    * @return array 0=userID, 1=pwd
    */
    static function decrypt($what)
    {
         $uid=fCryptography::symmetricKeyDecrypt("fCryptography::symmetric#$what",self::SALT);
         $uid=explode("|",$uid);
         return $uid;
    }

}
NeoLogin::init();
##
## Display HTML output if this was the URI
##
if ( strpos($_SERVER['REQUEST_URI'], NeoLogin::SSO_SCRIPT) !== false )  {
    $crypted=NeoLogin::encrypt($_REQUEST['uid'],$_REQUEST['pwd']);
    $decript=NeoLogin::decrypt($crypted);
    $decript=implode(",",$decript);
    ## NeoLogin::login($_REQUEST['uid'],$_REQUEST['pwd']);
?>
<html>
<?
    if (!empty(NeoLogin::$headerData)) {
echo "
<head>
".NeoLogin::$headerData."
</head>
";
    }
?>
<body>
Here we go...
<?
echo "<p>Headerdata:<code>
";
echo htmlspecialchars(NeoLogin::$headerData);
echo "
</code>
</p>";
NeoHooks::action('kdb','session');
NeoHooks::action('kdb','request');
?>
</body>
</html><?
}
?>

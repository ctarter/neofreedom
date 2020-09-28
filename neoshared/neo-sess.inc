<?php
/**
* Session control
*/
if (!defined('NEOSHARED_PATH')) die("no neobase");

/**
* Call via NeoHooks::filter('neosess-get','key') or NeoHooks::action('neosess-set','key','value')
*/
class NeoSess  {

    //const neoSessName = 'neosess::';
    const keyUpdated = 'updated';
    const keyWP = "NEO_WP";
    const keyKDB = 'kdb';
    const keyLastErrTS = 'lastErrorTS';

    static function init()
    {
        ## See neo-config.inc for hooks

        if(session_name()=="") fSession::ignoreSubdomain();
        //fSession::setPath(NEOSHARED_PATH."/inbox");
        fSession::open();
        fSession::set(self::keyUpdated,NeoTimestamp::format());
    }

    static function dump($htmlFmt=false)
    {
        if ($htmlFmt)
        {
            $rc = "<table><tr><td colspan='2'><b>SESSION VARS</b></td></tr>\n";
                foreach ($_SESSION as $kk => $vv)
                {
                    $vv = htmlspecialchars($vv);
                    $rc .= "<tr><td>$kk</td><td>$vv</td></tr>\n";
                }
        $rc .= "</table>\n";
            return $rc;
        }
        else return "<pre>".var_export($_SESSION,true)."</pre>\n";
    }

    /**
     * Sets data to the `$_SESSION` superglobal, prefixing it with `fSession::` to prevent issues with `$_REQUEST`
     *
     * @param  string $key     The name to save the value under
     * @param  mixed  $value   The value to store
     * @param  string $prefix  The prefix to stick before the key
     * @return void
     */
    static public function set($key, $value)
    {
        fSession::set($key,$value);
    }

    /** DON'T CALL
     *
     * @deprecated use ::set() / ::delete() or ::clear() instead
     * @param mixed array('var' => 'varname', 'val' => 'var_value', 'namespace' = 'neosess', 'exp' = 600) where 'exp' is # seconds to expire the value, or 'never'
    */
    static function save($key, $val=null) {
        if (!is_string($key)) throw new NeoExeption("Bad key given for NeoSess::save");
        #die(var_dump(debug_backtrace()));
#        $var=null;
#        $val=null;
#        $exp=600;       # expires $exp seconds from now

#        extract($args);

        fSession::set($var,$val);
    }

    /* Get the value of a session variable - use NeoHooks::filter('neosess-get','key')
     * @paramstring  Name of variable to load. null loads all variables in namespace (associative array)
     * @paramstring(optional) Namespace to use, defaults to 'default'
    */
    static function get($varName, $defaultValue=null) {
        return fSession::get($varName,$defaultValue);
    }

    /**
    *  Clears all variables in namespace
    */
    static function clear() {
        self::reset();
    }
    /**
     * Deletes a value from the session
     *
     * @param  string $key     The key of the value to delete
     * @param  string $namespace  The prefix to use for the key
     * @return void
     */
    static function delete($key)
    {
        fSession::delete($key);
    }
    static function reset()
    {
        self::destroy();
    }
    static function destroy()
    {
        $flag=self::get(KDB_FLAG);
        fSession::reset();
        self::init();
        self::set('RESET',var_export(debug_backtrace(),true));
        if ($flag) self::set(KDB_FLAG,$flag);
    }

}

NeoSess::init();

?>

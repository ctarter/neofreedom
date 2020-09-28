<?php
/**
* Shared config between systems
*/
class NeoConfig
{
    private static $theConfig=array();
    static function getDefaults($what=null)
    {
        if (!$what) return self::$theConfig;
        else return self::$theConfig[$what];
    }
    /*static function getManifestPath($filename=null)
    {
        $rc = self::$config['PATHS']['DOC_ROOT'].'/neoshared/manifest/';
        if (!$filename) return $rc;
        return $rc.self::$config[$filename]['filename'];
    }*/

    /**
    * Initialize config
    *
    * @param boolean $reset if true, resets config to defaults
    */
    static function init($reset=false)
    {
        if ($reset || !self::$theConfig)
        {
            #$theConfig['PATHS']['DOC_ROOT'] = "{$_SERVER['DOCUMENT_ROOT']}";
            self::$theConfig['ADZ-PACKAGE'] = array( 'filename' => "neo-adzpackage.inc",'server' => 'neoblogs.org');
            self::$theConfig['GLOSSARY'] = array( 'filename' => "neo-glossary.inc",'server' => 'neoxenos.info');

            //self::$theConfig['UBERNYMS'] = array( 'filename' =>  "neo-ubernyms.inc",'server' => 'neoxenos.info');
            self::$theConfig['NEO-HANDLERS']['neoviews-run'] = NEOSHARED_PATH."/neo-views.inc";
            self::$theConfig['NEO-HANDLERS']['neo-encrypt'] = NEOSHARED_PATH."/neo-crypto.inc";
            self::$theConfig['NEO-HANDLERS']['neo-decrypt'] = NEOSHARED_PATH."/neo-crypto.inc";
            self::$theConfig['NEO-HANDLERS']['neo-css'] = NEOSHARED_PATH."/neo-format.inc";
            self::$theConfig['NEO-HANDLERS']['neosess-get'] =array('filepath' => NEOSHARED_PATH."/neo-format.inc", 'handler' => 'NeoSess::get');
            self::$theConfig['NEO-HANDLERS']['neosess-save'] = array('filepath' => NEOSHARED_PATH."/neo-format.inc", 'handler' => 'NeoSess::save');
            self::$theConfig['NEO-HANDLERS']['neosess-set'] = array('filepath' => NEOSHARED_PATH."/neo-format.inc", 'handler' => 'NeoSess::set','args' =>2);
            self::$theConfig['NEO-HANDLERS']['neosess-clear'] = array('filepath' => NEOSHARED_PATH."/neo-format.inc", 'handler' => 'NeoSess::clear');
            self::$theConfig['NEO-HANDLERS']['neosess-delete'] = array('filepath' => NEOSHARED_PATH."/neo-format.inc", 'handler' => 'NeoSess::delete');
            self::$theConfig['NEO-HANDLERS']['neosess-reset'] = array('filepath' => NEOSHARED_PATH."/neo-format.inc", 'handler' => 'NeoSess::reset');
            self::$theConfig['NEO-HANDLERS']['kdb-error'] = array('filepath' => NEOSHARED_PATH."/neo-debug.inc", 'handler' => 'kdbErr');
            self::$theConfig['NEO-HANDLERS']['kdb-debugmode'] = array('filepath' => NEOSHARED_PATH."/neo-debug.php", 'handler' => 'kdbDebugMode');
            self::$theConfig['NEOWIKI']['[[Keith McCallum]]'] = '<a href="http://remonstrance.neoblogs.org" title="Visit Keith\'s Blog">Keith McCallum</a>';
            self::$theConfig['PATHS']['DOC_ROOT'] = "{$_SERVER['DOCUMENT_ROOT']}";
            self::$theConfig['URLS']['GLOSSARY-FEED'] = "http://neoxenos.info/terms/feed/";
            self::$theConfig['FILES']['COMMENTS-RSS-ALL'] = "neo-comments-rss-all.inc";
            self::$theConfig['FILES']['ADZ-PACKAGE'] = array( 'filename' => "neo-adzpackage.inc",
                                                    'server' => 'neoblogs.org');
            self::$theConfig['FILES']['GLOSSARY'] = "neo-glossary.inc";
            self::$theConfig['FILES']['COMMENTS-RSS-ALL'] = array( 'filename' => "neo-comments-rss-all.inc",
                                                    'server' => 'neozine.org');
            self::$theConfig['FILES']['UBERNYMS'] = array( 'filename' =>  "neo-ubernyms.inc",
                                                'server' => 'neoxenos.info');

        }
    }
    /**
    * Append value to given $tag
    *
    * @param mixed $tag
    * @param mixed $val
    */
    static function addOption($tag,$val)
    {
        self::$theConfig[$tag][]=$val;
    }
    /**
    * Set a family of options
    *
    * @param mixed $tag1 family name
    * @param mixed $tag2 family values OR child name
    * @param mixed $tag3 child value
    */
    static function setOption($tag1,$tag2,$tag3=null)
    {
        if (!$tag3) self::$theConfig[$tag1]=$tag2;
        else self::$theConfig[$tag1][$tag2]=$tag3;
        //if (function_exists('update_option')) update_option("neoConfig",NeoConfig::$theConfig);
    }
    static function getAllOptions()
    {
        return self::$theConfig;
    }
    /**
    * Get family (or child) values
    *
    * @param mixed $family
    * @param mixed $child
    * @return NeoConfig
    */
    static function getOption($family, $child=null)
    {
        //if (function_exists('get_option') && empty(self::$theConfig['CONFIG_INIT'])) self::$theConfig=array_merge(self::$theConfig,get_option("neoConfig"),array('CONFIG_INIT',true));
        if (!$child) return @self::$theConfig[$family];
        else return @self::$theConfig[$family][$child];
    }
}

NeoConfig::init();

?>

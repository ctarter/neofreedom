<?php
/**
* Wiki-style formatter
*/

//include_once(NEOSHARED_PATH."/flourish/fTemplating.php");

class NeoWiki //extends fTemplating
{
        private static $repl=array();
        private static $srch=array();
        private static $words=array();

        static function init()
        {
            self::loadDefaults();
            NeoHooks::addFilter('neowiki',array(new NeoWiki(),'format'));
        }
        static function loadDefaults()
        {
            //$this->subst("/\{([0-9a-zA-Z-]*)\}/e",'NeoWiki::getter("\$1")');
            $opts=NeoConfig::getOption('NEOWIKI');
            foreach($opts as $kk => $vv)
            {
                self::$srch[] = $kk;
                self::$repl[] = $vv;
            }
        }
        /**
        * Get a symbol substitution
        *
        * @param mixed $arr
        */
        static function subst($key)
        {
            $rc=@self::$words[$key];
            if (!$rc) return $key;
            else return $rc;
        }
        static function getWords()
        {
            return self::$words;
        }
        static function setWords($arr)
        {
            self::$words=$arr;
            foreach($arr as $kk => $vv)
            {
                if (@self::$srch[$kk]) continue;
                self::$srch[]=$kk;
                self::$repl[]=$vv;
            }
        }
        /**
        * Format given string with substitutions
        *
        * @param mixed $str
        */
        static function format($str)
        {
            $srch=self::$srch;
            $repl=self::$repl;
            $srch[]="{the:datestamp}";
            $repl[]=date('n/j/y');
            $srch[]="{the:timestamp/";
            $repl[]=date("g:m.s a");
            $rc=str_replace($srch,$repl,$str);
            ##$rc=preg_replace($srch, $repl, $str);
            return $rc;
        }

        static function getter($id,$default='')
        {
            if (self::$curr) return self::$curr->get($id,$default);
            else return $default;
        }

        public function setSubst($srch,$repl)
        {
            $this->repl[] = $repl;
            $this->srch[] = $srch;
        }
}

NeoWiki::init();
?>

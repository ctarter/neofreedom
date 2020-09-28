<?php
/**
* Templating functionality
*/
include_once(NEOSHARED_PATH."/flourish/fTemplating.php");

class NeoViews {

    const viewDefault = 'view-simple';
    const optRootDir = "/views";
    const keyRootPath = 'views-path';
    const keyView = 'view';
    const keyTemplates = 'INC';
    var $myTpl=null;
    //var $myOpts=array();

    function NeoViews($args)
    {
        $vpath=@$args[self::keyRootPath];
        if (!$vpath) $vpath=NEOSHARED_PATH . self::optRootDir . "/PHP";
        $this->myTpl = new NeoTPL();
    }
    function config($args)
    {
        //if (!$this->_init($args)) return false;

        $errs=null;
        NeoHooks::action('kdb',array('kdbdata' => $args , 'kdbmsg' => '$args'));

        foreach ($args as $kk => $vv)
        {
            switch(true)
            {
                case ($kk=='opts'):
                {
                    $this->myTpl->set('options', $vv);
                    break;
                }
                case ($kk=='handlers'):
                {
                    ## should be an array of handler arrays, but we accomodate array(msg => filepath)
                    foreach($vv as $msg => $parms)
                    {
                        if (is_string($parms)) $parms = array('filepath' => $parms);
                        $parms['filepath'] = $this->myTpl->buildOutputPath($parms['filepath']);
                        NeoHooks::addHandler($msg,$parms);
                    }
                    break;
                }
                case ($kk==self::keyView):
                {
                    $this->addTemplates($vv);
                    break;
                }
                case ($kk==self::keyTemplates):
                    if (!$this->addTemplates($vv,$kk)) $errs[] = "Could not add template: $kk";
                    break;
                case is_array($vv):
                    foreach($vv as $item)
                    {
                        $this->myTpl->add($kk,$item);
                    }
                    break;
                case is_string($vv):
                    $this->myTpl->set($kk,$vv);
                    break;
                default:
                    $errs[]="UKNOWN CONIG: $kk";
                    break;
            }
        }

        if ($errs) return neoRC($this,false,NEO::ERR,"*** ERRORS: " . implode(',',$errs));
        else return neoRC($this,true,NEO::OK,"Config processed".count($args)." items.");

    }
    /**
    * Add additional templates
    *
    * @param mixed $vnames
    * @param string $type file extensions: inc,js,css
    */
    function addTemplates($vnames,$type=self::keyTemplates)
    {
        $types=array(self::keyView => 'php', self::keyTemplates => 'php');
        if (is_string($vnames)) $vnames=array($vnames);

        $type = @$types[$type];

        if (!$type) throw new NeoException('No template Type provided');

        $force = (!$this->myTpl->getElem('options','RECOMPILE',false) ? true : false);

        if ($force) $this->myTpl->trace("Will force recompile.");
        else $this->myTpl->trace("Will NOT force recompile.");

        if (!$this->myTpl->parse($vnames,$type,$force)) throw new NeoException(NeoMsg::get('rc'));

        foreach($vnames as $name)
        {
            $this->myTpl->set($name,"$name.{$type}");
        }
        return neoRC($this,true);
    }
    function runview($name)
    {
        $toRun=$this->myTpl->buildOutputPath("{$name}.php");
        if (!file_exists($toRun)) {
            $errMsg="Cannot load: $toRun";
            echo $errMsg;
            throw new NeoException($errMsg);
        }
        ##
        ## Config Defaults Setup - LAST CHANCE!
        ##
        $defaults['view'] = $name;
        $defaults=$this->myTpl->get('defaults',$defaults);
        $defaults=NeoHooks::filter('neo-view-defaults',$defaults);
        $this->myTpl->set('defaults',$defaults);
        ##
        ## Display the view
        ##
        $this->myTpl->place($name);   ## Call fTemplating
        $this->myTpl->trace("Ran view: $toRun");
        if ( $this->myTpl->getElem('opts', 'DEBUG') && $this->myTpl->get('TRACE',false) )
            echo "<h3>Trace:</h3>\n".implode("<br>",$this->myTpl->get('TRACE'));
    }

    static function init()
    {
        NeoHooks::addAction('neoviews-run','NeoViews::run');
    }
    /**
    * Run & display this View
    * @param mixed $args - ['opts']['DEBUG'] will dump / ['ERRORS'] displays give errors
    * ['opts']['RECOMPILE'] forces template recompiling
    * @uses filter 'neo-prep-data' - DBRecs-to-array conversion hook
    */
    static function run($args)
    {
        $toRun=@$args[self::keyView];
        //$args['opts']['RECOMPILE']=true;
        if (!$toRun) throw new NeoException("NO VIEW PROVIDED");
        ##
        ## Format raw posts, if available...
        ##
        #$outp="";
        /*if (!empty($args['POSTS']))
        {
            $thePosts=$args['POSTS'];
            foreach ($thePosts as $thePost)
            {
                $args['content'][]="Got post ID {$thePost->ID}<br>";
                #$fmtData = apply_filters('neo-prep-data',null,$thePost);  # Initial formatting / expect View Variables (key/val array) returned, else null
                #if (is_array($fmtData) && !empty($fmtData)) {
                #    foreach($fmtData as $kk => $vv)
                #    {
                #        if (!empty($args[$kk])) $args[$kk][] = $vv;     # Append data
                #        else $args[$kk] = $vv;                          # Set data
                #    }
                #}
            }

        }*/
        //$args['content'][]=$outp;
        ##
        ## Parse & Display
        ##
        try {
            $runner=new NeoViews($args);
            if ($errMsg=NeoMsg::get('err','NeoViews')) throw new NeoException($errMsg);
            if (!$runner->config($args)) throw new NeoException("Cannot process config: ".implode("\n",$args));
            $runner->runView($toRun);
            if (!empty($args['opts']['DEBUG'])) {
                echo "<h3>Debug Dump:</h3><pre>";
                var_dump($args);
                echo "</pre>";
            }
            if (!empty($args['ERRORS'])) {
                echo "<h3>Errors:</h3>";
                echo implode("<br>",$args['ERRORS']);
            }
        } catch (Exception $ee) {
            kdbErr("EXCEPTION at ".$ee->getFile().":".$ee->getLine()." MSG: ".$ee->getMessage());
        }

    }
}

NeoViews::init();

class NeoTPL extends fTemplating {
        private $repl=array();
        private $srch=array();
        const keySettings ='defaults';   # Settings consumed by template
        const keyDataStore ='display';   # Data containers used by template
        #const keyLiveData = 'li'; # Live data not parsed by template regex
        const keyRegexSrch = 'srch';     # Used by parse()
        const keyRegexRepl = 'repl';     # Used by parse()

        const keyPreamble =
'<?php
/**
* {the:filename} parsed at {the:datestamp} {the:timestamp}
* Source: {the:template-path}
*/
        if (!isset($this) || ! $this instanceof fTemplating) throw new fValidationException("no NeoTPL object available...");
?>';

        private $docRoot=NeoViews::optRootDir;

        function __construct($docRoot=null)
        {
            if (!$docRoot) $docRoot=NEOSHARED_PATH.NeoViews::optRootDir;
            parent::__construct($docRoot."/PHP");
            $this->docRoot=$docRoot;
            $this->loadDefaults();
        }
        /**
        * Format given string with substitutions
        *
        * @param mixed $str
        */
        function format($str)
        {
            $srch=$this->get('srch',array());
            $repl=$this->get('repl',array());
            $srch[]="/\{the\:datestamp\}/";
            $repl[]=date('n/j/y');
            $srch[]="/\{the\:timestamp\}/";
            $repl[]=date("g:m.s a");
            $rc=preg_replace($srch, $repl, $str);
            return $rc;
        }
        /**
        * Overrides base functionality
        *
        */
        function loadDefaults()
        {
            $this->subst("/\{POSTS\:([0-9a-zA-Z-_]*)\}/","<?=\$this->getElem('live-rec','\$1'); ?>");
            /*$this->subst("/\%item\:([0-9a-zA-Z-_]*)\%/","\$this->getElem('live-rec','\$1')");
            $this->subst("/\{option\:([0-9a-zA-Z-_]*)\}/","<?=\$this->getElem('options',$1); ?>");
            $this->subst("/\{beginposts\}/","<? \$myPosts = \$this->get('POSTS'); if (\$myPosts) foreach( \$myPosts as \$myPost) {   ## BEGIN POSTS LOOP ?>");
            $this->subst("/\{post\:([0-9a-zA-Z-_]*)\}/","<?=(empty(\$myPost->\$1) ? (\$1 unavailable) : \$myPost->\$1); ?>");
            $this->subst("/\%post\:([0-9a-zA-Z-_]*)\%/","(empty(\$myPost->\$1) ? (\$1 unavailable) : \$myPost->\$1); ");
            $this->subst("/\{endposts\}/","<? } # END POSTS ?>"); */
            $this->subst("/\{the:template-path\}/","{$this->docRoot}");
            /*$this->subst("/\{math\:([0-9a-zA-Z-]+)\:([-+/*]+)\:([0-9]+)\}/",
                        "<? \$__toCalc=\$this->get('\$1',0); \$__toCalc=(\$__toCalc \$2 \$3) \$this->set('\$1','\$__toCalc') ?>");                ## Basic substitution*/

            $this->subst("/\{set\:([0-9a-zA-Z-]*)\:([0-9a-zA-Z]*)\}/","<? \$this->set('\$1','\$2'); ?>");                             ## Basic substitution

            /*$this->subst("/\%([0-9a-zA-Z-]*)\%/","\$this->get('\$1','')");*/
            $this->subst("/\{rows\:([0-9a-zA-Z-]*)\}/","<?=implode('<br>',\$this->get('\$1',''))?>");         ## Rows of flattened data
            $this->subst("/\{include\:([a-zA-Z-]*)\}/","<? $this->place('\$1') ?>");
            $this->subst("/\{if\:([0-9a-zA-Z-]*)\:([0-9a-zA-Z]*)\}/","<? if (\$this->get('\$1') == '\$2') { ?>\n");
            $this->subst("/\{if\:([0-9a-zA-Z-]*)\>([0-9a-zA-Z]*)\}/","<? if (\$this->get('\$1') > '\$2') { ?>\n");
            $this->subst("/\{end\-if\}/","<? } ?>");
            $this->subst("/\{else}/","<? } else { ?>");
            $this->subst("/\{begin\:([0-9a-zA-Z-]*)\}/",
                            "\n<? \n## \$\$1 Begin loop \n\$this->set('COUNTER',1);\n\$$1 = \$this->get('\$1',array());\nforeach(\$\$1 as \$live_rec) { \n\$this->set('live-rec',\$live_rec); ?> ");
            $this->subst("/\{end\:([0-9a-zA-Z-]*)\}/","\n<? }\n## \$\$1 loop end ?>\n");
            $this->subst("/\{([0-9a-zA-Z-]*)\}/","<?=\$this->get('\$1','');?>");                             ## Basic substitution
            $this->subst("/\?\>\s*\<\?\=/m","\necho ");
            $this->subst("/\?\>\s*\<\?/m","\n");

        }
        public function subst($srch,$repl)
        {
            $this->add(self::keyRegexSrch,$srch);
            $this->add(self::keyRegexRepl,$repl);
        }
        /**
        * Prefix given filename with the current Views document root
        *
        * @param mixed $filename - without path
        * @returns string path
        * @example /var/www/templ/filename.php
        */
        function buildTemplatePath($filename)
        {
            return $this->docRoot."/".$filename;
        }
        function buildOutputPath($filename)
        {
            return $this->docRoot."/PHP/".$filename;
        }
        /**
        * Pluck an element out of an array
        *
        * @param mixed $parent
        * @param mixed $child
        * @param mixed $default
        * @return mixed
        */
        function getElem($parent,$child=null,$default='defaults')
        {
            if ($default=='defaults') $default=$this->getElem('defaults',$child,null);
            $arr=$this->get($parent,$default);
            if (!$arr || !$child) return $default;
            elseif (!empty($arr[$child])) return $arr[$child];
            else return null;
        }
        /**
        * Leave traces - for debugging purposes
        *
        * @param mixed $msg
        */
        function trace($msg)
        {
            $this->add("TRACE","From ".getMyCaller(__FILE__,__LINE__)." - ".$msg);
        }

        function get($var,$default=null)
        {
            if (!$default) {
                $defaults=parent::get('defaults');
                $default=@$defaults[$var];
            }
            $v2=parent::get($var,$default);
            $v2= preg_replace('/\{\:([0-9a-zA-Z-]*)\:\}/e',"NeoTPL::lateBind(\$this,'\$1')",$v2); #str_replace("{blogname}",parent::get('blogname'),$v2);
            return $v2;
        }

        static function lateBind($tpl,$name)
        {
            return $tpl->get($name);
        }

        /**
        * Read / Parse / Write a .tpl to a .php
        *
        * @param mixed $names - base name w/o extensions
        * @param string $extension - output will be saved with this extension
        * @param bool $force - wether to force parsing the file (true), or only if out-of-date
        * @TODO VIEWS Module
        * @category Templates
        */
        function parse($names,$extension='php',$force=false)
        {
            if(is_string($names)) $names=array($names);

            $ttl=0;

            foreach($names as $name)
            {
                $rc=$this->_parse($name,$extension,$force);
                if (!$rc) $errs[]=NeoMsg::get($this);
                else $ttl .= $rc;
            }

            if (!empty($errs)) {
                return neoRC($this,false,NEO::ERR,implode("\n",$errs));
            }
            else return neoRC($this,$ttl);
        }
        private function _parse($name,$extension,$force)
        {
            $this->subst("/\{the\:filename\}/","{$name}.tpl");
            $tpl=$out=null;
            $outName=$this->buildOutputPath("{$name}.{$extension}");
            $tplName=$this->buildTemplatePath("{$name}.tpl");

            $tplTime=$outTime=false;
            if (file_exists($tplName)) $tplTime = filemtime($tplName);
            if (file_exists($outName)) $outTime = filemtime($outName);
            if (!$tplTime)
            {
                //NeoHooks::action('kdb',array('kdbdata' =>  $tplName, 'kdbmsg' => 'KMcC'));
                throw new NeoException('Template not found:'.$tplName);
            }
            if (!$force && $tplTime < $outTime)
            {
                return neoRC($this,1,NEO::OK,"Template is older than {$outName} - not parsed.");
            }
            ##
            ## now PARSE!!
            ##
            $toParse=file_get_contents($tplName);
            $toParse=self::keyPreamble."\n{$toParse}\n";
            $toParse=$this->format($toParse);

            $size=file_put_contents($outName,$toParse);

            if (!$size) throw new NeoException("Could not write $outName with $size bytes");
            return neoRC($this,$size,NEO::OK,"Parsed template: {$name}.tpl");

        }
}


?>
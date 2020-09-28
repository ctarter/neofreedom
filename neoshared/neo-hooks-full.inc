<?php
/**
* Reproduction of WP filter system
*/
class NeoHooks extends NeoBase
{
    static $neoFilter = array(); # 308 09-00308 ginther
    static $neo_merged_filters = array();
    static $neo_actions = array();
    static $initialized = false;
    static function initHooks()
    {
        if (self::$initialized) return;
        NeoHooks::addAction('neo-handler','NeoHooks::addHandler',10,2);
        ##$CI->load->library('neopub');
        //self::addFilter('neohelp',array($CI->neopublication,'neoPromptHelp'));
        self::$initialized = true;
    }

    //static function addGetter($tag,$function_to_add, $priority = 10, $accepted_args = 1)
    //{
    //    return self::addFilter($tag,$function_to_add, $priority, $accepted_args);
   // }
    static function addFilter($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
        if (defined("NEO_WP")) {
            return add_filter($tag,$function_to_add,$priority,$accepted_args);
        }

        // So the format is wp_filter['tag']['array of priorities']['array of functions serialized']['array of ['array (functions, accepted_args)]']
        $idx = self::_filterBuildUniqueId($tag, $function_to_add, $priority);
        self::$neoFilter[$tag][$priority][$idx] = array('function' => $function_to_add, 'accepted_args' => $accepted_args);
        //self::$neoFilter[$tag][$priority][serialize($function_to_add)] = array('function' => $function_to_add, 'accepted_args' => $accepted_args);
        unset( self::$neo_merged_filters[ $tag ] );
        return true;
    }
    static function get($tag, $crit)
    {
        $rc=self::filter($tag,$crit);
        if($rc==$crit) return null;
        else return $rc;
    }
    static function filter($tag, $string) {
        self::loadHandler($tag);
        if ( !isset( self::$neo_merged_filters[ $tag ] ) )
            self::mergeFilters($tag);

        if ( !isset(self::$neoFilter[$tag]) )
        {
            if (function_exists('apply_filters')) return apply_filters($tag,$string);
            else return $string;
        }


        reset( self::$neoFilter[ $tag ] );

        $args = func_get_args();

        do{
            foreach( (array) current(self::$neoFilter[$tag]) as $the_ )
                if ( !is_null($the_['function']) ){
                    $args[1] = $string;
                    $string = call_user_func_array($the_['function'], array_slice($args, 1, (int) $the_['accepted_args']));
                }

        } while ( next( self::$neoFilter[$tag] ) !== false );

        return $string;
    }
    static function mergeFilters($tag) {

        if ( isset(self::$neoFilter['all']) && is_array(self::$neoFilter['all']) )
            self::$neoFilter[$tag] = array_merge(self::$neoFilter['all'], (array) self::$neoFilter[$tag]);

        if ( isset(self::$neoFilter[$tag]) ){
            reset(self::$neoFilter[$tag]);
            uksort(self::$neoFilter[$tag], "strnatcasecmp");
        }
        self::$neo_merged_filters[ $tag ] = true;
    }
    static function removeFilter($tag, $function_to_remove, $priority = 10, $accepted_args = 1)
    {
        $function_to_remove = self::_filterBuildUniqueId($tag, $function_to_remove, $priority);

        $r = isset($GLOBALS['wp_filter'][$tag][$priority][$function_to_remove]);

        unset($GLOBALS['wp_filter'][$tag][$priority][$function_to_remove]);
        unset($GLOBALS['merged_filters'][$tag]);

        return $r;
    }
    /**
    * Esure the handler is loaded - called by NeoHooks automatically
    *
    * @param string $msg - handler
    */
    static function loadHandler($msg)
    {
        $handlers=NeoConfig::getOption('NEO-HANDLERS');
        if ($hndl=@$handlers[$msg])
        {
            if (is_array($hndl))
            {
                if (!empty($hndl['filepath']))include_once($hndl['filepath']);
                $call=@$hndl['handler'];
                if ($call)
                {
                $type='action';
                $args=1;
                $priority=10;
                extract($hndl);
                if ($type=='action') NeoHooks::addAction($msg,$call,$priority,$args);
                else NeoHooks::addFilter($msg,$call,$priority,$args);
                }
            }
            else include_once($hndl);
            unset($handlers[$msg]);                 ## Don't duplicate it!
            NeoConfig::setOption('NEO-HANDLERS',$handlers);
        }
    }
    /**
    * Utility creates proper parms for handlers
    *
    * @param mixed $parms - if string, it's the 'filepath' argument, else given array is combined with defaults
    * @return array - with keys: type,priority,args,filepath
    */
    static function makeHandlerParms($parms=array())
    {
        $defaults = array('type' =>'action',
                            'priority' => 10,
                            'args' => 1);
        if (is_string($parms)) { $defaults['filepath'] = $parms; $args=$defaults; }
        else
        {
            $args=array_merge($defaults,$parms);
        }
        return $args;
    }
    /**
    * Adds action or filter handler from given filepath
    *
    * @param mixed $tag
    * @param mixed $parms -  [filepath=include_once]* [type=action|filter] [handler=string|array(object,string)]* [priority=1-10] [args=#accepted_args]
    */
    static function addHandler($tag,$parms=array())
    {
        $args = self::makeHandlerParms($parms);
        if (empty($args['filepath']) && empty($args['handler'])) {
            kdbErr("ADD-HANDLER: NO FILEPATH OR HANDLER GIVEN FOR MSG:$tag",__FILE__,__LINE__,false);
            return;
        }
        NeoConfig::setOption('NEO-HANDLERS', $tag, $args);
    }

    static function addAction($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
        self::addFilter($tag, $function_to_add, $priority, $accepted_args);
    }
    static function action($tag, $arg = '') {

        self::loadHandler($tag);

        if ( is_array(self::$neo_actions) )
            self::$neo_actions[] = $tag;
        else
            self::$neo_actions = array($tag);

        $args = array();
        //echo getMyCaller(__FILE__,__LINE__).">>". $tag."<br />";
        if ( is_array($arg) && 1 == count($arg) && !empty($arg[0]) && is_object($arg[0]))  // array(&$this)
            $args[] =& $arg[0];
        else
            $args[] = $arg;
        for ( $a = 2; $a < func_num_args(); $a++ )
            $args[] = func_get_arg($a);

        self::mergeFilters($tag);

        $rc = false;

        if ( !isset(self::$neoFilter[$tag]) )
        {
            if (function_exists('do_action')) {
                array_unshift($args,$tag);
                //NeoHooks::action('kdb',array('kdbdata' => $args , 'kdbmsg' => '$args'));
                return call_user_func_array('do_action',$args);
            }
            else
            {
                kdbErr("Hook NOT FOUND: $tag");
                return $rc;
            }
        }
        else
        {

            do{
                foreach( (array) current(self::$neoFilter[$tag]) as $the_ )
                    if ( is_null($the_['function']) ) $rc = false;
                    else $rc = call_user_func_array($the_['function'], array_slice($args, 0, (int) $the_['accepted_args']));

            } while ( next(self::$neoFilter[$tag]) !== false );
        }

        return $rc; #if (!$rc) {$myStatus=NEO::WARN; $log="NEO-HOOKS: Tag $tag rc==false";}
        #else {$myStatus=NEO::OK; $log="rc=={$rc}";}

        #return neoRC(__CLASS__,$rc,$myStatus,$log);

    }
    static function didAction($tag) {

        if ( empty(self::$neo_actions) )
            return 0;

        return count(array_keys(self::$neo_actions, $tag));
    }
    static function removeAction($tag, $function_to_remove, $priority = 10, $accepted_args = 1) {
        return self::removeFilter($tag, $function_to_remove, $priority, $accepted_args);
    }
    static function _filterBuildUniqueId($tag, $function, $priority = 10)
    {
        // If function then just skip all of the tests and not overwrite the following.
        // Static Calling
        if( is_string($function) )
            return $function;
        // Object Class Calling
        else if(is_object($function[0]) )
        {
            $obj_idx = get_class($function[0]).$function[1];
            if( empty($function[0]->wp_filter_id) ) {
                $count = isset(self::$neoFilter[$tag][$priority]) ? count((array)self::$neoFilter[$tag][$priority]) : 0; #kmcc bugfix
                $function[0]->wp_filter_id = $count;
                $obj_idx .= $count;
                unset($count);
            } else
                $obj_idx .= $function[0]->wp_filter_id;
            return $obj_idx;
        }
        else if( is_string($function[0]) )
            return $function[0].$function[1];
    }
}

NeoHooks::initHooks();

?>

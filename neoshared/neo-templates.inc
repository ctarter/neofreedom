<?php
/**
* Templating functionality
*/
include_once("neo-base.inc");
include_once(NEOSHARED_PATH."/templates/class.TemplatePower.inc.php");
/*
$myTpl = new TemplatePower(NEOSHARED_PATH."/templates/tpl/header.tpl");
$myTpl->prepare();
$myTpl->assign('title',"My Title");
$myTpl->printToScreen();
*/

class NeoTPL
{

    var $myTpl=null;                  ## Handle instantiated by fTemplating

    const defaultLayout="simple.tpl";


    function NeoTPL($layout,$tplDir=null,$includes=null)
    {

        if ($tplDir) $this->tplDir=$tplDir;
        else $this->tplDir=NEOSHARED_PATH."/templates/tpl/";

        $tpl=$layout;

        $this->myTpl = new TemplatePower($this->tplDir.$layout);

        if (is_array($includes)) {
            while ($tpl=array_shift($includes)) $this->myTpl->assignInclude(rtrim(basename($tpl,'tpl'),'.'), $this->tplDir.$tpl);
        }

        $this->myTpl->prepare();
        $this->myTpl->assign('version',$this->myTpl->version());

    }

    /**
    * Sets default values globally for each key=>val pair
    *
    * @param mixed $keyVals
    */
    function setDefaults($keyVals)
    {
        if (is_string($keyVals)) {
             parse_str( $keyVals, $array );
        } else $array=$keyVals;
        $this->myTpl->assignGlobal($array);
    }
    function addBlock($name="_ROOT")
    {
        $this->myTpl->newBlock($name);

    }
    /**
    * @deprecated use addBlock
    */
    function block($name="_ROOT")
    {
        $this->block($name);
    }
    function addVar($key,$val,$block=null)
    {
        if ($block) $this->myTpl->newBlock($block);
        $this->myTpl->assign(array($key=>$val));
    }
    /**
    * @deprecated use addArray()
    */
    function put($arr,$block=null)
    {
        $this->addArray($arr,$block);
    }
    function addArray($arr,$block=null)
    {
        foreach($arr as $key => $val)
        {
            if (is_array($val)) $this->addArray($val,$key);
            else $this->addVar($key,$val,$block);
        }
    }
    function varDump()
    {
        $this->myTpl->__printVars();
    }
    function addStrQ($qStr,$block=null)
    {
        parse_str($qStr,$arr);
        $this->addArray($arr,$block);
    }
    /**
    * Add data to template (view)
    *
    * @param mixed $keyVals array or query string
    * @param boolean $append - override default 'php' file type with 'css', 'js', etc.
    * @deprecated use addVarQ or addVar (with addBlock)
    * @returns void
    */
    function add($keyVals,$block=null)
    {
        if (is_string($keyVals)) $this->addVarQ($keyVals,$block);
        else $this->addArray($keyVals,$block);
    }

    /**
    * go, go, go...!
    *
    * @param mixed $tmpl
    * @param string $output 'echo' or see self::parse options...
    */
    function run($args=null,$output='echo')
    {
        if ($args) {
            $this->put($args);
        }
        //$this->myTpl->prepare();
        $this->myTpl->showUnAssigned(true);
        $outp=$this->myTpl->getOutputContent();
        if ($output=='echo') echo $outp; #$this->myTpl->printToScreen();
        else return $outp; #$this->myTpl->getOutputContent();
    }

}
?>
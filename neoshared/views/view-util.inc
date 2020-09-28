<?php
class MyView
{
    var $handle=null;

    /**
    * Run this view
    *
    * @param mixed $flourish - fTemplating object
    */
    public function MyView($flourish)
    {
        if ($flourish instanceof fTemplating) $this->handle=$flourish;
        else throw new fProgrammerException('Bad $flourish object given:'.var_export($flourish,true));
    }
    public function getHandle()
    {
        return $this->handle;
    }
}

## Include this & perform the following:
## if (isset($this)) $myView = new MyView($this);
## else throw new fProgrammerException('NO $this object available!');

?>
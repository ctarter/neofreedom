<?php
include_once(dirname(__FILE__).'/flourish/fTimestamp.php');

class NeoTimestamp
{
    private $tz='America/New_York';
    private $ts=null;

    public function NeoTimestamp($timeStamp=0,$timeZone=null)
    {
        static $first=true;

        if ($first)
        {
            fTimestamp::defineFormat('full', "n/j/\'y g:m.s a");
            $first=false;
        }

        if ($timeZone) $this->tz = $timeZone;
        if ($timeStamp) $this->ts = new fTimestamp($timeStamp,$this->tz);
        else $this->ts = new fTimestamp('now',$this->tz);
    }

    static public function format($fmt=null,$timestamp=0,$timezone=null)
    {
        if (!$timestamp) $timestamp='now';
        $ts = new NeoTimestamp($timestamp,$timezone);
        return $ts->get($fmt);
    }

    public function get($fmt=null)
    {
        if (!$fmt) $fmt='full';
        return $this->ts->format($fmt);
    }
}
?>

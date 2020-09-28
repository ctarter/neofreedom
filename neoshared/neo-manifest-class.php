<?php

include_once("neo-config.inc");

class NeoManifest {
    var $package=array();
    const keyService = 'service';
    const keyID = 'packageID';
    const keyTime = 'timestamp';
    const keyData = 'data';
    const keyFilter = 'filter';

    function putManifest()
    {
        return;
    }
    function getManifest()
    {
        return true;
    }
    function lastERR()
    {
        return false;
    }
    function getFilteredData()
    {
        switch($this->package[self::keyFilter])
        {
            case 'ads':
                if (empty($this->package[self::keyData])) $rc ="No Ads";
                else $rc = $this->package[self::keyData];
                break;
            case 'dump':
                $dump = var_export($this->package,true);
                $rc = "<br>Source: {$this->package[self::keyServer]} <br>Service: {$this->package[self::keyService]}<br>Sent:{$this->package[self::keyClient]}<br> Received:{$this->package[self::keyData]}";
                break;
            case 'simple':
                if (is_array($this->package[self::keyData])) {
                    $dta = $this->package[self::keyData];
                    foreach($dta as $txt) {
                        $rc .= $txt;
                    }
                } else $rc = $this->package[self::keyData];
                break;
            default:
                $rc = 'unknown filter';
                break;
        }

        return $rc;
    }
}
?>

<?php
/**
* Provide DB access for NeoPlugins
*
* You can also go to http://mysql.neoxenos.org/  to manage your MySQL database from the web
*/

/* SCHEMA:
CREATE TABLE IF NOT EXISTS `settings` (
  `uid` varchar(15) collate utf8_bin NOT NULL,
  `remoteserver` varchar(15) collate utf8_bin NOT NULL default '''neoxenos.net''',
  `id` varchar(50) collate utf8_bin NOT NULL,
  `data` varchar(200) collate utf8_bin NOT NULL,
  `lastModif` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  KEY `server` (`server`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

See Sample Code at end of file
*/
include_once("neo-base.inc");
include_once("neo-sess.inc");
include_once('neo-hooks.inc');

class NeoMYSQL
{
    static $conn=null;

    #const dbName = 'neoxenos_neoshar';
    #const dbUser = 'neoxenos_neoshar';     // Your MySQL username
    const dbName = 'neoshared';
    const dbUser = 'neoxenos';     // Your MySQL username
    const dbPassw = '5opcomm01'; // ...and password
    const dbTblPrefix = "neo_";
    #const dbHost = 'localhost';
    const dbHost = 'mysql.neoxenos.org';

    var $errs=array();


    function NeoMYSQL()
    {

    }
    /**
    * delete from settings based on given criteria
    *
    * @param array $where fieldname/val pairs
    */
    function deleteSQL(array $where)
    {
        $q=$this->_where($where);
        krumo($q);
        return $this->_rawSQL("DELETE FROM `neoshared`.`settings` {$q}");
    }
    function insertSQL($what)
    {
        /* Typically:
        INSERT INTO `neoshared`.`settings` (
            `uid` ,
            `id` ,
            `data`
            )
            VALUES (
            'kmccallum', 'i1', 'asdfasdf'
        );*/
        $what=self::_prepFields($what);
        $s1=$s2='';
        foreach($what as $kk=>$vv)
        {
            $s1.="{$kk},";
            $s2.="{$vv},";
        }
        $s1=trim($s1,',');
        $s2=trim($s2,',');
        $q="INSERT INTO `neoshared`.`settings` ({$s1}) VALUES ({$s2});";
        return $this->_rawSQL($q);
    }
    function updateSQL($set,$where)
    {
        if (!$this->checkInit()) return false;

        $q="UPDATE `neoshared`.`settings` SET ";

        $set=self::_prepFields($set);

        foreach($set as $field => $value)
            $q .= "`{$field}` = {$value} ";

        $q .= $this->_where($where);

        mysql_real_escape_string($q);

        return $this->_rawSQL($q);

    }
    /**
    * build the WHERE part
    *
    * @param mixed $where "field" => "criteria" OR "field" => array("op" => "NE", "value" => "criteria")
    */
    function _where($where)
    {
        $q="WHERE ";

        $where=self::_prepFields($where);

        foreach($where as $field => $arr) {
            if (is_array($arr))
            {
                $op=$arr['op'];
                $v=$arr['value'];
            }
            else
            {
                $op="=";
                $v=$arr;
            }

            $q .= "{$field} {$op} {$v} ";
        }
        return $q;
    }

    function _rawSQL($sql)
    {
        $res=mysql_query($sql,self::$conn);

        if (!$res) {
            $this->errs[]=mysql_error();
            krumo($this->errs);
        }
        return $res;
    }

    /**
    * Perform remote SQL query & optionally cache it
    *
    * @param mixed $where
    * @param mixed $cacheID if provided, results will get cached & re-used during this session
    * @return resource
    */
    function querySQL($where,$cacheID=null)
    {
        if ($cacheID) {
            $rc=NeoHooks::filter('neosess-get',$cacheID);
            if($rc != $cacheID) return $rc;
        }

        if (!$this->checkInit()) return false;

        $presets = array('featurific' => '`id` = \'featurific\' LIMIT 0 , 10 ');

        if(empty($presets[$where])) $q=$where;
        else $q=$presets[$where];

        $q="SELECT * FROM `settings` WHERE ".$q;

        $res = $this->_rawSQL($q);

        if (!$res) return $res;

        $rc=array();

        while($row=mysql_fetch_assoc($res)) {
            $rc[]=$row;
        }
        //krumo($rc);
        NeoHooks::action('kdb',$rc);

        if ($cacheID && !empty($rc)) {
            NeoSession::save($cacheID,$rc);
            //NeoHooks::action('neosess-save',$cacheID,$rc);
        }

        mysql_free_result($res);

        return $rc;

    }

    function checkInit()
    {
        if (!self::$conn) {
            self::$conn = mysql_connect(self::dbHost, self::dbUser, self::dbPassw);
            if (!self::$conn) {
                $this->errs[] = "Could not connect: ".self::dbHost.' '.self::dbUser.' '.self::dbPassw;
                return false;
            }
            if (!mysql_select_db(self::dbName, self::$conn)) {
                $this->errs[] = "Could not select: ".self::dbName;
                return false;
            }
        }
        return true;
    }

    static function _prepFields($arr)
    {
        foreach($arr as $kk => $vv)
        {
            switch($kk) {
                case 'seq':
                    break;
                default:
                    $vv="'$vv'";
                    break;
            }
            $rc["`$kk`"] = $vv;
        }
        return $rc;
    }

}

/*CODE:
UPDATE `neoshared`.`settings` SET `uid` = 'kmcc',
`host` = 'neoxenos.net',
`id` = 'spec1',
`data` = 'The special data we saved from all things put together',
`lastModif` = NOW( ) WHERE `settings`.`seq` =1 LIMIT 1 ;

PHP Insert:

$sql = 'INSERT INTO `neoshared`.`settings` (`seq`, `uid`, `host`, `id`, `data`, `lastModif`) VALUES (NULL, \'kmcc\', \'neoxenos.net\', \'zzz\', \'the id "Keith" keeps\', NOW());';

SQL Insert:

INSERT INTO `neoshared`.`settings` (
`seq` ,
`uid` ,
`host` ,
`id` ,
`data` ,
`lastModif`
)
VALUES (
NULL , 'asd', 'all', 'asd', 'asdfasdf', NOW( )
);

SQL Abbreviated Insert:

INSERT INTO `neoshared`.`settings` (
`uid` ,
`id` ,
`data`
)
VALUES (
'kmccallum', 'i1', 'asdfasdf'
);
*/
?>

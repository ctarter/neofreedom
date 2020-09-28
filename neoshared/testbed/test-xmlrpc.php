<?php
/**
* Uses XMLRPC extensions in PHP 5
*/

/* method implementation */
function impl($method_name,$params,$user_data){
  var_dump(func_get_args('impl'));
  return array_sum($params);
}

/* create server */
$s=xmlrpc_server_create();
xmlrpc_server_register_method($s,'add','impl');

/* calling server method */
$req=xmlrpc_encode_request('add',array(1,2,3));
$resp=xmlrpc_server_call_method($s,$req,array(3,4));

/* process result */
$decoded=xmlrpc_decode($resp);
if(xmlrpc_is_fault($decoded)){
    echo 'fault!';
}

var_dump($decoded);
?>

<?php

function successRes($msg = "Success", $statusCode = 200) {
	$return = array();
	$return['status'] = 1;
	$return['status_code'] = $statusCode;
	$return['msg'] = $msg;
	return $return;
}

function errorRes($msg = "Error", $statusCode = 400) {
	$return = array();
	$return['status'] = 0;
	$return['status_code'] = $statusCode;
	$return['msg'] = $msg;
	return $return;
}
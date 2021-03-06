<?php
require_once 'lib/pinata.php';

layout('layout.phtml');

option('variable', 'test');

before(function() {
	echo 'start one';
});

$a = $b = $c = 1;

error(404, function() {
	echo 'Not found';
});

get('/user/(?<id>\d+)', $a == 1 && $b == 1, function() {

	before(function() {
		echo 'start '.option('variable');
	});

	after(function() {
		echo 'end';
	});

	assign('a', 1);
	assign('c', 2);

	display('x.phtml');

	echo param('id');
	echo route('/user/edit/234');

});

get('/user/edit/(?<id>\d+)', function() {

	error(404);

	echo param('id');
});
<?php
require('../../src/_require.php');

// create a FluentDOM
$fd = new FluentDOM\Query();
// we generate html
$fd->contentType = 'html';

//add the base menu node
$menu = $fd->append('<ul/>');

// output the created document
echo $fd;

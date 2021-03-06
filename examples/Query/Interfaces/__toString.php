<?php
header('Content-type: text/plain');

$xml = <<<XML
<html>
  <head>
    <title>Examples: FluentDOM\Query::__toString()</title>
  </head>
<body>
  <p>Hello</p><p>cruel</p><p>World</p>
</body>
</html>
XML;

require_once('../../../vendor/autoload.php');

echo FluentDOM($xml)
  ->find('//p')
  ->addClass('default')
  ->formatOutput();

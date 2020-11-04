<?php

header('HTTP/1.1 503 Service Unavailable');
header('Retry-After: 900'); // 15 minutes in seconds

?>
<!DOCTYPE html>
<meta charset="utf-8">
<meta name="robots" content="noindex">
<meta name="generator" content="Nette Framework">

<style>
	body { color: #333; background: white; width: 500px; margin: 100px auto }
	h1 { font: bold 47px/1.5 sans-serif; margin: .6em 0 }
	p { font: 21px/1.5 Georgia,serif; margin: 1.5em 0 }
</style>

<title>Probíhá údržba webu</title>

<h1>Omlouváme se,</h1>

<p>web je dočasně mimo provoz z důvodu údržby. V provozu by měl být opět během několika minut.</p>

<?php

exit;

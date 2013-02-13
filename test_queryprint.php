<?php
include('class.QueryPrint.inc');

$q = new QueryPrint('QP_Gray_White.mod');
$q->query("SELECT * from regularevents");
$q->printQuery();

?>
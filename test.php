<?php
$dom = new DOMDocument();
$xml = '<?xml version="1.0" encoding="UTF-8"?>
<node1>
<node2 />
</node1>
';


$dom->loadXML( $xml );
$nodeList = $dom->getElementsByTagName( 'node1' );
$node1 = $nodeList->item(0);
foreach ( $node1->childNodes as $node ) {
echo 'Class: ' . get_class( $node ) . "n";
echo "DOM Enable";

}
?>
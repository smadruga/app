<?php

$link = mysql_connect('45.55.249.18', 'usuario', '20UtpJ15');
if (!$link) {
    die('Não foi possível conectar: ' . mysql_error());
}
echo 'Conexão bem sucedida';
mysql_close($link);

$stid = oci_parse($conn, "SELECT CD_ESPECIALID, DS_ESPECIALID FROM ESPECIALID where SN_ativo = 'S' ORDER BY DS_ESPECIALID");
oci_execute($stid);

oci_close($conn);

#$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
#echo '=>'.json_encode($row);

$v = '[';
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    if ($v != "[") {$v .= ",";}
    $v .= json_encode($row);
}
$v .= ']';

echo $v;

/*
echo "<table border='1'>\n";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";
*/


?>

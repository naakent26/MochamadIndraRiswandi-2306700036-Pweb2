<?php
require_once 'Dosen.php';
require_once 'Mahasiswa.php';

echo "<h2>Data Sivitas Akademik</h2>";

$dosen = new Dosen("Pak Aris", "2306700000");
$mahasiswa = new Mahasiswa("Indra", "2306700036");

echo $dosen->getDosen();
echo "<br>";
echo $mahasiswa->getMahasiswa();
?>
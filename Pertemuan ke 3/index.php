<?php

require_once "class/Dosen.php";
require_once "class/Mahasiswa.php";

$dosen = new Dosen("Pak Budi", "2002002002");
$mahasiswa = new Mahasiswa("Indra", "2306700036");

echo "Dosen: " . $dosen->getNama() . " - NIDN: " . $dosen->getNidn();
echo "<br>";
echo "Mahasiswa: " . $mahasiswa->getNama() . " - NIM: " . $mahasiswa->getNim();
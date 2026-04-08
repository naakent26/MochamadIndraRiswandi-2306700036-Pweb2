<?php
require_once "SivitasAkademik.php";

class Mahasiswa extends SivitasAkademik {
    protected $nim;

    public function __construct($nama, $nim) {
        parent::__construct($nama);
        $this->nim = $nim;
    }

    public function getNim() {
        return $this->nim;
    }
}
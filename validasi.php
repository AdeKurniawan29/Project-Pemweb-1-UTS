<?php
// Memastikan nilai pilihan berasal dari daftar yang diperbolehkan.
function validasiPilihan($nilai, array $pilihan) {
    return is_string($nilai) && in_array($nilai, $pilihan, true);
}

function panjangTeks($teks) {
    return mb_strlen(trim($teks), 'UTF-8');
}
?>

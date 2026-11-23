<?php

if (! function_exists('format_rupiah')) {
    function format_rupiah(int $angka): string
    {
        return 'Rp' . number_format($angka, 0, ',', '.');
    }
}

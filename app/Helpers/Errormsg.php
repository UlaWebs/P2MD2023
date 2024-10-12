<?php

namespace App\Helpers;

class Errormsg
{
    public static function errordb($errorcode)
    {
        if ($errorcode == '23503') {
            $errormsg = "Data Tidak dihapus Karna Dipakai Data Lain";
        } else if ($errorcode == '23505') {
            $errormsg = "Data sudah ada";
        } else {
            $errormsg = "Error Tidak Terdefinisi";
        }
        return $errormsg;
    }
}

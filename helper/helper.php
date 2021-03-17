<?php

use Carbon\Carbon;
use Illuminate\Support\Str;

function replaceArrayString($x)
{
    $y = preg_replace("(\]|\[|'|\")", "", $x);
    return $y;
}

function my_upload_file($file, $path="uploads", $withpath=false)
{
    $ext = $file->getClientOriginalExtension();
    $filename = Str::random().'.'.$ext;
    $file->move($path, $filename);
    if($withpath){
        return asset($path."/".$filename);
    }else{
        return $filename;
    }
}

function base64_to_image($data, $path)
{
    list($type, $data) = explode(';', $data);
    list(, $data) = explode(',', $data);
    $data = base64_decode($data);

    $up = File::put(public_path($path), $data);

    return $up;
}

function if_empty($str, $out = "-")
{
    if ($str == null) {
        return $out;
    }
    return $str;
}

function boolean_text($bool, $true = "aktif", $false = "tidak aktif")
{
    if ($bool == true) {
        return $true;
    } else {
        return $false;
    }
}

function text_boolean($text, $true = "aktif", $false = "tidak aktif")
{
    if ($text == $true) {
        return true;
    } else {
        return false;
    }
}

function getBulanFromDate($date, $year = false)
{
    $dt = Carbon::parse($date);
    if ($year) {
        return bulan($dt->month) . ' ' . $dt->year;
    }
    return bulan($dt->month);
}

function responseJson($message, $data = null, $status = true, $text = 'success', $statusCode = 200)
{
    return response(['status' => $status, 'text' => $text, 'message' => $message, 'data' => $data], $statusCode);
}

function bulan($month)
{
    if ($month == 1) {
        $bulan = 'januari';
    } else if ($month == 2) {
        $bulan = 'februari';
    } else if ($month == 3) {
        $bulan = 'maret';
    } else if ($month == 4) {
        $bulan = 'april';
    } else if ($month == 5) {
        $bulan = 'mei';
    } else if ($month == 6) {
        $bulan = 'juni';
    } else if ($month == 7) {
        $bulan = 'juli';
    } else if ($month == 8) {
        $bulan = 'agustus';
    } else if ($month == 9) {
        $bulan = 'september';
    } else if ($month == 10) {
        $bulan = 'oktober';
    } else if ($month == 11) {
        $bulan = 'november';
    } else if ($month == 12) {
        $bulan = 'desember';
    }

    return $bulan;
}

function waktu($timestamps)
{
    $dt = Carbon::parse($timestamps);
    return $dt->hour . ":" . $dt->minute;
}

function tanggal($timestamps, $separator)
{
    $dt = Carbon::parse($timestamps);
    return "{$dt->day}/{$dt->month}/$dt->year";
}

function tanggal_indo($timestamps, $tampilkan_hari = true, $tampilkan_waktu = false, $hanyaHari = false)
{
    $dt = Carbon::parse($timestamps);
    $hari = $dt->dayOfWeek;
    if ($hari == 1) {
        $hari = 'Senin';
    } else if ($hari == 2) {
        $hari = 'Selasa';
    } else if ($hari == 3) {
        $hari = 'Rabu';
    } else if ($hari == 4) {
        $hari = 'Kamis';
    } else if ($hari == 5) {
        $hari = 'Jumat';
    } else if ($hari == 6) {
        $hari = 'Sabtu';
    } else {
        $hari = 'Minggu';
    }

    if ($hanyaHari) {
        return $hari;
    }

    if ($tampilkan_hari == false) {
        $hari = "";
    }

    $day = $dt->day;
    $month = $dt->month;

    if ($month == 1) {
        $bulan = 'januari';
    } else if ($month == 2) {
        $bulan = 'februari';
    } else if ($month == 3) {
        $bulan = 'maret';
    } else if ($month == 4) {
        $bulan = 'april';
    } else if ($month == 5) {
        $bulan = 'mei';
    } else if ($month == 6) {
        $bulan = 'juni';
    } else if ($month == 7) {
        $bulan = 'juli';
    } else if ($month == 8) {
        $bulan = 'agustus';
    } else if ($month == 9) {
        $bulan = 'september';
    } else if ($month == 10) {
        $bulan = 'oktober';
    } else if ($month == 11) {
        $bulan = 'november';
    } else if ($month == 12) {
        $bulan = 'desember';
    }

    $bulan = ucwords($bulan);

    $tahun = $dt->year;

    $waktu = $dt->format("H:i:s");

    if ($tampilkan_waktu) {
        $tanggal = "$hari $day $bulan $tahun $waktu";
    } else {
        $tanggal = "$hari $day $bulan $tahun";
    }

    return $tanggal;
}

function rupiah($angka, $tampilkanRupiah=true)
{
    $hasil_rupiah = number_format($angka, 2, ',', '.');
    return $tampilkanRupiah ? "Rp." . $hasil_rupiah : $hasil_rupiah;
}

function generate_links($name, $id, $links_additional= [])
{
    $links = [
        'store' => route($name.".store"),
        'show' => route($name.'.show', $id),
        'edit' => route($name.'.edit', $id),
        'update' => route($name.'.update', $id),
        'destroy' => route($name.'.destroy', $id),
    ];
    if(count($links_additional) > 0){
        array_push($links, $links_additional);
    }
    return auth()->check() ? $links : [];
}

function generate_links_api($name, $id, $links_additional= [])
{
    $links = [
        'store' => route($name.".store"),
        'show' => route($name.'.show', $id),
        'update' => route($name.'.update', $id),
        'destroy' => route($name.'.destroy', $id),
    ];

    if(count($links_additional) > 0){
        /*foreach($links_additional as $key => $link){
            array_merge($links, $link);
        }*/
        $links = array_merge($links, $links_additional);
    }

    return $links;
}

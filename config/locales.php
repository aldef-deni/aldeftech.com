<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Bahasa yang tersedia di situs publik
    |--------------------------------------------------------------------------
    | Urutannya menentukan urutan tampil pada pemilih bahasa di header.
    | 'native' dipakai sebagai label, 'short' untuk tombol ringkas di mobile.
    */

    'default' => 'id',

    'available' => [
        'id' => ['native' => 'Bahasa Indonesia', 'short' => 'ID', 'flag' => '🇮🇩', 'html' => 'id'],
        'en' => ['native' => 'English',          'short' => 'EN', 'flag' => '🇬🇧', 'html' => 'en'],
    ],
];

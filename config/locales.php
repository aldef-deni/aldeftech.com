<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Bahasa yang tersedia di situs publik
    |--------------------------------------------------------------------------
    | Urutannya menentukan urutan tampil pada pemilih bahasa di header.
    | 'native' dipakai sebagai label, 'short' sebagai lencana kode bahasa.
    | Emoji bendera sengaja tidak dipakai: Windows tidak memuat font bendera,
    | sehingga 🇬🇧 tampil sebagai huruf "GB" dan 🇮🇩 sebagai "ID".
    */

    'default' => 'id',

    'available' => [
        'id' => ['native' => 'Bahasa Indonesia', 'short' => 'ID', 'html' => 'id'],
        'en' => ['native' => 'English',          'short' => 'EN', 'html' => 'en'],
    ],
];

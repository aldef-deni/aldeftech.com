<?php

namespace App\Exceptions;

use RuntimeException;

final class ArticleClaimException extends RuntimeException
{
    public function __construct(string $rule, string $field, string $phrase)
    {
        // Preserve only fixed editorial vocabulary. Numbers, names, URLs and
        // unknown tokens are masked, so generated text cannot leak credentials.
        $vocabulary = explode(' ', 'menurut berdasarkan hasil data penelitian riset survei survey research study studies studi laporan report statistik statistics menunjukkan menemukan membuktikan mengungkapkan mencatat melaporkan menyatakan menyebutkan menegaskan shows show found finds proves reveals reports confirms according to a the of based on persen percent per cent efisiensi efficiency produktivitas productivity kinerja performance pendapatan revenue profit keuntungan penjualan sales biaya cost costs waktu time akurasi accuracy penghematan savings konversi conversion pertumbuhan growth pangsa pasar market share kali times jam hours menit minutes detik seconds lebih faster slower fewer more less pengguna pelanggan perusahaan bisnis responden users customers companies businesses respondents mayoritas sebagian besar majority kasus case klien company client berhasil telah meraih achieved reported succeeded sumber source dari oleh diterbitkan published by');
        $tokens = preg_split('/\s+/u', mb_strtolower($phrase));
        $safe = [];
        foreach (array_slice($tokens, 0, 24) as $token) {
            $safe[] = in_array($token, $vocabulary, true) || $token === '%' ? $token : '[redacted]';
        }
        $snippet = implode(' ', $safe);
        // Rule and field originate exclusively in the validator, never the provider.
        parent::__construct("Unsupported claim (field: {$field}; rule: {$rule}; match: {$snippet}).");
    }
}

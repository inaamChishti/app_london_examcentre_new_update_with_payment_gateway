<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class SubjectQueryService
{
    /**
     * Apply option-code-based subject code filtering to the given query.
     * Delegates to the appropriate subject-specific method based on subjectCode.
     *
     * @param  Builder  $query
     * @param  string   $subjectCode   The base specification code (e.g. "8145")
     * @param  string   $optCode       The raw option code from the request (e.g. "8145AA")
     * @return Builder
     */
    public static function applyOptionCodeFilter(Builder $query, string $subjectCode, string $optCode): Builder
    {
         if (preg_match('/^8145(\/|$)/', $subjectCode)) {
            return self::AQA_GCSE_HISTORY($query, $optCode);
        }

        return $query;
    }

    /**
     * Map an 8145 option code to its component subject codes.
     * Strip digits from the raw option code (e.g. "8145AA" → "AA") then switch.
     */
    private static function AQA_GCSE_HISTORY(Builder $query, string $optCode): Builder
    {
        $cleanOpt = preg_replace('/\d+/', '', $optCode);
       
        switch ($cleanOpt) {
            case 'AA': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/A', '8145/2A/A', '8145/2B/A']); break;
            case 'AB': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/A', '8145/2A/A', '8145/2B/B']); break;
            case 'AC': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/A', '8145/2A/A', '8145/2B/C']); break;
            case 'AD': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/A', '8145/2A/A', '8145/2B/D']); break;
            case 'AE': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/A', '8145/2A/B', '8145/2B/A']); break;
            case 'AF': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/A', '8145/2A/B', '8145/2B/B']); break;
            case 'AG': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/A', '8145/2A/B', '8145/2B/C']); break;
            case 'AH': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/A', '8145/2A/B', '8145/2B/D']); break;
            case 'AJ': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/A', '8145/2A/C', '8145/2B/A']); break;
            case 'AK': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/A', '8145/2A/C', '8145/2B/B']); break;
            case 'AL': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/A', '8145/2A/C', '8145/2B/C']); break;
            case 'AM': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/A', '8145/2A/C', '8145/2B/D']); break;
            case 'BA': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/B', '8145/2A/A', '8145/2B/A']); break;
            case 'BB': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/B', '8145/2A/A', '8145/2B/B']); break;
            case 'BC': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/B', '8145/2A/A', '8145/2B/C']); break;
            case 'BD': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/B', '8145/2A/A', '8145/2B/D']); break;
            case 'BE': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/B', '8145/2A/B', '8145/2B/A']); break;
            case 'BF': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/B', '8145/2A/B', '8145/2B/B']); break;
            case 'BG': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/B', '8145/2A/B', '8145/2B/C']); break;
            case 'BH': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/B', '8145/2A/B', '8145/2B/D']); break;
            case 'BJ': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/B', '8145/2A/C', '8145/2B/A']); break;
            case 'BK': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/B', '8145/2A/C', '8145/2B/B']); break;
            case 'BL': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/B', '8145/2A/C', '8145/2B/C']); break;
            case 'BM': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/B', '8145/2A/C', '8145/2B/D']); break;
            case 'CA': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/C', '8145/2A/A', '8145/2B/A']); break;
            case 'CB': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/C', '8145/2A/A', '8145/2B/B']); break;
            case 'CC': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/C', '8145/2A/A', '8145/2B/C']); break;
            case 'CD': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/C', '8145/2A/A', '8145/2B/D']); break;
            case 'CE': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/C', '8145/2A/B', '8145/2B/A']); break;
            case 'CF': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/C', '8145/2A/B', '8145/2B/B']); break;
            case 'CG': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/C', '8145/2A/B', '8145/2B/C']); break;
            case 'CH': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/C', '8145/2A/B', '8145/2B/D']); break;
            case 'CJ': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/C', '8145/2A/C', '8145/2B/A']); break;
            case 'CK': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/C', '8145/2A/C', '8145/2B/B']); break;
            case 'CL': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/C', '8145/2A/C', '8145/2B/C']); break;
            case 'CM': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/C', '8145/2A/C', '8145/2B/D']); break;
            case 'DA': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/D', '8145/2A/A', '8145/2B/A']); break;
            case 'DB': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/D', '8145/2A/A', '8145/2B/B']); break;
            case 'DC': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/D', '8145/2A/A', '8145/2B/C']); break;
            case 'DD': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/D', '8145/2A/A', '8145/2B/D']); break;
            case 'DE': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/D', '8145/2A/B', '8145/2B/A']); break;
            case 'DF': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/D', '8145/2A/B', '8145/2B/B']); break;
            case 'DG': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/D', '8145/2A/B', '8145/2B/C']); break;
            case 'DH': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/D', '8145/2A/B', '8145/2B/D']); break;
            case 'DJ': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/D', '8145/2A/C', '8145/2B/A']); break;
            case 'DK': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/D', '8145/2A/C', '8145/2B/B']); break;
            case 'DL': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/D', '8145/2A/C', '8145/2B/C']); break;
            case 'DM': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/D', '8145/2A/C', '8145/2B/D']); break;
            case 'EA': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/E', '8145/2A/A', '8145/2B/A']); break;
            case 'EB': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/E', '8145/2A/A', '8145/2B/B']); break;
            case 'EC': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/E', '8145/2A/A', '8145/2B/C']); break;
            case 'ED': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/E', '8145/2A/A', '8145/2B/D']); break;
            case 'EE': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/E', '8145/2A/B', '8145/2B/A']); break;
            case 'EF': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/E', '8145/2A/B', '8145/2B/B']); break;
            case 'EG': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/E', '8145/2A/B', '8145/2B/C']); break;
            case 'EH': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/E', '8145/2A/B', '8145/2B/D']); break;
            case 'EJ': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/E', '8145/2A/C', '8145/2B/A']); break;
            case 'EK': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/E', '8145/2A/C', '8145/2B/B']); break;
            case 'EL': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/E', '8145/2A/C', '8145/2B/C']); break;
            case 'EM': $query->whereIn('subjectCode', ['8145/1A/A', '8145/1B/E', '8145/2A/C', '8145/2B/D']); break;
            case 'FA': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/A', '8145/2A/A', '8145/2B/A']); break;
            case 'FB': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/A', '8145/2A/A', '8145/2B/B']); break;
            case 'FC': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/A', '8145/2A/A', '8145/2B/C']); break;
            case 'FD': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/A', '8145/2A/A', '8145/2B/D']); break;
            case 'FE': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/A', '8145/2A/B', '8145/2B/A']); break;
            case 'FF': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/A', '8145/2A/B', '8145/2B/B']); break;
            case 'FG': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/A', '8145/2A/B', '8145/2B/C']); break;
            case 'FH': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/A', '8145/2A/B', '8145/2B/D']); break;
            case 'FJ': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/A', '8145/2A/C', '8145/2B/A']); break;
            case 'FK': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/A', '8145/2A/C', '8145/2B/B']); break;
            case 'FL': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/A', '8145/2A/C', '8145/2B/C']); break;
            case 'FM': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/A', '8145/2A/C', '8145/2B/D']); break;
            case 'GA': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/B', '8145/2A/A', '8145/2B/A']); break;
            case 'GB': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/B', '8145/2A/A', '8145/2B/B']); break;
            case 'GC': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/B', '8145/2A/A', '8145/2B/C']); break;
            case 'GD': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/B', '8145/2A/A', '8145/2B/D']); break;
            case 'GE': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/B', '8145/2A/B', '8145/2B/A']); break;
            case 'GF': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/B', '8145/2A/B', '8145/2B/B']); break;
            case 'GG': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/B', '8145/2A/B', '8145/2B/C']); break;
            case 'GH': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/B', '8145/2A/B', '8145/2B/D']); break;
            case 'GJ': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/B', '8145/2A/C', '8145/2B/A']); break;
            case 'GK': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/B', '8145/2A/C', '8145/2B/B']); break;
            case 'GL': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/B', '8145/2A/C', '8145/2B/C']); break;
            case 'GM': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/B', '8145/2A/C', '8145/2B/D']); break;
            case 'HA': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/C', '8145/2A/A', '8145/2B/A']); break;
            case 'HB': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/C', '8145/2A/A', '8145/2B/B']); break;
            case 'HC': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/C', '8145/2A/A', '8145/2B/C']); break;
            case 'HD': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/C', '8145/2A/A', '8145/2B/D']); break;
            case 'HE': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/C', '8145/2A/B', '8145/2B/A']); break;
            case 'HF': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/C', '8145/2A/B', '8145/2B/B']); break;
            case 'HG': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/C', '8145/2A/B', '8145/2B/C']); break;
            case 'HH': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/C', '8145/2A/B', '8145/2B/D']); break;
            case 'HJ': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/C', '8145/2A/C', '8145/2B/A']); break;
            case 'HK': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/C', '8145/2A/C', '8145/2B/B']); break;
            case 'HL': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/C', '8145/2A/C', '8145/2B/C']); break;
            case 'HM': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/C', '8145/2A/C', '8145/2B/D']); break;
            case 'JA': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/D', '8145/2A/A', '8145/2B/A']); break;
            case 'JB': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/D', '8145/2A/A', '8145/2B/B']); break;
            case 'JC': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/D', '8145/2A/A', '8145/2B/C']); break;
            case 'JD': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/D', '8145/2A/A', '8145/2B/D']); break;
            case 'JE': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/D', '8145/2A/B', '8145/2B/A']); break;
            case 'JF': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/D', '8145/2A/B', '8145/2B/B']); break;
            case 'JG': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/D', '8145/2A/B', '8145/2B/C']); break;
            case 'JH': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/D', '8145/2A/B', '8145/2B/D']); break;
            case 'JJ': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/D', '8145/2A/C', '8145/2B/A']); break;
            case 'JK': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/D', '8145/2A/C', '8145/2B/B']); break;
            case 'JL': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/D', '8145/2A/C', '8145/2B/C']); break;
            case 'JM': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/D', '8145/2A/C', '8145/2B/D']); break;
            case 'KA': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/E', '8145/2A/A', '8145/2B/A']); break;
            case 'KB': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/E', '8145/2A/A', '8145/2B/B']); break;
            case 'KC': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/E', '8145/2A/A', '8145/2B/C']); break;
            case 'KD': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/E', '8145/2A/A', '8145/2B/D']); break;
            case 'KE': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/E', '8145/2A/B', '8145/2B/A']); break;
            case 'KF': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/E', '8145/2A/B', '8145/2B/B']); break;
            case 'KG': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/E', '8145/2A/B', '8145/2B/C']); break;
            case 'KH': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/E', '8145/2A/B', '8145/2B/D']); break;
            case 'KJ': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/E', '8145/2A/C', '8145/2B/A']); break;
            case 'KK': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/E', '8145/2A/C', '8145/2B/B']); break;
            case 'KL': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/E', '8145/2A/C', '8145/2B/C']); break;
            case 'KM': $query->whereIn('subjectCode', ['8145/1A/B', '8145/1B/E', '8145/2A/C', '8145/2B/D']); break;
            case 'LA': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/A', '8145/2A/A', '8145/2B/A']); break;
            case 'LB': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/A', '8145/2A/A', '8145/2B/B']); break;
            case 'LC': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/A', '8145/2A/A', '8145/2B/C']); break;
            case 'LD': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/A', '8145/2A/A', '8145/2B/D']); break;
            case 'LE': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/A', '8145/2A/B', '8145/2B/A']); break;
            case 'LF': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/A', '8145/2A/B', '8145/2B/B']); break;
            case 'LG': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/A', '8145/2A/B', '8145/2B/C']); break;
            case 'LH': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/A', '8145/2A/B', '8145/2B/D']); break;
            case 'LJ': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/A', '8145/2A/C', '8145/2B/A']); break;
            case 'LK': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/A', '8145/2A/C', '8145/2B/B']); break;
            case 'LL': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/A', '8145/2A/C', '8145/2B/C']); break;
            case 'LM': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/A', '8145/2A/C', '8145/2B/D']); break;
            case 'MA': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/B', '8145/2A/A', '8145/2B/A']); break;
            case 'MB': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/B', '8145/2A/A', '8145/2B/B']); break;
            case 'MC': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/B', '8145/2A/A', '8145/2B/C']); break;
            case 'MD': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/B', '8145/2A/A', '8145/2B/D']); break;
            case 'ME': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/B', '8145/2A/B', '8145/2B/A']); break;
            case 'MF': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/B', '8145/2A/B', '8145/2B/B']); break;
            case 'MG': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/B', '8145/2A/B', '8145/2B/C']); break;
            case 'MH': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/B', '8145/2A/B', '8145/2B/D']); break;
            case 'MJ': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/B', '8145/2A/C', '8145/2B/A']); break;
            case 'MK': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/B', '8145/2A/C', '8145/2B/B']); break;
            case 'ML': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/B', '8145/2A/C', '8145/2B/C']); break;
            case 'MM': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/B', '8145/2A/C', '8145/2B/D']); break;
            case 'NA': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/C', '8145/2A/A', '8145/2B/A']); break;
            case 'NB': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/C', '8145/2A/A', '8145/2B/B']); break;
            case 'NC': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/C', '8145/2A/A', '8145/2B/C']); break;
            case 'ND': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/C', '8145/2A/A', '8145/2B/D']); break;
            case 'NE': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/C', '8145/2A/B', '8145/2B/A']); break;
            case 'NF': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/C', '8145/2A/B', '8145/2B/B']); break;
            case 'NG': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/C', '8145/2A/B', '8145/2B/C']); break;
            case 'NH': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/C', '8145/2A/B', '8145/2B/D']); break;
            case 'NJ': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/C', '8145/2A/C', '8145/2B/A']); break;
            case 'NK': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/C', '8145/2A/C', '8145/2B/B']); break;
            case 'NL': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/C', '8145/2A/C', '8145/2B/C']); break;
            case 'NM': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/C', '8145/2A/C', '8145/2B/D']); break;
            case 'OA': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/D', '8145/2A/A', '8145/2B/A']); break;
            case 'OB': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/D', '8145/2A/A', '8145/2B/B']); break;
            case 'OC': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/D', '8145/2A/A', '8145/2B/C']); break;
            case 'OD': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/D', '8145/2A/A', '8145/2B/D']); break;
            case 'OE': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/D', '8145/2A/B', '8145/2B/A']); break;
            case 'OF': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/D', '8145/2A/B', '8145/2B/B']); break;
            case 'OG': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/D', '8145/2A/B', '8145/2B/C']); break;
            case 'OH': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/D', '8145/2A/B', '8145/2B/D']); break;
            case 'OJ': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/D', '8145/2A/C', '8145/2B/A']); break;
            case 'OK': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/D', '8145/2A/C', '8145/2B/B']); break;
            case 'OL': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/D', '8145/2A/C', '8145/2B/C']); break;
            case 'OM': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/D', '8145/2A/C', '8145/2B/D']); break;
            case 'PA': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/E', '8145/2A/A', '8145/2B/A']); break;
            case 'PB': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/E', '8145/2A/A', '8145/2B/B']); break;
            case 'PC': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/E', '8145/2A/A', '8145/2B/C']); break;
            case 'PD': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/E', '8145/2A/A', '8145/2B/D']); break;
            case 'PE': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/E', '8145/2A/B', '8145/2B/A']); break;
            case 'PF': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/E', '8145/2A/B', '8145/2B/B']); break;
            case 'PG': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/E', '8145/2A/B', '8145/2B/C']); break;
            case 'PH': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/E', '8145/2A/B', '8145/2B/D']); break;
            case 'PJ': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/E', '8145/2A/C', '8145/2B/A']); break;
            case 'PK': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/E', '8145/2A/C', '8145/2B/B']); break;
            case 'PL': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/E', '8145/2A/C', '8145/2B/C']); break;
            case 'PM': $query->whereIn('subjectCode', ['8145/1A/C', '8145/1B/E', '8145/2A/C', '8145/2B/D']); break;
            case 'QA': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/A', '8145/2A/A', '8145/2B/A']); break;
            case 'QB': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/A', '8145/2A/A', '8145/2B/B']); break;
            case 'QC': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/A', '8145/2A/A', '8145/2B/C']); break;
            case 'QD': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/A', '8145/2A/A', '8145/2B/D']); break;
            case 'QE': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/A', '8145/2A/B', '8145/2B/A']); break;
            case 'QF': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/A', '8145/2A/B', '8145/2B/B']); break;
            case 'QG': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/A', '8145/2A/B', '8145/2B/C']); break;
            case 'QH': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/A', '8145/2A/B', '8145/2B/D']); break;
            case 'QJ': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/A', '8145/2A/C', '8145/2B/A']); break;
            case 'QK': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/A', '8145/2A/C', '8145/2B/B']); break;
            case 'QL': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/A', '8145/2A/C', '8145/2B/C']); break;
            case 'QM': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/A', '8145/2A/C', '8145/2B/D']); break;
            case 'RA': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/B', '8145/2A/A', '8145/2B/A']); break;
            case 'RB': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/B', '8145/2A/A', '8145/2B/B']); break;
            case 'RC': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/B', '8145/2A/A', '8145/2B/C']); break;
            case 'RD': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/B', '8145/2A/A', '8145/2B/D']); break;
            case 'RE': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/B', '8145/2A/B', '8145/2B/A']); break;
            case 'RF': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/B', '8145/2A/B', '8145/2B/B']); break;
            case 'RG': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/B', '8145/2A/B', '8145/2B/C']); break;
            case 'RH': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/B', '8145/2A/B', '8145/2B/D']); break;
            case 'RJ': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/B', '8145/2A/C', '8145/2B/A']); break;
            case 'RK': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/B', '8145/2A/C', '8145/2B/B']); break;
            case 'RL': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/B', '8145/2A/C', '8145/2B/C']); break;
            case 'RM': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/B', '8145/2A/C', '8145/2B/D']); break;
            case 'SA': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/C', '8145/2A/A', '8145/2B/A']); break;
            case 'SB': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/C', '8145/2A/A', '8145/2B/B']); break;
            case 'SC': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/C', '8145/2A/A', '8145/2B/C']); break;
            case 'SD': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/C', '8145/2A/A', '8145/2B/D']); break;
            case 'SE': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/C', '8145/2A/B', '8145/2B/A']); break;
            case 'SF': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/C', '8145/2A/B', '8145/2B/B']); break;
            case 'SG': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/C', '8145/2A/B', '8145/2B/C']); break;
            case 'SH': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/C', '8145/2A/B', '8145/2B/D']); break;
            case 'SJ': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/C', '8145/2A/C', '8145/2B/A']); break;
            case 'SK': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/C', '8145/2A/C', '8145/2B/B']); break;
            case 'SL': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/C', '8145/2A/C', '8145/2B/C']); break;
            case 'SM': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/C', '8145/2A/C', '8145/2B/D']); break;
            case 'TA': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/D', '8145/2A/A', '8145/2B/A']); break;
            case 'TB': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/D', '8145/2A/A', '8145/2B/B']); break;
            case 'TC': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/D', '8145/2A/A', '8145/2B/C']); break;
            case 'TD': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/D', '8145/2A/A', '8145/2B/D']); break;
            case 'TE': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/D', '8145/2A/B', '8145/2B/A']); break;
            case 'TF': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/D', '8145/2A/B', '8145/2B/B']); break;
            case 'TG': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/D', '8145/2A/B', '8145/2B/C']); break;
            case 'TH': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/D', '8145/2A/B', '8145/2B/D']); break;
            case 'TJ': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/D', '8145/2A/C', '8145/2B/A']); break;
            case 'TK': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/D', '8145/2A/C', '8145/2B/B']); break;
            case 'TL': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/D', '8145/2A/C', '8145/2B/C']); break;
            case 'TM': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/D', '8145/2A/C', '8145/2B/D']); break;
            case 'UA': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/E', '8145/2A/A', '8145/2B/A']); break;
            case 'UB': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/E', '8145/2A/A', '8145/2B/B']); break;
            case 'UC': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/E', '8145/2A/A', '8145/2B/C']); break;
            case 'UD': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/E', '8145/2A/A', '8145/2B/D']); break;
            case 'UE': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/E', '8145/2A/B', '8145/2B/A']); break;
            case 'UF': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/E', '8145/2A/B', '8145/2B/B']); break;
            case 'UG': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/E', '8145/2A/B', '8145/2B/C']); break;
            case 'UH': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/E', '8145/2A/B', '8145/2B/D']); break;
            case 'UJ': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/E', '8145/2A/C', '8145/2B/A']); break;
            case 'UK': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/E', '8145/2A/C', '8145/2B/B']); break;
            case 'UL': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/E', '8145/2A/C', '8145/2B/C']); break;
            case 'UM': $query->whereIn('subjectCode', ['8145/1A/D', '8145/1B/E', '8145/2A/C', '8145/2B/D']); break;
        }

        return $query;
    }

}

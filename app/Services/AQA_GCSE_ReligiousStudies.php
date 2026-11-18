<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class AQA_GCSE_ReligiousStudies
{
    /**
     * Map an AQA GCSE Religious Studies (8062) option code to its component
     * subject codes and apply a whereIn filter to the given query.
     *
     * Strip digits from the raw option code (e.g. "8062AA" → "AA") then switch.
     *
     * @param  Builder  $query
     * @param  string   $optCode   Raw option code from the request (e.g. "8062AA")
     * @return Builder
     */
    public static function apply(Builder $query, string $optCode): Builder
    {
        $cleanOpt = preg_replace('/\d+/', '', $optCode);

        switch ($cleanOpt) {
            case 'CA': $query->whereIn('subjectCode', ['8062/11', '8062/14', '8062/2A']); break;
            case 'DA': $query->whereIn('subjectCode', ['8062/11', '8062/15', '8062/2A']); break;
            case 'EA': $query->whereIn('subjectCode', ['8062/11', '8062/16', '8062/2A']); break;
            case 'FA': $query->whereIn('subjectCode', ['8062/11', '8062/17', '8062/2A']); break;
            case 'GA': $query->whereIn('subjectCode', ['8062/12', '8062/14', '8062/2A']); break;
            case 'GB': $query->whereIn('subjectCode', ['8062/12', '8062/14', '8062/2B']); break;
            case 'HA': $query->whereIn('subjectCode', ['8062/12', '8062/15', '8062/2A']); break;
            case 'HB': $query->whereIn('subjectCode', ['8062/12', '8062/15', '8062/2B']); break;
            case 'JA': $query->whereIn('subjectCode', ['8062/12', '8062/16', '8062/2A']); break;
            case 'JB': $query->whereIn('subjectCode', ['8062/12', '8062/16', '8062/2B']); break;
            case 'KA': $query->whereIn('subjectCode', ['8062/12', '8062/17', '8062/2A']); break;
            case 'KB': $query->whereIn('subjectCode', ['8062/12', '8062/17', '8062/2B']); break;
            case 'LA': $query->whereIn('subjectCode', ['8062/13', '8062/14', '8062/2A']); break;
            case 'LB': $query->whereIn('subjectCode', ['8062/13', '8062/14', '8062/2B']); break;
            case 'MA': $query->whereIn('subjectCode', ['8062/13', '8062/15', '8062/2A']); break;
            case 'MB': $query->whereIn('subjectCode', ['8062/13', '8062/15', '8062/2B']); break;
            case 'NA': $query->whereIn('subjectCode', ['8062/13', '8062/16', '8062/2A']); break;
            case 'NB': $query->whereIn('subjectCode', ['8062/13', '8062/16', '8062/2B']); break;
            case 'OA': $query->whereIn('subjectCode', ['8062/13', '8062/17', '8062/2A']); break;
            case 'OB': $query->whereIn('subjectCode', ['8062/13', '8062/17', '8062/2B']); break;
            case 'PA': $query->whereIn('subjectCode', ['8062/14', '8062/15', '8062/2A']); break;
            case 'QA': $query->whereIn('subjectCode', ['8062/14', '8062/16', '8062/2A']); break;
            case 'RA': $query->whereIn('subjectCode', ['8062/14', '8062/17', '8062/2A']); break;
            case 'SA': $query->whereIn('subjectCode', ['8062/15', '8062/16', '8062/2A']); break;
            case 'TA': $query->whereIn('subjectCode', ['8062/15', '8062/17', '8062/2A']); break;
            case 'UA': $query->whereIn('subjectCode', ['8062/16', '8062/17', '8062/2A']); break;
        }

        return $query;
    }

    // -------------------------------------------------------------------------
    // AQA GCSE Religious Studies B (8063)
    // -------------------------------------------------------------------------

    public static function applyB(Builder $query, string $optCode): Builder
    {
        $cleanOpt = preg_replace('/\d+/', '', $optCode);

        switch ($cleanOpt) {
            case 'XA': 
            case '8063XA': // Agar full code bhi match karna ho ya jo pattern ho
                $query->whereIn('subjectCode', ['8063/1', '8063/2X', '8063/2A']); 
                break;
            case 'XB': 
                $query->whereIn('subjectCode', ['8063/1', '8063/2X', '8063/2B']); 
                break;
            case 'YA': 
                $query->whereIn('subjectCode', ['8063/1', '8063/2Y', '8063/2A']); 
                break;
            case 'YB': 
                $query->whereIn('subjectCode', ['8063/1', '8063/2Y', '8063/2B']); 
                break;
            default:
                break;
        }

        return $query;
    }
}

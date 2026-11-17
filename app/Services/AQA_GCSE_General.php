<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class AQA_GCSE_General
{
    /**
     * Apply subject-code-based paper filtering for general AQA GCSE subjects.
     * Matches on the full subjectCode (e.g. "8300F", "8465H") and restricts
     * the query to the relevant component paper codes.
     *
     * @param  Builder  $query
     * @param  string   $subjectCode   Full subject code from the request (e.g. "8300F")
     * @return Builder
     */
    public static function apply(Builder $query, string $subjectCode): Builder
    {
        switch ($subjectCode) {
            case '8638F': $query->whereIn('subjectCode', ['8638/LF', '8638/SF', '8638/RF', '8638/WF']); break;
            case '8638H': $query->whereIn('subjectCode', ['8638/LH', '8638/SH', '8638/RH', '8638/WH']); break;

            case '8461F': $query->whereIn('subjectCode', ['8461/1F', '8461/2F']); break;
            case '8461H': $query->whereIn('subjectCode', ['8461/1H', '8461/2H']); break;

            case '8132': $query->whereIn('subjectCode', ['8132/1', '8132/2']); break;

            case '8462F': $query->whereIn('subjectCode', ['8462/1F', '8462/2F']); break;
            case '8462H': $query->whereIn('subjectCode', ['8462/1H', '8462/2H']); break;

            case '8100': $query->whereIn('subjectCode', ['8100/1', '8100/2']); break;

            case '8465F': $query->whereIn('subjectCode', ['8465/1F', '8465/2F', '8465/3F', '8465/4F']); break;
            case '8465H': $query->whereIn('subjectCode', ['8465/1H', '8465/2H', '8465/3H', '8465/4H']); break;

            case '8673F': $query->whereIn('subjectCode', ['8673/LF', '8673/SF', '8673/RF', '8673/WF']); break;
            case '8673H': $query->whereIn('subjectCode', ['8673/LH', '8673/SH', '8673/RH', '8673/WH']); break;

            case '8464F': $query->whereIn('subjectCode', ['8464/B/1F', '8464/B/2F', '8464/C/1F', '8464/C/2F', '8464/P/1F', '8464/P/2F']); break;
            case '8464H': $query->whereIn('subjectCode', ['8464/B/1H', '8464/B/2H', '8464/C/1H', '8464/C/2H', '8464/P/1H', '8464/P/2H']); break;

            case '8525A': $query->whereIn('subjectCode', ['8525/1A', '8525/2']); break;
            case '8525B': $query->whereIn('subjectCode', ['8525/1B', '8525/2']); break;
            case '8525C': $query->whereIn('subjectCode', ['8525/1C', '8525/2']); break;

            case '8136': $query->whereIn('subjectCode', ['8136/1', '8136/2']); break;
            case '8852': $query->whereIn('subjectCode', ['8852/C', '8852/W']); break;

            case '8700': $query->whereIn('subjectCode', ['8700/C', '8700/1', '8700/2']); break;
            case '8702': $query->whereIn('subjectCode', ['8702/1', '8702/2']); break;

            case '8652F': $query->whereIn('subjectCode', ['8652/LF', '8652/SF', '8652/RF', '8652/WF']); break;
            case '8652H': $query->whereIn('subjectCode', ['8652/LH', '8652/SH', '8652/RH', '8652/WH']); break;

            case '8662F': $query->whereIn('subjectCode', ['8662/LF', '8662/SF', '8662/RF', '8662/WF']); break;
            case '8662H': $query->whereIn('subjectCode', ['8662/LH', '8662/SH', '8662/RH', '8662/WH']); break;

            case '8633F': $query->whereIn('subjectCode', ['8633/LF', '8633/SF', '8633/RF', '8633/WF']); break;
            case '8633H': $query->whereIn('subjectCode', ['8633/LH', '8633/SH', '8633/RH', '8633/WH']); break;

            case '8300F': $query->whereIn('subjectCode', ['8300/1F', '8300/2F', '8300/3F']); break;
            case '8300H': $query->whereIn('subjectCode', ['8300/1H', '8300/2H', '8300/3H']); break;

            case '8678F': $query->whereIn('subjectCode', ['8678/LF', '8678/SF', '8678/RF', '8678/WF']); break;
            case '8678H': $query->whereIn('subjectCode', ['8678/LH', '8678/SH', '8678/RH', '8678/WH']); break;

            case '8683F': $query->whereIn('subjectCode', ['8683/LF', '8683/SF', '8683/RF', '8683/WF']); break;
            case '8683H': $query->whereIn('subjectCode', ['8683/LH', '8683/SH', '8683/RH', '8683/WH']); break;

            case '8463F': $query->whereIn('subjectCode', ['8463/1F', '8463/2F']); break;
            case '8463H': $query->whereIn('subjectCode', ['8463/1H', '8463/2H']); break;

            case '8688F': $query->whereIn('subjectCode', ['8688/LF', '8688/SF', '8688/RF', '8688/WF']); break;
            case '8688H': $query->whereIn('subjectCode', ['8688/LH', '8688/SH', '8688/RH', '8688/WH']); break;

            case '8182': $query->whereIn('subjectCode', ['8182/1', '8182/2']); break;
            case '8192': $query->whereIn('subjectCode', ['8192/1', '8192/2']); break;

            case '8692F': $query->whereIn('subjectCode', ['8692/LF', '8692/SF', '8692/RF', '8692/WF']); break;
            case '8692H': $query->whereIn('subjectCode', ['8692/LH', '8692/SH', '8692/RH', '8692/WH']); break;

            case '8382F': $query->whereIn('subjectCode', ['8382/1F', '8382/2F']); break;
            case '8382H': $query->whereIn('subjectCode', ['8382/1H', '8382/2H']); break;

            case '8648F': $query->whereIn('subjectCode', ['8648/LF', '8648/SF', '8648/RF', '8648/WF']); break;
            case '8648H': $query->whereIn('subjectCode', ['8648/LH', '8648/SH', '8648/RH', '8648/WH']); break;

            default: break;
        }

        return $query;
    }
}

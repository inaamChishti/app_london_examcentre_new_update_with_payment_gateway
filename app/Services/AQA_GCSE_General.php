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
        // dd($subjectCode);
        switch ($subjectCode) {
            case '8638F':
            case '8638/LF':
            case '8638/SF':
            case '8638/WF':
            case '8638/RF':
                $query->whereIn('subjectCode', ['8638/LF', '8638/SF', '8638/RF', '8638/WF']);
                break;
            case '8638H':
            case '8638/LH':
            case '8638/SH':
            case '8638/RH':
            case '8638/WH':
                $query->whereIn('subjectCode', ['8638/LH', '8638/SH', '8638/RH', '8638/WH']);
                break;

            case '8461F':
            case '8461/1F':
            case '8461/2F':
                $query->whereIn('subjectCode', ['8461/1F', '8461/2F']);
                break;
            case '8461H':
            case '8461/1H':
            case '8461/2H':
                $query->whereIn('subjectCode', ['8461/1H', '8461/2H']);
                break;

            case '8132':
            case '8132/1':
            case '8132/2':
                $query->whereIn('subjectCode', ['8132/1', '8132/2']);
                break;

            case '8462F':
            case '8462/1F':
            case '8462/2F':
                $query->whereIn('subjectCode', ['8462/1F', '8462/2F']);
                break;
            case '8462H':
            case '8462/1H':
            case '8462/2H':
                $query->whereIn('subjectCode', ['8462/1H', '8462/2H']);
                break;

            case '8100':
            case '8100/1':
            case '8100/2':
                $query->whereIn('subjectCode', ['8100/1', '8100/2']);
                break;

            case '8465F':
            case '8465/1F':
            case '8465/2F':
            case '8465/3F':
            case '8465/4F':
                $query->whereIn('subjectCode', ['8465/1F', '8465/2F', '8465/3F', '8465/4F']);
                break;
            case '8465H':
            case '8465/1H':
            case '8465/2H':
            case '8465/3H':
            case '8465/4H':
                $query->whereIn('subjectCode', ['8465/1H', '8465/2H', '8465/3H', '8465/4H']);
                break;

            case '8673F':
            case '8673/LF':
            case '8673/SF':
            case '8673/RF':
            case '8673/WF':
                $query->whereIn('subjectCode', ['8673/LF', '8673/SF', '8673/RF', '8673/WF']);
                break;
            case '8673H':
            case '8673/LH':
            case '8673/SH':
            case '8673/RH':
            case '8673/WH':
                $query->whereIn('subjectCode', ['8673/LH', '8673/SH', '8673/RH', '8673/WH']);
                break;

            case '8464F':
            case '8464/B/1F':
            case '8464/B/2F':
            case '8464/C/1F':
            case '8464/C/2F':
            case '8464/P/1F':
            case '8464/P/2F':
                $query->whereIn('subjectCode', ['8464/B/1F', '8464/B/2F', '8464/C/1F', '8464/C/2F', '8464/P/1F', '8464/P/2F']);
                break;
            case '8464H':
            case '8464/B/1H':
            case '8464/B/2H':
            case '8464/C/1H':
            case '8464/C/2H':
            case '8464/P/1H':
            case '8464/P/2H':
                $query->whereIn('subjectCode', ['8464/B/1H', '8464/B/2H', '8464/C/1H', '8464/C/2H', '8464/P/1H', '8464/P/2H']);
                break;

            case '8525A':
            case '8525/1A':
            case '8525/1B':
            case '8525/1C':
            case '8525/2':
                $query->whereIn('subjectCode', ['8525/1A', '8525/1B', '8525/1C', '8525/2']);
                break;
            case '8525B':
            case '8525/1B':
            case '8525/1A':
            case '8525/1C':
            case '8525/2':
                $query->whereIn('subjectCode', ['8525/1A', '8525/1B', '8525/1C', '8525/2']);
                break;
            case '8525C':
            case '8525/1C':
            case '8525/1A':
            case '8525/1B':
            case '8525/2':
                $query->whereIn('subjectCode', ['8525/1A', '8525/1B', '8525/1C', '8525/2']);
                break;

            case '8136':
            case '8136/1':
            case '8136/2':
                $query->whereIn('subjectCode', ['8136/1', '8136/2']);
                break;
            case '8852':
            case '8852/C':
            case '8852/W':
                $query->whereIn('subjectCode', ['8852/C', '8852/W']);
                break;

            case '8700':
            case '8700/1':
            case '8700/C':
            case '8700/2':
                $query->whereIn('subjectCode', ['8700/C', '8700/1', '8700/2']);
                break;
            case '8702':
            case '8702/1':
            case '8702/2':
                $query->whereIn('subjectCode', ['8702/1', '8702/2']);
                break;

            case '8652F':
            case '8652/LF':
            case '8652/SF':
            case '8652/RF':
            case '8652/WF':
                $query->whereIn('subjectCode', ['8652/LF', '8652/SF', '8652/RF', '8652/WF']);
                break;
            case '8652H':
            case '8652/LH':
            case '8652/SH':
            case '8652/RH':
            case '8652/WH':
                $query->whereIn('subjectCode', ['8652/LH', '8652/SH', '8652/RH', '8652/WH']);
                break;

            case '8662F':
            case '8662/LF':
            case '8662/SF':
            case '8662/RF':
            case '8662/WF':
                $query->whereIn('subjectCode', ['8662/LF', '8662/SF', '8662/RF', '8662/WF']);
                break;
            case '8662H':
            case '8662/LH':
            case '8662/SH':
            case '8662/RH':
            case '8662/WH':
                $query->whereIn('subjectCode', ['8662/LH', '8662/SH', '8662/RH', '8662/WH']);
                break;

            case '8633F':
            case '8633/LF':
            case '8633/SF':
            case '8633/RF':
            case '8633/WF':
                $query->whereIn('subjectCode', ['8633/LF', '8633/SF', '8633/RF', '8633/WF']);
                break;
            case '8633H':
            case '8633/LH':
            case '8633/SH':
            case '8633/RH':
            case '8633/WH':
                $query->whereIn('subjectCode', ['8633/LH', '8633/SH', '8633/RH', '8633/WH']);
                break;

            case '8300F':
            case '8300/1F':
            case '8300/2F':
            case '8300/3F':
                $query->whereIn('subjectCode', ['8300/1F', '8300/2F', '8300/3F']);
                break;
            case '8300H':
            case '8300/1H':
            case '8300/2H':
            case '8300/3H':
                $query->whereIn('subjectCode', ['8300/1H', '8300/2H', '8300/3H']);
                break;

            case '8678F':
            case '8678/LF':
            case '8678/SF':
            case '8678/RF':
            case '8678/WF':
                $query->whereIn('subjectCode', ['8678/LF', '8678/SF', '8678/RF', '8678/WF']);
                break;
            case '8678H':
            case '8678/LH':
            case '8678/SH':
            case '8678/RH':
            case '8678/WH':
                $query->whereIn('subjectCode', ['8678/LH', '8678/SH', '8678/RH', '8678/WH']);
                break;

            case '8683F':
            case '8683/LF':
            case '8683/SF':
            case '8683/RF':
            case '8683/WF':
                $query->whereIn('subjectCode', ['8683/LF', '8683/SF', '8683/RF', '8683/WF']);
                break;
            case '8683H':
            case '8683/LH':
            case '8683/SH':
            case '8683/RH':
            case '8683/WH':
                $query->whereIn('subjectCode', ['8683/LH', '8683/SH', '8683/RH', '8683/WH']);
                break;

            case '8463F':
            case '8463/1F':
            case '8463/2F':
                $query->whereIn('subjectCode', ['8463/1F', '8463/2F']);
                break;
            case '8463H':
            case '8463/1H':
            case '8463/2H':
                $query->whereIn('subjectCode', ['8463/1H', '8463/2H']);
                break;

            case '8688F':
            case '8688/LF':
            case '8688/SF':
            case '8688/RF':
            case '8688/WF':
                $query->whereIn('subjectCode', ['8688/LF', '8688/SF', '8688/RF', '8688/WF']);
                break;
            case '8688H':
            case '8688/LH':
            case '8688/SH':
            case '8688/RH':
            case '8688/WH':
                $query->whereIn('subjectCode', ['8688/LH', '8688/SH', '8688/RH', '8688/WH']);
                break;

            case '8182':
            case '8182/1':
            case '8182/2':
                $query->whereIn('subjectCode', ['8182/1', '8182/2']);
                break;
            case '8192':
            case '8192/1':
            case '8192/2':
                $query->whereIn('subjectCode', ['8192/1', '8192/2']);
                break;

            case '8692F':
            case '8692/LF':
            case '8692/SF':
            case '8692/RF':
            case '8692/WF':
                $query->whereIn('subjectCode', ['8692/LF', '8692/SF', '8692/RF', '8692/WF']);
                break;
            case '8692H':
            case '8692/LH':
            case '8692/SH':
            case '8692/RH':
            case '8692/WH':
                $query->whereIn('subjectCode', ['8692/LH', '8692/SH', '8692/RH', '8692/WH']);
                break;

            case '8382F':
            case '8382/1F':
            case '8382/2F':
                $query->whereIn('subjectCode', ['8382/1F', '8382/2F']);
                break;
            case '8382H':
            case '8382/1H':
            case '8382/2H':
                $query->whereIn('subjectCode', ['8382/1H', '8382/2H']);
                break;

            case '8648F':
            case '8648/LF':
            case '8648/SF':
            case '8648/RF':
            case '8648/WF':
                $query->whereIn('subjectCode', ['8648/LF', '8648/SF', '8648/RF', '8648/WF']);
                break;
            case '8648H':
            case '8648/LH':
            case '8648/SH':
            case '8648/RH':
            case '8648/WH':
                $query->whereIn('subjectCode', ['8648/LH', '8648/SH', '8648/RH', '8648/WH']);
                break;

            default:
                break;
        }

        return $query;
    }
}

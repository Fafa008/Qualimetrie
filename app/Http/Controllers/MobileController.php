<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MobilePlan;

class MobileController extends Controller
{
    public function calculateBill(Request $request)
    {
        $base = 15;
        $quota = 50;
        $total = 0;

        $dataUsed = $request->input('dataUsed');
        $dataNonEU = $request->input('dataNonEU_MB');
        $anciennete = $request->input('anciennete');
        $isEtudiant = $request->input('isEtudiant');

        if ($anciennete > 5) {
            $quota = 100;
        } else {
            $quota = 50;
        }

        if ($isEtudiant) {
            $base = $base - ($base * 0.10);
        } else {
            $base = $base;
        }

        if ($dataUsed > $quota) {
            if ($quota == 100) {
                $extra = $dataUsed - 100;
                $total = $total + ($extra * 2);
            } else {
                if ($quota == 50) {
                    $extra = $dataUsed - 50;
                    $total = $total + ($extra * 2);
                }
            }
        }

        if ($dataNonEU > 0) {
            $horsUE = $dataNonEU * 5;

            if ($horsUE > 50) {
                $horsUE = 50;
            }

            $total = $total + $horsUE;
        }

        $total = $total + $base;

        // Sauvegarde (optionnel)
        MobilePlan::create([
            'dataUsed' => $dataUsed,
            'dataNonEU_MB' => $dataNonEU,
            'anciennete' => $anciennete,
            'isEtudiant' => $isEtudiant,
            'total' => $total
        ]);

        return response()->json([
            'total' => $total
        ]);
    }
}

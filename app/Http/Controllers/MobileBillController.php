<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileBillController extends Controller
{
    private const FORFAIT_BASE        = 15.0;
    private const DATA_INCLUSE_STD    = 50;
    private const DATA_INCLUSE_FIDELE = 100;
    private const PRIX_EXTRA_GO       = 2.0;
    private const PRIX_HORS_UE_MO     = 5.0;
    private const PLAFOND_HORS_UE     = 50.0;
    private const REMISE_ETUDIANT     = 0.10;
    private const SEUIL_ANCIENNETE    = 5;

    public function calculate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'data_go'           => 'required|numeric|min:0',
            'anciennete_annees' => 'required|numeric|min:0',
            'statut'            => 'required|string|in:standard,etudiant',
            'hors_ue_mo'        => 'sometimes|numeric|min:0',
        ]);

        $dataGo           = (float) $validated['data_go'];
        $ancienneteAnnees = (int)   $validated['anciennete_annees'];
        $statut           = $validated['statut'];
        $horsUeMo         = (float) ($validated['hors_ue_mo'] ?? 0);

        $dataIncluse = $this->calculerDataIncluse($ancienneteAnnees);
        $base        = $this->calculerForfaitBase($statut);
        $extraData   = $this->calculerExtraData($dataGo, $dataIncluse);
        $horsUE      = $this->calculerHorsUE($horsUeMo);
        $total       = $base + $extraData + $horsUE;

        return response()->json([
            'total'  => round($total, 2),
            'detail' => [
                'forfait_base' => round($base, 2),
                'extra_data'   => round($extraData, 2),
                'hors_ue'      => round($horsUE, 2),
                'data_incluse' => $dataIncluse,
            ],
        ]);
    }

    public function calculerForfaitBase(string $statut): float
    {
        $remise = ($statut === 'etudiant') ? self::REMISE_ETUDIANT : 0;
        return self::FORFAIT_BASE * (1 - $remise);
    }

    public function calculerDataIncluse(int $ancienneteAnnees): int
    {
        return ($ancienneteAnnees > self::SEUIL_ANCIENNETE)
            ? self::DATA_INCLUSE_FIDELE
            : self::DATA_INCLUSE_STD;
    }

    public function calculerExtraData(float $dataGo, int $dataIncluse): float
    {
        $depassement = $dataGo - $dataIncluse;
        return ($depassement > 0) ? $depassement * self::PRIX_EXTRA_GO : 0.0;
    }

    public function calculerHorsUE(float $horsUeMo): float
    {
        if ($horsUeMo <= 0) {
            return 0.0;
        }
        return min($horsUeMo * self::PRIX_HORS_UE_MO, self::PLAFOND_HORS_UE);
    }
}
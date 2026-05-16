<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Trafic;

class TraficController extends Controller
{
    public function detail(): void
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 300000);

        $model = new Trafic();
        $rows  = $model->getDetail();
        $stats = $model->getDetailStats($rows);

        $this->view('trafic/detail', [
            'pageTitle' => 'Trafic Bureau Détail - Wilaya 31 (Oran)',
            'rows'      => $rows,
            'stats'     => $stats,
        ]);
    }

    public function global(): void
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 300000);

        $model = new Trafic();
        $rows  = $model->getGlobal();
        $stats = $model->getGlobalStats($rows);

        $this->view('trafic/global', [
            'pageTitle' => 'Trafic Bureau Global - Wilaya 31 (Oran)',
            'rows'      => $rows,
            'stats'     => $stats,
        ]);
    }
}

<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Cnep;

class CnepController extends Controller
{
    public function index(): void
    {
        $model = new Cnep();
        $rows  = $model->getByWilaya(31);
        $stats = $model->getStats($rows);

        $this->view('cnep/index', [
            'pageTitle' => 'Opérations CNEP',
            'rows'      => $rows,
            'stats'     => $stats,
        ]);
    }
}

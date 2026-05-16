<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Carnet;

class CarnetController extends Controller
{
    public function index(): void
    {
        $model = new Carnet();
        $rows  = $model->getByWilaya('31');
        $totalCarnets = $model->getTotalCarnets($rows);

        $this->view('carnet/index', [
            'pageTitle'    => 'Commandes Carnets de Chèque',
            'rows'         => $rows,
            'totalRows'    => count($rows),
            'totalCarnets' => $totalCarnets,
        ]);
    }
}

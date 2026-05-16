<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Bureau;

class BureauController extends Controller
{
    public function index(): void
    {
        $model = new Bureau();
        $rows  = $model->getAll();

        $this->view('bureau/index', [
            'pageTitle' => 'Liste des Bureaux',
            'rows'      => $rows,
            'totalRows' => count($rows),
        ]);
    }
}

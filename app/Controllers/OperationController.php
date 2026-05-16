<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Operation;

class OperationController extends Controller
{
    public function index(): void
    {
        $model = new Operation();
        $rows  = $model->getAll();

        $this->view('operation/index', [
            'pageTitle' => 'Liste des Opérations',
            'rows'      => $rows,
            'totalRows' => count($rows),
        ]);
    }
}

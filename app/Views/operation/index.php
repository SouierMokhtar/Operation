<div class="container">
    <div class="header">
        <div class="logo-container">
            <img src="<?= BASE_URL ?>/assets/images/logo-round.png" alt="Algérie Poste">
            <h1><?= htmlspecialchars($pageTitle) ?></h1>
        </div>
        <p class="subtitle">Direction Régionale - Oran</p>
    </div>

    <div class="search-container">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Rechercher une opération..." autocomplete="off">
        </div>
        <div class="result-count">
            <i class="fas fa-filter"></i>
            Résultats : <span class="count-value" id="countValue"><?= $totalRows ?></span>
        </div>
        <div class="export-buttons">
            <button class="export-btn btn-excel" onclick="exportExcel()"><i class="fas fa-file-excel"></i>Excel</button>
            <button class="export-btn btn-pdf" onclick="exportPDF()"><i class="fas fa-file-pdf"></i>PDF</button>
            <button class="export-btn btn-txt" onclick="exportTXT()"><i class="fas fa-file-alt"></i>TXT</button>
        </div>
    </div>

    <div class="table-wrapper">
        <table id="dataTable">
            <thead>
                <tr>
                    <th><i class="fas fa-barcode"></i>Code Opération</th>
                    <th><i class="fas fa-tasks"></i>Nom Opération</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if ($totalRows > 0): ?>
                    <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['CodeOperation']) ?></td>
                        <td><?= htmlspecialchars($row['NomOperation']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="no-results">Aucune opération trouvée</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-icon orange"><i class="fas fa-tasks"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="statTotal"><?= number_format($totalRows) ?></div>
                <div class="stat-label">Total Opérations</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2026 Algérie Poste - Tous droits réservés</p>
    </div>
</div>

<script>
    const headers = ['Code Opération', 'Nom Opération'];
    const allData = <?= json_encode(array_map(function($r) {
        return [
            'Code Opération' => $r['CodeOperation'],
            'Nom Opération'  => $r['NomOperation'],
        ];
    }, $rows), JSON_UNESCAPED_UNICODE) ?>;

    initSimpleSearch('searchInput', 'tableBody', 'countValue', allData, headers, {
        statElements: { 'statTotal': { type: 'count' } },
        exportConfig: {
            fileName: 'operations_export',
            sheetName: 'Operations',
            title: 'Liste des Opérations',
            titleColor: [249, 115, 22],
            headerColor: [249, 115, 22],
        }
    });
</script>

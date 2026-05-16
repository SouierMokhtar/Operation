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
            <input type="text" id="searchInput" placeholder="Rechercher un bureau..." autocomplete="off">
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
                    <th><i class="fas fa-key"></i>Code Gestionnaire</th>
                    <th><i class="fas fa-barcode"></i>Code Bureau</th>
                    <th><i class="fas fa-building"></i>Nom Bureau</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if ($totalRows > 0): ?>
                    <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['CodeGestionnaire']) ?></td>
                        <td><?= htmlspecialchars($row['CodeBureau']) ?></td>
                        <td><?= htmlspecialchars($row['NomBureau']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="no-results">Aucun bureau trouvé</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-icon blue"><i class="fas fa-building"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="statTotal"><?= number_format($totalRows) ?></div>
                <div class="stat-label">Total Bureaux</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2026 Algérie Poste - Tous droits réservés</p>
    </div>
</div>

<script>
    const headers = ['Code Gestionnaire', 'Code Bureau', 'Nom Bureau'];
    const allData = <?= json_encode(array_map(function($r) {
        return [
            'Code Gestionnaire' => $r['CodeGestionnaire'],
            'Code Bureau'       => $r['CodeBureau'],
            'Nom Bureau'        => $r['NomBureau'],
        ];
    }, $rows), JSON_UNESCAPED_UNICODE) ?>;

    initSimpleSearch('searchInput', 'tableBody', 'countValue', allData, headers, {
        statElements: { 'statTotal': { type: 'count' } },
        exportConfig: {
            fileName: 'bureaux_export',
            sheetName: 'Bureaux',
            title: 'Liste des Bureaux',
            titleColor: [40, 80, 160],
            headerColor: [30, 60, 140],
        }
    });
</script>

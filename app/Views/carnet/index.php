<div class="container">
    <div class="header">
        <div class="logo-container">
            <img src="<?= BASE_URL ?>/assets/images/logo-round.png" alt="Algérie Poste">
            <h1><?= htmlspecialchars($pageTitle) ?></h1>
        </div>
        <p class="subtitle">Wilaya 31 - Oran</p>
    </div>

    <div class="search-container">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Rechercher une commande..." autocomplete="off">
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
                    <th><i class="fas fa-hashtag"></i>Code Bureau</th>
                    <th><i class="fas fa-building"></i>Nom Bureau</th>
                    <th><i class="fas fa-calendar-alt"></i>Date</th>
                    <th><i class="fas fa-book"></i>Nombre Carnet</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if ($totalRows > 0): ?>
                    <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><span class="badge badge-blue"><i class="fas fa-hashtag"></i><?= htmlspecialchars($row['CodeBureau']) ?></span></td>
                        <td><span class="badge badge-purple"><i class="fas fa-building"></i><?= htmlspecialchars($row['NomBureau']) ?></span></td>
                        <td><div class="date-cell"><i class="fas fa-calendar"></i><?= htmlspecialchars($row['Date']) ?></div></td>
                        <td><span class="badge badge-green"><i class="fas fa-book"></i><?= htmlspecialchars($row['NombreCarnet']) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h3>Aucune commande trouvée</h3>
                                <p>Aucune commande de carnet enregistrée pour la wilaya 31.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-icon purple"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="statCommands"><?= number_format($totalRows) ?></div>
                <div class="stat-label">Total Commandes</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon green"><i class="fas fa-book"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="statCarnets"><?= number_format($totalCarnets) ?></div>
                <div class="stat-label">Total Carnets</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2026 Algérie Poste - Tous droits réservés</p>
    </div>
</div>

<script>
    const headers = ['Code Bureau', 'Nom Bureau', 'Date', 'Nombre Carnet'];
    const allData = <?= json_encode(array_map(function($r) {
        return [
            'Code Bureau'   => $r['CodeBureau'],
            'Nom Bureau'    => $r['NomBureau'],
            'Date'          => $r['Date'],
            'Nombre Carnet' => (int)$r['NombreCarnet'],
        ];
    }, $rows), JSON_UNESCAPED_UNICODE) ?>;

    initSimpleSearch('searchInput', 'tableBody', 'countValue', allData, headers, {
        badgeColumns: {
            0: 'badge-blue',
            1: 'badge-purple',
            2: 'date',
            3: 'badge-green',
        },
        statElements: {
            'statCommands': { type: 'count' },
            'statCarnets':  { type: 'sum', column: 'Nombre Carnet' },
        },
        exportConfig: {
            fileName: 'commandes_carnets',
            sheetName: 'Commandes_Carnets',
            title: 'Commandes Carnets de Chèque',
            subtitle: 'Wilaya 31 - Oran',
            titleColor: [139, 92, 246],
            headerColor: [139, 92, 246],
        }
    });
</script>

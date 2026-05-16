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
            <input type="text" id="searchInput" placeholder="Rechercher une opération CNEP..." autocomplete="off">
        </div>
        <div class="result-count">
            <i class="fas fa-filter"></i>
            Résultats : <span class="count-value" id="countValue"><?= $stats['total_operations'] ?></span>
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
                    <th><i class="fas fa-building"></i>Bureau</th>
                    <th><i class="fas fa-tasks"></i>Nom Opération</th>
                    <th><i class="fas fa-calendar-alt"></i>Date</th>
                    <th><i class="fas fa-map-marker-alt"></i>Wilaya</th>
                    <th><i class="fas fa-arrow-down"></i>RPVU</th>
                    <th><i class="fas fa-arrow-up"></i>RIPV</th>
                    <th><i class="fas fa-coins"></i>Montant</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (count($rows) > 0): ?>
                    <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><span class="badge badge-blue"><i class="fas fa-building"></i><?= htmlspecialchars($row['Bureau']) ?></span></td>
                        <td><span class="badge badge-orange"><i class="fas fa-tasks"></i><?= htmlspecialchars($row['NomOperation']) ?></span></td>
                        <td><div class="date-cell"><i class="fas fa-calendar"></i><?= htmlspecialchars($row['Date']) ?></div></td>
                        <td><span class="badge badge-purple"><i class="fas fa-map-marker-alt"></i><?= htmlspecialchars($row['Wilaya']) ?></span></td>
                        <td><span class="badge badge-cyan"><i class="fas fa-arrow-down"></i><?= number_format((int)$row['RPVU'], 0, ',', ' ') ?></span></td>
                        <td><span class="badge badge-pink"><i class="fas fa-arrow-up"></i><?= number_format((int)$row['RIPV'], 0, ',', ' ') ?></span></td>
                        <td><span class="badge badge-green"><i class="fas fa-coins"></i><?= number_format((float)$row['Montant'], 2, ',', ' ') ?> DA</span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h3>Aucune donnée trouvée</h3>
                                <p>Aucune opération CNEP enregistrée pour la wilaya 31.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-icon pink"><i class="fas fa-chart-line"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="statOps"><?= number_format($stats['total_operations']) ?></div>
                <div class="stat-label">Total Opérations</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon green"><i class="fas fa-coins"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="statMontant"><?= number_format($stats['total_montant'], 2, ',', ' ') ?> DA</div>
                <div class="stat-label">Montant Total</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon cyan"><i class="fas fa-arrow-down"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="statRpvu"><?= number_format($stats['total_rpvu'], 0, ',', ' ') ?></div>
                <div class="stat-label">Total RPVU</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon purple"><i class="fas fa-arrow-up"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="statRipv"><?= number_format($stats['total_ripv'], 0, ',', ' ') ?></div>
                <div class="stat-label">Total RIPV</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2026 Algérie Poste - Tous droits réservés</p>
    </div>
</div>

<script>
    const headers = ['Bureau', 'Nom Opération', 'Date', 'Wilaya', 'RPVU', 'RIPV', 'Montant'];
    const allData = <?= json_encode(array_map(function($r) {
        return [
            'Bureau'        => $r['Bureau'],
            'Nom Opération' => $r['NomOperation'],
            'Date'          => $r['Date'],
            'Wilaya'        => $r['Wilaya'],
            'RPVU'          => (int)$r['RPVU'],
            'RIPV'          => (int)$r['RIPV'],
            'Montant'       => (float)$r['Montant'],
        ];
    }, $rows), JSON_UNESCAPED_UNICODE) ?>;

    initSimpleSearch('searchInput', 'tableBody', 'countValue', allData, headers, {
        badgeColumns: {
            0: 'badge-blue',
            1: 'badge-orange',
            2: 'date',
            3: 'badge-purple',
            4: 'badge-cyan',
            5: 'badge-pink',
            6: 'badge-green',
        },
        currencyColumns: [6],
        statElements: {
            'statOps':     { type: 'count' },
            'statMontant': { type: 'sum', column: 'Montant', currency: true },
            'statRpvu':    { type: 'sum', column: 'RPVU' },
            'statRipv':    { type: 'sum', column: 'RIPV' },
        },
        exportConfig: {
            fileName: 'cnep_operations',
            sheetName: 'CNEP',
            title: 'Opérations CNEP - Wilaya 31',
            subtitle: 'Oran',
            titleColor: [236, 72, 153],
            headerColor: [236, 72, 153],
        }
    });
</script>

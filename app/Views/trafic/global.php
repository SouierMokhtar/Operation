<div class="container">
    <div class="header">
        <div class="logo-container">
            <img src="<?= BASE_URL ?>/assets/images/logo-round.png" alt="Algérie Poste">
            <h1><?= htmlspecialchars($pageTitle) ?></h1>
        </div>
        <p class="subtitle">Vue agrégée du trafic par bureau</p>
    </div>

    <div class="filter-container">
        <div class="multi-select" id="bureauMultiSelect">
            <button type="button" class="multi-select-btn">
                <span id="bureauBtnText">Tous les bureaux</span>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="multi-select-dropdown" id="bureauDropdown"></div>
        </div>
        <div class="multi-select" id="operationMultiSelect">
            <button type="button" class="multi-select-btn">
                <span id="operationBtnText">Toutes les opérations</span>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="multi-select-dropdown" id="operationDropdown"></div>
        </div>
    </div>

    <div class="search-container">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Rechercher..." autocomplete="off">
        </div>
        <div class="result-count">
            <i class="fas fa-filter"></i>
            Résultats : <span class="count-value" id="countValue"><?= $stats['total_lines'] ?></span>
        </div>
        <div class="export-buttons">
            <button class="export-btn btn-excel" onclick="exportExcel()"><i class="fas fa-file-excel"></i>Excel</button>
            <button class="export-btn btn-pdf" onclick="exportPDF()"><i class="fas fa-file-pdf"></i>PDF</button>
            <button class="export-btn btn-txt" onclick="exportTXT()"><i class="fas fa-file-alt"></i>TXT</button>
        </div>
    </div>

    <div class="table-wrapper">
        <table id="traficTable">
            <thead>
                <tr>
                    <th><i class="fas fa-barcode"></i>Code Bureau</th>
                    <th><i class="fas fa-building"></i>Nom Bureau</th>
                    <th><i class="fas fa-tasks"></i>Nom Opération</th>
                    <th><i class="fas fa-sort-numeric-up"></i>Nb Opérations</th>
                    <th><i class="fas fa-coins"></i>Montant Opérations</th>
                    <th><i class="fas fa-gavel"></i>Droit</th>
                    <th><i class="fas fa-undo"></i>Remboursement</th>
                    <th><i class="fas fa-sort-numeric-up"></i>Nb Remb.</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if ($stats['total_lines'] > 0): ?>
                    <?php foreach ($rows as $row): ?>
                    <tr data-code="<?= htmlspecialchars($row['CodeBureau']) ?>"
                        data-nom="<?= htmlspecialchars($row['NomBureau']) ?>"
                        data-operation="<?= htmlspecialchars($row['NomOperation']) ?>"
                        data-nbops="<?= $row['NombreOperations'] ?>"
                        data-montant="<?= $row['MontantOperations'] ?>"
                        data-droit="<?= $row['Droit'] ?>"
                        data-remb="<?= $row['Remboursement'] ?>"
                        data-nbremb="<?= $row['NombreRemboursements'] ?>">
                        <td><span class="badge badge-blue"><i class="fas fa-barcode" style="font-size:9px;"></i><?= htmlspecialchars($row['CodeBureau']) ?></span></td>
                        <td><span class="badge badge-purple"><i class="fas fa-building" style="font-size:9px;"></i><?= htmlspecialchars($row['NomBureau']) ?></span></td>
                        <td><span class="badge badge-orange"><i class="fas fa-tasks" style="font-size:9px;"></i><?= htmlspecialchars($row['NomOperation']) ?></span></td>
                        <td><span class="badge badge-cyan"><i class="fas fa-sort-numeric-up" style="font-size:9px;"></i><?= number_format((float)$row['NombreOperations'], 0, ',', ' ') ?></span></td>
                        <td><span class="badge badge-green"><i class="fas fa-coins" style="font-size:9px;"></i><?= number_format((float)$row['MontantOperations'], 2, ',', ' ') ?> DA</span></td>
                        <td><span class="badge badge-pink"><i class="fas fa-gavel" style="font-size:9px;"></i><?= number_format((float)$row['Droit'], 2, ',', ' ') ?> DA</span></td>
                        <td><span class="badge badge-red"><i class="fas fa-undo" style="font-size:9px;"></i><?= number_format((float)$row['Remboursement'], 2, ',', ' ') ?> DA</span></td>
                        <td><span class="badge badge-cyan"><i class="fas fa-sort-numeric-up" style="font-size:9px;"></i><?= number_format((float)$row['NombreRemboursements'], 0, ',', ' ') ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h3>Aucune donnée trouvée</h3>
                                <p>Aucun trafic agrégé enregistré pour la wilaya 31.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-icon red"><i class="fas fa-globe"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="statLines"><?= number_format($stats['total_lines']) ?></div>
                <div class="stat-label">Lignes total</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon cyan"><i class="fas fa-sort-numeric-up"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="statOps"><?= number_format($stats['total_ops'], 0, ',', ' ') ?></div>
                <div class="stat-label">Total Opérations</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon blue"><i class="fas fa-coins"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="statMontant"><?= number_format($stats['total_montant'], 2, ',', ' ') ?> DA</div>
                <div class="stat-label">Montant Total</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon pink"><i class="fas fa-gavel"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="statDroit"><?= number_format($stats['total_droit'], 2, ',', ' ') ?> DA</div>
                <div class="stat-label">Total Droits</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon red"><i class="fas fa-undo"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="statRemb"><?= number_format($stats['total_remb'], 2, ',', ' ') ?> DA</div>
                <div class="stat-label">Total Remb.</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon orange"><i class="fas fa-sort-numeric-up"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="statNbRemb"><?= number_format($stats['total_nbr_remb'], 0, ',', ' ') ?></div>
                <div class="stat-label">Nb Remb. Total</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2026 Algérie Poste - Tous droits réservés</p>
    </div>
</div>

<script>
    const allRows = [];
    document.querySelectorAll('#tableBody tr').forEach(row => {
        if (row.cells.length === 8 && !row.querySelector('.empty-state')) {
            allRows.push({
                code: row.dataset.code || '',
                nom: row.dataset.nom || '',
                operation: row.dataset.operation || '',
                nbOps: parseFloat(row.dataset.nbops) || 0,
                montant: parseFloat(row.dataset.montant) || 0,
                droit: parseFloat(row.dataset.droit) || 0,
                remb: parseFloat(row.dataset.remb) || 0,
                nbRemb: parseFloat(row.dataset.nbremb) || 0
            });
        }
    });

    const uniqueOperations = [...new Set(allRows.map(r => r.operation))].sort((a, b) => a.localeCompare(b));
    const uniqueBureaus = [...new Set(allRows.map(r => r.nom))].sort((a, b) => a.localeCompare(b));

    createMultiSelect('bureauMultiSelect', uniqueBureaus, 'Tous les bureaux', 'bureauBtnText', 'bureauDropdown', performSearch);
    createMultiSelect('operationMultiSelect', uniqueOperations, 'Toutes les opérations', 'operationBtnText', 'operationDropdown', performSearch);

    const searchInput = document.getElementById('searchInput');
    const countValue = document.getElementById('countValue');
    const statLines = document.getElementById('statLines');
    const statOps = document.getElementById('statOps');
    const statMontant = document.getElementById('statMontant');
    const statDroit = document.getElementById('statDroit');
    const statRemb = document.getElementById('statRemb');
    const statNbRemb = document.getElementById('statNbRemb');
    const tableBody = document.getElementById('tableBody');

    function performSearch() {
        const searchTerm = searchInput.value.trim().toLowerCase();
        const selectedBureaus = getSelectedItems('bureauMultiSelect');
        const selectedOperations = getSelectedItems('operationMultiSelect');

        let filteredRows = allRows;
        if (selectedBureaus.length < uniqueBureaus.length) filteredRows = filteredRows.filter(r => selectedBureaus.includes(r.nom));
        if (selectedOperations.length < uniqueOperations.length) filteredRows = filteredRows.filter(r => selectedOperations.includes(r.operation));
        if (searchTerm !== '') {
            filteredRows = filteredRows.filter(r =>
                r.code.toLowerCase().includes(searchTerm) || r.nom.toLowerCase().includes(searchTerm) ||
                r.operation.toLowerCase().includes(searchTerm) || r.nbOps.toString().includes(searchTerm) ||
                r.montant.toString().includes(searchTerm) || r.droit.toString().includes(searchTerm) ||
                r.remb.toString().includes(searchTerm) || r.nbRemb.toString().includes(searchTerm)
            );
        }

        let totalOps=0, totalMontant=0, totalDroit=0, totalRemb=0, totalNbRemb=0;
        filteredRows.forEach(r => { totalOps+=r.nbOps; totalMontant+=r.montant; totalDroit+=r.droit; totalRemb+=r.remb; totalNbRemb+=r.nbRemb; });

        countValue.textContent = filteredRows.length;
        statLines.textContent = formatNumber(filteredRows.length);
        statOps.textContent = formatNumber(totalOps);
        statMontant.textContent = formatMontant(totalMontant);
        statDroit.textContent = formatMontant(totalDroit);
        statRemb.textContent = formatMontant(totalRemb);
        statNbRemb.textContent = formatNumber(totalNbRemb);

        if (filteredRows.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="8"><div class="empty-state"><i class="fas fa-search"></i><h3>Aucun résultat</h3><p>Modifiez vos filtres.</p></div></td></tr>';
            return;
        }

        let html = '';
        filteredRows.forEach(r => {
            let code = escapeHtml(r.code), nom = escapeHtml(r.nom), op = escapeHtml(r.operation),
                nbOps = formatNumber(r.nbOps), montant = formatMontant(r.montant),
                droit = formatMontant(r.droit), remb = formatMontant(r.remb), nbRemb = formatNumber(r.nbRemb);

            if (searchTerm) {
                const regex = new RegExp('(' + escapeRegex(searchTerm) + ')', 'gi');
                code = code.replace(regex, '<mark>$1</mark>');
                nom = nom.replace(regex, '<mark>$1</mark>');
                op = op.replace(regex, '<mark>$1</mark>');
                nbOps = nbOps.replace(regex, '<mark>$1</mark>');
                montant = montant.replace(regex, '<mark>$1</mark>');
                droit = droit.replace(regex, '<mark>$1</mark>');
                remb = remb.replace(regex, '<mark>$1</mark>');
                nbRemb = nbRemb.replace(regex, '<mark>$1</mark>');
            }

            html += '<tr>' +
                '<td><span class="badge badge-blue"><i class="fas fa-barcode" style="font-size:9px;"></i>' + code + '</span></td>' +
                '<td><span class="badge badge-purple"><i class="fas fa-building" style="font-size:9px;"></i>' + nom + '</span></td>' +
                '<td><span class="badge badge-orange"><i class="fas fa-tasks" style="font-size:9px;"></i>' + op + '</span></td>' +
                '<td><span class="badge badge-cyan"><i class="fas fa-sort-numeric-up" style="font-size:9px;"></i>' + nbOps + '</span></td>' +
                '<td><span class="badge badge-green"><i class="fas fa-coins" style="font-size:9px;"></i>' + montant + '</span></td>' +
                '<td><span class="badge badge-pink"><i class="fas fa-gavel" style="font-size:9px;"></i>' + droit + '</span></td>' +
                '<td><span class="badge badge-red"><i class="fas fa-undo" style="font-size:9px;"></i>' + remb + '</span></td>' +
                '<td><span class="badge badge-cyan"><i class="fas fa-sort-numeric-up" style="font-size:9px;"></i>' + nbRemb + '</span></td>' +
                '</tr>';
        });
        tableBody.innerHTML = html;
    }

    searchInput.addEventListener('input', performSearch);

    function getFilteredData() {
        const searchTerm = searchInput.value.trim().toLowerCase();
        const selectedBureaus = getSelectedItems('bureauMultiSelect');
        const selectedOperations = getSelectedItems('operationMultiSelect');
        let filtered = allRows;
        if (selectedBureaus.length < uniqueBureaus.length) filtered = filtered.filter(r => selectedBureaus.includes(r.nom));
        if (selectedOperations.length < uniqueOperations.length) filtered = filtered.filter(r => selectedOperations.includes(r.operation));
        if (searchTerm) {
            filtered = filtered.filter(r =>
                r.code.toLowerCase().includes(searchTerm) || r.nom.toLowerCase().includes(searchTerm) ||
                r.operation.toLowerCase().includes(searchTerm) || r.nbOps.toString().includes(searchTerm) ||
                r.montant.toString().includes(searchTerm) || r.droit.toString().includes(searchTerm) ||
                r.remb.toString().includes(searchTerm) || r.nbRemb.toString().includes(searchTerm)
            );
        }
        return filtered.map(r => ({
            'Code Bureau': r.code, 'Nom Bureau': r.nom, 'Nom Opération': r.operation,
            'Nb Opérations': r.nbOps, 'Montant Opérations (DA)': r.montant,
            'Droit (DA)': r.droit, 'Remboursement (DA)': r.remb, 'Nb Remboursements': r.nbRemb
        }));
    }

    function exportExcel() {
        const data = getFilteredData();
        if (!data.length) { alert('Aucune donnée à exporter.'); return; }
        const ws = XLSX.utils.json_to_sheet(data);
        ws['!cols'] = [{wch:15},{wch:25},{wch:30},{wch:15},{wch:18},{wch:15},{wch:18},{wch:15}];
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Trafic_Global');
        XLSX.writeFile(wb, 'trafic_bureau_global.xlsx');
    }

    function exportPDF() {
        const data = getFilteredData();
        if (!data.length) { alert('Aucune donnée à exporter.'); return; }
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'landscape' });
        doc.setFontSize(18); doc.setTextColor(239, 68, 68);
        doc.text('Trafic Bureau Global - Wilaya 31 (Oran)', 14, 16);
        doc.setFontSize(10); doc.setTextColor(100);
        doc.text('Exporté le : ' + new Date().toLocaleDateString('fr-FR', {day:'2-digit',month:'2-digit',year:'numeric',hour:'2-digit',minute:'2-digit'}), 14, 24);

        const totalOps = data.reduce((s,r) => s + r['Nb Opérations'], 0);
        const totalMontant = data.reduce((s,r) => s + r['Montant Opérations (DA)'], 0);
        const totalDroit = data.reduce((s,r) => s + r['Droit (DA)'], 0);
        const totalRemb = data.reduce((s,r) => s + r['Remboursement (DA)'], 0);
        const totalNbRemb = data.reduce((s,r) => s + r['Nb Remboursements'], 0);

        doc.setFontSize(9);
        doc.text('Total Opérations: ' + totalOps.toLocaleString('fr-FR') + ' | Montant Total: ' + totalMontant.toLocaleString('fr-FR') + ' DA | Total Droits: ' + totalDroit.toLocaleString('fr-FR') + ' DA', 14, 32);
        doc.text('Total Remboursements: ' + totalRemb.toLocaleString('fr-FR') + ' DA | Nb Remboursements: ' + totalNbRemb.toLocaleString('fr-FR'), 14, 38);
        doc.line(14, 42, 280, 42);

        const body = data.map(r => [r['Code Bureau'], r['Nom Bureau'], r['Nom Opération'],
            r['Nb Opérations'].toString(), r['Montant Opérations (DA)'].toLocaleString('fr-FR')+' DA',
            r['Droit (DA)'].toLocaleString('fr-FR')+' DA', r['Remboursement (DA)'].toLocaleString('fr-FR')+' DA',
            r['Nb Remboursements'].toString()]);
        doc.autoTable({
            head: [['Code Bureau','Nom Bureau','Opération','Nb Ops','Montant','Droit','Remboursement','Nb Remb']],
            body: body, startY: 47,
            styles: { font:'helvetica', fontSize:7, cellPadding:2 },
            headStyles: { fillColor:[239,68,68], textColor:255, fontStyle:'bold' },
            alternateRowStyles: { fillColor:[245,248,250] },
            margin: { left:14, right:14 },
        });

        const pageCount = doc.internal.getNumberOfPages();
        for(let i=1;i<=pageCount;i++){doc.setPage(i);doc.setFontSize(8);doc.setTextColor(150);doc.text('Page '+i+' / '+pageCount,doc.internal.pageSize.getWidth()-20,doc.internal.pageSize.getHeight()-10);}
        doc.save('trafic_bureau_global.pdf');
    }

    function exportTXT() {
        const data = getFilteredData();
        if (!data.length) { alert('Aucune donnée à exporter.'); return; }
        let content = '='.repeat(120) + '\nTRAFIC BUREAU GLOBAL - WILAYA 31 (ORAN)\n' + '='.repeat(120) + '\n';
        content += "Date d'export : " + new Date().toLocaleString('fr-FR') + '\n';
        content += 'Nombre de lignes : ' + data.length + '\n';
        content += '='.repeat(120) + '\n\n';
        content += 'Code Bureau | Nom Bureau | Nom Opération | Nb Opérations | Montant | Droit | Remboursement | Nb Remb\n';
        content += '-'.repeat(120) + '\n';
        data.forEach(r => {
            content += r['Code Bureau']+' | '+r['Nom Bureau']+' | '+r['Nom Opération']+' | '+r['Nb Opérations']+' | '+r['Montant Opérations (DA)'].toLocaleString('fr-FR')+' DA | '+r['Droit (DA)'].toLocaleString('fr-FR')+' DA | '+r['Remboursement (DA)'].toLocaleString('fr-FR')+' DA | '+r['Nb Remboursements']+'\n';
        });
        content += '\n'+'='.repeat(120)+'\nFin du rapport\n';
        const blob = new Blob([content], {type:'text/plain;charset=utf-8'});
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'trafic_bureau_global.txt';
        document.body.appendChild(link); link.click(); document.body.removeChild(link);
    }

    countValue.textContent = allRows.length;
    performSearch();
</script>

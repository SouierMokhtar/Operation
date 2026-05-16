/**
 * Shared search utilities for Algérie Poste MVC
 */

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

function escapeRegex(str) {
    return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

function formatNumber(num, decimals) {
    decimals = decimals || 0;
    return num.toLocaleString('fr-FR', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
}

function formatMontant(montant) {
    return montant.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' DA';
}

/**
 * Initialize simple search + export on a table.
 * @param {string} searchInputId
 * @param {string} tableBodyId
 * @param {string} countValueId
 * @param {Array}  allData - array of row objects
 * @param {Array}  headers - column header strings
 * @param {Object} options - { badgeColumns, currencyColumns, statElements, exportConfig }
 */
function initSimpleSearch(searchInputId, tableBodyId, countValueId, allData, headers, options) {
    options = options || {};
    var searchInput = document.getElementById(searchInputId);
    var tableBody   = document.getElementById(tableBodyId);
    var countValue  = document.getElementById(countValueId);

    var badgeColumns    = options.badgeColumns || {};
    var currencyColumns = options.currencyColumns || [];
    var statElements    = options.statElements || {};
    var exportConfig    = options.exportConfig || {};

    var badgeIcons = {
        'badge-blue':   '<i class="fas fa-hashtag" style="font-size:10px;"></i>',
        'badge-purple': '<i class="fas fa-building" style="font-size:10px;"></i>',
        'badge-green':  '<i class="fas fa-book" style="font-size:10px;"></i>',
        'badge-orange': '<i class="fas fa-tasks" style="font-size:10px;"></i>',
        'badge-cyan':   '<i class="fas fa-sort-numeric-up" style="font-size:10px;"></i>',
        'badge-pink':   '<i class="fas fa-arrow-up" style="font-size:10px;"></i>',
        'badge-red':    '<i class="fas fa-undo" style="font-size:10px;"></i>',
    };

    function getFilteredData() {
        var searchTerm = searchInput.value.trim().toLowerCase();
        if (searchTerm === '') return allData.slice();
        return allData.filter(function(row) {
            var values = Object.values(row);
            for (var i = 0; i < values.length; i++) {
                if (String(values[i]).toLowerCase().indexOf(searchTerm) !== -1) return true;
            }
            return false;
        });
    }

    function performSearch() {
        var filteredData = getFilteredData();
        var searchTerm   = searchInput.value.trim();

        countValue.textContent = filteredData.length;

        // Update stat elements
        Object.keys(statElements).forEach(function(elId) {
            var el = document.getElementById(elId);
            if (!el) return;
            var cfg = statElements[elId];
            if (cfg.type === 'count') {
                el.textContent = filteredData.length.toLocaleString();
            } else if (cfg.type === 'sum') {
                var total = 0;
                filteredData.forEach(function(row) { total += (parseFloat(row[cfg.column]) || 0); });
                el.textContent = cfg.currency ? formatMontant(total) : total.toLocaleString();
            }
        });

        if (filteredData.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="' + headers.length + '" class="no-results"><span>&#128269;</span> Aucun résultat trouvé pour "' + escapeHtml(searchTerm) + '"</td></tr>';
            return;
        }

        var html = '';
        filteredData.forEach(function(row) {
            html += '<tr>';
            var keys = Object.keys(row);
            keys.forEach(function(key, idx) {
                var val = row[key];
                var display = escapeHtml(String(val));

                if (currencyColumns.indexOf(idx) !== -1) {
                    display = formatMontant(parseFloat(val) || 0);
                }

                if (searchTerm !== '') {
                    var regex = new RegExp('(' + escapeRegex(searchTerm) + ')', 'gi');
                    display = display.replace(regex, '<mark>$1</mark>');
                }

                var badgeClass = badgeColumns[idx];
                if (badgeClass === 'date') {
                    html += '<td><div class="date-cell"><i class="fas fa-calendar"></i>' + display + '</div></td>';
                } else if (badgeClass) {
                    var icon = badgeIcons[badgeClass] || '';
                    html += '<td><span class="badge ' + badgeClass + '">' + icon + display + '</span></td>';
                } else {
                    html += '<td>' + display + '</td>';
                }
            });
            html += '</tr>';
        });
        tableBody.innerHTML = html;
    }

    searchInput.addEventListener('input', performSearch);

    // Expose export functions globally
    window.exportExcel = function() {
        var data = getFilteredData();
        if (!data.length) { alert('Aucune donnée à exporter.'); return; }
        var ws = XLSX.utils.json_to_sheet(data);
        var colWidths = headers.map(function() { return { wch: 20 }; });
        ws['!cols'] = colWidths;
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, exportConfig.sheetName || 'Export');
        XLSX.writeFile(wb, (exportConfig.fileName || 'export') + '.xlsx');
    };

    window.exportPDF = function() {
        var data = getFilteredData();
        if (!data.length) { alert('Aucune donnée à exporter.'); return; }
        var jsPDFConstructor = window.jspdf.jsPDF;
        var doc = new jsPDFConstructor({ orientation: 'landscape' });

        var titleColor = exportConfig.titleColor || [40, 80, 160];
        var headerColor = exportConfig.headerColor || [30, 60, 140];

        doc.setFontSize(18);
        doc.setTextColor(titleColor[0], titleColor[1], titleColor[2]);
        doc.text(exportConfig.title || 'Export', 14, 16);

        doc.setFontSize(10);
        doc.setTextColor(100, 100, 100);
        var now = new Date().toLocaleDateString('fr-FR', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' });
        doc.text('Exporté le : ' + now, 14, 24);

        var startY = 34;
        if (exportConfig.subtitle) {
            doc.text(exportConfig.subtitle, 14, 32);
            startY = 40;
        }

        var searchTerm = searchInput.value.trim();
        if (searchTerm !== '') {
            doc.text('Recherche : "' + searchTerm + '"', 14, startY);
            startY += 8;
        }

        doc.line(14, startY - 2, 280, startY - 2);

        var body = data.map(function(r) { return Object.values(r).map(function(v) { return String(v); }); });
        doc.autoTable({
            head: [headers],
            body: body,
            startY: startY + 3,
            styles: { font: 'helvetica', fontSize: 9, cellPadding: 4 },
            headStyles: { fillColor: headerColor, textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [245, 248, 250] },
            margin: { left: 14, right: 14 },
        });

        var pageCount = doc.internal.getNumberOfPages();
        for (var i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(8);
            doc.setTextColor(150, 150, 150);
            doc.text('Page ' + i + ' / ' + pageCount, doc.internal.pageSize.getWidth() - 20, doc.internal.pageSize.getHeight() - 10);
        }

        doc.save((exportConfig.fileName || 'export') + '.pdf');
    };

    window.exportTXT = function() {
        var data = getFilteredData();
        if (!data.length) { alert('Aucune donnée à exporter.'); return; }

        var content = '='.repeat(80) + '\n';
        content += (exportConfig.title || 'EXPORT').toUpperCase() + '\n';
        content += '='.repeat(80) + '\n';
        content += "Date d'export : " + new Date().toLocaleString('fr-FR') + '\n';
        content += "Nombre d'enregistrements : " + data.length + '\n';

        var searchTerm = searchInput.value.trim();
        if (searchTerm !== '') {
            content += 'Recherche effectuée : ' + searchTerm + '\n';
        }

        content += '='.repeat(80) + '\n\n';
        content += headers.join(' | ') + '\n';
        content += '-'.repeat(80) + '\n';

        data.forEach(function(row) {
            content += Object.values(row).join(' | ') + '\n';
        });

        content += '\n' + '='.repeat(80) + '\n';
        content += 'Fin du rapport\n';

        var blob = new Blob([content], { type: 'text/plain;charset=utf-8' });
        var link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = (exportConfig.fileName || 'export') + '.txt';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };
}

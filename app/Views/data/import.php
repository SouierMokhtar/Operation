<div class="container">
    <div class="header">
        <div class="logo-container">
            <img src="<?= BASE_URL ?>/assets/images/logo-round.png" alt="Algérie Poste">
            <h1><?= htmlspecialchars($pageTitle) ?></h1>
        </div>
        <p class="subtitle">Charger les fichiers de données dans la base</p>
    </div>

    <div style="max-width:600px;margin:40px auto;text-align:center;">
        <div style="padding:40px;background:rgba(15,23,42,0.8);border:1px solid rgba(59,130,246,0.15);border-radius:16px;">
            <i class="fas fa-database" style="font-size:48px;color:#3b82f6;margin-bottom:20px;"></i>
            <h3 style="color:#f1f5f9;margin-bottom:15px;">Importation des Données</h3>
            <p style="color:#94a3b8;margin-bottom:25px;">
                Placez vos fichiers de données dans le dossier <code style="background:rgba(59,130,246,0.15);padding:3px 8px;border-radius:4px;color:#93c5fd;">/data</code>
                puis lancez l'importation.
            </p>
            <button id="importBtn" onclick="startImport()" class="export-btn btn-excel" style="padding:14px 30px;font-size:14px;">
                <i class="fas fa-upload"></i> Lancer l'importation
            </button>
            <div id="importResult" style="margin-top:20px;display:none;"></div>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2026 Algérie Poste - Tous droits réservés</p>
    </div>
</div>

<script>
    function startImport() {
        const btn = document.getElementById('importBtn');
        const result = document.getElementById('importResult');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Importation en cours...';
        result.style.display = 'none';

        fetch('<?= BASE_URL ?>/data/process', { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                result.style.display = 'block';
                if (data.success) {
                    result.innerHTML = '<div style="color:#34d399;"><i class="fas fa-check-circle"></i> ' + data.message + '</div>';
                } else {
                    result.innerHTML = '<div style="color:#f87171;"><i class="fas fa-exclamation-circle"></i> ' + (data.error || 'Erreur inconnue') + '</div>';
                }
            })
            .catch(err => {
                result.style.display = 'block';
                result.innerHTML = '<div style="color:#f87171;"><i class="fas fa-exclamation-circle"></i> Erreur de connexion.</div>';
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-upload"></i> Lancer l\'importation';
            });
    }
</script>

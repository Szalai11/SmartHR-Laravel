<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Fiche de prise de fonction</title>
    <style>
        @page { margin: 1.5cm; size: A4; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; line-height: 1.4; color: #333; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #2563EB; padding-bottom: 15px; margin-bottom: 20px; }
        .logo { font-size: 20px; font-weight: bold; color: #2563EB; }
        .subtitle { font-size: 11px; color: #666; }
        .title { text-align: center; font-size: 18px; font-weight: bold; margin: 20px 0; text-transform: uppercase; color: #1F2937; }
        .content { margin: 15px 0; }
        .info-row { margin: 8px 0; }
        .info-label { font-weight: bold; width: 180px; display: inline-block; }
        .info-value { display: inline-block; }
        .signature { margin-top: 35px; display: flex; justify-content: space-between; }
        .signature-box { text-align: center; width: 45%; }
        .footer { text-align: center; font-size: 10px; color: #999; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🏢 SmartHR</div>
        <div class="subtitle">Fiche de prise de fonction</div>
    </div>

    <div class="title">Fiche de prise de fonction</div>

    <div class="content">
        <div class="info-row"><span class="info-label">Matricule :</span><span class="info-value">{{ $employe->matricule }}</span></div>
        <div class="info-row"><span class="info-label">Nom et prénom :</span><span class="info-value">{{ $employe->prenom }} {{ $employe->nom }}</span></div>
        <div class="info-row"><span class="info-label">Poste :</span><span class="info-value">{{ $employe->poste_actuel }}</span></div>
        <div class="info-row"><span class="info-label">Service :</span><span class="info-value">{{ $employe->service }}</span></div>
        <div class="info-row"><span class="info-label">Date de prise de fonction :</span><span class="info-value">{{ \Carbon\Carbon::parse($employe->date_embauche)->format('d/m/Y') }}</span></div>
        @if($contrat)
        <div class="info-row"><span class="info-label">Type de contrat :</span><span class="info-value">{{ $contrat->type }}</span></div>
        @endif
        <div class="info-row"><span class="info-label">Solde de congés initial :</span><span class="info-value">{{ $employe->solde_conge }} jours</span></div>
    </div>

    <p style="margin-top: 10px;">Le présent document atteste la prise de fonction officielle du salarié mentionné ci-dessus, à la date indiquée, au sein de SmartHR.</p>

    <div class="signature">
        <div class="signature-box"><p>Le Responsable RH</p><br><br><p>_______________________</p></div>
        <div class="signature-box"><p>L'employé(e)</p><br><br><p>_______________________</p></div>
    </div>

    <div class="footer">
        Document généré le {{ $date_generation }} | SmartHR - Gestion RH
    </div>
</body>
</html>
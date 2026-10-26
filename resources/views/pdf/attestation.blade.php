<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Attestation de travail</title>
    <style>
        @page {
            margin: 1.5cm;
            size: A4;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            line-height: 1.4;
            color: #333;
            font-size: 12px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2563EB;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 20px;
            font-weight: bold;
            color: #2563EB;
        }
        .subtitle {
            font-size: 11px;
            color: #666;
        }
        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
            color: #1F2937;
        }
        .content {
            margin: 15px 0;
        }
        .info-row {
            margin: 8px 0;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
            display: inline-block;
        }
        .info-value {
            display: inline-block;
        }
        .signature {
            margin-top: 35px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 30px;
            padding-top: 8px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-cdi { background: #10B981; color: white; }
        .badge-cdd { background: #3B82F6; color: white; }
        .badge-stage { background: #8B5CF6; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🏢 SmartHR</div>
        <div class="subtitle">Solutions RH innovantes</div>
        <div class="subtitle">Lomé, Togo | Tél: +228 99951693</div>
    </div>

    <div class="title">
        ATTESTATION DE TRAVAIL
    </div>

    <div class="content">
        <p><strong>SmartHR</strong> atteste par la présente que :</p>

        <div class="info-row">
            <span class="info-label">Nom et prénom :</span>
            <span class="info-value">{{ $employe->prenom }} {{ $employe->nom }}</span>
        </div>

        <div class="info-row">
            <span class="info-label">Matricule :</span>
            <span class="info-value">{{ $employe->matricule }}</span>
        </div>

        <div class="info-row">
            <span class="info-label">Poste occupé :</span>
            <span class="info-value">{{ $employe->poste_actuel }}</span>
        </div>

        <div class="info-row">
            <span class="info-label">Service :</span>
            <span class="info-value">{{ $employe->service }}</span>
        </div>

        <div class="info-row">
            <span class="info-label">Date d'embauche :</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($employe->date_embauche)->format('d/m/Y') }}</span>
        </div>

        @if($contrat)
        <div class="info-row">
            <span class="info-label">Type de contrat :</span>
            <span class="info-value">
                <span class="badge badge-{{ strtolower($contrat->type) }}">{{ $contrat->type }}</span>
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">Poste sous contrat :</span>
            <span class="info-value">{{ $contrat->poste }}</span>
        </div>

        <div class="info-row">
            <span class="info-label">Salaire :</span>
            <span class="info-value">{{ number_format($contrat->salaire_base, 0, ',', ' ') }} FCFA</span>
        </div>
        @else
        <div class="info-row">
            <span class="info-label">Type de contrat :</span>
            <span class="info-value">Non défini</span>
        </div>
        @endif

        <p style="margin-top: 20px;">
            La présente attestation est délivrée à l'intéressé(e) pour servir et valoir ce que de droit.
        </p>

        <p>
            Fait à Lomé, le {{ $date_generation }}
        </p>
    </div>

    <div class="signature">
        <div class="signature-box">
            <div class="signature-line">Signature</div>
            <div style="font-size: 10px; margin-top: 5px;">SmartHR</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Cachet de l'entreprise</div>
        </div>
    </div>

    <div class="footer">
        Document généré le {{ $date_generation }} | SmartHR - Gestion RH
    </div>
</body>
</html>
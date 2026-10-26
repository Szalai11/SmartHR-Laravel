<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bon de congé</title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563EB;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563EB;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
        }
        .title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin: 30px 0;
            text-transform: uppercase;
            color: #1F2937;
        }
        .info-block {
            margin: 15px 0;
        }
        .info-label {
            font-weight: bold;
            width: 180px;
            display: inline-block;
        }
        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 250px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 10px;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #10B981;
            color: white;
        }
        .badge-warning {
            background-color: #F59E0B;
            color: white;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🏢 SmartHR</div>
        <div class="subtitle">Solutions RH innovantes</div>
        <div class="subtitle">Lomé, Togo | Tél: +228 99951693</div>
    </div>

    <div class="title">
        BON DE CONGÉ
    </div>

    <div class="info-block">
        <span class="info-label">N° Matricule :</span>
        <span class="info-value">{{ $employe->matricule }}</span>
    </div>

    <div class="info-block">
        <span class="info-label">Nom et prénom :</span>
        <span class="info-value">{{ $employe->prenom }} {{ $employe->nom }}</span>
    </div>

    <div class="info-block">
        <span class="info-label">Poste :</span>
        <span class="info-value">{{ $employe->poste_actuel }}</span>
    </div>

    <div class="info-block">
        <span class="info-label">Service :</span>
        <span class="info-value">{{ $employe->service }}</span>
    </div>

    <div class="info-block">
        <span class="info-label">Type de congé :</span>
        <span class="info-value">
            @if($conge->type == 'congé')
                <span class="badge badge-success">CONGÉ PAYÉ</span>
            @else
                <span class="badge badge-warning">PERMISSION</span>
            @endif
        </span>
    </div>

    <div class="info-block">
        <span class="info-label">Période :</span>
        <span class="info-value">
            Du {{ \Carbon\Carbon::parse($conge->date_debut)->format('d/m/Y') }} 
            au {{ \Carbon\Carbon::parse($conge->date_fin)->format('d/m/Y') }}
        </span>
    </div>

    <div class="info-block">
        <span class="info-label">Nombre de jours :</span>
        <span class="info-value"><strong>{{ $conge->nombre_jours }} jours ouvrables</strong></span>
    </div>

    <div class="info-block">
        <span class="info-label">Motif :</span>
        <span class="info-value">{{ $conge->motif ?? 'Non précisé' }}</span>
    </div>

    <div class="signature">
        <div class="signature-box">
            <div class="signature-line">Signature du Chef de service</div>
            @if($conge->statut === 'valide_chef' || $conge->statut === 'valide_rh')
                <div style="font-size: 11px; margin-top: 5px;">Validé le {{ $conge->updated_at->format('d/m/Y') }}</div>
            @endif
        </div>
        <div class="signature-box">
            <div class="signature-line">Signature RH / Direction</div>
            @if($conge->statut === 'valide_rh')
                <div style="font-size: 11px; margin-top: 5px;">{{ $validateur ? 'Validé par ' . $validateur->name : 'Validé' }}</div>
                <div style="font-size: 11px;">le {{ $conge->updated_at->format('d/m/Y') }}</div>
            @endif
        </div>
    </div>

    <div class="footer">
        Document généré le {{ $date_generation }} | SmartHR - Gestion RH
    </div>
</body>
</html>
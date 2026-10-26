<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mise à jour de votre candidature</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2563EB; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; background: #f9fafb; border: 1px solid #e5e7eb; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #6b7280; }
        .badge { 
            display: inline-block; 
            padding: 8px 16px; 
            border-radius: 20px; 
            font-weight: bold;
            margin: 15px 0;
        }
        .badge-valid { background: #dcfce7; color: #166534; }
        .badge-attente { background: #fef3c7; color: #92400e; }
        .badge-refus { background: #fee2e2; color: #991b1b; }
        .badge-entretien { background: #e0e7ff; color: #3730a3; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🏢 SmartHR</h2>
            <p>Mise à jour de votre candidature</p>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $candidature->candidat->prenom }} {{ $candidature->candidat->nom }}</strong>,</p>
            
            <p>Le statut de votre candidature pour le poste de <strong>{{ $candidature->offre->titre }}</strong> a été mis à jour :</p>
            
            @php
                $badgeClass = match($candidature->statut) {
                    'valide' => 'badge-valid',
                    'refuse' => 'badge-refus',
                    'entretien' => 'badge-entretien',
                    default => 'badge-attente'
                };
                $statutText = match($candidature->statut) {
                    'valide' => '✅ Félicitations ! Retenu(e)',
                    'refuse' => '❌ Non retenu(e)',
                    'entretien' => '📅 Convoqué(e) en entretien',
                    'preselectionne' => '⭐ Présélectionné(e)',
                    'en_attente' => '⏳ En cours d\'examen',
                    default => ucfirst($candidature->statut)
                };
            @endphp
            
            <div class="badge {{ $badgeClass }}" style="text-align: center;">
                {{ $statutText }}
            </div>
            
            @if($candidature->statut == 'entretien')
            <p>Vous serez contacté(e) prochainement pour fixer la date de l'entretien.</p>
            @elseif($candidature->statut == 'valide')
            <p>Félicitations ! Un responsable RH vous contactera pour la suite du processus.</p>
            @elseif($candidature->statut == 'refuse')
            <p>Nous vous remercions pour votre candidature et vous souhaitons une bonne continuation.</p>
            @endif
            
            <p style="margin-top: 30px; text-align: center;">
                <a href="{{ url('/') }}" class="btn" style="background:#2563EB; color:white; padding:10px 20px; text-decoration:none; border-radius:8px;">📌 Voir le détail</a>
            </p>
        </div>
        <div class="footer">
            <p>SmartHR - Solutions RH innovantes</p>
        </div>
    </div>
</body>
</html>
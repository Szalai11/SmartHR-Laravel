<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nouvelle candidature reçue</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2563EB; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; background: #f9fafb; border: 1px solid #e5e7eb; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #6b7280; }
        .candidat-info { background: white; padding: 15px; border-radius: 8px; margin: 15px 0; border: 1px solid #e5e7eb; }
        .btn { background: #2563EB; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🏢 SmartHR</h2>
            <p>Nouvelle candidature reçue</p>
        </div>
        <div class="content">
            <p>Bonjour <strong>Équipe RH</strong>,</p>
            
            <p>Une nouvelle candidature a été déposée pour le poste de :</p>
            <div style="font-size: 18px; color: #2563EB; font-weight: bold; margin: 10px 0;">
                {{ $candidature->offre->titre }}
            </div>
            
            <div class="candidat-info">
                <p><strong>👤 Candidat :</strong> {{ $candidature->candidat->prenom }} {{ $candidature->candidat->nom }}</p>
                <p><strong>📧 Email :</strong> {{ $candidature->candidat->email }}</p>
                <p><strong>📅 Date de candidature :</strong> {{ $candidature->created_at->format('d/m/Y H:i') }}</p>
            </div>
            
            <p style="margin-top: 20px; text-align: center;">
                <a href="{{ url('/admin/candidatures/' . $candidature->id) }}" class="btn">📊 Voir le détail de la candidature</a>
            </p>
        </div>
        <div class="footer">
            <p>SmartHR - Administration RH</p>
            <p style="font-size: 11px;">Cet email est un message automatique. Connectez-vous pour traiter cette candidature.</p>
        </div>
    </div>
</body>
</html>
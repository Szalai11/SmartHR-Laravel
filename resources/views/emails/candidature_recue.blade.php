<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmation de candidature</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2563EB; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; background: #f9fafb; border: 1px solid #e5e7eb; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #6b7280; }
        .btn { background: #2563EB; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; display: inline-block; }
        .offre-title { font-size: 18px; color: #2563EB; font-weight: bold; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🏢 SmartHR</h2>
            <p>Confirmation de candidature</p>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $candidature->candidat->prenom }} {{ $candidature->candidat->nom }}</strong>,</p>
            
            <p>Nous vous confirmons la bonne réception de votre candidature pour le poste de :</p>
            <div class="offre-title">📋 {{ $candidature->offre->titre }}</div>
            
            <p>Nous vous remercions de l'intérêt que vous portez à notre entreprise.</p>
            
            <p>Notre équipe RH examinera votre dossier et vous tiendra informé(e) de l'évolution de votre candidature.</p>
            
            <p style="margin-top: 30px; text-align: center;">
                <a href="{{ url('/') }}" class="btn">📌 Suivre ma candidature</a>
            </p>
        </div>
        <div class="footer">
            <p>SmartHR - Solutions RH innovantes</p>
            <p>🌐 Lomé, Togo | 📧 emileanalene03@gmail.com</p>
            <p style="font-size: 11px;">Cet email est un message automatique, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>
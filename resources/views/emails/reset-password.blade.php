<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe — L'Arène des Légendes</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #0f0f1a;
            color: #e2e8f0;
        }
        .wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #0f0f1a;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #1e1b4b 0%, #4c1d95 50%, #1e1b4b 100%);
            padding: 40px 32px 32px;
            text-align: center;
            border-bottom: 2px solid #7c3aed;
        }
        .header-logo {
            font-size: 48px;
            margin-bottom: 12px;
        }
        .header-title {
            font-size: 22px;
            font-weight: 800;
            color: #fbbf24;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-shadow: 0 0 20px rgba(251, 191, 36, 0.5);
        }
        .header-subtitle {
            font-size: 13px;
            color: #a78bfa;
            margin-top: 4px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* Divider */
        .divider {
            height: 3px;
            background: linear-gradient(90deg, transparent, #7c3aed, #fbbf24, #7c3aed, transparent);
        }

        /* Body */
        .body {
            background-color: #111827;
            padding: 40px 32px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #f3f4f6;
            margin-bottom: 16px;
        }
        .greeting span {
            color: #fbbf24;
        }
        .message {
            font-size: 15px;
            color: #9ca3af;
            line-height: 1.7;
            margin-bottom: 32px;
        }

        /* CTA Button */
        .btn-wrapper {
            text-align: center;
            margin: 32px 0;
        }
        .btn {
            display: inline-block;
            padding: 16px 40px;
            background: linear-gradient(135deg, #7c3aed, #4f46e5);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0.5px;
            border: 2px solid rgba(196, 181, 253, 0.3);
            box-shadow: 0 4px 20px rgba(124, 58, 237, 0.5);
        }

        /* Info box */
        .info-box {
            background: rgba(124, 58, 237, 0.1);
            border: 1px solid rgba(124, 58, 237, 0.3);
            border-radius: 8px;
            padding: 16px 20px;
            margin: 24px 0;
            font-size: 13px;
            color: #9ca3af;
            line-height: 1.6;
        }
        .info-box strong {
            color: #a78bfa;
        }

        /* URL fallback */
        .url-fallback {
            margin-top: 20px;
            font-size: 12px;
            color: #6b7280;
            word-break: break-all;
            line-height: 1.5;
        }
        .url-fallback a {
            color: #7c3aed;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1e1b4b 0%, #0f0f1a 100%);
            padding: 24px 32px;
            text-align: center;
            border-top: 1px solid rgba(124, 58, 237, 0.2);
        }
        .footer-logo {
            font-size: 24px;
            margin-bottom: 8px;
        }
        .footer-name {
            font-size: 14px;
            font-weight: 700;
            color: #fbbf24;
            margin-bottom: 4px;
        }
        .footer-text {
            font-size: 11px;
            color: #4b5563;
            line-height: 1.6;
        }
        .footer-text a {
            color: #6b7280;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="wrapper">

    <!-- Header -->
    <div class="header">
        <div class="header-logo">⚔️</div>
        <div class="header-title">L'Arène des Légendes</div>
        <div class="header-subtitle">Sanctuaire des Chevaliers</div>
    </div>

    <div class="divider"></div>

    <!-- Body -->
    <div class="body">

        <p class="greeting">
            Salut <span>{{ $user->name }}</span> !
        </p>

        <p class="message">
            Tu as demandé à réinitialiser le mot de passe de ton compte sur <strong style="color:#a78bfa">L'Arène des Légendes</strong>.<br><br>
            Clique sur le bouton ci-dessous pour choisir un nouveau mot de passe et reprendre le combat !
        </p>

        <div class="btn-wrapper">
            <a href="{{ $url }}" class="btn">
                🔑 Réinitialiser mon mot de passe
            </a>
        </div>

        <div class="info-box">
            <strong>⏳ Ce lien expire dans 60 minutes.</strong><br>
            Si tu n'es pas à l'origine de cette demande, ignore cet email — ton mot de passe restera inchangé.
        </div>

        <div class="url-fallback">
            Si le bouton ne fonctionne pas, copie ce lien dans ton navigateur :<br>
            <a href="{{ $url }}">{{ $url }}</a>
        </div>

    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-logo">🏛️</div>
        <div class="footer-name">L'Arène des Légendes</div>
        <div class="footer-text">
            Cet email a été envoyé automatiquement, merci de ne pas y répondre.<br>
            &copy; {{ date('Y') }} L'Arène des Légendes — Tous droits réservés.
        </div>
    </div>

</div>
</body>
</html>

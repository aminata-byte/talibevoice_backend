<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement {{ $provider === 'wave' ? 'Wave' : 'Orange Money' }} — Simulation</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .card {
            background: white;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            text-align: center;
        }
        .badge {
            display: inline-block;
            background: #fef3c7;
            color: #92400e;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 1rem;
            letter-spacing: 0.05em;
        }
        .logo {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: {{ $provider === 'wave' ? '#00A3E0' : '#FF7900' }};
        }
        .amount {
            font-size: 2rem;
            font-weight: 800;
            color: #1a202c;
            margin: 1rem 0;
        }
        .reference {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 1.5rem;
        }
        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn-pay {
            background: {{ $provider === 'wave' ? '#00A3E0' : '#FF7900' }};
            color: white;
        }
        .btn-cancel {
            background: #f1f5f9;
            color: #64748b;
        }
        .notice {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 1.5rem;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="card">
        <span class="badge">MODE SIMULATION — DÉMO</span>
        <div class="logo">{{ $provider === 'wave' ? 'Wave' : 'Orange Money' }}</div>
        <div class="amount">{{ number_format((float) $amount, 0, ',', ' ') }} FCFA</div>
        <div class="reference">Référence : {{ $reference }}</div>

        <form method="POST" action="{{ route('paiement.simuler.confirmer') }}">
            @csrf
            <input type="hidden" name="reference" value="{{ $reference }}">
            <input type="hidden" name="success_url" value="{{ $successUrl }}">
            <input type="hidden" name="error_url" value="{{ $errorUrl }}">
            <input type="hidden" name="action" value="payer">
            <button type="submit" class="btn-pay">Confirmer le paiement</button>
        </form>

        <form method="POST" action="{{ route('paiement.simuler.confirmer') }}">
            @csrf
            <input type="hidden" name="reference" value="{{ $reference }}">
            <input type="hidden" name="success_url" value="{{ $successUrl }}">
            <input type="hidden" name="error_url" value="{{ $errorUrl }}">
            <input type="hidden" name="action" value="annuler">
            <button type="submit" class="btn-cancel">Annuler</button>
        </form>

        <p class="notice">
            Aucune clé API {{ $provider === 'wave' ? 'Wave' : 'Orange Money' }} n'est configurée
            sur ce serveur — ceci est une page de simulation à des fins de démonstration.
            Aucun argent réel n'est transféré.
        </p>
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tus fotos</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #007bff;">¡Gracias por tu compra!</h1>
        
        <p>Hola,</p>
        
        <p>Tu pago se ha procesado correctamente. Ya puedes descargar tus fotos:</p>
        
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Detalles de tu compra:</strong><br>
            📧 Email: {{ $purchase->email }}<br>
            📸 Fotos: {{ count($purchase->media_ids) }}<br>
            💰 Total: {{ number_format($purchase->amount, 2) }} €<br>
            ⏰ Válido hasta: {{ $purchase->expires_at->format('d/m/Y H:i') }}
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('download.show', $purchase->download_token) }}" 
               style="background: #007bff; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Descargar mis Fotos
            </a>
        </div>
        
        <p><small>Este enlace es válido durante 72 horas. Si tienes algún problema, contáctanos.</small></p>
        
        <hr style="margin: 30px 0;">
        <p style="color: #666; font-size: 14px;">
            Saludos,<br>
            El equipo de BuscaTusFotos.com
        </p>
    </div>
</body>
</html>
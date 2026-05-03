<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tus fotos — BuscaTusFotos</title>
</head>
<body style="margin:0; padding:0; background-color:#fafafa; font-family: Helvetica, Arial, sans-serif; color:#0A0A0A;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#fafafa; padding:40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; background-color:#ffffff; border:1px solid #e4e4e7;">

                    <tr>
                        <td style="padding:40px 40px 0 40px;">
                            <p style="margin:0 0 8px 0; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#71717a;">Pago confirmado</p>
                            <h1 style="margin:0; font-family: Georgia, 'Times New Roman', serif; font-weight:normal; font-size:32px; line-height:1.1; color:#0A0A0A;">
                                ¡Gracias por tu compra!
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:24px 40px 0 40px;">
                            <p style="margin:0; font-size:15px; line-height:1.6; color:#3f3f46;">
                                Tu pago se ha procesado correctamente. Ya puedes descargar tus fotos en alta calidad y sin marca de agua.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px 40px 0 40px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e4e4e7;">
                                <tr>
                                    <td style="padding:16px 20px; border-bottom:1px solid #e4e4e7;">
                                        <p style="margin:0 0 4px 0; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#71717a;">Email</p>
                                        <p style="margin:0; font-size:14px; color:#0A0A0A;">{{ $purchase->email }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 20px; border-bottom:1px solid #e4e4e7;">
                                        <p style="margin:0 0 4px 0; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#71717a;">Fotos</p>
                                        <p style="margin:0; font-size:14px; color:#0A0A0A;">{{ count($purchase->media_ids) }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 20px; border-bottom:1px solid #e4e4e7;">
                                        <p style="margin:0 0 4px 0; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#71717a;">Total</p>
                                        <p style="margin:0; font-family: Georgia, serif; font-size:18px; color:#0A0A0A;">{{ number_format($purchase->amount, 2) }} €</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <p style="margin:0 0 4px 0; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#71717a;">Válido hasta</p>
                                        <p style="margin:0; font-size:14px; color:#0A0A0A;">{{ $purchase->expires_at->format('d/m/Y H:i') }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:36px 40px 0 40px;">
                            <a href="{{ route('download.show', $purchase->download_token) }}"
                               style="display:inline-block; background-color:#FF6B47; color:#ffffff; padding:16px 36px; text-decoration:none; font-size:13px; font-weight:500; letter-spacing:0.08em; text-transform:uppercase; border-radius:2px;">
                                Descargar mis fotos
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px 40px 40px 40px;">
                            <p style="margin:0; font-size:12px; color:#71717a; line-height:1.6;">
                                Este enlace es válido durante 72 horas. Si tienes algún problema, responde a este email.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:24px 40px; border-top:1px solid #e4e4e7; background-color:#fafafa;">
                            <p style="margin:0; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#71717a;">
                                BuscaTusFotos · Fotografía de surf
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

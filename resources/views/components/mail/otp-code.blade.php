<!-- OTP Code Component -->
<table width="100%" cellpadding="0" cellspacing="0" style="margin: 32px 0;">
  <tr>
    <td align="center">
      <table cellpadding="0" cellspacing="0" style="background: #f0f9ff; border: 2px solid #0080ff; border-radius: 12px; max-width: 300px;">
        <tr>
          <td style="padding: 32px 24px; text-align: center;">
            <div style="color: #1e40af; font-size: 14px; font-weight: bold; margin-bottom: 8px; letter-spacing: 1px; text-transform: uppercase;">
              Código de Verificación
            </div>
            <div style="color: #003468; font-size: 36px; font-weight: bold; letter-spacing: 8px; margin-bottom: 12px; font-family: 'Courier New', monospace;">
              {{ $code }}
            </div>
            <div style="color: #6b7280; font-size: 13px; margin: 0;">
              @if(isset($expiresIn))
                Expira en {{ $expiresIn }}
              @else
                Válido por tiempo limitado
              @endif
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<!-- Security Notice -->
<table width="100%" cellpadding="0" cellspacing="0" style="margin: 16px 0;">
  <tr>
    <td style="text-align: center; padding: 0 20px;">
      <div style="color: #dc2626; font-size: 13px; font-weight: bold; margin-bottom: 8px;">
        🔒 Importante
      </div>
      <div style="color: #6b7280; font-size: 13px; line-height: 1.5;">
        No compartas este código con nadie. Solo úsalo una vez y dentro del tiempo límite.
      </div>
    </td>
  </tr>
</table>

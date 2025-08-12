<table width="100%" cellpadding="0" cellspacing="0"
  style="background:#f5f5f5;margin:0; padding:0; background:#f5f5f5; font-family: Arial, sans-serif;">
  <tr>
    <td align="center" style="padding:0 8px;">
      <table width="100%" cellpadding="0" cellspacing="0"
        style="background:#fff; border-radius:10px; box-shadow:0 4px 12px rgba(59,130,246,0.08); margin:30px 0; max-width:600px; width:100%;">

        <!-- Header -->
        <tr>
          <td align="center"
            style="background: linear-gradient(135deg, #003468 0%, #0080ff 100%); padding: 32px 20px 20px 20px; border-radius:10px 10px 0 0;">
            <img src="{{asset('assets/images/rex-white.png')}}" alt="Hyundai de Rexville" width="100"
              style="max-width:100%; height:auto; margin-bottom:10px;">

            <div style="color:#fff; font-size:14px; font-weight:bold; margin-bottom:0px; text-align:center;">
              Hyundai de Rexville
            </div>
          </td>
        </tr>

        <!-- Title Section -->
        <tr>
          <td style="padding:36px 24px 16px 24px;">
            <div style="color:#222; font-size:24px; font-weight:bold; margin-bottom:8px; text-align:center;">
              @yield('title')
            </div>
          </td>
        </tr>

        <!-- Greeting & Message -->
        <tr>
          <td style="padding:0 24px 24px 24px;">
            <div style="color:#374151; font-size:16px; line-height:1.6; text-align:left;">
              @if(trim($__env->yieldContent('user_name')))
                <p style="margin:0 0 16px 0;">
                  Hola <strong>@yield('user_name')</strong>,
                </p>
              @endif

              @if(trim($__env->yieldContent('message')))
                <p style="margin:0 0 24px 0; color:#374151;">
                  @yield('message')
                </p>
              @endif
            </div>
          </td>
        </tr>

        <!-- Main Content -->
        @if(trim($__env->yieldContent('content')))
        <tr>
          <td style="padding:0 24px 32px 24px;">
            @yield('content')
          </td>
        </tr>
        @endif

        <!-- Additional Information -->
        @if(trim($__env->yieldContent('additional_info')))
        <tr>
          <td style="padding:0 24px 32px 24px;">
            @yield('additional_info')
          </td>
        </tr>
        @endif

        <!-- Footer -->
        <tr>
          <td style="background:#f8fafc; text-align:center; padding:24px 24px 20px 24px; border-radius:0 0 10px 10px;">
            <div style="color:#64748b; font-size:14px; margin-bottom:8px;">
              <strong>Hyundai de Rexville - Plataforma de Gestión para Concesionarios</strong>
            </div>
            <div style="color:#64748b; font-size:13px; margin-bottom:12px;">
              Puerto Rico &nbsp;|&nbsp; +1 787 488 8080 &nbsp;|&nbsp; info@rexville.com
            </div>
            <div style="margin-top:12px; font-size:12px;">
              <a href="#" style="color:#64748b; text-decoration:none; margin:0 8px;">Política de Privacidad</a> |
              <a href="#" style="color:#64748b; text-decoration:none; margin:0 8px;">Términos y Condiciones</a>
            </div>
            <div style="color:#94a3b8; font-size:11px; margin-top:12px;">
              Este correo fue generado automáticamente por Hyundai de Rexville.
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

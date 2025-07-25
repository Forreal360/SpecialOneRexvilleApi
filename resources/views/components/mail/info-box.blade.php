<!-- Info Box Component -->
<table width="100%" cellpadding="0" cellspacing="0" style="margin: 20px 0;">
  <tr>
    <td style="background: {{ $bgColor ?? '#f0f9ff' }}; border: 1px solid {{ $borderColor ?? '#0080ff' }}; border-radius: 8px; padding: 20px;">
      @if(isset($icon))
        <div style="text-align: center; margin-bottom: 12px; font-size: 24px;">
          {{ $icon }}
        </div>
      @endif

      @if(isset($title))
        <div style="color: {{ $titleColor ?? '#003468' }}; font-size: 16px; font-weight: bold; margin-bottom: 8px;">
          {{ $title }}
        </div>
      @endif

      <div style="color: {{ $textColor ?? '#374151' }}; font-size: 14px; line-height: 1.6; margin: 0;">
        {{ $content }}
      </div>
    </td>
  </tr>
</table>

@props([
    'url',
    'color' => 'primary',
    'align' => 'center',
])
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="{{ $align }}">
            <a href="{{ $url }}" class="button" target="_blank" rel="noopener" style="background-color: {{ $color === 'primary' ? '#007bff' : '#6c757d' }}; border-radius: 5px; color: #ffffff; display: inline-block; font-size: 16px; font-weight: bold; padding: 12px 24px; text-align: center; text-decoration: none;">
                {{ $slot }}
            </a>
        </td>
    </tr>
</table>
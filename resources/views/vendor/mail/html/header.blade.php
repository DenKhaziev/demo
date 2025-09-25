@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === config('app.name'))
<img src="https://new.so-penaty.ru/storage/essentials/logo.svg" class="logo" alt="Школа Пенаты">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>

@component('mail::message')
# {{ __('تم قبول طلب إنشاء المجتمع') }}

{{ __('مرحباً') }} {{ $community->user->name }},

{{ __('نود إعلامك بأنه تم قبول طلبك لإنشاء مجتمع') }} "{{ $community->name }}".
{{ __('يمكنك الآن الوصول إلى مجتمعك والبدء في إضافة المحتوى.') }}

@component('mail::button', ['url' => route('communities.show', $community->id)])
{{ __('زيارة المجتمع') }}
@endcomponent

{{ __('شكراً لك،') }}<br>
{{ config('app.name') }}
@endcomponent
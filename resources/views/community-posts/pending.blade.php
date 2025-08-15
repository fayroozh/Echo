@extends('layouts.app')

@section('content')
<h2>مراجعة المنشورات المعلقة لمجتمع: {{ $community->name }}</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($community->posts->count() > 0)
<table class="table">
    <thead>
        <tr>
            <th>المستخدم</th>
            <th>المحتوى</th>
            <th>تاريخ الإرسال</th>
            <th>إجراءات</th>
        </tr>
    </thead>
    <tbody>
        @foreach($community->posts as $post)
        <tr>
            <td>{{ $post->user->name }}</td>
            <td>{{ $post->content }}</td>
            <td>{{ $post->created_at->format('Y-m-d H:i') }}</td>
            <td>
                <form action="{{ route('community-posts.approve', [$community->id, $post->id]) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">قبول</button>
                </form>
                <form action="{{ route('community-posts.reject', [$community->id, $post->id]) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">رفض</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>لا توجد منشورات معلقة حالياً.</p>
@endif
@endsection

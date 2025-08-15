@extends('layouts.app')

@section('content')
    <h2>إضافة منشور جديد في مجتمع: {{ $community->name }}</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('community-posts.store', $community->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="content" class="form-label">محتوى المنشور</label>
            <textarea name="content" id="content" rows="6" class="form-control" required>{{ old('content') }}</textarea>
            @error('content')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">إرسال للمراجعة</button>
    </form>

@endsection
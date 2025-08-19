<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('نتائج البحث') }}</div>

                    <div class="card-body">
                        @if(isset($users) && count($users) > 0)
                            <ul class="list-group">
                                @foreach($users as $user)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <a href="{{ route('profile.show', $user->id) }}">{{ $user->name }}</a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>{{ __('لا توجد نتائج للبحث') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-right">
                {{ __('طلبات الانضمام للمجتمع') }}: {{ $community->name }}
            </h2>
            <a href="{{ route('communities.dashboard', $community) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('العودة للوحة التحكم') }}
            </a>
    @if($community->members->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المستخدم</th>
                    <th>الحالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($community->members as $member)
                    <tr>
                        <td>{{ $member->user->name }}</td>
                        <td>{{ $member->status }}</td>
                        <td>
                            <form action="{{ route('communities.requests.approve', [$community->id, $member->id]) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <button class="btn btn-success" type="submit">قبول</button>
                            </form>

                            <form action="{{ route('communities.requests.reject', [$community->id, $member->id]) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <button class="btn btn-danger" type="submit">رفض</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>لا توجد طلبات انضمام حالياً.</p>
    @endif
@endsection
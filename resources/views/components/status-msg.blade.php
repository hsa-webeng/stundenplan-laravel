<div class="flex justify-center">
    @if (Session::has('success'))
        <div class="alert alert-success max-w-7xl sm:mx-6 lg:mx-8 w-full">
            <ul>
                <li>{{ Session::get('success') }}</li>
            </ul>
        </div>
    @elseif (Session::has('error'))
        <div class="alert alert-error max-w-7xl sm:mx-6 lg:mx-8 w-full">
            <ul>
                <li>{{ Session::get('error') }}</li>
            </ul>
        </div>
    @elseif (Session::has('info'))
        <div class="alert alert-info max-w-7xl sm:mx-6 lg:mx-8 w-full">
            <ul>
                <li>{{ Session::get('info') }}</li>
            </ul>
        </div>
    @endif
</div>

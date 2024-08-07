@if (Session::has('success'))
    <div class="alert alert-success max-w-7xl mx-auto sm:px-6 lg:px-8">
        <ul>
            <li>{{ Session::get('success') }}</li>
        </ul>
    </div>
@elseif (Session::has('error'))
    <div class="alert alert-danger max-w-7xl mx-auto sm:px-6 lg:px-8">
        <ul>
            <li>{{ Session::get('error') }}</li>
        </ul>
    </div>
@endif

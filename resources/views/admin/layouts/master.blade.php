<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Panel</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin/admin-dashboard.css') }}">
</head>
<body>
    <div class="admin-container">

        @include('admin.layouts.sidebar')

        <div class="main-content">

            @include('admin.layouts.header')

            <div class="page-content">

                @yield('content')
            </div>

        </div>
    </div>

    {{-- Page-level scripts if needed --}}
    @yield('scripts')

</body>
</html>

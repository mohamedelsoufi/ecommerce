@include('admin.includes.header')

@include('admin.includes.slider')

<div class="app-content content">
    <div class="content-wrapper">
        @yield('content')
    </div>
</div>

@include('admin.includes.footer')
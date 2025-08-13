@include('layouts.header')

@include('layouts.sidebar')

<div class="main">
    @include('layouts.top-navbar')

    <main class="content">
        <div class="container-fluid p-0">
            @yield('content')
        </div>
    </main>
    

    <div class="modal fade" id="editModal" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="addEditContent">
            </div>
        </div>
    </div>

    @include('layouts.footer')
</div>
</div> <!-- end wrapper -->

<!-- JS -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{asset('js')}}/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
@stack('scripts')
</body>
</html>
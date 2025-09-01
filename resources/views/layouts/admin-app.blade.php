@include('layouts.header')

@include('layouts.sidebar')

@stack('custom-style')
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

<!-- datatable -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<!-- toastr -->
<script src="{{asset('js')}}/toastr.min.js"></script>

<!-- select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

<!-- moment js -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@stack('scripts')
@yield('custom-scripts')
</body>
</html>
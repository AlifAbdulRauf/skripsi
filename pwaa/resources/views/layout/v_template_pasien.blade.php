
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 <head>

     
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    




    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom fonts for this template-->
    <link href=" {{ URL::to('vendor/fontawesome-free/css/all.min.css') }} " rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <!-- Include CSS for Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



    <!-- Favicon -->
    <link href="{{ asset('img/favicon.png') }}" rel="icon" type="image/png">

</head>

<body id="page-top">

    @include('vendor.sweetalert.alert')

    <!-- Page Wrapper -->
    <div id="wrapper">

       @include('layout.v_nav_pasien')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column" >
            <!-- Topbar -->
            <nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 ">

                <!-- Topbar Navbar -->
                @include('layouts.navigation')

            </nav>
            <!-- Main Content -->
            <div id="content">


                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                @stack('notif')
                @yield('main-content')

                    <!-- Page Heading
                    <h1 class="h3 mb-4 text-gray-800">Blank Page</h1> -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
            @stack('js')

                

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Xenon Dental House</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Ready to Leave?') }}</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-link" type="button" data-dismiss="modal">{{ __('Cancel') }}</button>
                <a class="btn btn-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ URL::to('vendor/jquery/jquery.min.js') }} "></script>
    <script src="{{ URL::to('vendor/bootstrap/js/bootstrap.bundle.min.js') }} "></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ URL::to('vendor/jquery-easing/jquery.easing.min.js') }} "></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ URL::to('js/sb-admin-2.min.js') }} "></script>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <!-- DataTables JS -->
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
        <!-- Inisialisasi DataTables -->
        <script>
            
        $(document).ready(function() {
            var table = $('#example').DataTable({
                "pageLength": 8 // Menentukan jumlah baris per halaman
            });
            
    
            // Handle click on "Select all" control
            $('#selectAll').on('click', function(){
               // Check/uncheck all checkboxes in the table
               var rows = table.rows({ 'search': 'applied' }).nodes();
               $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });
    
            // Handle click on checkbox to set state of "Select all" control
            $('#example tbody').on('change', 'input[type="checkbox"]', function(){
               // If checkbox is not checked
               if(!this.checked){
                  var el = $('#selectAll').get(0);
                  // If "Select all" control is checked and has 'indeterminate' property
                  if(el && el.checked && ('indeterminate' in el)){
                     // Set visual state of "Select all" control 
                     // as 'indeterminate'
                     el.indeterminate = true;
                  }
               }
            });
        });
        </script>

        <!-- Include JS for Select2 -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                function checkPerawatanSelection() {
                    var selectedOptions = $('.select2-multiple').select2('data');
            
                    // Logic to check selected options
                    if (selectedOptions.some(option => parseInt(option.element.dataset.estimasi) === 120)) {
                        if (selectedOptions.length > 1) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Peringatan!',
                                text: 'Pasang behel tidak bisa dilakukan bersamaan dengan perawatan lain.',
                            });
                            return false;
                        }
                    }
            
                    return true;
                }
            
                $('.select2-multiple').select2({
                    maximumSelectionLength: 2
                }).on('select2:select select2:unselect', function() {
                    if (!checkPerawatanSelection()) {
                        $(this).val(null).trigger('change');
                    }
                });
            });
        </script>



</body>

</html>
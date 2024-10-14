 <!-- Sidebar -->
 <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion " id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center " href="/dokter">
        <div class="sidebar-brand-icon ">
            <img src="/img/logo-xe-white.png" alt="" style="width: 150px">
        </div>
        <div class="sidebar-brand-text mx-3">Xenon Dental House</div>
    </a>
    
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    
    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="/dokter">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Jadwal</span></a>
    </li>
    
    {{-- @if (auth()->user()->role_user=='Admin') --}}
    <!-- Divider -->
    <hr class="sidebar-divider">
     <!-- Nav Item - Pages Collapse Menu -->
     <li class="nav-item">
        <a class="nav-link " href="/ddatapasien">
            <i class="fas fa-fw fa-cog"></i>
            <span>Data Pasien</span>
        </a>
        {{-- <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                <a class="collapse-item" href="/reservasi">Reservasi</a>
                <a class="collapse-item" href="/user">Data Pasien</a>
                <a class="collapse-item" href="/lokasi">Data Dokter</a>
                <a class="collapse-item" href="penginapan">Data User</a>
                <a class="collapse-item" href="penginapan">Profil</a>
            </div>
        </div> --}}
    </li>
    
    <!-- Heading -->
    <!-- <div class="sidebar-heading">
        Dinas Luar
    </div> -->
    <!-- Divider -->
    <!-- <hr class="sidebar-divider">
    <!- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link" href="/dataabsen">
            <i class="fas fa-fw fa-folder"></i>
            <span>Data Absen</span>
        </a>
    </li>

     
    
    <!-- Divider -->
    <hr class="sidebar-divider">
    
    <!-- Heading
    <div class="sidebar-heading">
        Addons
    </div> -->
    
    <!-- Nav Item - Pages Collapse Menu -->
    <!-- <li class="nav-item active">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
            aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Pages</span>
        </a>
        <div id="collapsePages" class="collapse show" aria-labelledby="headingPages"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Login Screens:</h6>
                <a class="collapse-item" href="login.html">Login</a>
                <a class="collapse-item" href="register.html">Register</a>
                <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                <div class="collapse-divider"></div>
                <h6 class="collapse-header">Other Pages:</h6>
                <a class="collapse-item" href="404.html">404 Page</a>
                <a class="collapse-item active" href="blank.html">Blank Page</a>
            </div>
        </div>
    </li> -->
    
    
    
    
    
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
    
    </ul>
    <!--End of Sidebar -->

    <!-- jQuery and Bootstrap JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script >
    $(document).ready(function () {
    $('#sidebarToggle').on('click', function () {
        $('.sidebar').toggleClass('toggled');
    });
});
</script>
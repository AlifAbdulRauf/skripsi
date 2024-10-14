 <!-- Sidebar -->
 <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion " id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center " href="/pasien">
        <div class="sidebar-brand-icon ">
            <img src="/img/logo-xe-white.png" alt="" style="width: 150px">
        </div>
        <div class="sidebar-brand-text mx-3">Xenon Dental House</div>
    </a>
    
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Reservasi</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                <a class="collapse-item" href="{{ route("pasien.history") }}">Riwayat Reservasi </a>
                <a class="collapse-item" href="{{ route("pasien.add") }}">Reservasi Pasien Baru</a>
                <a class="collapse-item" href="{{ route("pasienlama.add") }}">Reservasi Pasien Lama</a>
            </div>
        </div>
    </li>
    
    {{-- @if (auth()->user()->role_user=='Admin') --}}
    <!-- Divider -->
    <hr class="sidebar-divider">
     <!-- Nav Item - Pages Collapse Menu -->
     <li class="nav-item">
        <a class="nav-link" href="/home">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Halaman Home</span></a>
    </li>

    <hr class="sidebar-divider">

    
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
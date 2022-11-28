<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item dashboard-menu-link">
        <a class="nav-link" href="{{route('admin.index')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <li class="nav-item category-menu-link">
        <a class="nav-link" href="{{route('admin.category')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Category</span></a>
    </li>
    <li class="nav-item extra-charges-menu-link">
        <a class="nav-link" href="{{route('admin.extra_charge')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Extra Charges</span></a>
    </li>
    <li class="nav-item table-menu-link">
        <a class="nav-link" href="{{route('admin.table')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Tables</span></a>
    </li>
    <li class="nav-item products-menu-link">
        <a class="nav-link" href="{{route('admin.product')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Products</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider ">
    <li class="nav-item order-menu-link">
        <a class="nav-link" href="{{route('admin.orders')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Orders</span></a>
    </li>
    <li class="nav-item manage-table-menu-link">
        <a class="nav-link" href="{{route('admin.manage_table')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Manage Orders</span></a>
    </li>


</ul>

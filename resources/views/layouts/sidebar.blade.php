<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Factory App</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset(Auth::user()->user_pic) }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item {{ Request::path() == 'home' ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::path() == 'home' ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/home" class="nav-link {{ Request::path() == 'home' ? 'active' : '' }}">
                  <i class="fas fa-home nav-icon"></i>
                  <p>Home</p>
                </a>
              </li>
            </ul> 
          </li>
          <li class="nav-item {{ (Request::path() == 'user') || (Request::path() == 'account')  ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ (Request::path() == 'user') || (Request::path() == 'account')  ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/user" class="nav-link {{ Request::path() == 'user' ? 'active' : '' }}">
                  <i class="fas fa-user nav-icon"></i>
                  <p>Add/Show Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/account" class="nav-link {{ Request::path() == 'account' ? 'active' : '' }}">
                  <i class="far fa-credit-card nav-icon"></i>
                  <p>User Accounts</p>
                </a>
              </li>
            </ul> 
          </li>
          <li class="nav-item {{ (Request::path() == 'product') || (Request::path() == 'productDetail') || (request()->is('editPDetails/*')) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ (Request::path() == 'product') || (Request::path() == 'productDetail') || (request()->is('editPDetails/*')) ? 'active' : '' }}">
              <i class="nav-icon fab fa-product-hunt"></i>
              <p>
                Products
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/product" class="nav-link {{ Request::path() == 'product' || (request()->is('editPDetails/*')) ? 'active' : '' }}">
                  <i class="fa fa-{{ request()->is('editPDetails/*') ? 'sync fa-spin' : 'plus' }} nav-icon"></i>
                  <p>{{ request()->is('editPDetails/*') ? 'Update' : 'Add' }} Product</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/productDetail" class="nav-link {{ Request::path() == 'productDetail' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Show Products</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item {{ (Request::path() == 'layer') || (Request::path() == 'part') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ (Request::path() == 'layer') || (Request::path() == 'part') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Product Parts
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/layer" class="nav-link {{ Request::path() == 'layer' ? 'active' : '' }}">
                  <i class="fas fa-layer-group nav-icon"></i>
                  <p>Layers</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/part" class="nav-link {{ Request::path() == 'part' ? 'active' : '' }}">
                  <i class="fas fa-boxes nav-icon"></i>
                  <p>Options</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item {{ (Request::path() == 'stock') || (Request::path() == 'purchase') || (Request::path() == 'sale') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ (Request::path() == 'stock') || (Request::path() == 'purchase') || (Request::path() == 'sale') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cubes"></i>
              <p>
                Inventory
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/stock" class="nav-link {{ Request::path() == 'stock' ? 'active' : '' }}">
                  <i class="fas fa-warehouse nav-icon"></i>
                  <p>Stock</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/purchase" class="nav-link {{ Request::path() == 'purchase' ? 'active' : '' }}">
                  <i class="fas fa-shopping-bag nav-icon"></i>
                  <p>Purchase</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/sale" class="nav-link {{ Request::path() == 'sale' ? 'active' : '' }}">
                  <i class="fas fa-dolly nav-icon"></i>
                  <p>Sale</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}" 
                onclick="event.preventDefault(); 
                document.getElementById('logout-form').submit();">

                <i class="nav-icon fas fa-power-off text-danger"></i>
                {{ __('Log Out') }}

            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
          </li><!-- logout -->
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
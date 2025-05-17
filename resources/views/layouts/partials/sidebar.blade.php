<div class="sidebar bg-dark text-white d-flex flex-column vh-100 p-3">
    <!-- Sidebar Header -->
    <h4 class="text-center mb-4">Admin Panel</h4>

    <!-- Sidebar Links -->
    <a href="{{ route('dashboard') }}" 
       class="text-white text-decoration-none mb-3 py-2 px-3 rounded-3 
              {{ request()->is('dashboard') ? 'bg-primary' : '' }}"
       style="transition: background-color 0.3s;">
        <i class="fa fa-tachometer-alt"></i> Dashboard
    </a>

    <a href="{{ route('admin.vouchers.index') }}" 
       class="text-white text-decoration-none mb-3 py-2 px-3 rounded-3 
              {{ request()->is('admin/vouchers*') ? 'bg-primary' : '' }}"
       style="transition: background-color 0.3s;">
        <i class="fas fa-gift"></i> Vouchers
    </a>

    <!-- Logout Button -->
    <form action="{{ route('logout') }}" method="POST" class="mt-auto">
        @csrf
        <button type="submit" class="btn btn-danger w-100 py-2 rounded-3 mt-3">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </form>
</div>

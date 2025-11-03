<header class="bg-black text-white flex justify-between items-center px-6 py-3">
    <div class="flex items-center space-x-3">
        <img src="/logo.png" alt="Logo" class="w-10 h-10 rounded-full">
        <h1 class="font-bold text-lg">Septa Classic Motor</h1>
    </div>
    <nav class="flex space-x-6">
        <a href="/admin/dashboard"
           class="{{ $active == 'dashboard' ? 'text-blue-400 font-semibold' : 'text-white hover:text-gray-300' }}">
            Dashboard
        </a>
        <a href="/admin/catalog"
           class="{{ $active == 'catalog' ? 'text-blue-400 font-semibold' : 'text-white hover:text-gray-300' }}">
            Catalog
        </a>
        <a href="/admin/orders"
           class="{{ $active == 'orders' ? 'text-blue-400 font-semibold' : 'text-white hover:text-gray-300' }}">
            Order List
        </a>
    </nav>
    <div>
        <i class="fa fa-user-circle text-xl"></i>
    </div>
</header>

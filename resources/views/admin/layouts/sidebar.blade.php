<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{ request()->segment(2) == 'dashboard' ? '' : 'collapsed' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->segment(2) == 'notes' ? '' : 'collapsed' }}" href="{{ route('admin.notes') }}">
                <i class="bi bi-grid"></i>
                <span>Notes</span>
            </a>
        </li>


    </ul>

</aside>

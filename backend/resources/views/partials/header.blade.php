<header class="app-header">
    <div class="header-container">
        <a href="/dashboard" class="logo">
            <div class="logo-icon">🏥</div>
            <span>Healthcare AI Platform</span>
        </a>
        
        <nav class="nav">
            <a href="/dashboard" class="nav-link {{ request()->is('dashboard') || request()->is('/') ? 'active' : '' }}">
                <span class="nav-icon">📊</span>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="/patients" class="nav-link {{ request()->is('patients*') ? 'active' : '' }}">
                <span class="nav-icon">👥</span>
                <span class="nav-text">Patients</span>
            </a>
            <a href="/medgemma" class="nav-link {{ request()->is('medgemma*') ? 'active' : '' }}">
                <span class="nav-icon">🤖</span>
                <span class="nav-text">AI Analysis</span>
            </a>
            <a href="/dicom-upload" class="nav-link {{ request()->is('dicom-upload*') ? 'active' : '' }}">
                <span class="nav-icon">📁</span>
                <span class="nav-text">DICOM Upload</span>
            </a>
            @if(auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->email === 'admin@medgemma.com'))
            <a href="/user-management" class="nav-link {{ request()->is('user-management*') ? 'active' : '' }}">
                <span class="nav-icon">⚙️</span>
                <span class="nav-text">Users</span>
            </a>
            @endif
            <a href="/help" class="nav-link {{ request()->is('help*') ? 'active' : '' }}">
                <span class="nav-icon">❓</span>
                <span class="nav-text">Help</span>
            </a>
        </nav>
        
        <div class="user-info">
            <div class="user-details">
                <span class="user-icon">👤</span>
                <span class="user-name">{{ auth()->user()->name ?? 'Guest' }}</span>
                @if(auth()->check() && auth()->user()->hasRole('Admin'))
                <span class="user-role">Admin</span>
                @endif
            </div>
            <form method="POST" action="/logout" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">
                    <span class="logout-icon">🚪</span>
                    <span class="logout-text">Sign Out</span>
                </button>
            </form>
        </div>
    </div>
</header>

@include('partials.global-styles')

<style>
.app-header {
    background: var(--glass-background);
    backdrop-filter: var(--backdrop-blur);
    border-bottom: 1px solid var(--glass-border);
    padding: var(--spacing-md) 0;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: var(--shadow-md);
}

.header-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: var(--spacing-md);
}

.logo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
    color: var(--text-primary);
    font-weight: 700;
    font-size: 1.2rem;
    transition: transform 0.2s ease;
}

.logo:hover {
    transform: scale(1.02);
    color: var(--text-primary);
}

.logo-icon {
    font-size: 1.5rem;
}

.nav {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    flex: 1;
    justify-content: center;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: 0.6rem var(--spacing-md);
    text-decoration: none;
    color: var(--text-secondary);
    border-radius: var(--radius-md);
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.nav-link:hover {
    background: var(--glass-background);
    color: var(--text-primary);
    transform: translateY(-1px);
    border-color: var(--glass-border);
}

.nav-link.active {
    background: var(--glass-background-active);
    color: var(--text-primary);
    border-color: var(--glass-border-hover);
}

.nav-icon {
    font-size: 1rem;
}

.user-info {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
}

.user-details {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    background: var(--glass-background);
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: 20px;
    font-size: 0.9rem;
    border: 1px solid var(--glass-border);
}

.user-icon {
    font-size: 1.1rem;
}

.user-name {
    font-weight: 500;
}

.user-role {
    background: var(--glass-background-active);
    padding: 0.2rem var(--spacing-sm);
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: 600;
}

.logout-form {
    margin: 0;
}

.logout-btn {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    background: var(--btn-primary-bg);
    border: 1px solid var(--btn-primary-border);
    color: var(--text-primary);
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: var(--radius-md);
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.logout-btn:hover {
    background: var(--btn-primary-hover);
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
}

.logout-icon {
    font-size: 1rem;
}

/* Mobile responsive design */
@media (max-width: 1024px) {
    .nav-text {
        display: none;
    }
    
    .nav-link {
        padding: 0.6rem;
    }
    
    .user-name {
        display: none;
    }
    
    .logout-text {
        display: none;
    }
    
    .logout-btn {
        padding: 0.5rem;
    }
}

@media (max-width: 768px) {
    .header-container {
        flex-direction: column;
        gap: 1rem;
        padding: 0 1rem;
    }
    
    .nav {
        justify-content: flex-start;
        overflow-x: auto;
        width: 100%;
        padding-bottom: 0.5rem;
    }
    
    .nav::-webkit-scrollbar {
        height: 4px;
    }
    
    .nav::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 2px;
    }
    
    .nav::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 2px;
    }
    
    .user-info {
        width: 100%;
        justify-content: space-between;
    }
    
    .nav-text {
        display: inline;
    }
    
    .user-name {
        display: inline;
    }
    
    .logout-text {
        display: inline;
    }
}

@media (max-width: 480px) {
    .logo span {
        display: none;
    }
    
    .nav-link {
        flex-direction: column;
        gap: 0.25rem;
        padding: 0.5rem;
        font-size: 0.8rem;
    }
    
    .nav-text {
        font-size: 0.7rem;
    }
    
    .user-details {
        padding: 0.4rem 0.8rem;
    }
    
    .logout-btn {
        padding: 0.4rem 0.8rem;
    }
}
</style>

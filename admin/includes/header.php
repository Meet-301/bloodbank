<div class="admin-header">
  <div class="header-content">
    <!-- Logo and System Name -->
    <div class="brand-logo">
      <a href="dashboard.php" class="system-brand">
        <i class="fas fa-tint blood-icon"></i>
        <span class="system-name">LifeSavior Admin</span>
        <span class="system-tagline">Smart Blood Bank Management System</span>
      </a>
    </div>
    
    <!-- Mobile Menu Toggle -->
    <button class="menu-toggle" aria-label="Toggle navigation">
      <span class="hamburger-icon"><i class="fas fa-bars"></i></span>
    </button>
    
    <!-- User Profile Navigation -->
    <div class="user-nav">
      <div class="dropdown profile-dropdown">
        <button class="dropdown-toggle" aria-expanded="false">
          <div class="user-avatar">
            <img src="img/adminavatar.jpg" alt="Admin Avatar" class="avatar-img">
            <span class="user-name">Admin</span>
            <i class="fas fa-chevron-down dropdown-arrow"></i>
          </div>
        </button>
        <ul class="dropdown-menu">
          <li><a href="profile.php"><i class="fas fa-user-circle"></i> My Profile</a></li>
          <li><a href="change-password.php"><i class="fas fa-key"></i> Change Password</a></li>
          <li><a href="INVENTORY.php" target="blank"><i class="fas fa-box"></i> Inventory</a></li>
          <li class="divider"></li>
          <li><a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<style>
/* Modern Header Styles */
.admin-header {
  background: linear-gradient(135deg, #d32f2f, #b71c1c);
  color: white;
  padding: 0 2rem;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  position: sticky;
  top: 0;
  z-index: 1000;
}

.header-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 70px;
  max-width: 1200px;
  margin: 0 auto;
}

.brand-logo a {
  display: flex;
  align-items: center;
  text-decoration: none;
  color: white;
}

.blood-icon {
  font-size: 24px;
  margin-right: 12px;
  color: #ffcdd2;
}

.system-name {
  font-size: 20px;
  font-weight: 600;
  margin-right: 8px;
}

.system-tagline {
  font-size: 14px;
  opacity: 0.8;
  font-weight: 400;
}

.user-nav {
  position: relative;
}

.profile-dropdown {
  position: relative;
}

.dropdown-toggle {
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 0;
}

.user-avatar {
  display: flex;
  align-items: center;
  gap: 10px;
}

.avatar-img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: 2px solid rgba(255, 255, 255, 0.3);
}

.user-name {
  font-weight: 500;
}

.dropdown-arrow {
  font-size: 12px;
  transition: transform 0.2s;
}

.dropdown-toggle[aria-expanded="true"] .dropdown-arrow {
  transform: rotate(180deg);
}

.dropdown-menu {
  position: absolute;
  right: 0;
  top: 100%;
  background: white;
  min-width: 200px;
  border-radius: 4px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  padding: 10px 0;
  margin-top: 10px;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s;
  z-index: 1000;
}

.dropdown-menu.show {
  opacity: 1;
  visibility: visible;
  margin-top: 5px;
}

.dropdown-menu li a {
  display: flex;
  align-items: center;
  padding: 10px 20px;
  color: #333;
  text-decoration: none;
  transition: all 0.2s;
}

.dropdown-menu li a:hover {
  background: #f5f5f5;
  color: #d32f2f;
}

.dropdown-menu li a i {
  margin-right: 10px;
  width: 20px;
  text-align: center;
}

.divider {
  height: 1px;
  background: #eee;
  margin: 8px 0;
}

.logout-link {
  color: #d32f2f !important;
}

.menu-toggle {
  background: none;
  border: none;
  color: white;
  font-size: 20px;
  cursor: pointer;
  display: none;
}

@media (max-width: 768px) {
  .menu-toggle {
    display: block;
  }
  
  .system-tagline {
    display: none;
  }
  
  .header-content {
    padding: 0 15px;
  }
}
</style>

<script>
// Simple dropdown toggle functionality
document.querySelector('.dropdown-toggle').addEventListener('click', function() {
  const dropdown = this.parentElement;
  const menu = dropdown.querySelector('.dropdown-menu');
  const isExpanded = this.getAttribute('aria-expanded') === 'true';
  
  this.setAttribute('aria-expanded', !isExpanded);
  menu.classList.toggle('show');
});

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
  if (!e.target.closest('.dropdown')) {
    const openMenus = document.querySelectorAll('.dropdown-menu.show');
    openMenus.forEach(menu => {
      menu.classList.remove('show');
      menu.previousElementSibling.setAttribute('aria-expanded', 'false');
    });
  }
});
</script>
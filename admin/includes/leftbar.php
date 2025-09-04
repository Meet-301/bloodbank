<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="admin-sidebar">
  <div class="sidebar-menu">
    <div class="sidebar-header">
      <span class="sidebar-title">ACTIONS</span>
    </div>

    <ul class="sidebar-list">
      <li class="sidebar-item <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
        <a href="dashboard.php" class="sidebar-link">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <li class="sidebar-item has-submenu 
    <?php echo (
      $current_page == 'add-bloodgroup.php' ||
      $current_page == 'manage-bloodgroup.php'
    ) ? 'active' : ''; ?>">
        <a href="#" class="sidebar-link">
          <i class="fas fa-tint"></i>
          <span>Blood Group</span>
          <i class="fas fa-chevron-down dropdown-arrow"></i>
        </a>
        <ul class="submenu" style="<?php
        echo (
          $current_page == 'add-bloodgroup.php' ||
          $current_page == 'manage-bloodgroup.php'
        ) ? 'max-height: 200px;' : ''; ?>">
          <li class="submenu-item <?php echo ($current_page == 'add-bloodgroup.php') ? 'active' : ''; ?>">
            <a href="add-bloodgroup.php">Add Blood Group</a>
          </li>
          <li class="submenu-item <?php echo ($current_page == 'manage-bloodgroup.php') ? 'active' : ''; ?>">
            <a href="manage-bloodgroup.php">Manage Blood Group</a>
          </li>
        </ul>
      </li>

      <li class="sidebar-item <?php echo ($current_page == 'donor-approvals.php') ? 'active' : ''; ?>">
        <a href="donor-approvals.php" class="sidebar-link">
          <i class="fas fa-user-plus"></i>
          <span>Donor Approvals</span>
        </a>
      </li>
<li class="sidebar-item <?php echo ($current_page == 'INVENTORY.php') ? 'active' : ''; ?>">
        <a href="INVENTORY.php" class="sidebar-link">
          <i class="fas fa-user-plus"></i>
          <span>Inventory</span>
        </a>
      </li>
      <li class="sidebar-item <?php echo ($current_page == 'donor-list.php') ? 'active' : ''; ?>">
        <a href="donor-list.php" class="sidebar-link">
          <i class="fas fa-users"></i>
          <span>Donor List</span>
        </a>
      </li>

      <li class="sidebar-item <?php echo ($current_page == 'manage-conactusquery.php') ? 'active' : ''; ?>">
        <a href="manage-conactusquery.php" class="sidebar-link">
          <i class="fas fa-envelope"></i>
          <span>Contact Queries</span>
        </a>
      </li>

      <!-- <li class="sidebar-item">
        <a href="manage-pages.php" class="sidebar-link">
          <i class="fas fa-file-alt"></i>
          <span>Manage Pages</span>
        </a>
      </li> -->

      <!-- <li class="sidebar-item">
        <a href="update-contactinfo.php" class="sidebar-link">
          <i class="fas fa-address-card"></i>
          <span>Contact Info</span>
        </a>
      </li> -->

      <!-- <li class="sidebar-item">
        <a href="request-received-bydonar.php" class="sidebar-link">
          <i class="fas fa-hand-holding-heart"></i>
          <span>Donation Requests</span>
        </a>
      </li> -->
    </ul>
  </div>
</nav>

<style>
  /* Sidebar Styles */

  .sidebar-item.active>.sidebar-link {
    background-color: rgba(255, 255, 255, 0.15);
    color: #fff;
  }

  .sidebar-item.active>.sidebar-link i {
    color: #fff;
  }

  .submenu-item.active>a {
    color: #fff;
    background-color: rgba(255, 255, 255, 0.1);
  }


  .admin-sidebar {
    width: 250px;
    height: 100vh;
    background: #2c3e50;
    color: #ecf0f1;
    position: fixed;
    left: 0;
    top: 70px;
    /* Match header height */
    transition: all 0.3s;
    z-index: 900;
  }

  .sidebar-header {
    padding: 20px 15px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  }

  .sidebar-title {
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: rgba(255, 255, 255, 0.5);
    font-weight: 600;
  }

  .sidebar-list {
    list-style: none;
    padding: 15px 0;
  }

  .sidebar-item {
    position: relative;
  }

  .sidebar-link {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: #ecf0f1;
    text-decoration: none;
    transition: all 0.3s;
  }

  .sidebar-link:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
  }

  .sidebar-link i {
    font-size: 16px;
    margin-right: 12px;
    width: 20px;
    text-align: center;
  }

  .sidebar-link span {
    flex-grow: 1;
  }

  .dropdown-arrow {
    font-size: 12px;
    transition: transform 0.3s;
  }

  .has-submenu.active .dropdown-arrow {
    transform: rotate(180deg);
  }

  .submenu {
    list-style: none;
    padding-left: 15px;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
  }

  .has-submenu.active .submenu {
    max-height: 200px;
  }

  .submenu-item a {
    display: block;
    padding: 10px 20px 10px 45px;
    color: #bdc3c7;
    text-decoration: none;
    transition: all 0.2s;
    position: relative;
  }

  .submenu-item a:before {
    content: "";
    position: absolute;
    left: 30px;
    top: 50%;
    width: 5px;
    height: 5px;
    background: #bdc3c7;
    border-radius: 50%;
    transform: translateY(-50%);
  }

  .submenu-item a:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.05);
  }

  .submenu-item a:hover:before {
    background: #fff;
  }

  /* Content Wrapper Offset to make room for fixed sidebar */
  .content-wrapper {
    margin-left: 250px;
  }

  @media (max-width: 768px) {
    .admin-sidebar {
      transform: translateX(-100%);
    }

    .admin-sidebar.active {
      transform: translateX(0);
    }

    /* Remove left margin when sidebar hidden on mobile */
    .content-wrapper {
      margin-left: 0;
    }
  }
</style>

<script>
  // Submenu toggle functionality
  document.querySelectorAll('.has-submenu > .sidebar-link').forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      const parent = this.parentElement;
      parent.classList.toggle('active');
    });
  });

  // Safe mobile menu toggle
  const toggleBtn = document.querySelector('.menu-toggle');
  if (toggleBtn) {
    toggleBtn.addEventListener('click', function () {
      document.querySelector('.admin-sidebar').classList.toggle('active');
    });
  }
</script>
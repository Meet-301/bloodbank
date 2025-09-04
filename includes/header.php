<?php
session_start();
$isLoggedIn = isset($_SESSION['bbdmsdid']) && !empty($_SESSION['bbdmsdid']);
?>

<!-- Header -->
<header class="bg-primary text-white">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary py-3">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <div class="logo-icon bg-danger rounded-circle d-flex align-items-center justify-content-center me-2"
                    style="width: 40px; height: 40px;">
                    <i class="fas fa-tint fa-lg text-white"></i>
                </div>
                <span class="fw-bold fs-4">Life<span class="text-danger"> Savior</span></span>
            </a>

            <!-- Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Nav Links -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php
                        echo (basename($_SERVER['PHP_SELF']) == 'index.php' ||
                            basename($_SERVER['PHP_SELF']) == 'index2.php')
                            ? 'active' : '';
                        ?>" href="index.php">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>"
                            href="about.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>"
                            href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'donor-list.php' ? 'active' : ''; ?>"
                            href="donor-list.php">Donors</a>
                    </li>

                    <!-- Conditional Nav Items -->
                    <?php if ($isLoggedIn): ?>
                        <!-- My Profile Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i> My Profile
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a>
                                </li>
                                <li><a class="dropdown-item" href="change-password.php"><i
                                            class="fas fa-key me-2"></i>Change Password</a></li>
                                <li><a class="dropdown-item" href="request-received.php"><i
                                            class="fas fa-inbox me-2"></i>Requests</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="logout.php"><i
                                            class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Login Button -->
                        <li class="nav-item">
                            <a href="login.php" class="btn btn-outline-light ms-lg-3 mt-3 mt-lg-0">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

include_once __DIR__ . '/cart_functions.php';
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTOP จันทบุรี | มหัศจรรย์เมืองจันท์</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600;700&family=Sarabun:wght@300;400;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #004d40;
            --gold: #ffc107;
            --dark: #1a1a1a;
        }

        body {
            font-family: 'Sarabun', sans-serif;
            background: #fdfdfd;
            color: #2c3e50;
        }

        h1,
        h2,
        h3,
        .navbar-brand {
            font-family: 'Kanit', sans-serif;
        }

        /* Navbar Modern */
        .navbar {
            background: var(--primary) !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 15px 0;
        }

        .navbar-brand {
            font-size: 1.8rem;
            letter-spacing: 1px;
        }

        .nav-link {
            color: #ffffff !important;
            margin: 0 15px;
            padding: 10px 0 !important;
            transition: 0.3s;
            border-bottom: 3px solid transparent;
            font-weight: 500;
            display: flex;
            align-items: center;
            white-space: nowrap;
            line-height: 1.2;
            height: 100%;
        }

        .nav-link:hover {
            color: var(--gold) !important;
            border-bottom-color: var(--gold);
            transform: translateY(-2px);
        }

        .nav-link.active {
            color: var(--gold) !important;
            border-bottom-color: var(--gold);
            font-weight: 700;
        }

        /* Hero Section (แก้ไขบั๊กพื้นหลังเทา) */
        .hero-premium {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)),
                url('https://images.unsplash.com/photo-1596402184320-417d7178b2cd?q=80&w=1920');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 500px;
            display: flex;
            align-items: center;
            color: white;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
        }

        /* Card ลูกเล่นใหม่ */
        .custom-card {
            border: none;
            border-radius: 20px;
            transition: 0.4s;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .custom-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        .btn-gold {
            background: var(--gold);
            color: var(--dark);
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }

        .btn-gold:hover {
            background: #ffcc00;
            color: #000 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(255, 193, 7, 0.4);
        }



        .cart-nav-item {
            display: flex;
            align-items: center;
            margin-left: 10px;
        }

        .nav-link[href="cart.php"] {
            font-size: 1.1rem;
            margin: 0 !important;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-gem text-warning me-2"></i>CHANTHABURI
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="product.php">สินค้า OTOP</a></li>
                    <li class="nav-item"><a class="nav-link" href="tradition.php">ประเพณี</a></li>
                    <li class="nav-item"><a class="nav-link" href="travel.php">สถานที่ท่องเที่ยว</a></li>
                    <li class="nav-item"><a class="nav-link" href="team.php">ผู้จัดทำ</a></li>

                </ul>
                <ul class="navbar-nav align-items-center">

                    <li class="nav-item ms-lg-2 cart-nav-item">
                        <a class="nav-link position-relative" href="cart.php" style="font-size: 1.2rem;">
                            <i class="fas fa-shopping-cart"></i>
                            <?php $cart_count = getCartCount(); ?>
                            <?php if ($cart_count > 0): ?>
                                <span id="cart-badge"
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    style="font-size: 0.75rem;">
                                    <?= $cart_count ?>
                                </span>
                            <?php else: ?>
                                <span id="cart-badge"
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    style="font-size: 0.75rem; display: none;">
                                    0
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>

                    <?php if (isset($_SESSION['admin_username'])): ?>
                        <li class="nav-item">
                            <a class="nav-link text-warning fw-bold" href="admin_dashboard.php">
                                <i class="fas fa-user-shield"></i> จัดการระบบ
                            </a>
                        </li>
                        <li class="nav-item dropdown ms-lg-3">
                            <a class="btn btn-danger rounded-pill px-4 text-white shadow-sm" href="logout.php"
                                onclick="event.preventDefault(); Swal.fire({title: 'คุณต้องการออกจากระบบใช่หรือไม่?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'ตกลง', cancelButtonText: 'ยกเลิก'}).then((result) => { if (result.isConfirmed) { window.location.href = this.href; } })">
                                <?= $_SESSION['admin_fullname'] ?> (Admin) <i class="fas fa-sign-out-alt ms-1"></i>
                            </a>
                        </li>

                    <?php elseif (isset($_SESSION['username'])): ?>
                        <li class="nav-item dropdown ms-lg-3">
                            <a class="btn btn-outline-warning rounded-pill px-4 text-white" href="logout.php"
                                onclick="event.preventDefault(); Swal.fire({title: 'คุณต้องการออกจากระบบใช่หรือไม่?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'ตกลง', cancelButtonText: 'ยกเลิก'}).then((result) => { if (result.isConfirmed) { window.location.href = this.href; } })">
                                <?= $_SESSION['fullname'] ?> <i class="fas fa-sign-out-alt ms-1"></i>
                            </a>
                        </li>

                    <?php else: ?>
                        <li class="nav-item ms-lg-3"><a href="login.php" class="btn btn-gold px-4">เข้าสู่ระบบ</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <script>
        // Highlight active menu based on current page
        document.addEventListener('DOMContentLoaded', function () {
            const currentPage = window.location.pathname.split('/').pop() || 'index.php';
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href === currentPage || href === '') {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        });
    </script>
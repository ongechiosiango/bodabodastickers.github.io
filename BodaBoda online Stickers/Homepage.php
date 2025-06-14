<?php include('db-conn.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BODABODA ONLINE STICKERS</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        html, body {
            height: 100%;
            width: 100%;
            overflow-x: hidden;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('https://images.unsplash.com/photo-1568605114967-8130f4826a4f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #fff;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            width: 100%;
            margin: 0;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            flex: 1;
        }
        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
        header {
            text-align: center;
            padding: 20px 0;
            width: 100%;
            margin-top: 60px; /* Added space for the fixed nav */
        }
        h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        .welcome-note {
            font-size: 1.2rem;
            margin-bottom: 30px;
            line-height: 1.6;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }
        .image-gallery {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin: 20px 0;
            gap: 20px;
            width: 100%;
        }
        .image-gallery img {
            width: 100%;
            max-width: 350px;
            height: 250px;
            object-fit: cover;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            transition: transform 0.3s ease;
        }
        .image-gallery img:hover {
            transform: scale(1.03);
        }
        nav {
            background-color: #333;
            padding: 15px 0;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        .nav-links {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            transition: all 0.3s ease;
            font-weight: bold;
            white-space: nowrap;
        }
        nav a:hover {
            background-color: #4CAF50;
            border-radius: 3px;
            transform: translateY(-2px);
        }
        footer {
            text-align: center;
            padding: 20px 0;
            background-color: #333;
            width: 100%;
            margin-top: auto;
        }
        footer p {
            margin: 0;
            font-size: 0.9rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            h1 {
                font-size: 1.8rem;
            }
            .welcome-note {
                font-size: 1rem;
                padding: 0 15px;
            }
            .nav-links {
                flex-direction: column;
                gap: 5px;
                padding: 0 10px;
            }
            nav a {
                width: 100%;
                text-align: center;
                padding: 10px;
            }
            .image-gallery {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }
            .image-gallery img {
                height: 200px;
                max-width: 90%;
            }
            header {
                margin-top: 120px; /* More space for stacked nav items */
            }
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
            <a href="admin-login.php">Admin</a>
            <a href="about.php">About Us</a>
        </div>
    </nav>
    
    <div class="container">
        <div class="content-wrapper">
            <header>
                <h1>BODABODA ONLINE STICKERS SYSTEM</h1>
                <p class="welcome-note">Welcome to our online platform for bodaboda sticker registration and management. Get your official sticker today and enjoy seamless operations across the county/ country.</p>
            </header>
            
            <div class="image-gallery">
                <!-- Bodaboda Bike Image from Unsplash -->
                <img src="https://images.unsplash.com/photo-1558981806-ec527fa84c39?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Bodaboda Bike">
                
                <!-- Bodaboda Rider Image from Pexels -->
                <img src="https://images.unsplash.com/photo-1571607388263-9c9d476942b1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Bodaboda Rider">
                
                <!-- Motorcycle Sticker Image from Pexels -->
                <img src="https://images.unsplash.com/photo-1601758003122-53c40e686a19?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Sticker Sample">
            </div>
        </div>
    </div>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> BODABODA ONLINE STICKERS. All rights reserved.</p>
    </footer>
</body>
</html>
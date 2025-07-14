<?php
require_once 'db.php';
$db = new Database();
$categories = $db->getCategories();
$featuredArticles = $db->getFeaturedArticles(3);
$breakingNews = $db->getBreakingNews(5);
$recentArticles = $db->getRecentArticles(6);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global News Network - Breaking News & Latest Updates</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header Styles */
        .header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .logo {
            font-size: 2.5rem;
            font-weight: bold;
            text-decoration: none;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .search-container {
            position: relative;
        }
        
        .search-box {
            padding: 12px 45px 12px 15px;
            border: none;
            border-radius: 25px;
            width: 300px;
            font-size: 14px;
            outline: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: #ff6b35;
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-btn:hover {
            background: #e55a2b;
            transform: translateY(-50%) scale(1.1);
        }
        
        /* Navigation */
        .nav {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 0.5rem;
        }
        
        .nav-list {
            display: flex;
            list-style: none;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .nav-item a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }
        
        .nav-item a:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
        
        /* Breaking News Banner */
        .breaking-banner {
            background: linear-gradient(90deg, #ff4757, #ff3838);
            color: white;
            padding: 15px 0;
            overflow: hidden;
            position: relative;
        }
        
        .breaking-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .breaking-label {
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            flex-shrink: 0;
        }
        
        .breaking-text {
            font-size: 1.1rem;
            font-weight: 500;
            animation: scroll-left 30s linear infinite;
        }
        
        @keyframes scroll-left {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        
        /* Main Content */
        .main-content {
            padding: 2rem 0;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        /* Featured Articles */
        .featured-section h2 {
            color: #1e3c72;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            position: relative;
            padding-bottom: 10px;
        }
        
        .featured-section h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #ff6b35, #f7931e);
            border-radius: 2px;
        }
        
        .featured-grid {
            display: grid;
            gap: 1.5rem;
        }
        
        .featured-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .featured-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .featured-card.main {
            grid-column: 1 / -1;
        }
        
        .featured-card.main .card-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
        }
        
        .card-image {
            position: relative;
            overflow: hidden;
        }
        
        .card-image img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .featured-card:hover .card-image img {
            transform: scale(1.05);
        }
        
        .card-category {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .card-info {
            padding: 1.5rem;
        }
        
        .card-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #1e3c72;
            margin-bottom: 0.8rem;
            line-height: 1.4;
        }
        
        .card-excerpt {
            color: #666;
            margin-bottom: 1rem;
            line-height: 1.6;
        }
        
        .card-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            color: #888;
        }
        
        .card-author {
            font-weight: 500;
            color: #1e3c72;
        }
        
        /* Sidebar */
        .sidebar {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 2rem;
        }
        
        .sidebar h3 {
            color: #1e3c72;
            margin-bottom: 1rem;
            font-size: 1.3rem;
            position: relative;
            padding-bottom: 8px;
        }
        
        .sidebar h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, #ff6b35, #f7931e);
            border-radius: 2px;
        }
        
        .trending-item {
            display: flex;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .trending-item:hover {
            background: #f8f9fa;
            margin: 0 -1rem;
            padding: 1rem;
            border-radius: 8px;
        }
        
        .trending-item:last-child {
            border-bottom: none;
        }
        
        .trending-image {
            width: 80px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
        }
        
        .trending-content h4 {
            font-size: 0.95rem;
            color: #1e3c72;
            line-height: 1.4;
            margin-bottom: 0.5rem;
        }
        
        .trending-meta {
            font-size: 0.8rem;
            color: #888;
        }
        
        /* Recent Articles Section */
        .recent-section {
            margin-top: 3rem;
        }
        
        .recent-section h2 {
            color: #1e3c72;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            text-align: center;
            position: relative;
            padding-bottom: 10px;
        }
        
        .recent-section h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #ff6b35, #f7931e);
            border-radius: 2px;
        }
        
        .recent-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
        }
        
        .recent-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .recent-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }
        
        .recent-card .card-image img {
            height: 200px;
        }
        
        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }
        
        .footer-content {
            text-align: center;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }
        
        .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: #ff6b35;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }
            
            .header-top {
                flex-direction: column;
                gap: 1rem;
            }
            
            .search-box {
                width: 250px;
            }
            
            .nav-list {
                justify-content: center;
            }
            
            .content-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .featured-card.main .card-content {
                grid-template-columns: 1fr;
            }
            
            .recent-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-links {
                flex-direction: column;
                gap: 1rem;
            }
        }
        
        @media (max-width: 480px) {
            .logo {
                font-size: 2rem;
            }
            
            .search-box {
                width: 200px;
            }
            
            .nav-item a {
                padding: 8px 12px;
                font-size: 0.8rem;
            }
            
            .card-title {
                font-size: 1.1rem;
            }
            
            .recent-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-top">
                <a href="index.php" class="logo">Global News Network</a>
                <div class="search-container">
                    <form action="search.php" method="GET">
                        <input type="text" name="q" class="search-box" placeholder="Search news..." required>
                        <button type="submit" class="search-btn">🔍</button>
                    </form>
                </div>
            </div>
            
            <nav class="nav">
                <ul class="nav-list">
                    <li class="nav-item"><a href="index.php">Home</a></li>
                    <?php foreach ($categories as $category): ?>
                        <li class="nav-item">
                            <a href="#" onclick="navigateToCategory('<?php echo $category['slug']; ?>')"><?php echo htmlspecialchars($category['name']); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Breaking News Banner -->
    <?php if (!empty($breakingNews)): ?>
    <div class="breaking-banner">
        <div class="container">
            <div class="breaking-content">
                <span class="breaking-label">Breaking News</span>
                <div class="breaking-text">
                    <?php 
                    $breakingTitles = array_map(function($article) {
                        return htmlspecialchars($article['title']);
                    }, $breakingNews);
                    echo implode(' • ', $breakingTitles);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-grid">
                <!-- Featured Articles -->
                <section class="featured-section">
                    <h2>Featured Stories</h2>
                    <div class="featured-grid">
                        <?php foreach ($featuredArticles as $index => $article): ?>
                            <article class="featured-card <?php echo $index === 0 ? 'main' : ''; ?>" onclick="navigateToArticle('<?php echo $article['slug']; ?>')">
                                <div class="card-content">
                                    <div class="card-image">
                                        <img src="<?php echo $article['featured_image'] ?: '/placeholder.svg?height=250&width=400'; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                                        <span class="card-category"><?php echo htmlspecialchars($article['category_name']); ?></span>
                                    </div>
                                    <div class="card-info">
                                        <h3 class="card-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                                        <p class="card-excerpt"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                                        <div class="card-meta">
                                            <span class="card-author">By <?php echo htmlspecialchars($article['author']); ?></span>
                                            <span class="card-date"><?php echo date('M j, Y', strtotime($article['created_at'])); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- Sidebar -->
                <aside class="sidebar">
                    <h3>Trending Now</h3>
                    <?php foreach ($breakingNews as $article): ?>
                        <div class="trending-item" onclick="navigateToArticle('<?php echo $article['slug']; ?>')">
                            <img src="<?php echo $article['featured_image'] ?: '/placeholder.svg?height=60&width=80'; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="trending-image">
                            <div class="trending-content">
                                <h4><?php echo htmlspecialchars(substr($article['title'], 0, 80)) . (strlen($article['title']) > 80 ? '...' : ''); ?></h4>
                                <div class="trending-meta">
                                    <?php echo htmlspecialchars($article['category_name']); ?> • <?php echo date('M j', strtotime($article['created_at'])); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </aside>
            </div>

            <!-- Recent Articles -->
            <section class="recent-section">
                <h2>Latest News</h2>
                <div class="recent-grid">
                    <?php foreach ($recentArticles as $article): ?>
                        <article class="recent-card" onclick="navigateToArticle('<?php echo $article['slug']; ?>')">
                            <div class="card-image">
                                <img src="<?php echo $article['featured_image'] ?: '/placeholder.svg?height=200&width=350'; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                                <span class="card-category"><?php echo htmlspecialchars($article['category_name']); ?></span>
                            </div>
                            <div class="card-info">
                                <h3 class="card-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                                <p class="card-excerpt"><?php echo htmlspecialchars(substr($article['excerpt'], 0, 120)) . '...'; ?></p>
                                <div class="card-meta">
                                    <span class="card-author">By <?php echo htmlspecialchars($article['author']); ?></span>
                                    <span class="card-date"><?php echo date('M j, Y', strtotime($article['created_at'])); ?></span>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-links">
                    <a href="#" onclick="navigateToPage('about')">About Us</a>
                    <a href="#" onclick="navigateToPage('contact')">Contact</a>
                    <a href="#" onclick="navigateToPage('privacy')">Privacy Policy</a>
                    <a href="#" onclick="navigateToPage('terms')">Terms of Service</a>
                </div>
                <p>&copy; 2024 Global News Network. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Navigation functions
        function navigateToCategory(categorySlug) {
            window.location.href = `category.php?category=${categorySlug}`;
        }
        
        function navigateToArticle(articleSlug) {
            window.location.href = `article.php?slug=${articleSlug}`;
        }
        
        function navigateToPage(page) {
            window.location.href = `${page}.php`;
        }
        
        // Search functionality
        document.querySelector('.search-box').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = this.value.trim();
                if (query) {
                    window.location.href = `search.php?q=${encodeURIComponent(query)}`;
                }
            }
        });
        
        // Smooth scrolling for internal links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Add loading animation for cards
        document.querySelectorAll('.featured-card, .recent-card, .trending-item').forEach(card => {
            card.addEventListener('click', function() {
                this.style.opacity = '0.7';
                this.style.transform = 'scale(0.98)';
            });
        });
        
        // Lazy loading for images
        const images = document.querySelectorAll('img');
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.style.opacity = '0';
                    img.style.transition = 'opacity 0.3s ease';
                    img.onload = () => {
                        img.style.opacity = '1';
                    };
                    observer.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    </script>
</body>
</html>

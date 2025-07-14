<?php
require_once 'db.php';
$db = new Database();

$categorySlug = $_GET['category'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 12;
$offset = ($page - 1) * $limit;

if (!$categorySlug) {
    header('Location: index.php');
    exit;
}

$categories = $db->getCategories();
$articles = $db->getArticlesByCategory($categorySlug, $limit, $offset);
$currentCategory = null;

foreach ($categories as $cat) {
    if ($cat['slug'] === $categorySlug) {
        $currentCategory = $cat;
        break;
    }
}

if (!$currentCategory) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($currentCategory['name']); ?> News - Global News Network</title>
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
        
        .nav-item a:hover,
        .nav-item a.active {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
        
        /* Breadcrumb */
        .breadcrumb {
            background: white;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .breadcrumb-list {
            display: flex;
            list-style: none;
            gap: 0.5rem;
            align-items: center;
        }
        
        .breadcrumb-item a {
            color: #666;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .breadcrumb-item a:hover {
            color: #1e3c72;
        }
        
        .breadcrumb-item.active {
            color: #1e3c72;
            font-weight: 500;
        }
        
        .breadcrumb-separator {
            color: #ccc;
        }
        
        /* Category Header */
        .category-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
            text-align: center;
        }
        
        .category-title {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .category-description {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Articles Grid */
        .articles-section {
            padding: 3rem 0;
        }
        
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .article-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .article-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .card-image {
            position: relative;
            overflow: hidden;
        }
        
        .card-image img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .article-card:hover .card-image img {
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
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }
        
        .pagination a,
        .pagination span {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-decoration: none;
            color: #666;
            transition: all 0.3s ease;
        }
        
        .pagination a:hover {
            background: #1e3c72;
            color: white;
            border-color: #1e3c72;
        }
        
        .pagination .current {
            background: #1e3c72;
            color: white;
            border-color: #1e3c72;
        }
        
        /* No Articles Message */
        .no-articles {
            text-align: center;
            padding: 3rem 0;
            color: #666;
        }
        
        .no-articles h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #1e3c72;
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
            
            .category-title {
                font-size: 2rem;
            }
            
            .articles-grid {
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
            
            .category-title {
                font-size: 1.8rem;
            }
            
            .card-title {
                font-size: 1.1rem;
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
                            <a href="#" onclick="navigateToCategory('<?php echo $category['slug']; ?>')" 
                               class="<?php echo $category['slug'] === $categorySlug ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <div class="container">
            <ul class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-separator">›</li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($currentCategory['name']); ?></li>
            </ul>
        </div>
    </nav>

    <!-- Category Header -->
    <section class="category-header">
        <div class="container">
            <h1 class="category-title"><?php echo htmlspecialchars($currentCategory['name']); ?></h1>
            <p class="category-description"><?php echo htmlspecialchars($currentCategory['description']); ?></p>
        </div>
    </section>

    <!-- Articles Section -->
    <main class="articles-section">
        <div class="container">
            <?php if (!empty($articles)): ?>
                <div class="articles-grid">
                    <?php foreach ($articles as $article): ?>
                        <article class="article-card" onclick="navigateToArticle('<?php echo $article['slug']; ?>')">
                            <div class="card-image">
                                <img src="<?php echo $article['featured_image'] ?: '/placeholder.svg?height=220&width=350'; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                                <span class="card-category"><?php echo htmlspecialchars($article['category_name']); ?></span>
                            </div>
                            <div class="card-info">
                                <h2 class="card-title"><?php echo htmlspecialchars($article['title']); ?></h2>
                                <p class="card-excerpt"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                                <div class="card-meta">
                                    <span class="card-author">By <?php echo htmlspecialchars($article['author']); ?></span>
                                    <span class="card-date"><?php echo date('M j, Y', strtotime($article['created_at'])); ?></span>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?category=<?php echo $categorySlug; ?>&page=<?php echo $page - 1; ?>">‹ Previous</a>
                    <?php endif; ?>
                    
                    <span class="current"><?php echo $page; ?></span>
                    
                    <?php if (count($articles) === $limit): ?>
                        <a href="?category=<?php echo $categorySlug; ?>&page=<?php echo $page + 1; ?>">Next ›</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="no-articles">
                    <h3>No articles found</h3>
                    <p>There are currently no articles in the <?php echo htmlspecialchars($currentCategory['name']); ?> category.</p>
                </div>
            <?php endif; ?>
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
        
        // Add loading animation for cards
        document.querySelectorAll('.article-card').forEach(card => {
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

<?php
require_once 'db.php';
$db = new Database();

$query = trim($_GET['q'] ?? '');
$categories = $db->getCategories();
$searchResults = [];

if ($query) {
    $searchResults = $db->searchArticles($query, 20);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $query ? 'Search Results for "' . htmlspecialchars($query) . '"' : 'Search'; ?> - Global News Network</title>
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
        
        /* Search Results */
        .search-section {
            padding: 3rem 0;
        }
        
        .search-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .search-title {
            font-size: 2.5rem;
            color: #1e3c72;
            margin-bottom: 1rem;
        }
        
        .search-subtitle {
            font-size: 1.2rem;
            color: #666;
        }
        
        .results-count {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 25px;
            display: inline-block;
            margin-bottom: 2rem;
            font-weight: 500;
        }
        
        .search-results {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }
        
        .result-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .result-card:hover {
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
        
        .result-card:hover .card-image img {
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
        
        /* No Results */
        .no-results {
            text-align: center;
            padding: 4rem 0;
            color: #666;
        }
        
        .no-results h3 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #1e3c72;
        }
        
        .no-results p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }
        
        .search-suggestions {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .suggestions-title {
            color: #1e3c72;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }
        
        .suggestions-list {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .suggestion-item {
            background: #f8f9fa;
            padding: 8px 16px;
            border-radius: 20px;
            border: 2px solid #eee;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .suggestion-item:hover {
            background: #1e3c72;
            color: white;
            border-color: #1e3c72;
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
            
            .search-title {
                font-size: 2rem;
            }
            
            .search-results {
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
            
            .search-title {
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
                        <input type="text" name="q" class="search-box" placeholder="Search news..." value="<?php echo htmlspecialchars($query); ?>" required>
                        <button type="submit" class="search-btn">🔍</button>
                    </form>
                </div>
            </div>
            
            <nav class="nav">
                <ul class="nav-list">
                    <li class="nav-item"><a href="index.php">Home</a></li>
                    <?php foreach ($categories as $category): ?>
                        <li class="nav-item">
                            <a href="#" onclick="navigateToCategory('<?php echo $category['slug']; ?>')">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Search Results Section -->
    <main class="search-section">
        <div class="container">
            <?php if ($query): ?>
                <div class="search-header">
                    <h1 class="search-title">Search Results</h1>
                    <p class="search-subtitle">Results for "<?php echo htmlspecialchars($query); ?>"</p>
                    <div class="results-count">
                        Found <?php echo count($searchResults); ?> article<?php echo count($searchResults) !== 1 ? 's' : ''; ?>
                    </div>
                </div>
                
                <?php if (!empty($searchResults)): ?>
                    <div class="search-results">
                        <?php foreach ($searchResults as $article): ?>
                            <article class="result-card" onclick="navigateToArticle('<?php echo $article['slug']; ?>')">
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
                <?php else: ?>
                    <div class="no-results">
                        <h3>No results found</h3>
                        <p>We couldn't find any articles matching "<?php echo htmlspecialchars($query); ?>"</p>
                        
                        <div class="search-suggestions">
                            <h4 class="suggestions-title">Try searching for:</h4>
                            <ul class="suggestions-list">
                                <li class="suggestion-item" onclick="searchFor('breaking news')">Breaking News</li>
                                <li class="suggestion-item" onclick="searchFor('politics')">Politics</li>
                                <li class="suggestion-item" onclick="searchFor('technology')">Technology</li>
                                <li class="suggestion-item" onclick="searchFor('sports')">Sports</li>
                                <li class="suggestion-item" onclick="searchFor('business')">Business</li>
                                <li class="suggestion-item" onclick="searchFor('health')">Health</li>
                                <li class="suggestion-item" onclick="searchFor('science')">Science</li>
                                <li class="suggestion-item" onclick="searchFor('entertainment')">Entertainment</li>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="search-header">
                    <h1 class="search-title">Search News</h1>
                    <p class="search-subtitle">Enter a keyword to search for articles</p>
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
        
        function searchFor(query) {
            window.location.href = `search.php?q=${encodeURIComponent(query)}`;
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
        document.querySelectorAll('.result-card').forEach(card => {
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
        
        // Auto-focus search box if no query
        <?php if (!$query): ?>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.search-box').focus();
        });
        <?php endif; ?>
    </script>
</body>
</html>

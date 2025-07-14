<?php
require_once 'db.php';
$db = new Database();

$slug = $_GET['slug'] ?? '';
if (!$slug) {
    header('Location: index.php');
    exit;
}

$article = $db->getArticle($slug);
if (!$article) {
    header('Location: index.php');
    exit;
}

// Update article views
$db->updateViews($article['id']);

$categories = $db->getCategories();
$comments = $db->getComments($article['id']);
$recentArticles = $db->getRecentArticles(4);

// Handle comment submission
if ($_POST && isset($_POST['submit_comment'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $comment = trim($_POST['comment'] ?? '');
    
    if ($name && $email && $comment) {
        if ($db->addComment($article['id'], $name, $email, $comment)) {
            $success_message = "Your comment has been submitted and is awaiting approval.";
        } else {
            $error_message = "Failed to submit comment. Please try again.";
        }
    } else {
        $error_message = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> - Global News Network</title>
    <meta name="description" content="<?php echo htmlspecialchars($article['excerpt']); ?>">
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
            flex-wrap: wrap;
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
        
        /* Article Layout */
        .article-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 3rem;
            padding: 2rem 0;
        }
        
        /* Article Content */
        .article-main {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .article-header {
            position: relative;
        }
        
        .article-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        
        .article-category {
            position: absolute;
            top: 20px;
            left: 20px;
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .article-content {
            padding: 2rem;
        }
        
        .article-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #1e3c72;
            margin-bottom: 1rem;
            line-height: 1.3;
        }
        
        .article-meta {
            display: flex;
            gap: 2rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #eee;
            flex-wrap: wrap;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #666;
            font-size: 0.95rem;
        }
        
        .meta-icon {
            font-size: 1.1rem;
        }
        
        .article-text {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #444;
        }
        
        .article-text p {
            margin-bottom: 1.5rem;
        }
        
        .article-text h2,
        .article-text h3 {
            color: #1e3c72;
            margin: 2rem 0 1rem 0;
        }
        
        /* Social Share */
        .social-share {
            margin: 2rem 0;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 10px;
            text-align: center;
        }
        
        .social-share h3 {
            margin-bottom: 1rem;
            color: #1e3c72;
        }
        
        .social-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .social-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .social-btn.facebook {
            background: #3b5998;
        }
        
        .social-btn.twitter {
            background: #1da1f2;
        }
        
        .social-btn.linkedin {
            background: #0077b5;
        }
        
        .social-btn.whatsapp {
            background: #25d366;
        }
        
        .social-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        /* Comments Section */
        .comments-section {
            margin-top: 3rem;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .comments-title {
            color: #1e3c72;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }
        
        .comment-form {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #1e3c72;
        }
        
        .form-group textarea {
            height: 120px;
            resize: vertical;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 60, 114, 0.3);
        }
        
        .alert {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .comments-list {
            space-y: 1rem;
        }
        
        .comment {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .comment-author {
            font-weight: bold;
            color: #1e3c72;
        }
        
        .comment-date {
            color: #888;
            font-size: 0.9rem;
        }
        
        .comment-content {
            color: #555;
            line-height: 1.6;
        }
        
        /* Sidebar */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }
        
        .sidebar-widget {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .widget-title {
            color: #1e3c72;
            margin-bottom: 1rem;
            font-size: 1.3rem;
            position: relative;
            padding-bottom: 8px;
        }
        
        .widget-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, #ff6b35, #f7931e);
            border-radius: 2px;
        }
        
        .related-article {
            display: flex;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .related-article:hover {
            background: #f8f9fa;
            margin: 0 -1rem;
            padding: 1rem;
            border-radius: 8px;
        }
        
        .related-article:last-child {
            border-bottom: none;
        }
        
        .related-image {
            width: 80px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
        }
        
        .related-content h4 {
            font-size: 0.95rem;
            color: #1e3c72;
            line-height: 1.4;
            margin-bottom: 0.5rem;
        }
        
        .related-meta {
            font-size: 0.8rem;
            color: #888;
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
            
            .article-layout {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .article-title {
                font-size: 2rem;
            }
            
            .article-meta {
                flex-direction: column;
                gap: 1rem;
            }
            
            .social-buttons {
                flex-direction: column;
                align-items: center;
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
            
            .article-title {
                font-size: 1.8rem;
            }
            
            .article-content {
                padding: 1.5rem;
            }
            
            .breadcrumb-list {
                font-size: 0.9rem;
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
                            <a href="#" onclick="navigateToCategory('<?php echo $category['slug']; ?>')">
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
                <li class="breadcrumb-item"><a href="#" onclick="navigateToCategory('<?php echo $article['category_slug']; ?>')"><?php echo htmlspecialchars($article['category_name']); ?></a></li>
                <li class="breadcrumb-separator">›</li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars(substr($article['title'], 0, 50)) . (strlen($article['title']) > 50 ? '...' : ''); ?></li>
            </ul>
        </div>
    </nav>

    <!-- Article Layout -->
    <main class="article-layout container">
        <!-- Article Content -->
        <article class="article-main">
            <div class="article-header">
                <img src="<?php echo $article['featured_image'] ?: '/placeholder.svg?height=400&width=800'; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="article-image">
                <span class="article-category"><?php echo htmlspecialchars($article['category_name']); ?></span>
            </div>
            
            <div class="article-content">
                <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
                
                <div class="article-meta">
                    <div class="meta-item">
                        <span class="meta-icon">👤</span>
                        <span>By <?php echo htmlspecialchars($article['author']); ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-icon">📅</span>
                        <span><?php echo date('F j, Y', strtotime($article['created_at'])); ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-icon">👁️</span>
                        <span><?php echo number_format($article['views']); ?> views</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-icon">⏱️</span>
                        <span><?php echo ceil(str_word_count($article['content']) / 200); ?> min read</span>
                    </div>
                </div>
                
                <div class="article-text">
                    <?php echo nl2br(htmlspecialchars($article['content'])); ?>
                </div>
                
                <!-- Social Share -->
                <div class="social-share">
                    <h3>Share this article</h3>
                    <div class="social-buttons">
                        <a href="#" onclick="shareOnFacebook()" class="social-btn facebook">Facebook</a>
                        <a href="#" onclick="shareOnTwitter()" class="social-btn twitter">Twitter</a>
                        <a href="#" onclick="shareOnLinkedIn()" class="social-btn linkedin">LinkedIn</a>
                        <a href="#" onclick="shareOnWhatsApp()" class="social-btn whatsapp">WhatsApp</a>
                    </div>
                </div>
                
                <!-- Comments Section -->
                <section class="comments-section">
                    <h3 class="comments-title">Comments (<?php echo count($comments); ?>)</h3>
                    
                    <!-- Comment Form -->
                    <form class="comment-form" method="POST">
                        <?php if (isset($success_message)): ?>
                            <div class="alert success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        
                        <?php if (isset($error_message)): ?>
                            <div class="alert error"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="name">Name *</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="comment">Comment *</label>
                            <textarea id="comment" name="comment" placeholder="Share your thoughts..." required></textarea>
                        </div>
                        
                        <button type="submit" name="submit_comment" class="submit-btn">Post Comment</button>
                    </form>
                    
                    <!-- Comments List -->
                    <div class="comments-list">
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment">
                                <div class="comment-header">
                                    <span class="comment-author"><?php echo htmlspecialchars($comment['author_name']); ?></span>
                                    <span class="comment-date"><?php echo date('M j, Y \a\t g:i A', strtotime($comment['created_at'])); ?></span>
                                </div>
                                <div class="comment-content">
                                    <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($comments)): ?>
                            <p style="text-align: center; color: #666; padding: 2rem;">No comments yet. Be the first to comment!</p>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </article>

        <!-- Sidebar -->
        <aside class="sidebar">
            <!-- Related Articles -->
            <div class="sidebar-widget">
                <h3 class="widget-title">Related Articles</h3>
                <?php foreach ($recentArticles as $relatedArticle): ?>
                    <div class="related-article" onclick="navigateToArticle('<?php echo $relatedArticle['slug']; ?>')">
                        <img src="<?php echo $relatedArticle['featured_image'] ?: '/placeholder.svg?height=60&width=80'; ?>" alt="<?php echo htmlspecialchars($relatedArticle['title']); ?>" class="related-image">
                        <div class="related-content">
                            <h4><?php echo htmlspecialchars(substr($relatedArticle['title'], 0, 60)) . (strlen($relatedArticle['title']) > 60 ? '...' : ''); ?></h4>
                            <div class="related-meta">
                                <?php echo htmlspecialchars($relatedArticle['category_name']); ?> • <?php echo date('M j', strtotime($relatedArticle['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </aside>
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
        
        // Social sharing functions
        function shareOnFacebook() {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
        }
        
        function shareOnTwitter() {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
            window.open(`https://twitter.com/intent/tweet?url=${url}&text=${title}`, '_blank', 'width=600,height=400');
        }
        
        function shareOnLinkedIn() {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
            window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, '_blank', 'width=600,height=400');
        }
        
        function shareOnWhatsApp() {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
            window.open(`https://wa.me/?text=${title} ${url}`, '_blank');
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
        
        // Smooth scrolling for comments
        function scrollToComments() {
            document.querySelector('.comments-section').scrollIntoView({
                behavior: 'smooth'
            });
        }
        
        // Form validation
        document.querySelector('.comment-form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const comment = document.getElementById('comment').value.trim();
            
            if (!name || !email || !comment) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                return false;
            }
        });
        
        // Add loading animation for related articles
        document.querySelectorAll('.related-article').forEach(article => {
            article.addEventListener('click', function() {
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

<?php
class Database {
    private $host = 'localhost'; // Try different hosts if this doesn't work
    private $dbname = 'dbkzom6nfkpu4';
    private $username = 'uc7ggok7oyoza';
    private $password = 'gqypavorhbbc';
    private $pdo;
    private $connected = false;
    
    // Fallback sample data
    private $sampleCategories = [
        ['id' => 1, 'name' => 'World', 'slug' => 'world', 'description' => 'International news and global events'],
        ['id' => 2, 'name' => 'Politics', 'slug' => 'politics', 'description' => 'Political news and government updates'],
        ['id' => 3, 'name' => 'Business', 'slug' => 'business', 'description' => 'Business news and market updates'],
        ['id' => 4, 'name' => 'Technology', 'slug' => 'technology', 'description' => 'Tech news and innovations'],
        ['id' => 5, 'name' => 'Sports', 'slug' => 'sports', 'description' => 'Sports news and updates'],
        ['id' => 6, 'name' => 'Entertainment', 'slug' => 'entertainment', 'description' => 'Entertainment and celebrity news'],
        ['id' => 7, 'name' => 'Health', 'slug' => 'health', 'description' => 'Health and medical news'],
        ['id' => 8, 'name' => 'Science', 'slug' => 'science', 'description' => 'Scientific discoveries and research']
    ];
    
    private $sampleArticles = [
        [
            'id' => 1,
            'title' => 'Global Climate Summit Reaches Historic Agreement',
            'slug' => 'global-climate-summit-historic-agreement',
            'content' => 'World leaders have reached a groundbreaking agreement at the Global Climate Summit, marking a significant step forward in international cooperation on environmental issues. The comprehensive deal includes commitments from over 190 countries to reduce carbon emissions by 50% within the next decade. This historic moment represents years of negotiations and diplomatic efforts to address one of the most pressing challenges of our time. The agreement encompasses various sectors including energy, transportation, and industrial processes, with specific targets and timelines for each participating nation. Environmental activists and scientists worldwide have praised this development as a crucial step toward combating climate change and preserving our planet for future generations.',
            'excerpt' => 'World leaders reach groundbreaking climate agreement with commitments from 190+ countries.',
            'featured_image' => '/placeholder.svg?height=400&width=600',
            'author' => 'Sarah Johnson',
            'category_id' => 1,
            'category_name' => 'World',
            'category_slug' => 'world',
            'is_featured' => true,
            'is_breaking' => true,
            'views' => 15420,
            'created_at' => '2024-01-15 10:30:00'
        ],
        [
            'id' => 2,
            'title' => 'Tech Giant Announces Revolutionary AI Breakthrough',
            'slug' => 'tech-giant-ai-breakthrough',
            'content' => 'A major technology company has unveiled its latest artificial intelligence system that promises to revolutionize how we interact with digital devices. The new AI platform demonstrates unprecedented capabilities in natural language processing and problem-solving, potentially transforming industries from healthcare to education. Early demonstrations show the system can perform complex tasks with human-like reasoning and creativity. The breakthrough represents years of research and development in machine learning and neural networks. Industry experts believe this advancement could accelerate the adoption of AI across various sectors, leading to improved efficiency and innovation. The company plans to gradually roll out the technology to developers and enterprise customers over the coming months.',
            'excerpt' => 'Revolutionary AI system promises to transform multiple industries with human-like capabilities.',
            'featured_image' => '/placeholder.svg?height=400&width=600',
            'author' => 'Michael Chen',
            'category_id' => 4,
            'category_name' => 'Technology',
            'category_slug' => 'technology',
            'is_featured' => true,
            'is_breaking' => false,
            'views' => 12890,
            'created_at' => '2024-01-14 14:15:00'
        ],
        [
            'id' => 3,
            'title' => 'Championship Finals Set Record Viewership',
            'slug' => 'championship-finals-record-viewership',
            'content' => 'The championship finals have shattered all previous viewership records, with over 500 million people tuning in worldwide. The thrilling match showcased exceptional athleticism and sportsmanship, keeping viewers on the edge of their seats until the final moments. This unprecedented global audience demonstrates the unifying power of sports and the growing international interest in the championship. The event featured cutting-edge broadcast technology, including multiple camera angles and real-time statistics, enhancing the viewing experience for fans around the world. Social media engagement reached new heights, with millions of posts and interactions throughout the event. The success of this championship is expected to influence future sporting events and broadcasting strategies.',
            'excerpt' => 'Championship finals break viewership records with 500+ million global viewers.',
            'featured_image' => '/placeholder.svg?height=400&width=600',
            'author' => 'David Rodriguez',
            'category_id' => 5,
            'category_name' => 'Sports',
            'category_slug' => 'sports',
            'is_featured' => false,
            'is_breaking' => false,
            'views' => 8750,
            'created_at' => '2024-01-13 20:45:00'
        ],
        [
            'id' => 4,
            'title' => 'Stock Markets Surge Following Economic Report',
            'slug' => 'stock-markets-surge-economic-report',
            'content' => 'Global stock markets experienced significant gains following the release of positive economic indicators. The comprehensive report showed stronger than expected growth across multiple sectors, boosting investor confidence and driving market optimism. Financial analysts predict continued positive momentum as economic fundamentals remain strong and corporate earnings exceed expectations. The report highlighted improvements in employment rates, consumer spending, and industrial production. Major indices reached new highs, with technology and healthcare sectors leading the gains. International markets also responded positively, reflecting the interconnected nature of the global economy. Economists suggest that this trend could continue if current policies and market conditions remain stable.',
            'excerpt' => 'Markets rally on positive economic data showing stronger than expected growth.',
            'featured_image' => '/placeholder.svg?height=400&width=600',
            'author' => 'Emily Watson',
            'category_id' => 3,
            'category_name' => 'Business',
            'category_slug' => 'business',
            'is_featured' => false,
            'is_breaking' => true,
            'views' => 6420,
            'created_at' => '2024-01-12 09:20:00'
        ],
        [
            'id' => 5,
            'title' => 'Breakthrough Medical Treatment Shows Promise',
            'slug' => 'breakthrough-medical-treatment-promise',
            'content' => 'Researchers have announced promising results from clinical trials of a revolutionary medical treatment that could transform patient care. The innovative therapy has shown remarkable success rates in early trials, offering hope to millions of patients worldwide. Medical experts are calling it one of the most significant advances in treatment methodology in recent decades. The treatment utilizes cutting-edge biotechnology and personalized medicine approaches to target specific conditions more effectively than traditional methods. Clinical trials involved patients from diverse backgrounds and showed consistent positive outcomes across different demographics. The research team is now preparing for larger-scale trials and regulatory approval processes. If successful, the treatment could be available to patients within the next few years.',
            'excerpt' => 'Revolutionary medical treatment shows remarkable success in clinical trials.',
            'featured_image' => '/placeholder.svg?height=400&width=600',
            'author' => 'Dr. James Wilson',
            'category_id' => 7,
            'category_name' => 'Health',
            'category_slug' => 'health',
            'is_featured' => true,
            'is_breaking' => false,
            'views' => 9340,
            'created_at' => '2024-01-11 16:30:00'
        ],
        [
            'id' => 6,
            'title' => 'Space Mission Discovers New Planetary System',
            'slug' => 'space-mission-discovers-planetary-system',
            'content' => 'A groundbreaking space mission has discovered a new planetary system that could provide insights into the formation of our universe. The discovery includes several planets with characteristics similar to Earth, raising possibilities for future exploration and research. Scientists are analyzing data collected from advanced telescopes and space probes to better understand these celestial bodies. The planetary system is located in a region of space that was previously unexplored, demonstrating the vast potential for future discoveries. This finding contributes to our growing knowledge of exoplanets and their potential for supporting life. The research team plans to continue monitoring the system and gathering additional data over the coming years.',
            'excerpt' => 'New planetary system discovery opens possibilities for future space exploration.',
            'featured_image' => '/placeholder.svg?height=400&width=600',
            'author' => 'Dr. Lisa Chang',
            'category_id' => 8,
            'category_name' => 'Science',
            'category_slug' => 'science',
            'is_featured' => false,
            'is_breaking' => false,
            'views' => 7890,
            'created_at' => '2024-01-10 11:15:00'
        ]
    ];
    
    private $sampleComments = [
        [
            'id' => 1,
            'article_id' => 1,
            'author_name' => 'John Smith',
            'author_email' => 'john@example.com',
            'content' => 'This is a significant step forward for global environmental cooperation. Hopefully, all countries will follow through on their commitments.',
            'status' => 'approved',
            'created_at' => '2024-01-15 12:45:00'
        ],
        [
            'id' => 2,
            'article_id' => 1,
            'author_name' => 'Maria Garcia',
            'author_email' => 'maria@example.com',
            'content' => 'Great news! It\'s about time world leaders took decisive action on climate change. This gives me hope for the future.',
            'status' => 'approved',
            'created_at' => '2024-01-15 14:20:00'
        ],
        [
            'id' => 3,
            'article_id' => 2,
            'author_name' => 'Tech Enthusiast',
            'author_email' => 'tech@example.com',
            'content' => 'The AI breakthrough sounds incredible! Can\'t wait to see how this technology will be implemented in real-world applications.',
            'status' => 'approved',
            'created_at' => '2024-01-14 16:30:00'
        ]
    ];
    
    public function __construct() {
        $this->attemptConnection();
    }
    
    private function attemptConnection() {
        // Try different host configurations
        $hosts = ['localhost', '127.0.0.1', $this->host];
        
        foreach ($hosts as $host) {
            try {
                $this->pdo = new PDO(
                    "mysql:host={$host};dbname={$this->dbname};charset=utf8mb4",
                    $this->username,
                    $this->password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::ATTR_TIMEOUT => 5
                    ]
                );
                $this->connected = true;
                break;
            } catch (PDOException $e) {
                // Continue to next host
                continue;
            }
        }
        
        // If still not connected, show debug info but continue with sample data
        if (!$this->connected) {
            error_log("Database connection failed. Using sample data. Error details: " . $e->getMessage());
        }
    }
    
    public function getConnection() {
        return $this->connected ? $this->pdo : null;
    }
    
    public function isConnected() {
        return $this->connected;
    }
    
    // Get all categories
    public function getCategories() {
        if ($this->connected) {
            try {
                $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY name");
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                error_log("Database query failed: " . $e->getMessage());
            }
        }
        return $this->sampleCategories;
    }
    
    // Get featured articles
    public function getFeaturedArticles($limit = 3) {
        if ($this->connected) {
            try {
                $stmt = $this->pdo->prepare("
                    SELECT a.*, c.name as category_name, c.slug as category_slug 
                    FROM articles a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    WHERE a.is_featured = 1 AND a.status = 'published' 
                    ORDER BY a.created_at DESC 
                    LIMIT ?
                ");
                $stmt->execute([$limit]);
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                error_log("Database query failed: " . $e->getMessage());
            }
        }
        
        $featured = array_filter($this->sampleArticles, function($article) {
            return $article['is_featured'];
        });
        return array_slice($featured, 0, $limit);
    }
    
    // Get breaking news
    public function getBreakingNews($limit = 5) {
        if ($this->connected) {
            try {
                $stmt = $this->pdo->prepare("
                    SELECT a.*, c.name as category_name, c.slug as category_slug 
                    FROM articles a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    WHERE a.is_breaking = 1 AND a.status = 'published' 
                    ORDER BY a.created_at DESC 
                    LIMIT ?
                ");
                $stmt->execute([$limit]);
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                error_log("Database query failed: " . $e->getMessage());
            }
        }
        
        $breaking = array_filter($this->sampleArticles, function($article) {
            return $article['is_breaking'];
        });
        return array_slice($breaking, 0, $limit);
    }
    
    // Get articles by category
    public function getArticlesByCategory($categorySlug, $limit = 10, $offset = 0) {
        if ($this->connected) {
            try {
                $stmt = $this->pdo->prepare("
                    SELECT a.*, c.name as category_name, c.slug as category_slug 
                    FROM articles a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    WHERE c.slug = ? AND a.status = 'published' 
                    ORDER BY a.created_at DESC 
                    LIMIT ? OFFSET ?
                ");
                $stmt->execute([$categorySlug, $limit, $offset]);
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                error_log("Database query failed: " . $e->getMessage());
            }
        }
        
        $categoryArticles = array_filter($this->sampleArticles, function($article) use ($categorySlug) {
            return $article['category_slug'] === $categorySlug;
        });
        return array_slice($categoryArticles, $offset, $limit);
    }
    
    // Get single article
    public function getArticle($slug) {
        if ($this->connected) {
            try {
                $stmt = $this->pdo->prepare("
                    SELECT a.*, c.name as category_name, c.slug as category_slug 
                    FROM articles a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    WHERE a.slug = ? AND a.status = 'published'
                ");
                $stmt->execute([$slug]);
                return $stmt->fetch();
            } catch (PDOException $e) {
                error_log("Database query failed: " . $e->getMessage());
            }
        }
        
        foreach ($this->sampleArticles as $article) {
            if ($article['slug'] === $slug) {
                return $article;
            }
        }
        return false;
    }
    
    // Search articles
    public function searchArticles($query, $limit = 10) {
        if ($this->connected) {
            try {
                $searchTerm = "%{$query}%";
                $stmt = $this->pdo->prepare("
                    SELECT a.*, c.name as category_name, c.slug as category_slug 
                    FROM articles a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    WHERE (a.title LIKE ? OR a.content LIKE ? OR a.excerpt LIKE ?) 
                    AND a.status = 'published' 
                    ORDER BY a.created_at DESC 
                    LIMIT ?
                ");
                $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $limit]);
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                error_log("Database query failed: " . $e->getMessage());
            }
        }
        
        $results = array_filter($this->sampleArticles, function($article) use ($query) {
            return stripos($article['title'], $query) !== false || 
                   stripos($article['content'], $query) !== false || 
                   stripos($article['excerpt'], $query) !== false;
        });
        return array_slice($results, 0, $limit);
    }
    
    // Get recent articles
    public function getRecentArticles($limit = 6) {
        if ($this->connected) {
            try {
                $stmt = $this->pdo->prepare("
                    SELECT a.*, c.name as category_name, c.slug as category_slug 
                    FROM articles a 
                    LEFT JOIN categories c ON a.category_id = c.id 
                    WHERE a.status = 'published' 
                    ORDER BY a.created_at DESC 
                    LIMIT ?
                ");
                $stmt->execute([$limit]);
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                error_log("Database query failed: " . $e->getMessage());
            }
        }
        
        return array_slice($this->sampleArticles, 0, $limit);
    }
    
    // Add comment
    public function addComment($articleId, $authorName, $authorEmail, $content) {
        if ($this->connected) {
            try {
                $stmt = $this->pdo->prepare("
                    INSERT INTO comments (article_id, author_name, author_email, content) 
                    VALUES (?, ?, ?, ?)
                ");
                return $stmt->execute([$articleId, $authorName, $authorEmail, $content]);
            } catch (PDOException $e) {
                error_log("Database query failed: " . $e->getMessage());
                return false;
            }
        }
        
        // For demo purposes, return true (comment would be added to sample data in real implementation)
        return true;
    }
    
    // Get comments for article
    public function getComments($articleId) {
        if ($this->connected) {
            try {
                $stmt = $this->pdo->prepare("
                    SELECT * FROM comments 
                    WHERE article_id = ? AND status = 'approved' 
                    ORDER BY created_at DESC
                ");
                $stmt->execute([$articleId]);
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                error_log("Database query failed: " . $e->getMessage());
            }
        }
        
        return array_filter($this->sampleComments, function($comment) use ($articleId) {
            return $comment['article_id'] == $articleId && $comment['status'] === 'approved';
        });
    }
    
    // Update article views
    public function updateViews($articleId) {
        if ($this->connected) {
            try {
                $stmt = $this->pdo->prepare("UPDATE articles SET views = views + 1 WHERE id = ?");
                return $stmt->execute([$articleId]);
            } catch (PDOException $e) {
                error_log("Database query failed: " . $e->getMessage());
                return false;
            }
        }
        
        // For demo purposes, return true
        return true;
    }
    
    // Debug method to show connection status
    public function getDebugInfo() {
        return [
            'connected' => $this->connected,
            'host' => $this->host,
            'database' => $this->dbname,
            'username' => $this->username,
            'using_sample_data' => !$this->connected
        ];
    }
}
?>

<?php
require_once 'db.inc.php';

// Fetch blog posts with associated username from the database
function getBlogPosts($limit = 10) {
    global $pdo;
    // Join blog and user tables to get username
    $stmt = $pdo->prepare("
        SELECT blog.*, users.username 
        FROM blog 
        JOIN users ON blog.user_id = users.user_id 
        ORDER BY date DESC 
        LIMIT :limit
    ");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$blogPosts = getBlogPosts();
?>


<style>
    .blog-post {
        background-color: var(--background-color);
        border: 1px solid #e1e4e8;
        border-radius: 6px;
        padding: 16px;
        margin-bottom: 16px;
        transition: all 0.3s ease;
    }
    .blog-post:hover {
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        transform: translateY(-3px);
    }
    .blog-post-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 8px;
        color: #24292e;
    }
    .blog-post-meta {
        font-size: 0.875rem;
        color: #586069;
        margin-bottom: 12px;
    }
    .blog-post-excerpt {
        font-size: 0.9rem;
        color: #24292e;
        margin-bottom: 12px;
    }
    .blog-post-link {
        color: #0366d6;
        text-decoration: none;
        font-weight: 500;
    }
    .blog-post-link:hover {
        text-decoration: underline;
    }
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 24px;
    }
    .pagination-item {
        padding: 8px 12px;
        border: 1px solid #e1e4e8;
        margin: 0 4px;
        border-radius: 4px;
        color: var(--text-color);
        text-decoration: none;
    }
    .pagination-item.active {
        background-color: var(--primary-color);
        color: white;
    }
</style>
<main class="flex-grow p-8">
<section id="blog-posts">
    <h2 class="text-3xl font-bold mb-6">Latest Blog Posts</h2>
    <?php foreach ($blogPosts as $post): ?>
        <article class="blog-post mb-4 p-4 border-b">
            <h3 class="blog-post-title text-2xl font-semibold"><?= htmlspecialchars($post['title']) ?></h3>
            <div class="blog-post-meta text-gray-600 mb-2">
                <span><?= date('F j, Y', strtotime($post['date'])) ?></span>
                <span> • </span>
                <span><?= htmlspecialchars($post['username']) ?></span> <!-- Display the username -->
            </div>
            <div class="blog-post-excerpt">
                <p>
                    <?= htmlspecialchars(trim(strip_tags(substr($post['content'], 0, 150)))) ?>...
                </p>
            </div>
            <a href="post.php?id=<?= $post['blog_id'] ?>" class="blog-post-link text-blue-500 underline">Read more</a>
        </article>
    <?php endforeach; ?>
</section>



    <div class="pagination">
        <a href="#" class="pagination-item">Previous</a>
        <a href="#" class="pagination-item active">1</a>
        <a href="#" class="pagination-item">2</a>
        <a href="#" class="pagination-item">3</a>
        <a href="#" class="pagination-item">Next</a>
    </div>
</main>


<script>
    // Initialize Feather icons
    feather.replace();

    // Additional JavaScript for the blog page, if needed
    document.getElementById('fab').addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Add hover effect to blog posts
    document.querySelectorAll('.blog-post').forEach(post => {
        post.addEventListener('mouseenter', () => {
            post.style.backgroundColor = '#f6f8fa';
        });
        post.addEventListener('mouseleave', () => {
            post.style.backgroundColor = 'var(--background-color)';
        });
    });
</script>
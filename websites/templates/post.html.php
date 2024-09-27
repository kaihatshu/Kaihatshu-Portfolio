<?php
require_once 'db.inc.php';

// Fetch blog post by ID
function getBlogPost($id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT blog.*, users.username 
        FROM blog 
        JOIN users ON blog.user_id = users.user_id 
        WHERE blog.blog_id = :id
    ");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Check if ID is passed and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $blogPost = getBlogPost($_GET['id']);

    // Check if the blog post exists
    if (!$blogPost) {
        die('Blog post not found.');
    }
} else {
    die('Invalid blog post ID.');
}
?>

<style>
    body {
        font-family: 'Georgia', serif;
        line-height: 1.8;
        background-color: #fafafa;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .content-area {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .hero-section {
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        height: 400px; /* Fixed height */
    }

    .hero-image {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Makes the image auto-adjust */
        border-radius: 8px;
    }

    .blog-article {
        margin-bottom: 40px;
    }

    .blog-title {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 10px;
        color: #333;
    }

    .blog-meta {
        font-size: 0.9rem;
        color: #777;
        margin-bottom: 20px;
    }

    .reading-time {
        color: #555;
    }

    .first-letter {
        font-size: 3rem;
        font-weight: bold;
        float: left;
        margin-right: 10px;
        line-height: 1;
        color: #000;
    }

    .blog-paragraph {
        font-size: 1.1rem;
        color: #444;
        margin-bottom: 20px;
    }

    .inline-image {
        width: 100%;
        max-width: 600px;
        margin: 20px 0;
        border-radius: 6px;
    }

    figure {
        text-align: center;
        margin-bottom: 20px;
    }

    figcaption {
        font-size: 0.9rem;
        color: #777;
        margin-top: 10px;
    }

    .related-posts {
        background-color: #f5f5f5;
        padding: 20px;
        border-radius: 8px;
    }

    .related-posts h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
    }

    .related-posts ul {
        list-style-type: none;
        padding: 0;
    }

    .related-posts li {
        margin-bottom: 10px;
    }

    .related-posts a {
        text-decoration: none;
        color: #3b5998;
    }

    .related-posts a:hover {
        text-decoration: underline;
    }

    /* Dark Mode Styling */
    body.dark-mode {
        background-color: #2c2c2c;
        color: #f1f1f1;
    }

    body.dark-mode .content-area {
        background-color: #3c3c3c;
        color: #e1e1e1;
    }

    body.dark-mode .blog-title {
        color: #f1f1f1;
    }

    body.dark-mode .blog-paragraph {
        color: #cccccc;
    }

    body.dark-mode .related-posts {
        background-color: #444444;
    }

    body.dark-mode .related-posts a {
        color: #ddddff;
    }

    body.dark-mode .hero-section img {
        filter: brightness(80%);
    }

    /* Dark Mode Toggle Button */
    .toggle-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .toggle-label {
        font-size: 1rem;
        margin-right: 10px;
        color: #333;
        font-weight: bold;
    }

    .toggle-switch {
        position: relative;
        width: 50px;
        height: 24px;
        background-color: #ddd;
        border-radius: 50px;
        transition: background-color 0.3s ease;
    }

    .toggle-switch::before {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 20px;
        height: 20px;
        background-color: white;
        border-radius: 50%;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .toggle-switch.active {
        background-color: #4caf50;
    }

    .toggle-switch.active::before {
        transform: translateX(26px);
    }
</style>

<main class="content-area">
    <!-- Blog Header with Hero Image -->
    <section class="hero-section">
        <img src="uploads/<?= htmlspecialchars($blogPost['image']) ?>" alt="Blog Image" class="hero-image">
    </section>

    <!-- Blog Content -->
    <article class="blog-article">
        <!-- Blog Title -->
      

        <!-- Blog Meta Information -->
        <div class="blog-meta">
            <span><?= date('F j, Y', strtotime($blogPost['date'])) ?> by 
                <strong><?= htmlspecialchars($blogPost['username']) ?></strong></span>
            <span class="reading-time"> • <?= ceil(str_word_count($blogPost['content']) / 200) ?> min read</span>
        </div>

        <!-- Blog Content with First Large Letter -->
        <h1 class="blog-title"><?= htmlspecialchars($blogPost['title']) ?></h1>
      
   
        <p class="blog-paragraph"><?= $blogPost['content'] ?></p>
        <!-- Example of inline images -->

   <figure>
            <img src="uploads/<?= htmlspecialchars($blogPost['image']) ?>" alt="Inline Image" class="inline-image">
            <figcaption>Image caption goes here.</figcaption>
        </figure>

        <!-- Continue blog content -->
        <p class="blog-paragraph">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque aliquet nisi vel metus pharetra, at congue sem tempor. Curabitur nec pharetra dolor. Fusce bibendum.</p>
    </article>

    <!-- Related Posts Section -->
    <section class="related-posts">
        <h3>Related Posts</h3>
        <ul>
            <li><a href="#">How to Design Like a Pro</a></li>
            <li><a href="#">The Future of Web Development</a></li>
            <li><a href="#">Creating Engaging Content</a></li>
        </ul>
    </section>
</main>

<!-- Toggle for Dark Mode -->
<div class="toggle-container">
    <span class="toggle-label">Dark Mode</span>
    <div class="toggle-switch" id="darkModeToggle"></div>
</div>

<script>
    // Toggle dark mode functionality
    const darkModeToggle = document.getElementById('darkModeToggle');
    let darkModeEnabled = false;

    darkModeToggle.addEventListener('click', () => {
        darkModeEnabled = !darkModeEnabled;
        document.body.classList.toggle('dark-mode');
        darkModeToggle.classList.toggle('active');

        // Save the mode preference in local storage
        localStorage.setItem('darkMode', darkModeEnabled ? 'enabled' : 'disabled');
    });

    // Check if dark mode was previously enabled and apply
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
        darkModeToggle.classList.add('active');
        darkModeEnabled = true;
    }
</script>

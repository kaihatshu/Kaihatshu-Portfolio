
                             <?php
require_once 'db.inc.php';

// Fetch hero section data
$stmt = $pdo->query("SELECT * FROM hero_section WHERE id = 3");
$hero = $stmt->fetch();


// Fetch projects from the database
$stmt = $pdo->prepare("SELECT title, description, image FROM project");
$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<main class="flex-grow">
    <!-- Hero Section -->
    <section class="hero" style="background-image: url('uploads/<?= htmlspecialchars($hero['hero_image']) ?>');">
        <div class="hero-overlay"></div>
        <div class="hero-content">
        <h1><?= htmlspecialchars($heroTitle ?? 'Welcome to Kaihatshu Portfolio', ENT_QUOTES, 'UTF-8') ?></h1>
        <p><?= htmlspecialchars($heroDescription ?? 'Crafting Unique Web Experiences', ENT_QUOTES, 'UTF-8') ?></p>
        <button onclick="scrollToPortfolio()">View My Work</button>
        </div>
    </section>




    <!-- Project Showcase -->
    <section id="portfolio" class="p-8">
    <h2 class="text-3xl font-bold mb-4">My Work</h2>
    <div id="project-showcase" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($projects as $project): ?>
            <div class="project-card bg-white p-4 rounded shadow">
                <!-- Display project image -->
                <img src="uploads/<?= htmlspecialchars($project['image']) ?>" alt="<?= htmlspecialchars($project['title']) ?>" class="rounded-lg w-full h-48 object-cover">
                
                <!-- Display project title -->
                <h3 class="text-xl font-semibold mt-2"><?= htmlspecialchars($project['title']) ?></h3>
                
                <!-- Display project description -->
                <p class="mt-2 text-gray-600"><?= htmlspecialchars($project['description']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>



 

            <section id="blog" class="p-8 bg-gray-100">
                <h2 class="text-3xl font-bold mb-4">Latest Blog Posts</h2>
                <div class="space-y-6">
                    <article class="bg-white p-4 rounded shadow">
                        <h3 class="text-xl font-semibold">Blog Post Title</h3>
                        <p class="text-gray-600">Preview of your blog post...</p>
                        <a href="#" class="text-blue-600 hover:underline">Read more</a>
                    </article>
                    <!-- Repeat for other blog posts -->
                </div>
            </section>
        </main>
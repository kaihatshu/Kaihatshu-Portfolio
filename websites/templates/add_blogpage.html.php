
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #eef2f7;
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow-y: scroll;
            scroll-behavior: smooth;
        }

        .container {
            background-color: #fff;
            padding: 25px;
            width: 60%;
            max-width: 1000px;
            margin: 20px auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #d1d1d1;
            border-radius: 5px;
            font-size: 1rem;
        }

        #editor-container {
            height: 300px;
            border: 1px solid #d1d1d1;
            border-radius: 5px;
            margin-bottom: 20px;
            overflow-y: auto;
        }

        #image-upload-zone {
            border: 2px dashed #9b59b6;
            padding: 15px;
            text-align: center;
            background-color: #f9f9f9;
            margin-bottom: 15px;
            cursor: pointer;
        }

        #image-upload-zone:hover {
            background-color: #f3e6ff;
        }

        #image-preview {
            max-width: 100%;
            margin-top: 10px;
            border-radius: 5px;
            display: none;
        }

        #remove-image-btn {
            display: none;
            background-color: #e74c3c;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        button[type="submit"] {
            background-color: #27ae60;
            color: white;
            padding: 12px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            background-color: #2ecc71;
        }

        .ql-editor img {
            display: block;
            max-width: 100%;
            margin: 10px 0;
        }

        /* Word and character count display */
        #char-count, #word-count {
            font-size: 0.9rem;
            color: #2c3e50;
            margin-bottom: 10px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create New Blog Post</h2>

        <!-- Blog Form -->
        <form id="blog-form" action="add_blog.php" method="POST" enctype="multipart/form-data">
            <!-- Blog Title -->
            <input type="text" id="blog-title" name="title" placeholder="Blog Title" required>

            <!-- Blog Description -->
            <textarea id="blog-description" name="description" placeholder="Enter a short description..." rows="3" required></textarea>

            <!-- Quill Editor for Blog Content -->
            <div id="editor-container"></div>

            <!-- Character and Word Count -->
            <div id="char-count">Characters: 0</div>
            <div id="word-count">Words: 0</div>

            <!-- Image Upload Section -->
            <div id="image-upload-zone">
                Drag and drop images here or click to upload
                <input type="file" id="image-upload" accept="image/*" style="display: none;" name="image">
            </div>

            <!-- Image Preview and Remove Button -->
            <img id="image-preview" src="" alt="Image preview">
            <button id="remove-image-btn" type="button">Remove Image</button>

            <!-- Submit Button -->
            <button type="submit">Publish Blog Post</button>
        </form>
    </div>

    <!-- Quill.js and JavaScript -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        // Initialize Quill editor with more toolbar options
        var quill = new Quill('#editor-container', {
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['link', 'image']
                ]
            },
            placeholder: 'Compose your blog content here...',
            theme: 'snow'
        });

        // Word and Character Counter
        const charCount = document.getElementById('char-count');
        const wordCount = document.getElementById('word-count');
        quill.on('text-change', function() {
            const text = quill.getText().trim();
            charCount.textContent = `Characters: ${text.length}`;
            wordCount.textContent = `Words: ${text.split(/\s+/).filter(word => word.length > 0).length}`;
        });

        // Image upload logic
        const imageUploadZone = document.getElementById('image-upload-zone');
        const imageUpload = document.getElementById('image-upload');
        const imagePreview = document.getElementById('image-preview');
        const removeImageBtn = document.getElementById('remove-image-btn');

        imageUploadZone.addEventListener('click', () => imageUpload.click());
        imageUploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            imageUploadZone.style.backgroundColor = '#e5e5e5';
        });
        imageUploadZone.addEventListener('dragleave', () => {
            imageUploadZone.style.backgroundColor = '';
        });
        imageUploadZone.addEventListener('drop', handleImageDrop);
        imageUpload.addEventListener('change', handleImageSelect);

        function handleImageDrop(e) {
            e.preventDefault();
            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                handleImage(e.dataTransfer.files[0]);
            }
        }

        function handleImageSelect(e) {
            if (e.target.files && e.target.files[0]) {
                handleImage(e.target.files[0]);
            }
        }

        function handleImage(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Set preview and display remove button
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                removeImageBtn.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }

        // Remove image logic
        removeImageBtn.addEventListener('click', function () {
            imagePreview.src = '';
            imagePreview.style.display = 'none';
            imageUpload.value = '';  // Reset file input
            removeImageBtn.style.display = 'none';  // Hide remove button
        });

        // Auto-save blog content to local storage every 5 seconds
        setInterval(function() {
            const blogTitle = document.getElementById('blog-title').value;
            const blogDescription = document.getElementById('blog-description').value;
            const blogContent = quill.root.innerHTML;

            localStorage.setItem('blogTitle', blogTitle);
            localStorage.setItem('blogDescription', blogDescription);
            localStorage.setItem('blogContent', blogContent);
        }, 5000);

        // Load saved data from local storage on page load
        window.onload = function() {
            if (localStorage.getItem('blogTitle')) {
                document.getElementById('blog-title').value = localStorage.getItem('blogTitle');
            }
            if (localStorage.getItem('blogDescription')) {
                document.getElementById('blog-description').value = localStorage.getItem('blogDescription');
            }
            if (localStorage.getItem('blogContent')) {
                quill.root.innerHTML = localStorage.getItem('blogContent');
            }
        }

        // Handle form submission
        document.getElementById('blog-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const title = document.getElementById('blog-title').value;
            const description = document.getElementById('blog-description').value;
            const content = quill.root.innerHTML;

            // Create form data
            const formData = new FormData(this);  // 'this' refers to the form
            formData.append('content', content);  // Append Quill editor content

            // Send form data via fetch
            fetch('add_blog.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                alert(result);  // Display success or error message
                localStorage.clear();  // Clear local storage after successful submission
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>

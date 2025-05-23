<?php
session_start();
?>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>CampusWall - Facebook Style with Functions</title>
<script src="https://cdn.tailwindcss.com"></script>
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
  rel="stylesheet"
/>
<style>
  /* Facebook style colors and animations */
  body {
    background-color: #f0f2f5;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  header {
    background-color: #1877f2;
    color: white;
  }
  .logo {
    font-weight: 700;
    font-size: 1.75rem;
    letter-spacing: -0.03em;
  }
  nav button {
    color: white;
    font-weight: 600;
    transition: background-color 0.3s ease;
  }
  nav button:hover {
    background-color: #145dbf;
    border-radius: 0.5rem;
  }
  .post-box textarea {
    background-color: #fff;
    border-radius: 0.75rem;
    box-shadow: 0 1px 2px rgb(0 0 0 / 0.1);
    font-size: 1.125rem;
    resize: none;
    transition: box-shadow 0.3s ease;
  }
  .post-box textarea:focus {
    outline: none;
    box-shadow: 0 0 0 3px #1877f2;
  }
  .post-box button.post-btn {
    background-color: #1877f2;
    color: white;
    font-weight: 600;
    border-radius: 0.75rem;
    padding: 0.5rem 1.5rem;
    transition: background-color 0.3s ease;
  }
  .post-box button.post-btn:hover {
    background-color: #145dbf;
  }
  .post-card {
    background-color: white;
    border-radius: 1rem;
    box-shadow: 0 2px 5px rgb(0 0 0 / 0.1);
    padding: 1.5rem;
    margin-top: 2.5rem;
    position: relative;
  }
  .post-header {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  .post-header img {
    border-radius: 50%;
    width: 64px;
    height: 64px;
    object-fit: cover;
  }
  .post-user {
    background-color: #606770;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 600;
    font-size: 1.125rem;
  }
  .post-visibility {
    color: #606770;
    font-size: 0.875rem;
    margin-top: 0.125rem;
  }
  .post-options-btn {
    position: absolute;
    top: 1.25rem;
    right: 1.25rem;
    background-color: #e4e6eb;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
  .post-options-btn:hover {
    background-color: #d8dadf;
  }
  .post-options-menu {
    position: absolute;
    top: 3.5rem;
    right: 1.25rem;
    background-color: white;
    border-radius: 0.75rem;
    box-shadow: 0 4px 12px rgb(0 0 0 / 0.15);
    width: 14rem;
    z-index: 50;
    display: none;
    flex-direction: column;
  }
  .post-options-menu.show {
    display: flex;
  }
  .post-options-menu button {
    padding: 0.75rem 1rem;
    font-size: 1rem;
    color: #050505;
    background: none;
    border: none;
    text-align: left;
    cursor: pointer;
    transition: background-color 0.2s ease;
  }
  .post-options-menu button:hover {
    background-color: #f2f2f2;
  }
  .post-content {
    border: 2px solid #ccd0d5;
    border-radius: 1rem;
    margin-top: 1.5rem;
    min-height: 12rem;
    padding: 1rem;
    font-size: 1.125rem;
    color: #050505;
    white-space: pre-wrap;
  }
  .post-reactions {
    display: flex;
    gap: 3rem;
    margin-top: 1.5rem;
    font-size: 2rem;
    color: #606770;
  }
  .post-reactions button {
    background: none;
    border: none;
    cursor: pointer;
    transition: color 0.3s ease;
  }
  .post-reactions button:hover {
    color: #1877f2;
  }
  .post-reactions button.heart:hover {
    color: #e0245e;
  }
  .post-reactions button.haha:hover {
    color: #f7b125;
  }
  .post-reactions button.liked,
  .post-reactions button.loved,
  .post-reactions button.hahaed {
    color: #1877f2;
  }
  .post-reactions button.loved {
    color: #e0245e;
  }
  .post-reactions button.hahaed {
    color: #f7b125;
  }
  .comment-section {
    background-color: #f0f2f5;
    border-radius: 1.5rem;
    margin-top: 2.5rem;
    padding: 1.5rem;
    display: flex;
    gap: 1.5rem;
    align-items: flex-start;
  }
  .comment-section img {
    border-radius: 50%;
    width: 64px;
    height: 64px;
    object-fit: cover;
  }
  .comment-content {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    width: 100%;
  }
  .comment-author {
    background-color: #606770;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.75rem;
    font-weight: 600;
    font-size: 1.125rem;
    user-select: none;
  }
  .comment-text {
    background-color: #606770;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.75rem;
    font-size: 1rem;
    user-select: none;
    white-space: pre-wrap;
  }
  .comment-input-container {
    margin-top: 1rem;
    display: flex;
    gap: 1rem;
    align-items: center;
  }
  .comment-input-container input {
    flex-grow: 1;
    border-radius: 9999px;
    border: 1px solid #ccd0d5;
    padding: 0.5rem 1rem;
    font-size: 1rem;
    outline: none;
    transition: box-shadow 0.3s ease;
  }
  .comment-input-container input:focus {
    box-shadow: 0 0 0 3px #1877f2;
  }
  .comment-input-container button {
    background-color: #1877f2;
    color: white;
    border-radius: 9999px;
    padding: 0.5rem 1.5rem;
    font-weight: 600;
    font-size: 1rem;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
  .comment-input-container button:disabled {
    background-color: #a3bffa;
    cursor: not-allowed;
  }
  .comment-input-container button:hover:not(:disabled) {
    background-color: #145dbf;
  }
  /* Responsive */
  @media (max-width: 768px) {
    header {
      flex-wrap: wrap;
      gap: 1rem;
      padding: 1rem 1.5rem;
    }
    nav {
      width: 100%;
      justify-content: center;
      gap: 2rem;
    }
    main {
      padding: 1rem 1.5rem;
      max-width: 100%;
    }
    .post-card {
      padding: 1rem;
    }
    .post-header img {
      width: 48px;
      height: 48px;
    }
    .post-user {
      font-size: 1rem;
      padding: 0.25rem 0.75rem;
    }
    .post-visibility {
      font-size: 0.75rem;
    }
    .post-options-btn {
      width: 32px;
      height: 32px;
      top: 1rem;
      right: 1rem;
    }
    .post-options-menu {
      width: 12rem;
      top: 3rem;
      right: 1rem;
    }
    .post-content {
      min-height: 8rem;
      font-size: 1rem;
    }
    .post-reactions {
      font-size: 1.5rem;
      gap: 2rem;
    }
    .comment-section {
      padding: 1rem;
      gap: 1rem;
    }
    .comment-section img {
      width: 48px;
      height: 48px;
    }
    .comment-author {
      font-size: 1rem;
      padding: 0.25rem 0.75rem;
    }
    .comment-text {
      font-size: 0.875rem;
    }
  }
</style>
</head>
<body>
<header class="flex items-center justify-between px-6 py-5 sticky top-0 z-50 shadow-md">
  <h1 class="logo select-none">CampusWall</h1>
  <nav class="flex items-center space-x-8 text-white text-lg font-semibold">
    <button aria-label="Home" class="flex items-center space-x-2 text-3xl px-3 py-2 rounded-md hover:bg-blue-600 transition">
      <i class="fas fa-home"></i>
      <span>Home</span>
    </button>
    <button aria-label="Notifications" class="flex items-center space-x-2 text-3xl px-3 py-2 rounded-md hover:bg-blue-600 transition">
      <i class="far fa-bell"></i>
      <span>Notification</span>
    </button>
    <button aria-label="Profile" class="flex items-center space-x-2 text-3xl px-3 py-2 rounded-md hover:bg-blue-600 transition">
      <div class="rounded-full bg-gray-400 w-12 h-12 flex items-center justify-center text-white text-2xl select-none">
        <i class="fas fa-user"></i>
      </div>
      <span>Profile</span>
    </button>
  </nav>
</header>
<main class="px-6 max-w-4xl mx-auto mt-8">
  <!-- New Post Box -->
  <section class="post-box bg-white rounded-xl p-6 shadow-md">
    <textarea
      id="new-post-text"
      aria-label="Write a new post"
      class="w-full h-28 p-4 text-lg resize-none focus:outline-none"
      placeholder="What's on your mind?"
    ></textarea>
    <div class="flex items-center justify-between mt-4">
      <div class="flex space-x-6 text-3xl text-gray-600">
        <label for="new-post-photo" class="cursor-pointer hover:text-blue-600 transition" title="Add photo">
          <i class="fas fa-image"></i>
        </label>
        <input type="file" id="new-post-photo" accept="image/*" class="hidden" />
        <label for="new-post-video" class="cursor-pointer hover:text-blue-600 transition" title="Add video">
          <i class="fas fa-video"></i>
        </label>
        <input type="file" id="new-post-video" accept="video/*" class="hidden" />
      </div>
      <button id="post-submit-btn" class="post-btn" type="button" disabled>
        Post
      </button>
    </div>
    <div id="preview-container" class="mt-4 flex flex-wrap gap-4"></div>
  </section>
  <!-- Posts Container -->
  <section id="posts-container"></section>
</main>
<script>
  // Elements
  const postText = document.getElementById('new-post-text');
  const postSubmitBtn = document.getElementById('post-submit-btn');
  const postsContainer = document.getElementById('posts-container');
  const photoInput = document.getElementById('new-post-photo');
  const videoInput = document.getElementById('new-post-video');
  const previewContainer = document.getElementById('preview-container');

  // State
  let selectedPhoto = null;
  let selectedVideo = null;

  // Enable/disable post button based on input
  function updatePostButtonState() {
    const hasText = postText.value.trim().length > 0;
    const hasMedia = selectedPhoto !== null || selectedVideo !== null;
    postSubmitBtn.disabled = !(hasText || hasMedia);
  }

  postText.addEventListener('input', updatePostButtonState);

  // Preview media
  function clearPreview() {
    previewContainer.innerHTML = '';
  }

  function createPreview(src, type) {
    const wrapper = document.createElement('div');
    wrapper.className = 'relative w-40 h-40 rounded-lg overflow-hidden border border-gray-300 shadow-sm';

    if (type === 'image') {
      const img = document.createElement('img');
      img.src = src;
      img.alt = 'Preview image';
      img.className = 'object-cover w-full h-full';
      wrapper.appendChild(img);
    } else if (type === 'video') {
      const video = document.createElement('video');
      video.src = src;
      video.controls = true;
      video.className = 'object-cover w-full h-full';
      wrapper.appendChild(video);
    }

    // Remove button
    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'absolute top-1 right-1 bg-black bg-opacity-50 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-opacity-75';
    removeBtn.innerHTML = '&times;';
    removeBtn.title = 'Remove media';
    removeBtn.onclick = () => {
      if (type === 'image') {
        selectedPhoto = null;
        photoInput.value = '';
      } else if (type === 'video') {
        selectedVideo = null;
        videoInput.value = '';
      }
      wrapper.remove();
      updatePostButtonState();
    };
    wrapper.appendChild(removeBtn);

    previewContainer.appendChild(wrapper);
  }

  photoInput.addEventListener('change', (e) => {
    if (e.target.files && e.target.files[0]) {
      selectedPhoto = URL.createObjectURL(e.target.files[0]);
      clearPreview();
      createPreview(selectedPhoto, 'image');
      if (selectedVideo) {
        createPreview(selectedVideo, 'video');
      }
      updatePostButtonState();
    }
  });

  videoInput.addEventListener('change', (e) => {
    if (e.target.files && e.target.files[0]) {
      selectedVideo = URL.createObjectURL(e.target.files[0]);
      clearPreview();
      if (selectedPhoto) {
        createPreview(selectedPhoto, 'image');
      }
      createPreview(selectedVideo, 'video');
      updatePostButtonState();
    }
  });

  // Create a post element
  function createPostElement(postData) {
    const postEl = document.createElement('section');
    postEl.className = 'post-card';
    postEl.setAttribute('tabindex', '0');

    // Post header
    const header = document.createElement('div');
    header.className = 'post-header';

    const profileImg = document.createElement('img');
    profileImg.src = postData.authorImage;
    profileImg.alt = `Profile picture of ${postData.authorName}`;
    profileImg.width = 64;
    profileImg.height = 64;
    header.appendChild(profileImg);

    const userInfo = document.createElement('div');
    const userName = document.createElement('div');
    userName.className = 'post-user select-none';
    userName.textContent = postData.authorName;
    userInfo.appendChild(userName);

    const visibility = document.createElement('div');
    visibility.className = 'post-visibility select-none';
    visibility.textContent = postData.visibility;
    userInfo.appendChild(visibility);

    header.appendChild(userInfo);

    // Options button and menu
    const optionsWrapper = document.createElement('div');
    optionsWrapper.className = 'ml-auto relative';

    const optionsBtn = document.createElement('button');
    optionsBtn.className = 'post-options-btn';
    optionsBtn.setAttribute('aria-haspopup', 'true');
    optionsBtn.setAttribute('aria-expanded', 'false');
    optionsBtn.setAttribute('aria-label', 'Post options');
    optionsBtn.type = 'button';
    optionsBtn.innerHTML = '<i class="fas fa-ellipsis-h"></i>';
    optionsWrapper.appendChild(optionsBtn);

    const optionsMenu = document.createElement('div');
    optionsMenu.className = 'post-options-menu';
    optionsMenu.setAttribute('role', 'menu');
    optionsMenu.setAttribute('tabindex', '-1');

    const editBtn = document.createElement('button');
    editBtn.textContent = 'Edit Post';
    editBtn.setAttribute('role', 'menuitem');
    editBtn.type = 'button';

    const deleteBtn = document.createElement('button');
    deleteBtn.textContent = 'Delete Post';
    deleteBtn.setAttribute('role', 'menuitem');
    deleteBtn.type = 'button';

    const visibilityBtn = document.createElement('button');
    visibilityBtn.textContent = 'Change Visibility';
    visibilityBtn.setAttribute('role', 'menuitem');
    visibilityBtn.type = 'button';

    optionsMenu.appendChild(editBtn);
    optionsMenu.appendChild(deleteBtn);
    optionsMenu.appendChild(visibilityBtn);

    optionsWrapper.appendChild(optionsMenu);
    header.appendChild(optionsWrapper);

    postEl.appendChild(header);

    // Post content
    const content = document.createElement('div');
    content.className = 'post-content';
    content.textContent = postData.text;
    postEl.appendChild(content);

    // Media container
    if (postData.photo || postData.video) {
      const mediaContainer = document.createElement('div');
      mediaContainer.className = 'mt-4 flex flex-wrap gap-4';

      if (postData.photo) {
        const imgWrapper = document.createElement('div');
        imgWrapper.className = 'rounded-lg overflow-hidden border border-gray-300 shadow-sm w-40 h-40 relative';
        const img = document.createElement('img');
        img.src = postData.photo;
        img.alt = 'Post image';
        img.className = 'object-cover w-full h-full';
        imgWrapper.appendChild(img);
        mediaContainer.appendChild(imgWrapper);
      }
      if (postData.video) {
        const videoWrapper = document.createElement('div');
        videoWrapper.className = 'rounded-lg overflow-hidden border border-gray-300 shadow-sm w-40 h-40 relative';
        const video = document.createElement('video');
        video.src = postData.video;
        video.controls = true;
        video.className = 'object-cover w-full h-full';
        videoWrapper.appendChild(video);
        mediaContainer.appendChild(videoWrapper);
      }
      postEl.appendChild(mediaContainer);
    }

    // Reactions
    const reactions = document.createElement('div');
    reactions.className = 'post-reactions';
    reactions.setAttribute('role', 'group');

    const likeBtn = document.createElement('button');
    likeBtn.className = 'like-btn';
    likeBtn.setAttribute('aria-label', 'Like');
    likeBtn.title = 'Like';
    likeBtn.type = 'button';
    likeBtn.innerHTML = '<i class="far fa-thumbs-up"></i>';
    reactions.appendChild(likeBtn);

    const heartBtn = document.createElement('button');
    heartBtn.className = 'heart-btn heart';
    heartBtn.setAttribute('aria-label', 'Heart');
    heartBtn.title = 'Heart';
    heartBtn.type = 'button';
    heartBtn.innerHTML = '<i class="fas fa-heart"></i>';
    reactions.appendChild(heartBtn);

    const hahaBtn = document.createElement('button');
    hahaBtn.className = 'haha-btn haha';
    hahaBtn.setAttribute('aria-label', 'Haha');
    hahaBtn.title = 'Haha';
    hahaBtn.type = 'button';
    hahaBtn.innerHTML = '<i class="far fa-smile"></i>';
    reactions.appendChild(hahaBtn);

    postEl.appendChild(reactions);

    // Comments container
    const commentsContainer = document.createElement('div');
    commentsContainer.className = 'mt-6';

    // Existing comments
    postData.comments.forEach((comment) => {
      const commentSection = document.createElement('section');
      commentSection.className = 'comment-section';

      const commentImg = document.createElement('img');
      commentImg.src = comment.authorImage;
      commentImg.alt = `Profile picture of ${comment.authorName}`;
      commentImg.width = 64;
      commentImg.height = 64;
      commentSection.appendChild(commentImg);

      const commentContent = document.createElement('div');
      commentContent.className = 'comment-content';

      const commentAuthor = document.createElement('div');
      commentAuthor.className = 'comment-author select-none';
      commentAuthor.textContent = comment.authorName;
      commentContent.appendChild(commentAuthor);

      const commentText = document.createElement('div');
      commentText.className = 'comment-text select-none';
      commentText.textContent = comment.text;
      commentContent.appendChild(commentText);

      commentSection.appendChild(commentContent);
      commentsContainer.appendChild(commentSection);
    });

    // Add comment input
    const commentInputContainer = document.createElement('div');
    commentInputContainer.className = 'comment-input-container';

    const commentInput = document.createElement('input');
    commentInput.type = 'text';
    commentInput.placeholder = 'Write a comment...';
    commentInput.setAttribute('aria-label', 'Write a comment');
    commentInputContainer.appendChild(commentInput);

    const commentSubmitBtn = document.createElement('button');
    commentSubmitBtn.textContent = 'Post';
    commentSubmitBtn.disabled = true;
    commentInputContainer.appendChild(commentSubmitBtn);

    commentsContainer.appendChild(commentInputContainer);
    postEl.appendChild(commentsContainer);

    // Event listeners for options menu
    optionsBtn.addEventListener('click', () => {
      const expanded = optionsBtn.getAttribute('aria-expanded') === 'true';
      optionsBtn.setAttribute('aria-expanded', !expanded);
      optionsMenu.classList.toggle('show');
    });

    // Close menu if clicked outside
    document.addEventListener('click', (e) => {
      if (!optionsBtn.contains(e.target) && !optionsMenu.contains(e.target)) {
        optionsMenu.classList.remove('show');
        optionsBtn.setAttribute('aria-expanded', 'false');
      }
    });

    // Edit post function
    editBtn.addEventListener('click', () => {
      optionsMenu.classList.remove('show');
      optionsBtn.setAttribute('aria-expanded', 'false');
      // Replace content with textarea for editing
      const editTextarea = document.createElement('textarea');
      editTextarea.className = 'w-full p-2 border border-gray-300 rounded-md resize-none text-lg';
      editTextarea.value = content.textContent;
      content.replaceWith(editTextarea);
      editTextarea.focus();

      // Replace reactions and comments with save/cancel buttons
      reactions.style.display = 'none';
      commentsContainer.style.display = 'none';

      const saveCancelContainer = document.createElement('div');
      saveCancelContainer.className = 'flex gap-4 mt-4';

      const saveBtn = document.createElement('button');
      saveBtn.textContent = 'Save';
      saveBtn.className = 'post-btn';
      saveBtn.type = 'button';

      const cancelBtn = document.createElement('button');
      cancelBtn.textContent = 'Cancel';
      cancelBtn.className = 'post-btn bg-gray-400 hover:bg-gray-500';
      cancelBtn.type = 'button';

      saveCancelContainer.appendChild(saveBtn);
      saveCancelContainer.appendChild(cancelBtn);
      postEl.appendChild(saveCancelContainer);

      saveBtn.addEventListener('click', () => {
        if (editTextarea.value.trim() === '') {
          alert('Post content cannot be empty.');
          editTextarea.focus();
          return;
        }
        content.textContent = editTextarea.value.trim();
        editTextarea.replaceWith(content);
        saveCancelContainer.remove();
        reactions.style.display = 'flex';
        commentsContainer.style.display = 'block';
      });

      cancelBtn.addEventListener('click', () => {
        editTextarea.replaceWith(content);
        saveCancelContainer.remove();
        reactions.style.display = 'flex';
        commentsContainer.style.display = 'block';
      });
    });

    // Delete post function
    deleteBtn.addEventListener('click', () => {
      optionsMenu.classList.remove('show');
      optionsBtn.setAttribute('aria-expanded', 'false');
      if (confirm('Are you sure you want to delete this post?')) {
        postEl.remove();
      }
    });

    // Change visibility function
    visibilityBtn.addEventListener('click', () => {
      optionsMenu.classList.remove('show');
      optionsBtn.setAttribute('aria-expanded', 'false');
      const newVisibility = prompt(
        'Enter new visibility (e.g., Public, Friends, Only Me):',
        postData.visibility
      );
      if (newVisibility && newVisibility.trim() !== '') {
        visibility.textContent = newVisibility.trim();
      }
    });

    // Reaction toggles
    likeBtn.addEventListener('click', () => {
      likeBtn.classList.toggle('liked');
      // Remove other reactions if any
      if (likeBtn.classList.contains('liked')) {
        heartBtn.classList.remove('loved');
        hahaBtn.classList.remove('hahaed');
      }
    });
    heartBtn.addEventListener('click', () => {
      heartBtn.classList.toggle('loved');
      if (heartBtn.classList.contains('loved')) {
        likeBtn.classList.remove('liked');
        hahaBtn.classList.remove('hahaed');
      }
    });
    hahaBtn.addEventListener('click', () => {
      hahaBtn.classList.toggle('hahaed');
      if (hahaBtn.classList.contains('hahaed')) {
        likeBtn.classList.remove('liked');
        heartBtn.classList.remove('loved');
      }
    });

    // Comment input enable/disable button
    commentInput.addEventListener('input', () => {
      commentSubmitBtn.disabled = commentInput.value.trim() === '';
    });

    // Add comment function
    commentSubmitBtn.addEventListener('click', () => {
      const commentText = commentInput.value.trim();
      if (commentText === '') return;

      // Create new comment element
      const newComment = document.createElement('section');
      newComment.className = 'comment-section';

      const commenterImg = document.createElement('img');
      commenterImg.src = 'https://storage.googleapis.com/a1aa/image/7425d1f9-6229-41f6-cd6f-253bdd1d2265.jpg'; // Example commenter image
      commenterImg.alt = 'Profile picture of Maria Clara';
      commenterImg.width = 64;
      commenterImg.height = 64;
      newComment.appendChild(commenterImg);

      const commentContent = document.createElement('div');
      commentContent.className = 'comment-content';

      const commentAuthor = document.createElement('div');
      commentAuthor.className = 'comment-author select-none';
      commentAuthor.textContent = 'Maria Clara'; // Example commenter name
      commentContent.appendChild(commentAuthor);

      const commentTextDiv = document.createElement('div');
      commentTextDiv.className = 'comment-text select-none';
      commentTextDiv.textContent = commentText;
      commentContent.appendChild(commentTextDiv);

      newComment.appendChild(commentContent);

      commentsContainer.insertBefore(newComment, commentInputContainer);

      commentInput.value = '';
      commentSubmitBtn.disabled = true;
    });

    return postEl;
  }

  // Handle new post submission
  postSubmitBtn.addEventListener('click', () => {
    const text = postText.value.trim();
    if (!text && !selectedPhoto && !selectedVideo) {
      alert('Please enter some text or add a photo/video.');
      return;
    }

    const formData = new FormData();
    formData.append('content', text);

    if (selectedPhoto) {
      // Convert object URL back to File is not possible, so we need to get the file from input
      if (photoInput.files.length > 0) {
        formData.append('image', photoInput.files[0]);
      }
    }
    if (selectedVideo) {
      if (videoInput.files.length > 0) {
        formData.append('video', videoInput.files[0]);
      }
    }

    fetch('save_post.php', {
      method: 'POST',
      body: formData,
      credentials: 'same-origin',
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Create new post element with returned data
          const newPostData = {
            authorName: '<?php echo htmlspecialchars($_SESSION["username"] ?? "Unknown User"); ?>',
            authorImage: 'https://storage.googleapis.com/a1aa/image/0a99002f-0579-41fa-a94f-7df018d4a9c2.jpg', // Placeholder, can be updated to user image
            visibility: 'Public',
            text: text,
            photo: data.image_path || selectedPhoto,
            video: data.video_path || selectedVideo,
            comments: [],
          };
          const newPostEl = createPostElement(newPostData);
          postsContainer.prepend(newPostEl);

          // Reset new post box
          postText.value = '';
          selectedPhoto = null;
          selectedVideo = null;
          photoInput.value = '';
          videoInput.value = '';
          previewContainer.innerHTML = '';
          updatePostButtonState();
        } else {
          alert('Error saving post: ' + data.message);
        }
      })
      .catch(error => {
        alert('Error saving post: ' + error.message);
      });
  });

  // Initialize post button state
  updatePostButtonState();
</script>
</body>
</html>
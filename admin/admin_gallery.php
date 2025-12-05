<?php
session_start();
require_once '../config/db.php'; // Ensure this path is correct

// --- Centralized Action Handler ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $conn->begin_transaction();
    try {
        switch ($_POST['action']) {
            // 1. CREATE NEW ALBUM
            case 'create_album':
                if (empty($_FILES['images']['name'][0])) throw new Exception('You must select at least one image.');
                
                $album_title = trim($_POST['album_title']) ?: 'Untitled Album';
                $album_description = trim($_POST['album_description']) ?: '';

                $stmt = $conn->prepare("INSERT INTO albums (album_title, album_description) VALUES (?, ?)");
                $stmt->bind_param("ss", $album_title, $album_description);
                if (!$stmt->execute()) throw new Exception("Database Error: Could not create album. " . $stmt->error);
                $album_id = $conn->insert_id;
                $stmt->close();
                
                // Call image upload function
                $uploadedCount = uploadAndLinkImages($conn, $album_id, $_FILES['images']);
                if ($uploadedCount == 0) throw new Exception("Image upload failed.");

                $_SESSION['message'] = "Album '{$album_title}' created with {$uploadedCount} image(s).";
                break;

            // 2. UPDATE ALBUM DETAILS (TITLE/DESC)
            case 'update_album_details':
                $album_id = (int)$_POST['album_id'];
                $album_title = trim($_POST['album_title']) ?: 'Untitled Album';
                $album_description = trim($_POST['album_description']) ?: '';

                $stmt = $conn->prepare("UPDATE albums SET album_title = ?, album_description = ? WHERE id = ?");
                $stmt->bind_param("ssi", $album_title, $album_description, $album_id);
                if (!$stmt->execute()) throw new Exception("Failed to update album details.");
                $stmt->close();
                $_SESSION['message'] = "Album details updated successfully.";
                break;

            // 3. ADD MORE IMAGES TO AN EXISTING ALBUM
            case 'add_images_to_album':
                if (empty($_FILES['images']['name'][0])) throw new Exception('You must select at least one image to add.');
                $album_id = (int)$_POST['album_id'];
                $uploadedCount = uploadAndLinkImages($conn, $album_id, $_FILES['images']);
                $_SESSION['message'] = "Added {$uploadedCount} new image(s) to the album.";
                break;

            // 4. UPDATE A SINGLE IMAGE
            case 'update_image':
                $image_id = (int)$_POST['image_id'];
                
                // Get old image path to delete it
                $stmt = $conn->prepare("SELECT image_path FROM album_images WHERE id = ?");
                $stmt->bind_param("i", $image_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $old_image = $result->fetch_assoc();
                if ($old_image && file_exists('../' . $old_image['image_path'])) {
                    unlink('../' . $old_image['image_path']);
                }
                $stmt->close();

                // Upload new image
                $new_path = uploadAndLinkImages($conn, null, $_FILES['image'], $image_id);
                $_SESSION['message'] = "Image replaced successfully.";
                break;

            // 5. DELETE A SINGLE IMAGE
            case 'delete_image':
                $image_id = (int)$_POST['image_id'];
                $stmt = $conn->prepare("SELECT image_path FROM album_images WHERE id = ?");
                $stmt->bind_param("i", $image_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $image = $result->fetch_assoc();
                if ($image && file_exists('../' . $image['image_path'])) {
                    unlink('../' . $image['image_path']);
                }
                $stmt->close();

                $stmt = $conn->prepare("DELETE FROM album_images WHERE id = ?");
                $stmt->bind_param("i", $image_id);
                $stmt->execute();
                $stmt->close();
                $_SESSION['message'] = "Image deleted successfully.";
                break;

            // 6. DELETE AN ENTIRE ALBUM
            case 'delete_album':
                $album_id = (int)$_POST['album_id'];

                // First, delete all images associated with the album from server
                $stmt = $conn->prepare("SELECT image_path FROM album_images WHERE album_id = ?");
                $stmt->bind_param("i", $album_id);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    if (file_exists('../' . $row['image_path'])) {
                        unlink('../' . $row['image_path']);
                    }
                }
                $stmt->close();

                // Delete image records from DB
                $stmt = $conn->prepare("DELETE FROM album_images WHERE album_id = ?");
                $stmt->bind_param("i", $album_id);
                $stmt->execute();
                $stmt->close();

                // Delete album record from DB
                $stmt = $conn->prepare("DELETE FROM albums WHERE id = ?");
                $stmt->bind_param("i", $album_id);
                $stmt->execute();
                $stmt->close();
                $_SESSION['message'] = "Album and all its images deleted successfully.";
                break;
        }

        $conn->commit();
        $_SESSION['message_type'] = 'success';
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['message_type'] = 'error';
        $_SESSION['message'] = "Operation failed: " . $e->getMessage();
    }

    header("Location: admin_gallery.php");
    exit();
}

/**
 * Handles file uploads and links them to an album or updates an existing image.
 * @return int|string Number of files uploaded or the new path for a single update.
 */
function uploadAndLinkImages($conn, $album_id, $files, $image_id_to_update = null) {
    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $is_single_upload = !is_array($files['name']);
    $file_count = $is_single_upload ? 1 : count($files['name']);
    $uploaded_count = 0;

    for ($i = 0; $i < $file_count; $i++) {
        $name = $is_single_upload ? $files['name'] : $files['name'][$i];
        $tmp_name = $is_single_upload ? $files['tmp_name'] : $files['tmp_name'][$i];
        $error = $is_single_upload ? $files['error'] : $files['error'][$i];
        
        if ($error !== UPLOAD_ERR_OK) continue;

        $uniqueName = uniqid() . '-' . preg_replace('/[^A-Za-z0-9.\-_]/', '', $name);
        $targetPath = $uploadDir . $uniqueName;

        if (move_uploaded_file($tmp_name, $targetPath)) {
            $imgPathForDb = 'uploads/' . $uniqueName;
            
            if ($image_id_to_update) { // This is an update for a single image
                $stmt = $conn->prepare("UPDATE album_images SET image_path = ? WHERE id = ?");
                $stmt->bind_param("si", $imgPathForDb, $image_id_to_update);
                $stmt->execute();
                $stmt->close();
                return $imgPathForDb; // Return new path for single update
            } else { // This is an insert
                $stmt = $conn->prepare("INSERT INTO album_images (album_id, image_path) VALUES (?, ?)");
                $stmt->bind_param("is", $album_id, $imgPathForDb);
                if ($stmt->execute()) $uploaded_count++;
                $stmt->close();
            }
        }
    }
    return $uploaded_count;
}

// --- FETCH ALL ALBUMS WITH THEIR IMAGES ---
$albums_result = $conn->query("SELECT * FROM albums ORDER BY created_at DESC");
$albums = [];
if ($albums_result) {
    while ($album = $albums_result->fetch_assoc()) {
        $images_stmt = $conn->prepare("SELECT id, image_path FROM album_images WHERE album_id = ? ORDER BY id ASC");
        $images_stmt->bind_param("i", $album['id']);
        $images_stmt->execute();
        $images_result = $images_stmt->get_result();
        $album['images'] = $images_result->fetch_all(MYSQLI_ASSOC);
        $albums[] = $album;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gallery Management</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <style>
    /* Custom styles for modal and transitions */
    .modal-backdrop { background-color: rgba(0,0,0,0.5); }
    .modal { transition: opacity 0.3s ease, transform 0.3s ease; }
    .modal.hidden { opacity: 0; transform: scale(0.95); pointer-events: none; }
    .modal.flex { opacity: 1; transform: scale(1); }
    .image-card-hover-overlay {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .group:hover .image-card-hover-overlay {
        opacity: 1;
    }
  </style>
</head>
<body class="bg-gray-100 font-sans">

<div class="container mx-auto p-4 sm:p-6 lg:p-8">
  <header class="flex flex-wrap justify-between items-center gap-4 mb-8 pb-4 border-b border-gray-200">
    <div>
      <h1 class="text-3xl md:text-4xl font-bold text-gray-800">Photo Albums</h1>
      <p class="text-gray-500 mt-1">Manage your image collections.</p>
    </div>
    <button id="addAlbumBtn" class="bg-teal-500 hover:bg-teal-600 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-300 flex items-center gap-2">
      <i class="fas fa-plus"></i> Add New Album
    </button>
  </header>

  <?php if(isset($_SESSION['message'])): ?>
    <div id="flash-message" class="mb-6 p-4 rounded-lg <?php echo $_SESSION['message_type']=='success'?'bg-green-100 text-green-800':'bg-red-100 text-red-800'; ?>" role="alert">
      <?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
    </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
    <?php if (empty($albums)): ?>
        <p class="text-gray-500 col-span-full text-center">No albums found. Click 'Add New Album' to get started!</p>
    <?php else: ?>
        <?php foreach($albums as $album): ?>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col">
            <div class="p-6">
                <div class="flex justify-between items-start gap-4">
                    <div>
                        <h3 class="font-bold text-xl text-gray-900"><?php echo htmlspecialchars($album['album_title']); ?></h3>
                        <p class="text-gray-600 text-sm mt-1"><?php echo htmlspecialchars($album['album_description']); ?></p>
                    </div>
                    <div class="flex-shrink-0">
                        <button class="edit-album-details-btn text-gray-400 hover:text-blue-500 transition" 
                                data-album-id="<?php echo $album['id']; ?>"
                                data-album-title="<?php echo htmlspecialchars($album['album_title']); ?>"
                                data-album-description="<?php echo htmlspecialchars($album['album_description']); ?>"
                                title="Edit Title & Description">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6 pt-0 grid grid-cols-3 sm:grid-cols-4 gap-2 flex-grow content-start">
                <?php foreach(array_slice($album['images'], 0, 7) as $image): ?>
                    <div class="relative aspect-square group">
                        <img src="../<?php echo htmlspecialchars($image['image_path']); ?>" alt="Album Image" class="w-full h-full object-cover rounded-md">
                        <div class="absolute inset-0 bg-black bg-opacity-60 rounded-md flex items-center justify-center gap-2 image-card-hover-overlay">
                            <button class="edit-image-btn text-white hover:text-blue-400 text-lg" title="Change Image" data-image-id="<?php echo $image['id']; ?>"><i class="fas fa-edit"></i></button>
                            <button class="delete-image-btn text-white hover:text-red-400 text-lg" title="Delete Image" data-image-id="<?php echo $image['id']; ?>"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if(count($album['images']) > 7): ?>
                    <div class="aspect-square bg-gray-200 rounded-md flex items-center justify-center text-gray-600 font-bold text-lg">
                        +<?php echo count($album['images']) - 7; ?>
                    </div>
                <?php endif; ?>
                <button class="add-more-images-btn aspect-square bg-gray-50 border-2 border-dashed border-gray-300 rounded-md flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:border-gray-400 transition" title="Add More Images" data-album-id="<?php echo $album['id']; ?>">
                    <i class="fas fa-plus fa-lg"></i>
                </button>
            </div>
            <div class="p-4 bg-gray-50 border-t">
                 <button class="delete-album-btn text-sm text-red-500 hover:text-red-700 font-semibold transition" data-album-id="<?php echo $album['id']; ?>">
                    <i class="fas fa-trash-alt mr-1"></i> Delete Album
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<div id="addAlbumModal" class="modal hidden fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-lg">
        <form action="" method="post" enctype="multipart/form-data" class="p-6 space-y-4">
            <h2 class="text-2xl font-bold text-gray-800">Add New Album</h2>
            <input type="hidden" name="action" value="create_album">
            <div>
                <label class="block text-gray-700 font-medium">Album Title</label>
                <input type="text" name="album_title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="e.g., Summer Vacation 2025" required>
            </div>
            <div>
                <label class="block text-gray-700 font-medium">Album Description</label>
                <textarea name="album_description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="A short description of the album"></textarea>
            </div>
            <div>
                <label class="block text-gray-700 font-medium">Upload Images</label>
                <input type="file" name="images[]" multiple required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
            </div>
            <div class="flex justify-end gap-4 pt-4">
                <button type="button" class="close-modal-btn px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Album</button>
            </div>
        </form>
    </div>
</div>

<div id="editAlbumDetailsModal" class="modal hidden fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-lg">
        <form action="" method="post" class="p-6 space-y-4">
            <h2 class="text-2xl font-bold text-gray-800">Edit Album Details</h2>
            <input type="hidden" name="action" value="update_album_details">
            <input type="hidden" name="album_id" id="edit-album-id">
            <div>
                <label class="block text-gray-700 font-medium">Album Title</label>
                <input type="text" name="album_title" id="edit-album-title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div>
                <label class="block text-gray-700 font-medium">Album Description</label>
                <textarea name="album_description" id="edit-album-description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
            </div>
            <div class="flex justify-end gap-4 pt-4">
                <button type="button" class="close-modal-btn px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<div id="editImageModal" class="modal hidden fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-md">
        <form action="" method="post" enctype="multipart/form-data" class="p-6 space-y-4">
            <h2 class="text-2xl font-bold text-gray-800">Change Image</h2>
            <p class="text-sm text-gray-600">Upload a new file to replace the current image.</p>
            <input type="hidden" name="action" value="update_image">
            <input type="hidden" name="image_id" id="edit-image-id">
            <div>
                <label class="block text-gray-700 font-medium">New Image File</label>
                <input type="file" name="image" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
            </div>
            <div class="flex justify-end gap-4 pt-4">
                <button type="button" class="close-modal-btn px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Upload & Replace</button>
            </div>
        </form>
    </div>
</div>

<div id="addMoreImagesModal" class="modal hidden fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-md">
        <form action="" method="post" enctype="multipart/form-data" class="p-6 space-y-4">
            <h2 class="text-2xl font-bold text-gray-800">Add More Images</h2>
            <input type="hidden" name="action" value="add_images_to_album">
            <input type="hidden" name="album_id" id="add-more-album-id">
            <div>
                <label class="block text-gray-700 font-medium">Select Images to Add</label>
                <input type="file" name="images[]" multiple required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
            </div>
            <div class="flex justify-end gap-4 pt-4">
                <button type="button" class="close-modal-btn px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add Images</button>
            </div>
        </form>
    </div>
</div>

<div id="confirmModal" class="modal hidden fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-sm">
        <form action="" method="post" class="p-6 text-center">
            <input type="hidden" name="action" id="confirm-action">
            <input type="hidden" name="image_id" id="confirm-image-id">
            <input type="hidden" name="album_id" id="confirm-album-id">
            <i class="fas fa-exclamation-triangle text-5xl text-red-500 mb-4"></i>
            <h3 class="text-lg font-bold text-gray-800">Are you sure?</h3>
            <p class="text-gray-600 my-2" id="confirm-message">This action cannot be undone.</p>
            <div class="flex justify-center gap-4 mt-6">
                <button type="button" class="close-modal-btn px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                <button type="submit" id="confirm-delete-btn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Delete</button>
            </div>
        </form>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Generic Modal Handling ---
    const openModal = (modalId) => document.getElementById(modalId).classList.replace('hidden', 'flex');
    const closeModal = (modalEl) => modalEl.classList.replace('flex', 'hidden');

    document.querySelectorAll('.close-modal-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            closeModal(btn.closest('.modal'));
        });
    });
    
    // --- Open 'Add New Album' Modal ---
    document.getElementById('addAlbumBtn').addEventListener('click', () => {
        openModal('addAlbumModal');
    });

    // --- Open 'Edit Album Details' Modal ---
    document.querySelectorAll('.edit-album-details-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('edit-album-id').value = btn.dataset.albumId;
            document.getElementById('edit-album-title').value = btn.dataset.albumTitle;
            document.getElementById('edit-album-description').value = btn.dataset.albumDescription;
            openModal('editAlbumDetailsModal');
        });
    });
    
    // --- Open 'Change Image' Modal ---
    document.querySelectorAll('.edit-image-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('edit-image-id').value = btn.dataset.imageId;
            openModal('editImageModal');
        });
    });

    // --- Open 'Add More Images' Modal ---
    document.querySelectorAll('.add-more-images-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('add-more-album-id').value = btn.dataset.albumId;
            openModal('addMoreImagesModal');
        });
    });

    // --- Handle 'Delete Image' Confirmation ---
    document.querySelectorAll('.delete-image-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            // Reset fields
            document.getElementById('confirm-album-id').value = '';
            document.getElementById('confirm-image-id').value = btn.dataset.imageId;
            document.getElementById('confirm-action').value = 'delete_image';
            document.getElementById('confirm-message').textContent = 'This image will be permanently deleted. This action cannot be undone.';
            openModal('confirmModal');
        });
    });
    
    // --- Handle 'Delete Album' Confirmation ---
    document.querySelectorAll('.delete-album-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            // Reset fields
            document.getElementById('confirm-image-id').value = '';
            document.getElementById('confirm-album-id').value = btn.dataset.albumId;
            document.getElementById('confirm-action').value = 'delete_album';
            document.getElementById('confirm-message').textContent = 'This album and ALL its images will be permanently deleted. This action cannot be undone.';
            openModal('confirmModal');
        });
    });

    // --- Auto-hide flash message after 5 seconds ---
    const flashMessage = document.getElementById('flash-message');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.style.transition = 'opacity 0.5s ease';
            flashMessage.style.opacity = '0';
            setTimeout(() => flashMessage.remove(), 500);
        }, 5000);
    }
});
</script>

</body>
</html>
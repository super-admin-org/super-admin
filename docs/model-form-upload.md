# Image & File Upload

## Basic Usage

```php
// Single image
$form->image('avatar', 'Avatar');

// Single file
$form->file('document', 'Document');

// Multiple images
$form->multipleImage('photos', 'Photos');

// Multiple files
$form->multipleFile('attachments', 'Attachments');
```

## Storage Configuration

### 1. Configure a Filesystem Disk

In `config/filesystems.php`:

```php
'disks' => [
    'admin' => [
        'driver'     => 'local',
        'root'       => public_path('uploads'),
        'visibility' => 'public',
        'url'        => env('APP_URL') . '/uploads',
    ],
],
```

### 2. Set the Upload Disk

In `config/admin.php`:

```php
'upload' => [
    'disk' => 'admin',
    'directory' => [
        'image' => 'images',
        'file'  => 'files',
    ],
],
```

## File Naming

```php
// Random unique name
$form->image('avatar')->uniqueName();

// Custom name
$form->image('avatar')->name(function ($file) {
    return 'avatar-' . time() . '.' . $file->guessExtension();
});
```

## Directory

```php
// Custom upload directory
$form->image('avatar')->move('avatars/');

// Dynamic directory
$form->image('avatar')->move('users/' . auth('admin')->id());
```

## File Management

```php
// Allow file deletion
$form->image('avatar')->removable();

// Keep file when record is deleted
$form->image('avatar')->retainable();

// Allow download
$form->file('document')->downloadable();
```

## Image Thumbnails

Requires `intervention/image` package:

```bash
composer require intervention/image
```

### Fixed Size Thumbnails

```php
$form->image('photo')->thumbnail('small', 100, 100);

// Multiple thumbnails
$form->image('photo')
    ->thumbnail('small', 100, 100)
    ->thumbnail('medium', 300, 300)
    ->thumbnail('large', 600, 600);
```

### Custom Thumbnail Function

```php
$form->image('photo')->thumbnailFunction('watermarked', function ($image) {
    return $image->insert(public_path('watermark.png'), 'bottom-right');
});
```

### Accessing Thumbnails

Thumbnails are stored with a suffix: `photo-small.jpg`, `photo-medium.jpg`, etc.

## Image Manipulation

With `intervention/image` installed, you can chain image manipulation methods:

```php
$form->image('avatar')
    ->resize(300, null)
    ->insert(public_path('watermark.png'));
```

## Multiple File Storage

Multiple files are stored as a JSON array by default:

```php
// In your migration
$table->json('photos')->nullable();

// In your form
$form->multipleImage('photos', 'Photos')->removable()->sortable();
```

## Cloud Storage

Configure any Laravel-supported cloud driver (S3, GCS, etc.) in `config/filesystems.php` and set it as the upload disk:

```php
'upload' => [
    'disk' => 's3',
],
```

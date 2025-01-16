<?php

namespace Core\Upload;

class FileUploader
{
    protected string $uploadPath;
    protected array $allowedTypes;
    protected int $maxSize;
    protected array $errors = [];

    public function __construct(string $uploadPath = null)
    {
        $this->uploadPath = $uploadPath ?? config('filesystems.disks.local.root');
        $this->allowedTypes = config('filesystems.allowed_types', ['jpg', 'jpeg', 'png', 'pdf']);
        $this->maxSize = config('filesystems.max_size', 5 * 1024 * 1024); // 5MB default
    }

    public function upload($file, string $directory = ''): ?UploadedFile
    {
        if (!$this->validate($file)) {
            return null;
        }

        $fileName = $this->generateFileName($file);
        $path = $this->getUploadPath($directory, $fileName);

        if ($this->store($file, $path)) {
            return new UploadedFile([
                'name' => $fileName,
                'original_name' => $file['name'],
                'mime_type' => $file['type'],
                'size' => $file['size'],
                'path' => $path
            ]);
        }

        return null;
    }

    protected function validate(array $file): bool
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            $this->errors[] = 'Invalid file upload';
            return false;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->getUploadErrorMessage($file['error']);
            return false;
        }

        if ($file['size'] > $this->maxSize) {
            $this->errors[] = 'File size exceeds maximum limit';
            return false;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedTypes)) {
            $this->errors[] = 'File type not allowed';
            return false;
        }

        return true;
    }

    protected function generateFileName(array $file): string
    {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        return uniqid() . '_' . time() . '.' . $extension;
    }

    protected function getUploadPath(string $directory, string $fileName): string
    {
        $directory = trim($directory, '/');
        $uploadPath = rtrim($this->uploadPath, '/') . '/';
        
        if ($directory) {
            $uploadPath .= $directory . '/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
        }

        return $uploadPath . $fileName;
    }

    protected function store(array $file, string $path): bool
    {
        return move_uploaded_file($file['tmp_name'], $path);
    }

    protected function getUploadErrorMessage(int $error): string
    {
        return match ($error) {
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload',
            default => 'Unknown upload error',
        };
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
} 
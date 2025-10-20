<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use ZipArchive;
use DOMDocument;

class ScormManagerController extends Controller
{
    /**
     * Show SCORM manager page
     */
    public function index()
    {
        return view('admin.pages.scorm-manager');
    }

    /**
     * Handle SCORM file upload
     */
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'level' => 'nullable|in:beginner,intermediate,advanced',
                'price' => 'nullable|numeric|min:0',
                'duration_hours' => 'nullable|numeric|min:1',
                'scorm_file' => 'required|file|mimes:zip|max:512000', // 500MB
            ]);

            // Upload file
            $file = $request->file('scorm_file');
            $fileName = $file->getClientOriginalName();

            \Log::info('SCORM upload started', [
                'filename' => $fileName,
                'size' => $file->getSize(),
                'user_id' => auth()->id(),
            ]);

            // Create extraction directory
            $extractPath = storage_path('app/scorm/' . 'scorm_' . uniqid());
            if (!is_dir($extractPath)) {
                mkdir($extractPath, 0755, true);
            }

            // Extract ZIP
            $zip = new ZipArchive();
            if ($zip->open($file->getRealPath()) !== true) {
                throw new \Exception('Failed to open ZIP file');
            }

            $zip->extractTo($extractPath);
            $zip->close();

            // Find and parse imsmanifest.xml
            $manifestPath = $extractPath . '/imsmanifest.xml';
            if (!file_exists($manifestPath)) {
                throw new \Exception('imsmanifest.xml not found in SCORM package');
            }

            // Parse manifest
            $dom = new DOMDocument();
            $dom->load($manifestPath);

            // Get SCORM version
            $schemaVersion = $dom->documentElement->getAttribute('schemaversion') ??
                $dom->documentElement->getAttribute('version') ?? 'SCORM 1.2';

            $scormVersion = str_contains($schemaVersion, '2004') ? 'SCORM 2004' : 'SCORM 1.2';

            // Get entry point (href from first resource)
            $resources = $dom->getElementsByTagName('resource');
            $entryPoint = 'index.html';

            if ($resources->length > 0) {
                $firstResource = $resources->item(0);
                $href = $firstResource->getAttribute('href');
                if ($href) {
                    $entryPoint = $href;
                }
            }

            // Get title from manifest if not provided
            $title = $request->input('title');
            if (empty($title)) {
                $titles = $dom->getElementsByTagName('title');
                if ($titles->length > 0) {
                    $title = $titles->item(0)->nodeValue ?? 'SCORM Course';
                }
            }

            // Create course record
            $course = Course::create([
                'title' => $title,
                'slug' => Str::slug($title) . '-' . uniqid(),
                'description' => $request->input('description', 'SCORM Course'),
                'price' => (int) $request->input('price', 0),
                'level' => $request->input('level', 'beginner'),
                'duration_hours' => (int) $request->input('duration_hours', 1),
                'category_id' => $request->input('category_id', 1), // Default to category 1 or NULL
                'creator_id' => auth()->id(),
                'content_type' => 'scorm',
                'scorm_package_path' => basename($extractPath), // Just folder name
                'scorm_entry_point' => $entryPoint,
                'scorm_version' => $scormVersion,
                'scorm_manifest' => $manifestPath,
                'is_published' => false,
            ]);

            \Log::info('SCORM course created', [
                'course_id' => $course->id,
                'title' => $course->title,
                'path' => $course->scorm_package_path,
                'entry_point' => $entryPoint,
            ]);

            return redirect()
                ->route('admin.scorm.index')
                ->with('success', "SCORM course '{$title}' uploaded successfully!");

        } catch (\Exception $e) {
            \Log::error('SCORM upload error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    /**
     * View SCORM package contents (debug)
     */
    public function viewPackage(Course $course)
    {
        if ($course->content_type !== 'scorm') {
            abort(404);
        }

        $packagePath = storage_path('app/' . $course->scorm_package_path);

        if (!is_dir($packagePath)) {
            abort(404, 'Package not found');
        }

        // Scan directory recursively
        $files = $this->scanDirectory($packagePath);

        return view('admin.pages.scorm-viewer', [
            'course' => $course,
            'files' => $files,
            'packagePath' => $course->scorm_package_path,
        ]);
    }

    /**
     * Recursively scan directory
     */
    private function scanDirectory($path, $relPath = '')
    {
        $items = [];

        foreach (scandir($path) as $item) {
            if (in_array($item, ['.', '..', '.gitignore'])) {
                continue;
            }

            $fullPath = $path . '/' . $item;
            $itemRelPath = $relPath ? $relPath . '/' . $item : $item;

            if (is_dir($fullPath)) {
                $items[$item] = [
                    'type' => 'dir',
                    'path' => $itemRelPath,
                    'children' => $this->scanDirectory($fullPath, $itemRelPath),
                ];
            } else {
                $size = filesize($fullPath);
                $items[$item] = [
                    'type' => 'file',
                    'path' => $itemRelPath,
                    'size' => $this->formatBytes($size),
                    'ext' => strtolower(pathinfo($item, PATHINFO_EXTENSION)),
                ];
            }
        }

        return $items;
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}

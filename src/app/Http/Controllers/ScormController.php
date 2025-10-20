<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ScormController extends Controller
{
    /**
     * Serve SCORM package files
     * Ensures user has access to the course before serving files
     */
    public function serveScormFile(Course $course, $path = '')
    {
        try {
            \Log::info('SCORM file request', [
                'course_id' => $course->id,
                'path' => $path,
                'user_id' => Auth::id(),
                'scorm_entry_point' => $course->scorm_entry_point,
                'scorm_package_path' => $course->scorm_package_path
            ]);

            // Check if user is authenticated
            if (!Auth::check()) {
                \Log::warning('SCORM access: User not authenticated');
                return response('Unauthorized', 401);
            }

            // Check enrollment
            $enrollment = Auth::user()->enrollments()
                ->where('course_id', $course->id)
                ->first();

            $isAdmin = Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin';

            if (!$enrollment && !$isAdmin) {
                \Log::warning('SCORM access denied: Not enrolled and not admin', [
                    'user_id' => Auth::id(),
                    'course_id' => $course->id
                ]);
                return response('Access Denied', 403);
            }

            // If no path specified, serve the entry point
            if (empty($path)) {
                $path = $course->scorm_entry_point ?? 'index.html';
                \Log::info('Using entry point: ' . $path);
            }

            // Prevent directory traversal attacks
            $path = str_replace('..', '', $path);

            // Extract folder name from package path (e.g., "scorm/scorm_123" -> "scorm_123")
            $folderName = basename($course->scorm_package_path);
            $filePath = $folderName . '/' . $path;

            \Log::info('Serving file', [
                'folderName' => $folderName,
                'filePath' => $filePath,
                'fullPath' => Storage::disk('scorm')->path($filePath)
            ]);

            // Check if file exists in SCORM disk
            if (!Storage::disk('scorm')->exists($filePath)) {
                \Log::warning('File not found in storage', [
                    'filePath' => $filePath,
                    'extension' => pathinfo($path, PATHINFO_EXTENSION)
                ]);

                // For JSON/JS files, return empty objects to prevent SCORM from crashing
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                if ($ext === 'json') {
                    return response('{}', 200, ['Content-Type' => 'application/json']);
                } elseif ($ext === 'js') {
                    return response('console.log("File not found: ' . $path . '");', 200, ['Content-Type' => 'application/javascript']);
                } elseif ($ext === 'html' || $ext === 'htm') {
                    return response('<html><body>Content not found: ' . htmlspecialchars($path) . '</body></html>', 200, ['Content-Type' => 'text/html; charset=utf-8']);
                }

                // For other files, return 404
                return response('File not found: ' . $filePath, 404);
            }

            // Get full path
            $fullPath = Storage::disk('scorm')->path($filePath);

            // Check if physical file exists
            if (!file_exists($fullPath)) {
                \Log::error('Physical file does not exist', ['fullPath' => $fullPath]);
                return response('Physical file not found', 404);
            }

            // Determine content type
            $contentType = $this->getContentType($path);

            \Log::info('Serving file successfully', [
                'filePath' => $filePath,
                'contentType' => $contentType
            ]);

            // For HTML files, inject base href and SCORM API
            if ($contentType === 'text/html' || pathinfo($path, PATHINFO_EXTENSION) === 'html') {
                $htmlContent = file_get_contents($fullPath);

                // Get base URL for relative asset loading
                $baseDir = dirname($path);
                if ($baseDir === '.') {
                    $baseDir = '';
                }
                $baseUrl = route('courses.scorm-file', ['course' => $course->id, 'path' => '']) . ltrim($baseDir, '/');

                // Inject base href in head
                if (strpos($htmlContent, '<base ') === false) {
                    $htmlContent = str_replace('</head>', '<base href="' . $baseUrl . '/">' . PHP_EOL . '</head>', $htmlContent);
                }

                // Inject SCORM API script EARLY - at the beginning of head or body
                $scormApiUrl = asset('js/scorm-api.js');
                $scormScript = '<script src="' . $scormApiUrl . '"></script>' . PHP_EOL;

                if (strpos($htmlContent, 'scorm-api.js') === false) {
                    // Try to inject after opening <head> tag
                    if (preg_match('/<head[^>]*>/i', $htmlContent, $matches)) {
                        $htmlContent = str_replace($matches[0], $matches[0] . PHP_EOL . $scormScript, $htmlContent);
                    } else {
                        // Fallback: inject at beginning before first script tag
                        $count = 0;
                        $htmlContent = str_replace('<script', $scormScript . '<script', $htmlContent, $count);
                    }
                }

                return response($htmlContent, 200, [
                    'Content-Type' => $contentType,
                    'X-Content-Type-Options' => 'nosniff',
                ]);
            }

            // Stream the file
            return response()->file($fullPath, [
                'Content-Type' => $contentType,
                'X-Content-Type-Options' => 'nosniff',
            ]);

        } catch (\Exception $e) {
            \Log::error('SCORM file serving error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response('Error serving file: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get content type based on file extension
     */
    private function getContentType($filePath)
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        $mimeTypes = [
            'html' => 'text/html; charset=utf-8',
            'htm' => 'text/html; charset=utf-8',
            'js' => 'application/javascript',
            'css' => 'text/css',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'pdf' => 'application/pdf',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
            'ogg' => 'video/ogg',
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }
}

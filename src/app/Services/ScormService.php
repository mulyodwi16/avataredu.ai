<?php

namespace App\Services;

use ZipArchive;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\CourseLesson;
use App\Models\Category;

class ScormService
{
    /**
     * Process SCORM package and create course automatically
     */
    public function processScormPackage($zipFile, $creatorId, $categoryId = null, $customFields = [])
    {
        try {
            // Create temporary directory for extraction
            $tempDir = storage_path('app/temp/scorm_' . uniqid());
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Extract ZIP file
            $zip = new ZipArchive;
            if ($zip->open($zipFile->getPathname()) === TRUE) {
                $zip->extractTo($tempDir);
                $zip->close();
            } else {
                throw new \Exception('Could not extract SCORM package');
            }

            // Parse imsmanifest.xml
            $manifestPath = $tempDir . '/imsmanifest.xml';
            if (!file_exists($manifestPath)) {
                throw new \Exception('imsmanifest.xml not found in SCORM package');
            }

            $scormData = $this->parseManifest($manifestPath);

            // Create course from SCORM data
            $course = $this->createCourseFromScorm($scormData, $creatorId, $categoryId, $customFields);

            // Move SCORM content to permanent storage
            $scormPath = $this->moveScormContent($tempDir, $course->id);

            // Update course with SCORM path
            $entryPoint = $this->findEntryPoint($scormData);
            $course->update([
                'video_path' => $scormPath,
                'main_video_url' => $scormPath . '/' . $entryPoint,
                'scorm_entry_point' => $entryPoint,
            ]);

            // Create chapters and lessons from SCORM structure
            $this->createCourseStructure($course, $scormData, $scormPath);

            // Clean up temp directory
            $this->deleteDirectory($tempDir);

            return $course;

        } catch (\Exception $e) {
            Log::error('SCORM processing error: ' . $e->getMessage());
            // Clean up temp directory on error
            if (isset($tempDir) && file_exists($tempDir)) {
                $this->deleteDirectory($tempDir);
            }
            throw $e;
        }
    }

    /**
     * Parse SCORM manifest file
     */
    private function parseManifest($manifestPath)
    {
        $dom = new DOMDocument();
        $dom->load($manifestPath);
        $xpath = new DOMXPath($dom);

        // Register namespaces
        $xpath->registerNamespace('imscp', 'http://www.imsglobal.org/xsd/imscp_v1p1');
        $xpath->registerNamespace('adlcp', 'http://www.adlnet.org/xsd/adlcp_v1p3');

        $scormData = [
            'title' => '',
            'description' => '',
            'version' => '',
            'organizations' => [],
            'resources' => []
        ];

        // Get course title
        $titleNodes = $xpath->query('//imscp:manifest/imscp:metadata/imscp:schema | //imscp:manifest/imscp:organizations/imscp:organization/@identifier | //imscp:manifest/imscp:organizations/imscp:organization/imscp:title');
        if ($titleNodes->length > 0) {
            $scormData['title'] = trim($titleNodes->item(0)->nodeValue);
        }

        // Get organization structure (chapters/lessons)
        $organizations = $xpath->query('//imscp:manifest/imscp:organizations/imscp:organization');
        foreach ($organizations as $org) {
            $orgData = [
                'identifier' => $org->getAttribute('identifier'),
                'title' => '',
                'items' => []
            ];

            $orgTitle = $xpath->query('imscp:title', $org);
            if ($orgTitle->length > 0) {
                $orgData['title'] = trim($orgTitle->item(0)->nodeValue);
                if (empty($scormData['title'])) {
                    $scormData['title'] = $orgData['title'];
                }
            }

            // Get items (lessons)
            $items = $xpath->query('imscp:item', $org);
            foreach ($items as $item) {
                $itemData = [
                    'identifier' => $item->getAttribute('identifier'),
                    'identifierref' => $item->getAttribute('identifierref'),
                    'title' => '',
                    'subitems' => []
                ];

                $itemTitle = $xpath->query('imscp:title', $item);
                if ($itemTitle->length > 0) {
                    $itemData['title'] = trim($itemTitle->item(0)->nodeValue);
                }

                // Get sub-items
                $subitems = $xpath->query('imscp:item', $item);
                foreach ($subitems as $subitem) {
                    $subitemData = [
                        'identifier' => $subitem->getAttribute('identifier'),
                        'identifierref' => $subitem->getAttribute('identifierref'),
                        'title' => ''
                    ];

                    $subitemTitle = $xpath->query('imscp:title', $subitem);
                    if ($subitemTitle->length > 0) {
                        $subitemData['title'] = trim($subitemTitle->item(0)->nodeValue);
                    }

                    $itemData['subitems'][] = $subitemData;
                }

                $orgData['items'][] = $itemData;
            }

            $scormData['organizations'][] = $orgData;
        }

        // Get resources
        $resources = $xpath->query('//imscp:manifest/imscp:resources/imscp:resource');
        foreach ($resources as $resource) {
            $resourceData = [
                'identifier' => $resource->getAttribute('identifier'),
                'type' => $resource->getAttribute('type'),
                'href' => $resource->getAttribute('href'),
                'files' => []
            ];

            $files = $xpath->query('imscp:file', $resource);
            foreach ($files as $file) {
                $resourceData['files'][] = $file->getAttribute('href');
            }

            $scormData['resources'][] = $resourceData;
        }

        return $scormData;
    }

    /**
     * Create course from SCORM data
     */
    private function createCourseFromScorm($scormData, $creatorId, $categoryId = null, $customFields = [])
    {
        // Use default category if not provided
        if (!$categoryId) {
            $defaultCategory = Category::where('name', 'SCORM Courses')->first();
            if (!$defaultCategory) {
                $defaultCategory = Category::create([
                    'name' => 'SCORM Courses',
                    'description' => 'Courses imported from SCORM packages'
                ]);
            }
            $categoryId = $defaultCategory->id;
        }

        $course = Course::create([
            'title' => $scormData['title'] ?: 'SCORM Course',
            'slug' => Str::slug($scormData['title'] ?: 'scorm-course') . '-' . time(),
            'description' => $customFields['description'] ?: $scormData['description'] ?: 'Course imported from SCORM package',
            'category_id' => $categoryId,
            'creator_id' => $creatorId,
            'price' => $customFields['price'] ?? 0, // Use custom price or default to free
            'level' => $customFields['level'] ?? 'beginner', // Use custom level or default
            'content_type' => 'scorm',
            'scorm_version' => $scormData['version'] ?: '1.2',
            'scorm_manifest' => $scormData,
            'duration_hours' => $customFields['duration_hours'] ?? $this->estimateDuration($scormData),
            'is_published' => false, // Let admin review before publishing
        ]);

        return $course;
    }

    /**
     * Move SCORM content to permanent storage
     */
    private function moveScormContent($tempDir, $courseId)
    {
        $scormPath = 'courses/scorm/' . $courseId;
        $publicPath = storage_path('app/public/' . $scormPath);

        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0755, true);
        }

        // Copy all files from temp to permanent location
        $this->copyDirectory($tempDir, $publicPath);

        return $scormPath;
    }

    /**
     * Create course structure (chapters and lessons) from SCORM
     */
    private function createCourseStructure($course, $scormData, $scormPath)
    {
        $chapterOrder = 1;
        $totalLessons = 0;

        foreach ($scormData['organizations'] as $org) {
            // Create chapter for each major section
            if (!empty($org['items'])) {
                $chapter = CourseChapter::create([
                    'course_id' => $course->id,
                    'title' => $org['title'] ?: 'Chapter ' . $chapterOrder,
                    'description' => 'Chapter imported from SCORM package',
                    'order' => $chapterOrder++
                ]);

                $lessonOrder = 1;
                foreach ($org['items'] as $item) {
                    // Find resource for this item
                    $resource = $this->findResource($scormData['resources'], $item['identifierref']);

                    $lesson = CourseLesson::create([
                        'chapter_id' => $chapter->id,
                        'title' => $item['title'] ?: 'Lesson ' . $lessonOrder,
                        'content' => $this->generateLessonContent($resource, $scormPath),
                        'content_type' => 'scorm',
                        'video_url' => $resource ? $scormPath . '/' . $resource['href'] : null,
                        'order' => $lessonOrder++,
                        'duration_minutes' => 30 // Default duration
                    ]);

                    $totalLessons++;

                    // Create lessons for sub-items
                    foreach ($item['subitems'] as $subitem) {
                        $subResource = $this->findResource($scormData['resources'], $subitem['identifierref']);

                        CourseLesson::create([
                            'chapter_id' => $chapter->id,
                            'title' => $subitem['title'] ?: 'Lesson ' . $lessonOrder,
                            'content' => $this->generateLessonContent($subResource, $scormPath),
                            'content_type' => 'scorm',
                            'video_url' => $subResource ? $scormPath . '/' . $subResource['href'] : null,
                            'order' => $lessonOrder++,
                            'duration_minutes' => 20
                        ]);

                        $totalLessons++;
                    }
                }
            }
        }

        // Update course statistics
        $course->update([
            'total_chapters' => $chapterOrder - 1,
            'total_lessons' => $totalLessons
        ]);
    }

    /**
     * Find resource by identifier
     */
    private function findResource($resources, $identifierref)
    {
        foreach ($resources as $resource) {
            if ($resource['identifier'] === $identifierref) {
                return $resource;
            }
        }
        return null;
    }

    /**
     * Find the main entry point for SCORM package
     */
    private function findEntryPoint($scormData)
    {
        // Look for the first resource with type "webcontent"
        foreach ($scormData['resources'] as $resource) {
            if ($resource['type'] === 'webcontent' && !empty($resource['href'])) {
                return $resource['href'];
            }
        }

        // Fallback to common entry points
        $commonEntryPoints = ['index.html', 'index.htm', 'launch.html', 'course.html', 'start.html'];
        foreach ($commonEntryPoints as $entryPoint) {
            // This would require checking if file exists in temp directory
            // For now, default to index.html
            return 'index.html';
        }

        return 'index.html'; // Default fallback
    }

    /**
     * Generate lesson content for SCORM resource
     */
    private function generateLessonContent($resource, $scormPath)
    {
        if (!$resource) {
            return '<p>SCORM content not available</p>';
        }

        $href = $resource['href'];
        $publicUrl = asset('storage/' . $scormPath . '/' . $href);

        return "
            <div class='scorm-content'>
                <iframe src='{$publicUrl}' 
                        width='100%' 
                        height='600px' 
                        frameborder='0'
                        allowfullscreen>
                </iframe>
                <p><a href='{$publicUrl}' target='_blank'>Open in new window</a></p>
            </div>
        ";
    }

    /**
     * Estimate course duration from SCORM data
     */
    private function estimateDuration($scormData)
    {
        $totalItems = 0;
        foreach ($scormData['organizations'] as $org) {
            $totalItems += count($org['items']);
            foreach ($org['items'] as $item) {
                $totalItems += count($item['subitems']);
            }
        }

        // Estimate 30 minutes per item
        return max(1, ceil($totalItems * 0.5));
    }

    /**
     * Copy directory recursively
     */
    private function copyDirectory($src, $dst)
    {
        $dir = opendir($src);
        if (!file_exists($dst)) {
            mkdir($dst, 0755, true);
        }

        while (($file = readdir($dir)) !== false) {
            if ($file != '.' && $file != '..') {
                if (is_dir($src . '/' . $file)) {
                    $this->copyDirectory($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    /**
     * Delete directory recursively
     */
    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        return response()->json(Project::latest()->get());
    }

    public function store(Request $request)
    {
        $this->normalizeProjectPayload($request);

        $validated = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'required|string',
            'tech_stack'  => 'required|string',
            'live_url'    => 'nullable|url',
            'github_url'  => 'nullable|url',
            // Can be a full URL (e.g. CDN), or a local path like "/storage/projects/.."
            'image_url'   => 'nullable|string|max:2048',
            'image'       => 'nullable|file|max:5120|mimetypes:image/jpeg,image/png,image/webp,image/gif',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('projects', 'public');
            $validated['image_url'] = '/storage/'.$path;
        }

        $project = Project::create($validated);
        return response()->json($project, 201);
    }

    public function update(Request $request, Project $project)
    {
        $this->normalizeProjectPayload($request);

        $validated = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'required|string',
            'tech_stack'  => 'required|string',
            'live_url'    => 'nullable|url',
            'github_url'  => 'nullable|url',
            // Can be a full URL (e.g. CDN), or a local path like "/storage/projects/.."
            'image_url'   => 'nullable|string|max:2048',
            'image'       => 'nullable|file|max:5120|mimetypes:image/jpeg,image/png,image/webp,image/gif',
        ]);

        if ($request->hasFile('image')) {
            $this->deleteLocalProjectImageIfAny($project->image_url);
            $path = $request->file('image')->store('projects', 'public');
            $validated['image_url'] = '/storage/'.$path;
        }

        $project->update($validated);
        return response()->json($project);
    }

    public function destroy(Project $project)
    {
        $this->deleteLocalProjectImageIfAny($project->image_url);
        $project->delete();
        return response()->json(['message' => 'Project deleted']);
    }

    private function deleteLocalProjectImageIfAny(?string $imageUrl): void
    {
        if (!$imageUrl) {
            return;
        }

        $path = parse_url($imageUrl, PHP_URL_PATH) ?? $imageUrl;
        if (!is_string($path) || !str_starts_with($path, '/storage/')) {
            return;
        }

        $storagePath = ltrim(substr($path, strlen('/storage/')), '/');
        if ($storagePath !== '') {
            Storage::disk('public')->delete($storagePath);
        }
    }

    private function normalizeProjectPayload(Request $request): void
    {
        $mappings = [
            'techStack' => 'tech_stack',
            'liveUrl' => 'live_url',
            'githubUrl' => 'github_url',
            'imageUrl' => 'image_url',
        ];

        $optionalUrlFields = ['live_url', 'github_url', 'image_url'];
        $merge = [];

        foreach ($mappings as $from => $to) {
            if (!$request->has($to) && $request->has($from)) {
                $merge[$to] = $request->input($from);
            }
        }

        foreach ($optionalUrlFields as $field) {
            if (($merge[$field] ?? $request->input($field)) === '' && ($request->has($field) || array_key_exists($field, $merge))) {
                $merge[$field] = null;
            }
        }

        $techStack = $merge['tech_stack'] ?? $request->input('tech_stack');
        if (is_array($techStack)) {
            $merge['tech_stack'] = implode(', ', array_values(array_filter(array_map('trim', $techStack))));
        }

        if ($merge !== []) {
            $request->merge($merge);
        }
    }
}

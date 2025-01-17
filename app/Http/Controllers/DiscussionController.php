<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\DiscussionMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscussionController extends Controller
{
    public function index()
    {
        $discussions = Discussion::orderBy('date', 'desc')->get();
        return view('discussions.index', compact('discussions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        // Generate a unique conversation ID
        $validated['conversation_id'] = uniqid('conv_', true);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('discussion-images', $imageName, 'public');
            $validated['image'] = $path;
        }

        Discussion::create($validated);

        return redirect()->route('discussions.index')
            ->with('success', 'Agenda created successfully.');
    }

    public function show(Discussion $discussion)
    {
        $messages = $discussion->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('discussions.show', compact('discussion', 'messages'));
    }

    public function storeMessage(Request $request, Discussion $discussion)
    {
        $validated = $request->validate([
            'message' => 'required|string'
        ]);

        $discussion->messages()->create([
            'user_id' => Auth::id(),
            'message' => $validated['message']
        ]);

        return back()->with('success', 'Message sent successfully.');
    }

    public function endDiscussion(Discussion $discussion)
    {
        return back()->with('success', 'Discussion ended successfully.');
    }

    public function destroy(Discussion $discussion)
    {
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Only administrators can delete discussions.');
        }

        $discussion->delete();
        return redirect()->route('discussions.index')
            ->with('success', 'Discussion deleted successfully.');
    }
} 
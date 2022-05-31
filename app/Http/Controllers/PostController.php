<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Post;
use App\Http\Requests\PostRequest;
use App\Category;

class PostController extends Controller
{
    public function index(Post $post)
    {
        //Log::debug($post -> getPaginateByLimit());
        $client = new \GuzzleHttp\Client();
        $url = 'https://teratail.com/api/v1/questions';
        
        $response = $client -> request(
            'GET',
            $url,
            ['Bearer' => config('services.teratail.token')]
        );
        
        $questions = json_decode($response->getBody(), true);
        
        return view('posts/index') -> with([
            'posts' => $post -> getPaginateByLimit(),
            'questions' => $questions['questions'],
        ]);
    }
    
    public function show(Post $post)
    {
        return view('posts/show') -> with(['post' => $post]);
    }
    
    public function store(Post $post, PostRequest $request)
    {
        $input = $request['post'];
        $post -> fill($input) -> save();
        return redirect('/posts/' . $post -> id);
    }
    
    public function edit(Post $post)
    {
        return view('posts/edit') -> with(['post' => $post]);
    }
    
    public function update(Post $post, PostRequest $request)
    {
        $input = $request['post'];
        $post -> fill($input) -> save();
        return redirect('/posts/' . $post -> id);
    }
    
    public function destroy(Post $post)
    {
        $post -> delete();
        return redirect('/');
    }
    
    public function create(Category $category)
    {
        return view('posts/create')->with(['categories' => $category->get()]);
    }
}
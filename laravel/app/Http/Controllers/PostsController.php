<?php

namespace MovieBuffs\Http\Controllers;

use Illuminate\Http\Request;
use MovieBuffs\Post;
use DB; 

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

		// get all 
		// $posts = Post::all();
		
		// get a specific post
		//$post = Post::where('title', '1st Post')->get();
		
		// alternative syntax
		//$posts = DB::Select('SELECT * FROM posts');
		
		// limiting
		//$posts = Post::orderBy('title', 'desc')->take(1)->get();
		
		
		// pagination
		$posts = Post::orderBy('title', 'desc')->paginate(1);
		
		
		//$posts = Post::orderBy('title', 'desc')->get();
		
		
		return view('posts.index')->with('posts', $posts);
		
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
			'title'=>'required',
			'body'=>'required'
		]);
		
		// Create post code
		
		$post = new Post;
		$post->title = $request->input('title');
		$post->body = $request->input('body');
		
		$post->save();
		
		return redirect('/posts')->with('success', 'Post Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$post = Post::find($id);
		return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

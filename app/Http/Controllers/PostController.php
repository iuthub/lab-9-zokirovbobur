<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Session\Store;

class PostController extends Controller
{
    public function getIndex()
    {
       // $post = new Post();
        $posts = Post::orderBy('create_at', 'desc')->get();
        return view('blog.index', ['posts' => $posts]);
    }

    public function getAdminIndex()
    {
       // $post = new Post();
         $posts = Post::orderBy('title', 'asc')->get();
        return view('admin.index', ['posts' => $posts]);
    }

    public function getPost($id)
    {
        //$post = new Post();
        $post=Post::where('id', $id)->first();
        return view('blog.post', ['post' => $post]);
    }

    public function getAdminCreate()
    {
        $tags = Tag:: all ();
         return view ('admin .create ', [' tags ' =>$tags ]);
    }

    public function getAdminEdit($id)
    {
       // $post = new Post();

        $post = Post::find($id);
        $tags = Tag:: all ();
        return view('admin.edit', ['post' =>$post, 'postId' => $id, 'tags'=> $tags]);
    }

    public function postAdminCreate(Request $request)
    {

        $this->validate($request, [
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);
        $post = new Post([
            'title' => $request ->input('title'),
          'content'  => $request -> input ('content')
       ]);
         $post -> save ();

         $post -> tags () -> attach ( $request -> input (' tags ')=== null ? []: $request -> input ( ' tags '));

      return redirect () -> route ( ' admin . index ')
     -> with ( 'info ', ' Post created , Title is : ' . $request -> input (' title '));
    }
       
    

    public function postAdminUpdate(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);
       $post = Post:: find ( $request -> input ( 'id '));
       $post ->title= $request -> input (' title ');
       $post ->content = $request -> input ('content ');
       $post -> save ();
       $post -> tags () -> sync ( $request -> input (' tags ') === null ? [] :$request -> input (' tags '));
       return redirect () -> route ( ' admin . index ')
       -> with ( ' info ', ' Post edited , new Title is : ' . $request -> input (' title '));

    }

    public function getAdminDelete ( $id )
    {
      $post = Post::find ($id);
      $post -> likes () -> delete ();
      $post -> tags () -> detach ();
      $post -> delete ();
      return redirect () -> route ( ' admin . index ') -> with (' info ' , ' Post deleted ! ');
    }

    public function getLikePost ( $id )
      {
      $post = Post:: where ('id', $id )-> first ();
      $like = new Like ();
      $post -> likes () -> save ( $like );
       return redirect() -> back ();
}


}

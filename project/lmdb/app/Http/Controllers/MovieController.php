<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{

    //Gett all movies to show in /movies
    public function allMovies()
    {
        $movies = Movie::get();
        return view('movies', ['movies' => $movies]);
    }

    public function index()
    {
        $Movies = Movie::orderby('rating', 'DESC')->take(3)->get();
        $moviesDate = Movie::orderby('release_date', 'ASC')->take(3)->get();

        // If statement that only shows movies coming in the future and 
        // not staying in coming soon if date has passed. 

        // if ($moviesDate->release_date) {
        // }

        return view('homepage', ['moviesDate' => $moviesDate, 'Movies' => $Movies]);
   
    }

    //Gett all movies to show in genrepage.
    public function genreMovies()
    {
        $movies = Movie::get();
        return view('genre', ['movies' => $movies]);
    }

    public function showMovie($title)
    {
        if (Movie::where('title', $title)->exists()) {
            $movie = Movie::where('title', $title)->first();

            return view('movie', ['movie' => $movie]);
        }
    }

    // Get all the top 5 movies with the highest rating.
    public function showTopMovies()
    {
        $movies = Movie::orderby('rating', 'DESC')->take(6)->get();
        return view('top-movies', compact('movies'));
    }

    // function for search movie

    public function search(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);

        $title = $_GET['title'];
        $movies = Movie::where('title', 'LIKE', '%' . $title . '%')->get();

        return view('search', compact('movies'));
    }

    public function createMovie(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:movies',
            'description' => 'required:max:255',
            'genre' => 'required',
            'rating' => 'required|max:5',
            'release_date' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);



        $movie = new Movie();
        $movie->title = $request->title;
        $movie->description = $request->description;
        $movie->genre = $request->genre;
        $movie->rating = $request->rating;
        $movie->release_date = $request->release_date;

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('public/Image'), $filename);
            $movie['image'] = $filename;
        } else {
            return redirect('createmovie')->with('status', 'Please add a image');
        }
        $movie->save();

        return redirect('createmovie')->with('status', 'Movie Has Been Created');
    }
}

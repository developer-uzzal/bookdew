<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookAuth;
use App\Models\BookLanguage;
use App\Models\Category;
use App\Models\HomeBanner;
use App\Models\MenuFooter;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Member;
use App\Models\ContactMessage;

class HomeController extends Controller
{
    function index()
    {
        $menuFooter = MenuFooter::first();
        $categories = Category::Where('is_active', 1)->orderBy('name', 'asc')->get();
        $languages = BookLanguage::orderBy('name', 'asc')->get();
        $banner = HomeBanner::first();
        $trending = Book::where('is_trending', 1)->orderBy('created_at', 'desc')->limit(6)->get();
        $latest = Book::where('is_active', 1)->where('is_trending', 0)->orderBy('created_at', 'desc')->limit(18)->get();
        return Inertia::render('Home/Home', ["menuFooter" => $menuFooter, "categories" => $categories, 'banner' => $banner, 'languages' => $languages, 'trending' => $trending, 'latest' => $latest]);
    }

    function AdminSliderPage(Request $request)
    {
        $email = $request->session()->get('userEmail');
        $user = User::where('email', $email)->first();
        $menuFooter = MenuFooter::first();
        $list = HomeBanner::first();

        $member = Member::where("is_read", 0)->count();
        $contactMessage = ContactMessage::where("is_read", 0)->count();
        return Inertia::render('Auth/Home/Home', ["menuFooter" => $menuFooter, "list" => $list, "user" => $user, "member" => $member, "contactMessage" => $contactMessage]);
    }

    function AdminSliderCreate(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required',
            ]);

            if ($request->hasFile('image')) {
                $oldImage = $request->input('oldImage');
                $newImage = $request->file('image');

                $extension = $newImage->getClientOriginalExtension();
                $imageName = 'slider' . '-' . time() . '.' . $extension;
                $imgPath = $newImage->storeAs('homeBanner', $imageName, 'public');
                $imgUrl = "storage/" . $imgPath;

                if ($oldImage) {

                    if ($oldImage) {
                        $oldImagePath = public_path($oldImage);
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                }

                $slider = HomeBanner::first();
                $slider->img = $imgUrl;
                $slider->save();

                return redirect()->back()->with('success', ['status' => 'success', 'message' => 'Banner Image updated successfully']);
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', ['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    function search($slug)
    {
        $menuFooter = MenuFooter::first();
        $languages = BookLanguage::orderBy('name', 'asc')->get();

        // Convert slug back to possible readable text (optional, if needed)
        $textSearch = str_replace('-', ' ', $slug);

        // Search for book by `slug` (exact) or `title` (partial match)
        $book = Book::where('slug', 'LIKE', '%' . $slug . '%')
            ->orWhere('title', 'LIKE', '%' . $textSearch . '%')
            ->with([
                'category',
                'bookAuth',
                'bookLanguage',
                'publication',
                'country'
            ])
            ->first();

        // Fetch related books if a book is found
        $relatedBooks = $book
            ? Book::where('category_id', $book->category_id)
                ->where('id', '!=', $book->id)
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get()
            : collect(); // Empty collection if no book is found

        return Inertia::render('SearchBook/SearchBook', [
            'book' => $book,
            'menuFooter' => $menuFooter,
            'languages' => $languages,
            'relatedBooks' => $relatedBooks
        ]);
    }
}

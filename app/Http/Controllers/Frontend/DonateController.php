<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BookLanguage;
use App\Models\ContactMessage;
use App\Models\Donate;
use App\Models\Member;
use App\Models\MenuFooter;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DonateController extends Controller
{
    function index(){
        $menuFooter = MenuFooter::first();
        $languages = BookLanguage::orderBy('name', 'asc')->get();
        $list = Donate::whereNotIn('id', [12])->orderBy('id', 'desc')->get();
        $pageInfo = Donate::where('id', 12)->first();
        return Inertia::render('Donate/Donate',["menuFooter" => $menuFooter,"languages"=> $languages, "list" => $list, "pageInfo" => $pageInfo]);
    }

    function AdminDonatePage(Request $request){
        $list = Donate::whereNotIn('id', [12])->orderBy('id', 'desc')->get();
        $email = $request->session()->get('userEmail');
        $user = User::where('email', $email)->first();

        $pageInfo = Donate::where('id', 12)->first();

        $member = Member::where("is_read", 0)->count();
        $contactMessage = ContactMessage::where("is_read", 0)->count();

        return Inertia::render("Auth/Donate/DonateList", ['list' => $list, 'user' => $user, 'member' => $member, 'contactMessage' => $contactMessage, 'pageInfo' => $pageInfo]);
    }

    function AdminDonateCreate(Request $request){

        try {

            $request->validate([
                'name' => 'required',
                'img' => 'required',
                'des' => 'required',

            ]);

            $imgUrl = "";
            if ($request->hasFile('img')) {
                $image = $request->file('img');
                $image_name = time() . '.' . $image->getClientOriginalExtension();
                $imgUrl = $image->move(public_path('donate/'), $image_name);
                $imgUrl = "/donate/" . $image_name;
            }

            $donate = new Donate();
            $donate->name = $request->name;
            $donate->image = $imgUrl;
            $donate->des = $request->des;
            $donate->save();
            return redirect()->back()->with('success',['status' => 'success', 'message' => 'Donate created successfully']);

        }catch (Exception $e) {

            return redirect()->back()->with('error',['status' => 'error', 'message' => $e->getMessage()]);
        }

    }

    function AdminDonateUpdate(Request $request){

        try {
            $request->validate([
                'id' => 'required',
            ]);

            $imgUrl = "";
            if ($request->hasFile('img')) {

                if ($request->input('oldImage')) {
                    $oldImagePath = public_path($request->input('oldImage'));
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $image = $request->file('img');
                $image_name = time() . '.' . $image->getClientOriginalExtension();
                $imgUrl = $image->move(public_path('donate/'), $image_name);
                $imgUrl = "/donate/" . $image_name;

                $donate = Donate::find($request->id);
                $donate->name = $request->name;
                $donate->image = $imgUrl;
                $donate->des = $request->des;
                $donate->save();
                return redirect()->back()->with('success',['status' => 'success', 'message' => 'Donate updated successfully']);

            }else{

                $donate = Donate::find($request->id);
                $donate->name = $request->name;
                $donate->des = $request->des;
                $donate->save();
                return redirect()->back()->with('success',['status' => 'success', 'message' => 'Donate updated successfully']);

            }

        }catch (Exception $e) {

            return redirect()->back()->with('error',['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    function AdminDonateDelete(Request $request){
        try {
            $request->validate([
                'id' => 'required',
                'imagePath' => 'required',
            ]);

            if ($request->input('imagePath')) {
                $oldImagePath = public_path($request->input('imagePath'));
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $donate = Donate::find($request->id);
            $donate->delete();
            return redirect()->back()->with('success',['status' => 'success', 'message' => 'Donate deleted successfully']);

        } catch (Exception $e) {
            return redirect()->back()->with('error',['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    function AdminDonateUpdateInfo(Request $request){


        try {

            $id =12;
            $donate = Donate::find($id);

            $donate->name = $request->title;
            $donate->des = $request->description;
            $donate->save();
            return redirect()->back()->with('success', ['status' => '', 'message' => 'Info updated successfully']);
        } catch (Exception $e) {
            return redirect()->back()->with('error',['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}

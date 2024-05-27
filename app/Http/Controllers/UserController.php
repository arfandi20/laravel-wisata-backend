<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //index user
    public function index (Request $request) {
        $users= DB::table('users')->when($request->keyword, function($query) use ($request){
            $query->where('name', 'like', "%{$request->keyword}%")
            ->orWhere('email', 'like', "%{$request->keyword}%" )
            ->orWhere('phone', 'like', "%{$request->keyword}%");
        })->orderBy('id', 'desc')->paginate(20);
        return view('pages/users/index', compact('users'));
    }

    //create
    public function create() {
        return view('pages.users.create');
    }

    //store
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required',
            'email'=> 'required|email|unique:users',
            'password'=> 'required|min:8',
            'phone'=> 'required',
            'role'=> 'required',
        ]);

        User::create($request->all());
        return redirect()->route('users.index')->with('success', 'User Created Successfully');
    }

    //edit
    public function edit(User $user){
        return view('pages/users/edit', compact('user'));
    }

    //update
    public function update(Request $request, User $user){

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->save();

        //check if phone is not empty
        if ($request->phone){
            $user->update(['phone'=>$request->phone]);
        }
        //check if password not empty
        if ($request->password){
            $user->update(['password'=>Hash::make($request->password)]);
        }

        return redirect()->route('users.index')->with('success', 'User Update Successfully');
    }

    //hapus
    public function destroy(User $user){
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User Deleted Successfully');
    }
}

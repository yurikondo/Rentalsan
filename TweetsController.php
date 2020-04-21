<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tweet;
use Validator;
use Illuminate\Support\Facades\Auth;

class TweetsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 会員ホーム画面
    public function index()
    {
        $tweets = Tweet::orderBy('created_at', 'asc')->paginate(6);
        $auth_id = Auth::user()->id;
        return view('tweets.index', [
            'tweets' => $tweets,
            'auth_id' => $auth_id
        ]);
    }

    // 新規投稿　追加
    public function create()
    {
        return view('tweets.create');
    }

    public function store(Request $request)
    {
        //バリデーション
        $validator = Validator::make($request->all(), [
            'tweet_title' => 'required|min:1|max:255',
            'tweet_date' => 'required',
            'tweet_time' => 'required',
            'tweet_place' => 'required|min:1|max:255',
            'tweet_img' => 'required',
            'tweet_msg' => 'required|min:1|max:255',
        ]);

        //バリデーション:エラー 
        if ($validator->fails()) {
            return redirect('create')
                ->withInput()
                ->withErrors($validator);
        }

        // fileを取得
        $file = $request->file('tweet_img');
        // ファイル名を取得
        $filename = $file->getClientOriginalName();
        // ファイル名を取得
        $move = $file->move('./upload/', $filename);

        //以下に登録処理を記述（Eloquentモデル）
        $tweets = new Tweet;
        $tweets->tweet_title = $request->tweet_title;
        $tweets->user_id = Auth::user()->id;
        $tweets->tweet_date = $request->tweet_date;
        $tweets->tweet_time = $request->tweet_time;
        $tweets->tweet_place = $request->tweet_place;
        $tweets->tweet_img = $filename;
        $tweets->tweet_msg = $request->tweet_msg;
        $tweets->save();
        return redirect('/home');
    }

    // 削除
    public function delete(Tweet $tweet)
    {
        $tweet->delete();
        return redirect('/home');
    }

    // 投稿更新準備
    public function edit($tweet_id)
    {
        $tweets = Tweet::where('user_id', Auth::user()->id)->find($tweet_id);
        return view('tweets.edit', ['tweet' => $tweets]);
    }

    // 投稿更新
    public function update(Request $request)
    {
        //バリデーション
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'tweet_title' => 'required|min:1|max:255',
            'tweet_date' => 'required',
            'tweet_time' => 'required',
            'tweet_place' => 'required|min:1|max:255',
            'tweet_img' => 'required',
            'tweet_msg' => 'required|min:1|max:255',
        ]);

        //バリデーション:エラー 
        if ($validator->fails()) {
            return redirect('/edit/{tweets}')
                ->withInput()
                ->withErrors($validator);
        }

        // fileを取得
        $file = $request->file('tweet_img');
        // ファイル名を取得
        $filename = $file->getClientOriginalName();
        // ファイル名を取得
        $move = $file->move('./upload/', $filename);

        //以下に登録処理を記述（Eloquentモデル）
        $tweets = Tweet::find($request->id);
        $tweets->user_id = Book::where('user_id', Auth::user()->id)->find($request->id);
        $tweets->tweet_title = $request->tweet_title;
        $tweets->tweet_date = $request->tweet_date;
        $tweets->tweet_time = $request->tweet_time;
        $tweets->tweet_place = $request->tweet_place;
        $tweets->tweet_img = $filename;
        $tweets->tweet_msg = $request->tweet_msg;
        $tweets->save();
        return redirect('/home');
    }
}

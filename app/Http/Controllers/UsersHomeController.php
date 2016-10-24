<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\StoreConferenceRequest;
use App\Http\Requests\SubmitPaperRequest;
use App\User;
use App\Conference;
use App\Submission;
use App\SubmissionAuthor;
use App\SubmissionPaper;
use Illuminate\Support\Facades\Input;
use Response;
use Validator;
use App\RoleWriter;
// use App\Http\Requests;

class UsersHomeController extends Controller
{
  protected $viewData = [];
  protected $uploadFolder = 'uploads';

  public function __construct()
  {
    $this->middleware('auth');
    parent::__construct();

    $this->viewData['conferences'] = $this->user->authoring()->get();
  }

  public function index()
  {
    return view('users.home.index', $this->viewData);
  }

  public function addPaper(Conference $confUrl)
  {
    $this->viewData['conf'] = $confUrl;

    return view('users.home.add', $this->viewData);
  }

  public function manage(Conference $confUrl)
  {
    $this->viewData['conf'] = $confUrl;

    return view('users.home.manage', $this->viewData);
  }

  public function join(Conference $confUrl)
  {
    $this->viewData['conf'] = $confUrl;

    $writer = new RoleWriter($confUrl, $this->user, 'author');

    //TODO : EDIT AUTHOR

    return view('users.home.manage', $this->viewData);
    // return view('welcome');
  }

  public function submitPaper(Request $request, Conference $confUrl) {
    $validator = Validator::make($request->all(), [
      'title' => 'required',
      'abstract' => 'required',
      'keywords' => 'required',
      'paper' => 'required|mimes:pdf|max:5000'
    ]);

    if ($validator->fails()) {
      return redirect()
      ->back()
      ->withErrors($validator)
      ->withInput();
    }


    $submission = Submission::create($request->all());

    $this->user->submissions()->save($submission);
    $confUrl->submissions()->save($submission);

    $submissionPaper = SubmissionPaper::create(['version' => 1]);
    $submission->versions()->save($submissionPaper);

    $submission->update(['active_version' => $submissionPaper->version]);

    $paper = $request->file('paper');

    if ($paper->isValid()) {
    //     // dd($paper);
        $extension = $paper->getClientOriginalExtension(); // getting image extension
        $fileName = md5(uniqid('', true) . microtime()) . '.' . $extension; // renameing image
        $paper->move($this->uploadFolder, $fileName); // uploading file to given path
        $submissionPaper->path = $fileName;
        $submissionPaper->save();
    } else {
    }

    //TODO : show uploaded pdf
    // return redirect()->route('user.home.manage', $this->conf);
    return redirect()->route('user.home.single.show', ['conf' => $confUrl->url, 'paperId' => $submission->id]);
  }

  public function showSinglePaper(Conference $confUrl, $paperId)
  {
    $submission = Submission::findOrFail($paperId);

    $this->viewData['conf'] = $confUrl;
    $this->viewData['submission'] = $submission;
    $this->viewData['authors'] = $submission->authors->sortByDesc('is_primary');

    return view('users.home.single', $this->viewData);
  }

  public function addAuthor(Conference $confUrl, Request $request, $paperId)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'required|email',
      'phone' => 'required'
    ]);

    if ($validator->fails()) {
      return redirect()
      ->back()
      ->withErrors($validator)
      ->withInput();
    }

    $submission = Submission::findOrFail($paperId);

    $author = SubmissionAuthor::create($request->all());
    if ($submission->authors()->save($author)) {
    } else {
    }

    return redirect()->back();
  }

  public function changeContact(Conference $confUrl, $paperId, $authorId)
  {

    $submission = Submission::findOrFail($paperId);
    //
    if ($submission->authors->sortByDesc('is_primary')->first()->update(['is_primary' => 0])) {
      SubmissionAuthor::findOrFail($authorId)->update(['is_primary' => 1]);
    } else {
    }

    $this->viewData['conf'] = $confUrl;
    $this->viewData['submission'] = $submission;
    $this->viewData['authors'] = $submission->authors->sortByDesc('is_primary');

    return redirect()->route('user.home.single.show', ['conf' => $confUrl->url, 'paperId' => $paperId]);
  }

  public function updateConference(StoreConferenceRequest $request, Conference $confUrl)
  {
    $confUrl->update($request->all());
    flash()->success('Conferece Succesfully Updated');

    return redirect()->back();
  }

  protected function checkAllowed() {
    if ($this->user === null || !$this->user->isAdmin()) {
      abort(404);
    }
  }
}

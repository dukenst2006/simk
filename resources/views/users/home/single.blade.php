@extends('users.home.index')

@section('content')
  <div class="row">
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                  Submission ID: {{ $submission->id }}
                  @if($submission->isCanEdit())
                    <a href="{{ route('user.home.single.edit', ['confUrl' => $conf->url, 'paperId' => $submission->id]) }}" class="btn btn-xs btn-primary" {{ (!$submission->isCanEdit()) ? " disabled" : NULL }}>Edit</a>
                  @endif
                </div>
                <div class="panel-body">
                  <h4><strong>{{ $submission->title }}</strong></h4>
                  <p>
                    <strong>Keywords:</strong>
                    {{ $submission->keywords }}
                  </p>
                  <p>
                    <strong>Abstract:</strong>
                    <br>{{ $submission->abstract }}
                  </p>


                  <div class="row">
                    @foreach($versions as $ver)
                    <div class="col-md-12" style="padding-top:10px;">
                      @if($ver->version < 2)
                        <strong>First Version :</strong>
                      @else
                        <strong>Camera Ready Version {{ $ver->version - 1 }} :</strong>
                      @endif
                      <a href="/uploads/{{ $ver->path }}" class="btn btn-sm btn-primary">Download</a>


                      @if($ver->version === 1 && $submission->isPaperResolved())
                      <a href="{{ route('user.home.showPaperReview', ['confUrl' => $conf->url, 'paperId' => $submission->id]) }}" class="btn btn-sm btn-success">Show Review Results</a>
                      @endif
                    </div>
                    @endforeach

                    @if($submission->isPaperResolved() && $submission->isCameraReadyApproved() === false)
                    <div class="col-md-12" style="padding-top:10px;">
                      <form class="form form-vertical" action="{{ route('user.home.postCameraReady', ['confUrl' => $conf->url, 'paperId' => $submission->id]) }}" method="post" enctype="multipart/form-data">
                          {{ csrf_field() }}
                          <div class="control-group">
                              <div class="form-group{{ $errors->has('paper') ? ' has-error' : '' }}" >
                                  <label>Camera Ready Paper Upload
                                      <br>
                                  </label>
                                  <div class="controls">
                                    <div class="col-md-5">
                                      <input type="file" class="form-control input-sm" name="paper">
                                      @if ($errors->has('paper'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('paper') }}</strong>
                                          </span>
                                      @else
                                          <span class="help-block">
                                              <strong>Please upload file with .doc / .docx extension only.</strong>
                                          </span>
                                      @endif
                                    </div>
                                    <div class="col-md-7">
                                      <button type="submit" class="btn btn-primary btn-sm">Set Camera Ready</button>
                                    </div>
                                  </div>
                              </div>
                          </div>
                        </form>
                      @endif
                    </div>

                  <div class="col-md-12">
                    <div class="pull-right">
                        <strong>Status : {{ $submission->getLastPaperReadableStatus() }}</strong>
                    </div>
                  </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">Authors</div>
                <div class="panel-body">
                  <table class="table table-condensed">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Name</th>
                      <th>E-mail</th>
                      <th>Phone</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($authors as $author)
                      <tr>
                        <td>
                          {{ $author->author_no}}
                        </td>
                        <td>
                          {{ $author->name }}
                          @if($author->is_primary)
                            <span class="label label-success">Contact Author</span>
                          @endif
                        </td>
                        <td>{{ $author->email }}</td>
                        <td>{{ $author->phone }}</td>
                        <td>
                          <a href="{{ route('user.home.single.editAuthor', [
                              'conf' => $conf->url,
                              'paperId' => $submission->id,
                              'authorId' => $author->id
                          ])}}" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-edit"></span></a>

                          @if(!$author->is_primary)
                            <a href="{{ route('user.home.single.changeContact', [
                              'conf' => $conf->url,
                              'paperId' => $submission->id,
                              'authorId' => $author->id
                            ])}}" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-earphone"></span></a>

                            <a href="{{ route('user.home.single.removeAuthor', [
                              'conf' => $conf->url,
                              'paperId' => $submission->id,
                              'authorId' => $author->id
                            ])}}" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>
                          @endif

                          @if($author->author_no != 1)
                            <a href="{{ route('user.home.moveAuthor', [
                              'conf' => $conf->url,
                              'paperId' => $submission->id,
                              'from' => $author->author_no,
                              'to' => ($author->author_no - 1)
                            ])}}" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-arrow-up"></span></a>
                          @endif
                          @if($author->author_no != $authorCount)
                            <a href="{{ route('user.home.moveAuthor', [
                              'conf' => $conf->url,
                              'paperId' => $submission->id,
                              'from' => $author->author_no,
                              'to' => ($author->author_no + 1)
                            ])}}" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-arrow-down"></span></a>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="panel panel-default">
              @if (isset($edit))
                <div class="panel-heading">Edit Author</div>
              @else
                <div class="panel-heading">Add More Author</div>
              @endif
                <div class="panel-body">
                  @if (isset($edit))
                   <form class="form-horizontal" role="form" method="POST" action="{{ route('user.home.single.updateAuthor', ['conf' => $conf->url, 'paperId' => $submission->id, 'authorId' => $author->id]) }}">
                  @else
                   <form class="form-horizontal" role="form" method="POST" action="{{ route('user.home.single.addAuthor', ['conf' => $conf->url, 'paperId' => $submission->id]) }}">
                  @endif
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                @if (old('name') !== NULL)
                                  <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                @elseif(isset($edit))
                                  <input type="text" class="form-control" name="name" value="{{ $singleAuthor->name }}">
                                @else
                                  <input type="text" class="form-control" name="name" value="">
                                @endif

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Email</label>

                            <div class="col-md-6">
                                @if (old('email') !== NULL)
                                  <input type="text" class="form-control" name="email" value="{{ old('email') }}">
                                @elseif(isset($edit))
                                  <input type="text" class="form-control" name="email" value="{{ $singleAuthor->email }}">
                                @else
                                  <input type="text" class="form-control" name="email" value="">
                                @endif

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Phone No.</label>

                            <div class="col-md-6">
                                @if (old('phone') !== NULL)
                                  <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                                @elseif(isset($edit))
                                  <input type="text" class="form-control" name="phone" value="{{ $singleAuthor->phone }}">
                                @else
                                  <input type="text" class="form-control" name="phone" value="">
                                @endif

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-4 col-md-offset-7">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-btn fa-user"></i> {{ (isset($edit)) ? "Update" : "Add Author" }}
                                </button>

                                @if(isset($edit))
                                <a href="#" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>Cancel Edit
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
              </div>
            </div>
        </div>
    </div>
@endsection

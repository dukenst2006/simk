@extends('organizers.dashboard')

@section('content')
  <div class="row">
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                  Submission ID: {{ $submission->id }}
                  <a href="#" class="btn btn-xs btn-primary">Edit</a>
                  <div class="pull-right">
                    <a href="#" class="btn btn-xs btn-success">Resolve Submission</a>
                  </div>
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


                  <p>
                      <strong>File Version {{ $submission->active_version }} :</strong>
                      <a href="/uploads/{{ $submission->getCurrentActivePath() }}" class="btn btn-sm btn-default">Download</a>
                  </p>
                  <div class="pull-right">
                      <strong>Status : On Review</strong>
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
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                </div>
            </div>


        </div>
        <div class="col-md-10">
          <div class="panel panel-default">
            <div class="panel-heading">
                Reviewers
                <a href="#" class="btn btn-xs btn-primary">Assign New</a>
            </div>
              <div class="panel-body">
                  <table class="table table-condensed">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Name</th>
                      <th>Score</th>
                      <th>Decision</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td>Ikhwan Narurozi</td>
                      <td>4.5</td>
                      <td>Minor Revision</td>
                    </tr>
                    <tr>
                      <td>1</td>
                      <td>Ikhwan Narurozi</td>
                      <td>4.5</td>
                      <td>Minor Revision</td>
                    </tr>
                    <tr>
                      <td>1</td>
                      <td>Ikhwan Narurozi</td>
                      <td>4.5</td>
                      <td>Minor Revision</td>
                    </tr>
                    <tr>
                      <td>1</td>
                      <td>Ikhwan Narurozi</td>
                      <td>4.5</td>
                      <td>Minor Revision</td>
                    </tr>
                  </tbody>
                </table>
                </div>

              </div>
          </div>
        </div>
        </div>
    </div>
@endsection
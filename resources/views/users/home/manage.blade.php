@extends('users.home.index')

@section('content')
   <div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
              <strong>Your Papers on {{ $conf->name }} </strong>
              <a href="{{ route('user.home.addPaper', $conf->url) }}">
                <button type="button" class="btn btn-success btn-xs"
                {{ ($conf->isCanUpload()) ? NULL : " disabled"}}
                >Add Paper</button>
              </a>
                @if(!$conf->isCanUpload())
                  Uploading new paper disabled. Deadline passed.
                @endif
            </div>
            <div class="panel-body">
              <table class="table table-bordered conferences">
                <thead>
                  <tr class="text-center">
                    <th>#</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $count = 1 ?>
                  @forelse($submissions as $subs)
                    <tr>
                      <td>
                        {{ $count++ }}
                      </td>
                      <td class="title">
                        <a href="{{ route('user.home.single.show', ['conf' => $conf->url, 'paperId' => $subs->id]) }}"  data-toggle="tooltip" data-placement="top" title="{{ str_limit($subs->abstract, 100) }}">
                          {{ str_limit($subs->title, 70) }}
                        </a>
                      </td>
                      <td>
                        @foreach ($subs->authors as $author)
                          {{ $author->name }} ,
                        @endforeach
                      </td>
                      <td class="center">{{ $subs->getStatusFromAuthor() }}</td>
                      <td>
                        <button class="btn btn-danger btn-xs" onclick="confirmDelete({{ $subs->id }})">Cancel</button>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td>
                      </td>
                      <td class="title">
                      </td>
                      <td>
                      </td>
                      <td>
                      </td>
                      <td>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
        </div>
    </div>
  </div>
  <style media="screen">
    table.conferences th {
      text-align: center;
    }
    table.conferences td.date {
      text-align: center;
    }
    table.conferences td.title{
      width: 30%;
    }
    table.conferences td.center{
      text-align: center;
    }
  </style>
@endsection

@section('scripts.footer')
<script>
  function confirmDelete(submissionId) {
    if (confirm('Are you sure want to delete ?')) {
      // console.log(true);
      window.location.href = "{{ asset('/') }}users/home/manage/{{ $conf->url }}/" + submissionId + "/cancel";
    }
  }

</script>
@endsection

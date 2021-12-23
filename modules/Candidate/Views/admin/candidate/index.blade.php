@extends('admin.layouts.app')
@section('title','Candidate')
@section('content')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{__("All candidate")}}</h1>
            <div class="title-actions">
                <a href="{{route('user.admin.create', ['candidate_create' => 1])}}" class="btn btn-primary">{{__("Add new Candidate")}}</a>

                <a href="{{route('import.View')}}" class="btn btn-info">Import Candidates</a>
            </div>

        </div>
        @include('admin.message')
        <div class="filter-div d-flex justify-content-between ">
            <div class="col-left">
                @if(!empty($rows))
                    <form method="post" action="{{url('admin/module/candidate/bulkEdit')}}"
                          class="filter-form filter-form-left d-flex justify-content-start">
                        {{csrf_field()}}
                        <select name="action" class="form-control">
                            <option value="">{{__(" Bulk Actions ")}}</option>
                            <option value="delete">{{__(" Delete ")}}</option>
                        </select>
                        <button data-confirm="{{__("Do you want to delete?")}}" class="btn-info btn btn-icon dungdt-apply-form-btn" type="button">{{__('Apply')}}</button>
                    </form>
                @endif
            </div>
            <div class="col-left">
                <form method="get" action="{{url('/admin/module/candidate/')}} " class="filter-form filter-form-right d-flex justify-content-end flex-column flex-sm-row" role="search">
                    <input type="text" name="s" value="{{ Request()->s }}" placeholder="{{__('Search by name')}}"
                           class="form-control">
                    <select name="cate_id" class="form-control">
                        <option value="">{{ __('--All Category --')}} </option>
                        <?php
                        if (!empty($categories)) {
                            foreach ($categories as $category) {
                                printf("<option ".(Request()->cate_id == $category->id ? 'selected' : '')." value='%s' >%s</option>", $category->id, $category->name);
                            }
                        }
                        ?>
                    </select>
                    <button class="btn-info btn btn-icon btn_search" type="submit">{{__('Search Candidate')}}</button>
                </form>
            </div>
        </div>
        <div class="text-right">
            <p><i>{{__('Found :total items',['total'=>$rows->total()])}}</i></p>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-body">
                        <form action="" class="bravo-form-item">
                            <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th width="60px"><input type="checkbox" class="check-all"></th>
                                    <th class="title"> {{ __('Name')}}</th>
                                    <th class="title"> {{ __('Title')}}</th>
                                    <th class="title"> {{ __('Email')}}</th>
                                    <th class="title"> {{ __('Phone')}}</th>
                                    <th width="100px"> {{ __('Date')}}</th>
                                    <th width="100px">{{  __('Search ?')}}</th>
                                    <th width="100px"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($rows->total() > 0)
                                    @foreach($rows as $row)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="check-item" name="ids[]" value="{{$row->id}}">
                                            </td>
                                            <td class="title">
                                                <a href="{{route('user.admin.detail',['id'=>$row->id])}}">{{$row->getDisplayName()}}</a>
                                            </td>
                                            <td> {{ $row->business_name}}</td>
                                            <td> {{ $row->email}}</td>
                                            <td> {{ $row->phone}}</td>
                                            <td> {{ display_date($row->updated_at)}}</td>
                                            <td><span class="badge badge-{{ $row->status }}">{{ $row->status }}</span></td>
                                            <td>
                                                <a href="{{route('user.admin.detail',['id'=>$row->id])}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> {{__('Edit')}}</a>
                                            </td>
                                            <td>
                                            <button type="button" class="btn btn-primary btn-sm assign-job-btn" data-id="{{ $row->id }}" data-target="#exampleModal" data-toggle="modal" >
                                                Assign jobs
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6">{{__("No data")}}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            </div>
                        </form>
                        {{$rows->appends(request()->query())->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Assign Jobs</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
                                    
      <div class="modal-body">
        <form method="post" id="job-Form" action="{{route('assign.Job')}}" >
            @csrf
            <input type="hidden" id="candidate-id" name="candidate-id" value="">
            <div class="form-group">
                <select name="job" class="form-control">
                    @foreach ($candidateJobs as $candidateJob)
                        <option value="{{ $candidateJob->id }}">
                            {{ $candidateJob->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        <div class="form-group">
            <label for="exampleFormControlTextarea1">Message</label>
             <textarea class="form-control" name="message" id="exampleFormControlTextarea1"rows="3"></textarea>
         </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" class="btn btn-primary">Assign Job</button>
      </div>
        </form>
      </div>
      
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
    $('.assign-job-btn').click(function(){
        var id = $(this).data('id');
        $('#candidate-id').val(id);
    });
});
</script>
@endsection


@extends('layouts.backend')
@section('title', 'Role - Edit')
@section('content')
    <div class="row row-cards-pf">
        <div class="row-cards-pf card-pf">
            <ol class="breadcrumb">
                <li>
                    <span class="pficon pficon-home"></span>
                    <a href="{{url('home')}}">Dashboard</a>
                </li>
                <li>
                    <span class="pficon pficon-users"></span>
                    <a href="{{url('role')}}">Role</a>
                </li>
                <li class="active">
                    <strong>Edit</strong>
                </li>
            </ol>
        </div>
    </div><!-- /row -->

    <!-- Toolbar -->
    <div class="row row-cards-pf">
        <div class="col-sm-12">
            <div class="card-pf card-pf-accented card-pf-view card-pf-view-single-select">
                <div class="card-pf-heading">
                    <h1>
                        <span class="pficon pficon-users"></span>
                        Role
                        <small>Edit</small>
                    </h1>
                </div>

                <div class="card-pf-body">
                    <div class="row">
                        <form id="main_form" class="col-md-5">
                            {{ csrf_field() }}
                            <input type="hidden" name="role_id" value="{{ $role->id }}">
                            <div class="form-group required">
                                <label class="control-label">Name <span style="color: red;">*</span></label>
                                <input type="text" required name="name" class="form-control" placeholder="Name" value="{{ $role->name }}" autocomplete="off">
                            </div>
                            <div class="form-group required">
                                <label class="control-label">Display Name <span style="color: red;">*</span></label>
                                <input type="text" required name="display_name" class="form-control" placeholder="Display Name" value="{{ $role->display_name }}" autocomplete="off">
                            </div>
                            <div class="form-group required">
                                <label class="control-label">Description <span style="color: red;">*</span></label>
                                <input type="text" required name="description" class="form-control" placeholder="Description" value="{{ $role->description }}" autocomplete="off">
                            </div>

                            <a role="button" href="{{ url('/role') }}" class="btn btn-default btn"><i class="fa fa-arrow-circle-left fa-fw"></i> Back</a>

                            <button type="button" id="btnSave" class="btn btn-success btn btn-ml" onclick="edit_role()" style="margin-left: 10px"><i class="fa fa-check fa-fw"></i> Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- page script -->
    <script type="text/javascript">
        var edit_role = function() {
            var recordID = $("input[name=role_id]").val();

            $("#btnSave").prop('disabled','true');

            axios.put("/role/" + recordID, $('#main_form').serialize())
                .then(function(response) {
                    if (response.data.status == 1) {
                        swal({
                            title: "Good!",
                            text: response.data.message,
                            type: "success",
                            timer: 1000,
                            confirmButtonText: 'Ok'
                        }).then(function () {
                            $("form#main_form:not(.filter) :input:visible:enabled:first").focus();
                            window.location.replace(response.data.intended_url)
                        });
                    } else {
                        swal({
                            title: "Oops!",
                            text: response.data.message,
                            type: "error",
                            closeOnConfirm: false
                        });
                    }
                    $("#btnSave").removeAttr('disabled');
                })
                .catch(function(error) {
                    switch (error.response.status) {
                        case 422:
                            swal({
                                title: "Oops!",
                                text: 'Failed form validation. Please check your input.',
                                type: "error"
                            });
                            break;
                        case 500:
                            swal({
                                title: "Oops!",
                                text: 'Something went wrong.',
                                type: "error"
                            });
                            break;
                    }
                    $("#btnSave").removeAttr('disabled');
                });
        };
    </script>
@endsection
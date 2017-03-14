@extends('layouts.admin')

@section('header')
    @include('layouts.header')
@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection


@section('contentheader')
    {{--<!-- Content Header (Page header) -->--}}
    <section class="content-header">
        <h1>
            {{ trans('messages.name') }}
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> {{ trans('messages.dashboard') }}</a></li>
            <li class="active">{{ trans('messages.name') }}</li>
        </ol>
    </section>
@endsection

@section('content')

    <div>
        {!! Form::open(['action' => 'MessagesController@MessageValue', 'class' => 'form-horizontal form-label-left', 'data-parsley-validate']) !!}


        @include('portal.partials.input-text-area', ['field' => 'text', 'icon' => 'cp-user', 'value' => 'text' ,'required' => true])

        @include('partials.input-select', ['field' => 'group', 'options' => ['group'=>'Group','category'=>'Category','risk'=>'Risk','type'=>'Type','value'=>'Value'],
                            'value' => !empty($inputs) ? $inputs['group'] : 'group',
                            'icon' => 'cp-user', 'required' => true])
        <div id = "operator" style = "display:none">
        @include('partials.input-select', ['field' => 'operator', 'options' => ['lower than'=>'lower than','higher than' => 'Higher Than','equals'=>'Equals'],
                           'value' => 'equals',
                           'icon' => 'cp-user', 'required' => true])

        </div>
        <div id = "value" style = "display:none">
        @include('portal.partials.input-text-area', ['field' => 'value', 'icon' => 'cp-user', 'value' => '0', 'required' => true])
        </div>
        <div id = "grouptext" style = "display:inline">
            @include('portal.partials.input-text-area', ['field' => 'grouptext', 'icon' => 'cp-user', 'value' => 'text', 'required' => true])
        </div>

        {!! Form::submit(trans('Send'), ['class' => 'btn btn-success']) !!}

            <h5 style="color:#999; border-bottom:1px solid #ddd; height:40px; line-height:40px; font-weight:bold">
                Messages</h5>
            <div class="row">
                <div class="col-xs-12">
                    <table id="dt_table_messages" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr class="headings">
                            <th>{{ trans('messages.text') }}</th>
                            <th>{{ trans('messages.staff_id') }}</th>
                            <th>{{ trans('messages.filter') }}</th>
                            <th>{{ trans('messages.operator') }}</th>
                            <th>{{ trans('messages.value') }}</th>
                            <th>{{ trans('messages.date') }}</th>



                        </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
@endsection

@section('css')


@endsection

@section('scripts')

    <script>

        document.getElementById("messagebox").scrollTop = document.getElementById("messagebox").scrollHeight;
        setTimeout(function(){
            window.location.reload(1);
        }, 20000);


    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <script src="/assets/portal/js/bootstrap-filestyle.min.js"> </script>
    <script>
        $(":file").filestyle();
    </script>
            {{--<!-- DataTables -->--}}
            <script type="text/javascript" src="/assets/plugins/datatables/JSZip-2.5.0/jszip.min.js"></script>
            <script type="text/javascript" src="/assets/plugins/datatables/pdfmake-0.1.18/build/pdfmake.min.js"></script>
            <script type="text/javascript" src="/assets/plugins/datatables/pdfmake-0.1.18/build/vfs_fonts.js"></script>
            <script type="text/javascript" src="/assets/plugins/datatables/DataTables-1.10.10/js/jquery.dataTables.min.js"></script>
            <script type="text/javascript" src="/assets/plugins/datatables/DataTables-1.10.10/js/dataTables.bootstrap.min.js"></script>
            <script type="text/javascript" src="/assets/plugins/datatables/Buttons-1.1.0/js/dataTables.buttons.min.js"></script>
            <script type="text/javascript" src="/assets/plugins/datatables/Buttons-1.1.0/js/buttons.bootstrap.min.js"></script>
            <script type="text/javascript" src="/assets/plugins/datatables/Buttons-1.1.0/js/buttons.colVis.min.js"></script>
            <script type="text/javascript" src="/assets/plugins/datatables/Buttons-1.1.0/js/buttons.html5.min.js"></script>
            <script type="text/javascript" src="/assets/plugins/datatables/Buttons-1.1.0/js/buttons.print.min.js"></script>
            <script type="text/javascript" src="/assets/plugins/datatables/FixedColumns-3.2.0/js/dataTables.fixedColumns.min.js"></script>
            <script type="text/javascript" src="/assets/plugins/datatables/Responsive-2.0.0/js/dataTables.responsive.min.js"></script>
            <script type="text/javascript" src="/assets/plugins/datatables/Responsive-2.0.0/js/responsive.bootstrap.min.js"></script>
            <script type="text/javascript" src="/assets/plugins/datatables/Scroller-1.4.0/js/dataTables.scroller.min.js"></script>

            <script type="text/javascript" src="/assets/plugins/bootstrap-select/js/bootstrap-select.min.js"></script>
            <script type="text/javascript" src="/assets/plugins/bootstrap-select/js/i18n/defaults-pt_PT.min.js"></script>
            <script type="text/javascript" src="/assets/plugins/jquery-validation/jquery.validate.min.js"></script>
            <!--<script type="text/javascript" src="/assets/plugins/jquery-validation/localization/messages_pt_PT.js"></script>-->
            <script type="text/javascript" src="/assets/plugins/underscore-min.js"></script>

<script>



            $( "#group" ).change(function() {

               if($( "#group" ).val() == "value") {
                   $("#value").toggle(true);
                   $("#operator").toggle(true);
                   $("#grouptext").toggle(false);
               }else{
                   $("#grouptext").toggle(true);
                   $("#value").toggle(false);
                   $("#operator").toggle(false);
               }

            });



        $(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });



            var logins = $('#dt_table_messages').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, "desc"]],
                ajax: {
                    'url': 'customers/ajax/datatables/messagetypes/{{ $staff }}',
                    'type': "POST"
                },
                columns: [
                    {data: 'text', name: 'text'},
                    {data: 'staff_id', name: 'staff_id'},
                    {data: 'filter', name: 'filter'},
                    {data: 'operator', name: 'operator'},
                    {data: 'value', name: 'value'},
                    {data: 'created_at', name: 'created_at'}

                ]
            });
        });
</script>
@endsection
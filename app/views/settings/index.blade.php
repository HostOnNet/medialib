<form method="post" action="" class="form-horizontal">

    <div class="form-group">
        <label class="col-sm-3 control-label">Autoforward:</label>
        <div class="col-sm-5">
            {{ Form::select("auto_forward", [0 => "No", 1 => "Yes"], Settings::get('auto_forward') , ['class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label" for="">Autoforward Duration</label>
        <div class="col-sm-5">
            {{ Form::select("autoForwardDuration", [10 => 10, 20 => 20, 30 => 30, 45 => 45, 60 => 60, 90 => 90, 120 => 120], Settings::get('autoForwardDuration'), ['class' =>  'form-control'] ) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label" for="">skip_to_bookmark</label>
        <div class="col-sm-5">
            {{ Form::text("skip_to_bookmark", Settings::get('skip_to_bookmark'), ['class' =>  'form-control']) }}
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-5 col-sm-offset-3">
            {{ Form::submit("submit", ['class' =>  'btn btn-lg btn-default']) }}
        </div>
    </div>

</form>

@extends('core::layouts.master')

@section('content')

@include('core::config.side')

<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
    </div> 
    
    <div class="main-body scrollable">
        <div class="container-fluid">
            {form model="config('app')" route="core.config.safe" method="post" id="config" autocomplete="off"}

            <div class="form-title row">{{trans('core::config.safe.base')}}</div>

            <div class="form-group row">
                <label for="env" class="col-2 col-form-label required">{{trans('core::config.env.label')}}</label>
                <div class="col-8">
                    {field type="radiogroup" options="$envs" name="env" required="required"}
                    
                    @if ($errors->has('env'))
                    <span class="form-help text-error">{{ $errors->first('env') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.env.help')}}</span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="debug" class="col-2 col-form-label required">{{trans('core::config.debug.label')}}</label>
                <div class="col-8">
                    {field type="bool" name="debug" required="required"}
                    
                    @if ($errors->has('debug'))
                    <span class="form-help text-error">{{ $errors->first('debug') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.debug.help')}}</span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="admin_prefix" class="col-2 col-form-label required">{{trans('core::config.admin_prefix.label')}}</label>
                <div class="col-8">
                    {field type="text" name="admin_prefix" required="required"}
                    
                    @if ($errors->has('admin_prefix'))
                    <span class="form-help text-error">{{ $errors->first('admin_prefix') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.admin_prefix.help')}}</span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="key" class="col-2 col-form-label required">{{trans('core::config.key.label')}}</label>
                <div class="col-8">
                    {field type="text" name="key" required="required"}
                    
                    @if ($errors->has('key'))
                    <span class="form-help text-error">{{ $errors->first('key') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.key.help')}}</span>
                    @endif
                </div>
            </div>

            <div class="form-title row">{{trans('core::config.safe.log')}}</div>                                  

            {/form}           
        </div>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="mr-auto">
            {field type="submit" form="config" value="trans('core::master.save')" class="btn btn-primary"}
        </div>
    </div>
    
</div>

@endsection

@push('js')
<script type="text/javascript">
    $(function(){
        $('form.form').validate({       
            submitHandler:function(form){                
                var validator = this;

                $('.form-submit').prop('disabled',true);

                $.post($(form).attr('action'), $(form).serialize(), function(msg){
                    
                    $.msg(msg);

                    if ( msg.state && msg.url ) {
                        location.href = msg.url;
                        return true;
                    }

                    $('.form-submit').prop('disabled',false);
                    return false;

                },'json').fail(function(jqXHR){                    
                    $('.form-submit').prop('disabled',false);
                    return validator.showErrors(jqXHR.responseJSON.errors);
                });
            }            
        });
    })
</script>
@endpush
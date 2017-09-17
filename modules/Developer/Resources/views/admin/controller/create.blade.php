@extends('core::layouts.dialog')

@section('content')
<div class="main scrollable">

    {form model="$controller" route="['developer.module.controller.create', $module, $type]" method="post" class="p-3" autocomplete="off"}

        <div class="container-fluid">

            <div class="form-group row">
                <label for="controller_name" class="col-2 col-form-label required">{{trans('developer::module.controller_name.label')}}</label>
                <div class="col-10">
                    {field type="text" name="controller_name" pattern="^[a-z]+$" required="required"}

                    @if ($errors->has('controller_name'))
                    <span class="form-help text-error">{{ $errors->first('controller_name') }}</span>
                    @else
                    <span class="form-help">{{trans('developer::module.controller_name.help')}}</span>                     
                    @endif
                </div>                      
            </div>

            <div class="form-group row">
                <label for="controller_style" class="col-2 col-form-label required">{{trans('developer::module.controller_style.label')}}</label>
                <div class="col-10">
                    {field type="radiogroup" name="controller_style" options="$controller_styles" column="1" required="required"}

                    @if ($errors->has('controller_style'))
                    <span class="form-help text-error">{{ $errors->first('controller_style') }}</span>
                    @else
                    <span class="form-help">{{trans('developer::module.controller_style.help')}}</span>                     
                    @endif
                </div>                     
            </div>                                             
                       
        </div>

    {/form}
</div>


@endsection

@push('js')
<script type="text/javascript">

    // 对话框设置
    $dialog.callbacks['ok'] = function(){
        $('form.form').submit();
        return false;
    };

    $(function(){

        $('form.form').validate({
       
            submitHandler:function(form){                
                var validator = this;
                $.post($(form).attr('action'), $(form).serialize(), function(msg){

                    // 关闭对话框
                    msg.state && $dialog.close();                    
                    // 弹出消息
                    $.msg(msg);

                },'json').fail(function(jqXHR){
                    return validator.showErrors(jqXHR.responseJSON.errors);
                });
            }            
        });
        
    })  
</script>
@endpush
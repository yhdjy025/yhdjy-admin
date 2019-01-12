<div class="form-group">
    <label class="col-sm-2 control-label">{{ $field->title }}</label>
    <div class="col-sm-9 input-group">
        <input type="text" class="form-control" name="{{ $field->name }}" value="{{  $data[$field->name] ?? '' }}">
        <span class="input-group-btn">
        <button class="btn btn-success" type="button" id="file-{{ $field->name }}">上传</button>
      </span>
    </div>
</div>
<div class="prev-{{ $field->name }} form-group">
    <div class="col-sm-9 col-sm-offset-2"><img src="@if(!empty($data)) {{ Storage::url($data[$field->name]) }} @endif" alt="" width="80">

    </div>
</div>
<script src="{{ asset('js/dropzone.js') }}"></script>
<script>
    var in_nme = "{{ $field->name }}";
    $("#file-" + in_nme).dropzone({
        url: "{{ url('system/public/upload') }}",
        uploadMultiple: false,
        paramName: 'upfile',
        previewsContainer: false,
        previewTemplate: false,
        success: function (file, res ) {
            if (res.code == 200) {
                $('.prev-'+in_nme+' img').attr('src', res.data.url);
                $('input[name='+in_nme+']').val(res.data.path);
            } else {
                layer.msg(res.message, {icon:5, shade:0.3});
            }
        }
    })
</script>
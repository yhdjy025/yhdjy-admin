<form id="edit-form" class="form-horizontal"  style="min-height: {{ $_form->minHeight }}"
      method="{{ $_form->method }}" action="{{ $_form->action }}">
    @foreach($_form->items as $item)
        @if($item->tag == 'upload')
            @include('vendor.field.upload', ['data'=> $_form->data, 'field' => $item])
        @else
            @include('vendor.field.common', ['data'=> $_form->data, 'field' => $item])
        @endif
    @endforeach
</form>
<script>
    function dailogSubmit(index) {
        var url = $('#edit-form').attr('action');
        var params = $('#edit-form').serializeArray();
        var data = {};
        $.each(params, function (index, val) {
            data[val.name] = val.value;
        });
        $.post(url, data, function (ret) {
            if (ret.code == 200) {
                layer.msg(ret.message, {icon:6, shade:0.3,time:500});
                if (index) {
                    layer.close(index);
                } else {
                    window.location.reload();
                }
            }  else {
                layer.msg(ret.message, {icon:5, shade:0.3});
                return false;
            }
        })
    }
</script>
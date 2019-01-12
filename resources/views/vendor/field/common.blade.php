<div class="form-group">
    @if(!isset($field->type) || $field->type != 'hidden')
        <label class="col-sm-2 control-label">{{ $field->title }}</label>
    @endif
    <div class="col-sm-9 input-group">
        @switch($field->tag)
            @case('input')
            @switch($field->type)
                @case('hidden')
                <input type="hidden" name="{{ $field->name }}" class="form-control" value="{{ $data[$field->name] ?? '' }}">
                @break
                @case('text')
                <input type="text" name="{{ $field->name }}" class="form-control" {{ !empty($field->disabled) ? 'readonly' : '' }} value="{{ $data[$field->name] ?? $field->default }}">
                @break
                @case('password')
                <input type="password" name="{{ $field->name }}" class="form-control" value="{{ $data[$field->name] ?? '' }}">
                @break
                @case('radio')
                @foreach($field->options as $value => $title)
                    <label class="radio-inline">
                        <input type="radio" @if(isset($data[$field->name]) && $data[$field->name] == $value) checked @endif
                        name="{{ $field->name }}" value="{{ $value }}"> {{ $title }}
                    </label>
                @endforeach
                @break
                @case('checkbox')
                @foreach($field->options as $value => $title)
                    <label class="checkbox-inline">
                        <input type="checkbox" @if(isset($data[$field->name]) && $data[$field->name] == $value) checked @endif
                        name="{{ $field->name }}"  value="{{ $value }}"> {{ $title }}
                    </label>
                @endforeach
                @break

            @endswitch
            @break
            @case('select')
            <select name="{{ $field->name }}" class="form-control @if(!empty($field->search)) selectpicker @endif" @if(!empty($field->search)) data-live-search="true" @endif>
                @foreach($field->options as $value => $title)
                    <option @if(isset($data[$field->name]) && $data[$field->name] == $value) selected @endif
                    value="{{ $value }}">{{ $title }}</option>
                @endforeach
            </select>
            @if(!empty($field->search))
            <script>
                $('.selectpicker').selectpicker();
            </script>
            @endif
            @break
            @case('datePicker')
                <input type="text" class="form-control datepicker" name="day" value="{{ $data[$field->name]?? '' }}" readonly>
            <script>
                laydate.render({
                    elem: '.datepicker'
                });
            </script>
            @break
        @endswitch
    </div>
</div>
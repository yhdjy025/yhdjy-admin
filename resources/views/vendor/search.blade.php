@if(!empty($navs['navs']))
    <ul class="nav nav-tabs">
        @foreach($navs['navs']['sub'] as $nav)
            <li role="presentation" @if($navs['navs']['key'] == $nav['value']) class="active" @endif><a href="{{ parseUrl($nav['url']) }}">{{ $nav['title'] }}</a></li>
        @endforeach
@endif
<form class="form-inline text-right page-option" action="{{ url()->current() }}" method="get">
    @if(!empty($navs['btns']))
        <div class="input-group">
        @foreach($navs['btns'] as $btn)
            @switch($btn['name'])
                @case('add')
                <a href="javascript:;" class="btn btn-primary btn-sm add-btn"
                   data-url="{{ parseUrl($btn['url']) }}">添加</a>
                @break
                @case('del')
                <a href="javascript:;" class="btn btn-danger btn-sm del-btn"
                   data-url="{{ parseUrl($btn['url']) }}">删除</a>
                @break
            @endswitch
        @endforeach
        </div>
    @endif
    @if(!empty($navs['search']))
        @foreach(request()->input() as $name => $value)
            @if(!collect($navs['search'])->pluck('name')->contains($name))
                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
            @endif
        @endforeach
        @foreach($navs['search'] as $k => $item)
            <div class="input-group">
                @switch($item['type'])
                    @case('text')
                        <input type="text" name="{{ $item['name'] }}" class="form-control input-sm"
                               placeholder="请输入{{ $item['title'] ?? '关键字' }}搜索" value="{{ request($item['name'], '') }}">
                    @break
                    @case('select')

                        <select name="{{ $item['name'] }}" class="form-control input-sm">
                            <option value="">全部{{ $item['title'] ?? '' }}</option>
                            @foreach($item['options'] as $key => $value)
                                <option value="{{ $key }}"
                                        @if(($key !== 0 && $key == request($item['name'])) || ($key === 0 && request($item['name']) === '0'))selected @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                    @break
                    @case('datepicker')
                        <input type="text" name="{{ $item['name'] }}" class="form-control input-sm datepicker"
                               placeholder="{{ $item['title'] }}" value="{{ request($item['name'], '') }}" readonly>
                        <script>
                            laydate.render({
                                elem: '.datepicker'
                                @if(!empty($item['range']))
                                ,range: true
                                @endif
                            });
                        </script>
                    @break
                @endswitch
                @if(count($navs['search']) == $k + 1)
                <div class="input-group-btn">
                    <input type="submit" value="搜索" class="btn btn-success btn-sm">
                </div>
                @endif
            </div>
        @endforeach
    @endif
</form>
@if(!empty($navs['navs']))
    </ul>
@endif
